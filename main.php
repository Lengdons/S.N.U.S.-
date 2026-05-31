<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit;
}

require 'mysql/database.php';

$db = new database();

require 'room.php';
require 'booking.php';
require 'log.php';
require 'user.php';
require 'auth_check.php';

$room = new room($db);
$booking = new booking($db);
$log = new log($db);
$user = new user($db);

$msg = "";

/* ======================
   HANDLE ACTIONS (TOP ONLY)
====================== */

// LOAD USER DATA
$stmt = $db->conn->prepare("SELECT name,surname FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

$_SESSION['name'] = $userData['name'];
$_SESSION['surname'] = $userData['surname'];

$needsProfile = empty($userData['name']) || empty($userData['surname']);

// SAVE PROFILE
if(isset($_POST['save_profile'])){
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);

    if($name && $surname){
        $stmt = $db->conn->prepare("UPDATE users SET name=?, surname=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $surname, $_SESSION['user_id']);
        $stmt->execute();

        $log->add($name." ".$surname." has joined the system");

        header("Location: main.php");
        exit;
    } else {
        $msg = "Fill all fields";
    }
}

// ADD ROOM (ADMIN)
if(isset($_POST['add_room'])){
    if($_SESSION['role'] !== 'admin') die("No permission");

    $name = trim($_POST['room_name']);
    $name = ucwords(strtolower($name));

    if($name === ""){
        $msg = "Room name cannot be empty";
    } elseif($room->exists($name)){
        $msg = "Room already exists";
    } else {
        $room->add($name);

        $log->add($_SESSION['name']." ".$_SESSION['surname']." added room: ". $name);
        header("Location: main.php");
        exit;
    }
}

// DELETE ROOM (ADMIN)
if(isset($_POST['delete_room'])){
    if($_SESSION['role'] !== 'admin') die("No permission");

    $stmt = $db->conn->prepare("SELECT name FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $_POST['delete_room_id']);
    $stmt->execute();

    $roomData = $stmt->get_result()->fetch_assoc();

    $room->delete($_POST['delete_room_id']);

    $log->add($_SESSION['name']." ".$_SESSION['surname']." removed room: ". $roomData['name']);
    header("Location: main.php");
    exit;
}



if (isset($_POST['book'])) {

    if ($needsProfile) {
        $msg = "Complete your profile first.";
    } else {

        $bookUserId = $_SESSION['user_id'];

        if ($_SESSION['role'] === 'admin') {
            $bookUserId = $_POST['book_user_id'] ?? null;
            if (!$bookUserId) {
                $msg = "Select a user";
                return;
            }
        }

        $start_date = $_POST['start_date'];
        $end_date   = $_POST['end_date'];

        $start_time = $_POST['start_time'];
        $end_time   = $_POST['end_time'];

        $start = $start_date . " " . $start_time . ":00";
        $end   = $end_date . " " . $end_time . ":00";

        if (strtotime($start) >= strtotime($end)) {
            $msg = "End time must be after start time";
        }
        elseif (strtotime($start) < time()) {
            $msg = "Cannot book past time";
        }
        else {

            if ($booking->isAvailable($_POST['room_id'], $start, $end)) {

                $booking->book($bookUserId, $_POST['room_id'], $start, $end);

                $stmt = $db->conn->prepare("SELECT name,surname FROM users WHERE id=?");
                $stmt->bind_param("i", $bookUserId);
                $stmt->execute();
                $u = $stmt->get_result()->fetch_assoc();

                $stmt = $db->conn->prepare("SELECT name FROM rooms WHERE id=?");
                $stmt->bind_param("i", $_POST['room_id']);
                $stmt->execute();
                $roomData = $stmt->get_result()->fetch_assoc();

                $actor = $_SESSION['name'] . " " . $_SESSION['surname'];

                if ($_SESSION['role'] === 'admin' && $bookUserId != $_SESSION['user_id']) {
                    $log->add(
                        $actor .
                        " booked " . $roomData['name'] .
                        " for user " . $u['name'] . " " . $u['surname'] .
                        " from " . $start . " - " . $end
                    );
                } else {
                    $log->add(
                        $u['name'] . " " . $u['surname'] .
                        " booked " . $roomData['name'] .
                        " from " . $start . " - " . $end
                    );
                }

                $msg = "Room booked successfully";

            } else {
                $msg = "Room already booked in that range";
            }
        }
    }
}


function getBookedSlots($db, $room_id, $date){
    $booked = [];

    $dayStart = strtotime($date . " 00:00:00");
    $dayEnd   = strtotime($date . " 23:59:59");

    $stmt = $db->conn->prepare("
        SELECT start_time, end_time
        FROM bookings
        WHERE room_id = ?
        AND start_time <= ?
        AND end_time >= ?
    ");

    $endDateTime   = date('Y-m-d H:i:s', $dayEnd);
    $startDateTime = date('Y-m-d H:i:s', $dayStart);

    $stmt->bind_param("iss", $room_id, $endDateTime, $startDateTime);
    $stmt->execute();
    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){
        $start = strtotime($row['start_time']);
        $end = strtotime($row['end_time']);

        $start = max($start, $dayStart);
        $end   = min($end, $dayEnd);

        while($start < $end){
            $booked[] = date("H:i", $start);
            $start = strtotime("+30 minutes", $start);
        }
    }

    return array_unique($booked);
}

function getRoomStatus($db, $room_id){

    $now = date('Y-m-d H:i:s');

    $stmt = $db->conn->prepare(" SELECT bookings.end_time, users.name, users.surname 
        FROM bookings JOIN users on users.id = bookings.user_id WHERE room_id = ?
        AND start_time <= ?
        AND end_time > ?
        ORDER BY end_time ASC
        LIMIT 1
    ");

    $stmt->bind_param("iss", $room_id, $now, $now);
    $stmt->execute();

    $res = $stmt->get_result();

    if($row = $res->fetch_assoc()){

        return ['occupied' => true, 'until' => $row['end_time'], 'user' => $row['name'].' '.$row['surname']];
    }

    return ['occupied' => false, 'until' => null, 'user' => null];
}

if (isset($_POST['create_user'])) {

    if ($_SESSION['role'] !== 'admin') {
        die("No permission");
    }

    $email = $_POST['new_email'];
    $password = $_POST['new_password'];

    $durationDays = (int) $_POST['duration_days']; // e.g. 7, 30, etc.

    if ($durationDays > 365) $durationDays = 365;
    if ($durationDays < 1) $durationDays = 1;

    $expiresAt = date('Y-m-d H:i:s', strtotime("+$durationDays days"));

    $result = $user->register($email, $password, $expiresAt);

    if ($result === true) {
        $log->add($_SESSION['name']." created user: " . $email);
        $msg = "User created successfully";
    } else {
        $msg = $result;
    }
}

//delete ur account
if(isset($_POST['delete_my_account'])){

    $stmt = $db->conn->prepare("
        UPDATE users
        SET is_active = 0
        WHERE id = ?
    ");

    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();

    $log->add($_SESSION['name']." ".$_SESSION['surname']." is no longer amongus");

    session_unset();
    session_destroy();
    
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>


<div id="overlay" class="overlay" onclick="closePopup()">
    <div class="popup" onclick="event.stopPropagation()">

        <form method="POST">

            <input type="hidden" name="room_id" id="room_id">

            <?php if($_SESSION['role'] === 'admin'): ?>

            <label>Book for user:</label>
            <select name="book_user_id" required>

            <?php
                $users = $db->conn->query("
                    SELECT id,name,surname,email
                    FROM users
                    ORDER BY name ASC
                ");

                while($u = $users->fetch_assoc()):
                ?>

                    <option value="<?php echo $u['id']; ?>">
                        <?php
                        echo $u['name'] . " " .
                            $u['surname'] .
                            " (" . $u['email'] . ")";
                        ?>
                    </option>

                <?php endwhile; ?>

            </select>

            <?php endif; ?>

            <label>Start time:</label>
            <select name="start_time" required>
            <?php
            for ($h = 0; $h <= 23; $h++) {
                foreach (["00", "30"] as $m) {

                    $t = sprintf("%02d:%s", $h, $m);

                    echo "<option value='$t'>$t</option>";
                }
            }
            ?>
            </select>

            <label>End time:</label>
            <select name="end_time" required>
            <?php
            for ($h = 0; $h <= 23; $h++) {
                foreach (["00", "30"] as $m) {

                    $t = sprintf("%02d:%s", $h, $m);

                    echo "<option value='$t'>$t</option>";
                }
            }
            ?>
            </select>

            <?php if($_SESSION['role'] === 'admin'): ?>

                <label>Start date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo date('Y-m-d'); ?>" required>

                <label>End date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-d'); ?>" required>
            <?php else: ?>

                <!-- regular users automatically send date by default -->
                <input type="hidden" name="start_date" value="<?php echo date('Y-m-d'); ?>">
                <input type="hidden" name="end_date" value="<?php echo date('Y-m-d'); ?>">

            <?php endif; ?>

            <button name="book">Book</button>

        </form>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <form method="POST">
            <input type="hidden" name="delete_room_id" id="delete_room_id">
            <button name="delete_room"
                    onclick="return confirm('Delete this room?')">
                Delete Room
            </button>
        </form>
        <?php endif; ?>

    </div>
</div>

<div id="createUserOverlay" class="overlay" onclick="closeCreateUserPopup()">
    <div class="popup" onclick="event.stopPropagation()">

        <form method="POST">
            <h3>Create User</h3>

            <input type="email" name="new_email" placeholder="Email" required>
            <input type="password" name="new_password" placeholder="Password" required>
            <label>Account duration (days)</label>
            <input type="number" name="duration_days" value="7" min="1" max="365" oninput="this.value = Math.min(365, Math.max(1, this.value))">

            <button name="create_user">Create</button>
        </form>

    </div>
</div>

<!-- PROFILE POPUP (FORCED) -->
<?php if($needsProfile): ?>
<div id="profileOverlay" class="overlay">
    <div class="popup">
        <h3>Complete your profile</h3>
        <form method="POST">
            <input name="name" placeholder="Name" required>
            <input name="surname" placeholder="Surname" required>
            <button name="save_profile" disabled>Save</button>
        </form>
    </div>
</div>
<?php endif; ?>
<div id="app" data-needs-profile="<?php echo $needsProfile ? '1' : '0'; ?>"></div>

<div class="layout">

    <!-- LEFT SIDE -->
    <div class="sidebar">
        <h2>Sveiks, <?php echo $_SESSION['name'] ?: $_SESSION['user']; ?></h2>

        <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="log_page.php" class="nav-btn">Logs</a>
            <a href="accounts_page.php" class="nav-btn">Accounts</a>
            <a href="bookings_page.php" class="nav-btn">Bookings</a>
        <?php endif; ?>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <form method="POST">
            <input id="room_name" name="room_name" placeholder="New room">
            <button id="add_btn" name="add_room" disabled>Add Room</button>
            <button type="button" onclick="openCreateUserPopup()">Create Temp Account</button>
        </form>
        <?php endif; ?>
                <a href="login/logout.php" class="nav-btn logout">Logout</a>
        <form method="POST" onsubmit="return confirm('Are you sure you want to deactivate your account?');">
            <button name="delete_my_account"
                    class="danger-btn">
                Delete My Account
            </button>

        </form>
        
    </div>

    <!-- RIGHT SIDE -->
    <div class="content">
        <?php if($msg): ?>
        <div class="msg"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="rooms">
            <?php 
            $result = $room->getAll();

            while($r = $result->fetch_assoc()):
                $status = getRoomStatus($db, $r['id']);
            ?>
                <div class="room <?php echo $needsProfile ? 'locked' : ''; ?>"
                    <?php if(!$needsProfile): ?>
                        onclick='openPopup(<?php echo $r["id"]; ?>)'
                    <?php endif; ?>
                >

                    <?php echo $r['name']; ?>

                    <?php if($status['occupied']): ?>
                        <span class="busy">
                            Occupied by
                            <?php echo htmlspecialchars($status['user']); ?>
                            Until
                            <?php echo date('H:i', strtotime($status['until'])); ?>
                        </span>
                    <?php else: ?>
                        <span class="free">
                            Available
                        </span>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</div>

</body>
</html>