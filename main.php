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

        $log->add("Admin added room: ". $name);
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

if(isset($_POST['book'])){

    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $start = $date . " " . $start_time . ":00";
    $end   = $date . " " . $end_time . ":00";

    // basic validation
    if(strtotime($start) >= strtotime($end)){
        $msg = "End time must be after start time";
    }
    elseif(strtotime($start) < time()){
        $msg = "Cannot book past time";
    }
    else {
        // check availability for whole range
        if($booking->isAvailable($_POST['room_id'], $start, $end)){
            $booking->book($_SESSION['user_id'], $_POST['room_id'], $start, $end);
            
            // get user name
            $userName = $_SESSION['name'] . " " . $_SESSION['surname'];

            // get room name
            $stmt = $db->conn->prepare("SELECT name FROM rooms WHERE id=?");
            $stmt->bind_param("i", $_POST['room_id']);
            $stmt->execute();
            $roomData = $stmt->get_result()->fetch_assoc();
            $roomName = $roomData['name'];

            // log message
            $log->add($userName . " booked " . $roomName . " from " . $start . " - " . $end);

            $msg = "Room booked successfully";
        } else {
            $msg = "Room is already booked in that time range";
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

            <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">

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
        <h2>Hello, <?php echo $_SESSION['name'] ?: $_SESSION['user']; ?></h2>

        <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="log_page.php" class="nav-btn">Logs</a>
        <?php endif; ?>

        <a href="login/logout.php" class="nav-btn logout">Logout</a>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <form method="POST">
            <input id="room_name" name="room_name" placeholder="New room">
            <button id="add_btn" name="add_room" disabled>Add Room</button>
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
                <div class="room"
                    onclick='openPopup(<?php echo $r["id"]; ?>, <?php echo json_encode($booked); ?>)'>
                    <?php echo $r['name']; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</div>

</body>
</html>