<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit;
}

require 'mysql/DATABASE.php';
require 'Room.php';
require 'Booking.php';
require 'Log.php';
require 'User.php';

$db = new Database();
$room = new Room($db);
$booking = new Booking($db);
$log = new Log($db);
$user = new User($db);

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

    if($name === ""){
        $msg = "Room name cannot be empty";
    } elseif($room->exists($name)){
        $msg = "Room already exists";
    } else {
        $room->add($name);

        $log->add($_SESSION['name']." added room: ". $name);
        header("Location: main.php");
        exit;
    }
}

// DELETE ROOM (ADMIN)
if(isset($_POST['delete_room'])){
    if($_SESSION['role'] !== 'admin') die("No permission");

    $room->delete($_POST['delete_room_id']);

    $log->add("Admin removed room: ". $name);
    header("Location: main.php");
    exit;
}



function getBookedSlots($db, $room_id, $date){
    $booked = [];

    $stmt = $db->conn->prepare("
        SELECT start_time, end_time
        FROM bookings
        WHERE room_id = ?
        AND DATE(start_time) = ?
    ");

    $stmt->bind_param("is", $room_id, $date);
    $stmt->execute();
    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){
        $start = strtotime($row['start_time']);
        $end = strtotime($row['end_time']);

        while($start < $end){
            $booked[] = date("H:i", $start);
            $start = strtotime("+30 minutes", $start);
        }
    }

    return $booked;
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

                $log->add(
                    $u['name']." ".$u['surname'].
                    " booked ".$roomData['name'].
                    " from ".$start." - ".$end
                );

                $msg = "Room booked successfully";

            } else {
                $msg = "Room already booked in that range";
            }
        }
    }
}


$booked = [];

$date = date('Y-m-d');

$stmt = $db->conn->prepare("
    SELECT start_time, end_time
    FROM bookings
    WHERE room_id = ?
    AND DATE(start_time) = ?
");

$stmt->bind_param("is", $r['id'], $date);
$stmt->execute();
$res = $stmt->get_result();

while($row = $res->fetch_assoc()){
    $start = strtotime($row['start_time']);
    $end = strtotime($row['end_time']);

    while($start < $end){
        $booked[] = date("H:i", $start);
        $start = strtotime("+30 minutes", $start);
    }
}

if (isset($_POST['create_user'])) {

    if ($_SESSION['role'] !== 'admin') {
        die("No permission");
    }

    $email = $_POST['new_email'];
    $password = $_POST['new_password'];

    $result = $user->register($email, $password);

    if ($result === true) {
        $log->add($_SESSION['name']." created user: " . $email);
        $msg = "User created successfully";
    } else {
        $msg = $result;
    }
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
            for ($h = 6; $h <= 22; $h++) {
                foreach (["00", "30"] as $m) {

                    $t = sprintf("%02d:%s", $h, $m);

                    $disabled = in_array($t, $booked)
                        ? "disabled style='color:#aaa;background:#eee;'"
                        : "";

                    echo "<option value='$t' $disabled>
                            $t" . (in_array($t, $booked) ? " (taken)" : "") . "
                        </option>";
                }
            }
            ?>
            </select>

            <label>End time:</label>
            <select name="end_time" required>
            <?php
            for ($h = 6; $h <= 22; $h++) {
                foreach (["00", "30"] as $m) {

                    $t = sprintf("%02d:%s", $h, $m);

                    $disabled = in_array($t, $booked)
                        ? "disabled style='color:#aaa;background:#eee;'"
                        : "";

                    echo "<option value='$t' $disabled>
                            $t" . (in_array($t, $booked) ? " (taken)" : "") . "
                        </option>";
                }
            }
            ?>
            </select>

            <?php if($_SESSION['role'] === 'admin'): ?>

                <label>Start date:</label>
                <input type="date"
                    name="start_date"
                    value="<?php echo date('Y-m-d'); ?>"
                    required>

                <label>End date:</label>
                <input type="date"
                    name="end_date"
                    value="<?php echo date('Y-m-d'); ?>"
                    required>

            <?php else: ?>

                <!-- users still send today's date automatically -->
                <input type="hidden" name="start_date" value="<?php echo date('Y-m-d'); ?>">
                <input type="hidden" name="end_date" value="<?php echo date('Y-m-d'); ?>">

            <?php endif; ?>

            <button name="book">Book</button>

        </form>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <form method="POST" style="margin-top:10px;">
            <input type="hidden" name="delete_room_id" id="delete_room_id">
            <button name="delete_room"
                    onclick="return confirm('Delete this room?')"
                    style="background:red;color:white;">
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
        <?php endif; ?>

        <a href="login/logout.php" class="nav-btn logout">Logout</a>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <form method="POST">
            <input id="room_name" name="room_name" placeholder="New room">
            <button id="add_btn" name="add_room" disabled>Add Room</button>
            <button type="button" onclick="openCreateUserPopup()">Create Temp Account</button>
        </form>
        <?php endif; ?>
    </div>

    <!-- RIGHT SIDE -->
    <div class="content">
        <?php if($msg): ?>
        <div class="msg"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="rooms">
            <?php 
            $date = date('Y-m-d');
            $result = $room->getAll();

            while($r = $result->fetch_assoc()):
                $booked = getBookedSlots($db, $r['id'], $date);
            ?>
                <div class="room <?php echo $needsProfile ? 'locked' : ''; ?>"
                    <?php if(!$needsProfile): ?>
                        onclick='openPopup(<?php echo $r["id"]; ?>, <?php echo json_encode($booked); ?>)'
                    <?php endif; ?>
                >
                    <?php echo $r['name']; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</div>

</body>
</html>