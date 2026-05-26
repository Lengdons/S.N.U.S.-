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

$needsProfile = !($userData['name']) || !($userData['surname']);

// SAVE PROFILE
if(isset($_POST['save_profile'])){
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);

    if($name && $surname){
        $stmt = $db->conn->prepare("UPDATE users SET name=?, surname=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $surname, $_SESSION['user_id']);
        $stmt->execute();

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

// BOOK ROOM
if(isset($_POST['book'])){
    $start = $_POST['start'];
    $end = $_POST['end'];

    if($booking->isAvailable($_POST['room_id'], $start, $end)){
        $booking->book($_SESSION['user_id'], $_POST['room_id'], $start, $end);
        header("Location: main.php");
        exit;
    } else {
        $msg = "Room unavailable";
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

            <label>Start:</label>
            <input type="datetime-local" name="start" required>

            <label>End:</label>
            <input type="datetime-local" name="end" required>

            <button name="book">Book</button>
        </form>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <form method="POST" style="margin-top:10px;">
            <input type="hidden" name="delete_room_id" id="delete_room_id">
            <button name="delete_room" style="background:red;color:white;">Delete Room</button>
        </form>
        <?php endif; ?>

    </div>
</div>

<!-- PROFILE POPUP (FORCED) -->
<?php if($needsProfile): ?>
<div class="overlay">
    <div class="popup">
        <h3>Complete your profile</h3>
        <form method="POST">
            <input name="name" placeholder="Name" required>
            <input name="surname" placeholder="Surname" required>
            <button name="save_profile">Save</button>
        </form>
    </div>
</div>
<?php endif; ?>

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
            $result = $room->getAll();
            while($r = $result->fetch_assoc()):
            ?>
                <div class="room" onclick="openPopup(<?php echo $r['id']; ?>)">
                    <?php echo $r['name']; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</div>

</body>
</html>