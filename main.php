
<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require 'mysql/DATABASE.php';
require 'Room.php';
require 'Booking.php';
require 'Log.php';
require 'User.php';
$db = new Database();
$room = new Room($db);
$booking = new Booking($db);   // ← THIS LINE
$log = new Log($db);
$user = new User($db);
$msg="";

// PROFILE SAVE
if(isset($_POST['save_profile'])){
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);

    if($name !== "" && $surname !== ""){
        $stmt = $db->conn->prepare("UPDATE users SET name=?, surname=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $surname, $_SESSION['user_id']);
        $stmt->execute();

        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;

        header("Location: main.php");
        exit;
    } else {
        $msg = "Fill all fields";
    }
}

// ADD ROOM
if(isset($_POST['add_room'])){
    if($_SESSION['role'] !== 'admin') die("No permission");

    $name = trim($_POST['room_name']);

    if($name === ""){
        $msg = "Room name cannot be empty";
    } elseif($room->exists($name)){
        $msg = "Room already exists";
    } else {
        $room->add($name);
        header("Location: main.php");
        exit;
    }
}

// DELETE ROOM
if(isset($_POST['delete_room'])){
    if($_SESSION['role'] !== 'admin') die("No permission");

    $room->delete($_POST['delete_room_id']);
    header("Location: main.php");
    exit;
}

// BOOK ROOM
if(isset($_POST['book'])){
    $start = $_POST['start'];
    $end = $_POST['end'];

    if($booking->isAvailable($_POST['room_id'],$start,$end)){
        $booking->book($_SESSION['user_id'],$_POST['room_id'],$start,$end);
        header("Location: main.php");
        exit;
    } else {
        $msg = "Room unavailable";
    }
}

$stmt = $db->conn->prepare("SELECT name,surname FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

$needsProfile = empty($res['name']) || empty($res['surname']);

$_SESSION['name'] = $res['name'];

$needsProfile = empty($_SESSION['name']) || empty($_SESSION['surname']);

if(isset($_POST['save_profile'])){
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);

    if($name !== "" && $surname !== ""){
        $stmt = $db->conn->prepare("UPDATE users SET name=?, surname=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $surname, $_SESSION['user_id']);
        $stmt->execute();

        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;

        header("Location: main.php");
        exit;
    } else {
        $msg = "Please fill all fields";
    }
}

if(isset($_POST['book'])){
    $date = $_POST['date'];
    $time = $_POST['time'];

    $start = $date . " " . $time . ":00";
    $end = date("Y-m-d H:i:s", strtotime($start . " +30 minutes"));

    if($booking->isAvailable($_POST['room_id'], $start, $end)){
        $booking->book($_SESSION['user_id'], $_POST['room_id'], $start, $end);
        $log->add("User booked room at $start");

        header("Location: main.php?success=1");
        exit;
    } else {
        $msg = "This time slot is already taken";
    }
}
?>

<?php
$stmt->bind_param("i", $r['id']);
$stmt->execute();
$res = $stmt->get_result();

echo "<div style='font-size:12px;'>";
while($b = $res->fetch_assoc()){
    echo date("H:i", strtotime($b['start_time'])) . "<br>";
}
echo "</div>";
?>

<?php
$res = $db->conn->query("SELECT id, name, surname FROM users");

while($u = $res->fetch_assoc()){
    echo "<option value='{$u['id']}'>{$u['name']} {$u['surname']}</option>";
}
?>

<?php
if(isset($_POST['delete_room'])){
    if($_SESSION['role'] !== 'admin'){
        die("No permission");
    }

    $room->delete($_POST['delete_room_id']);
    $log->add("Admin deleted room");

    header("Location: main.php");
    exit;
}
?>

<?php if($_SESSION['role'] === 'admin'): ?>
<form method="POST">
    <input id="room_name" name="room_name" placeholder="New room">
    <button id="add_btn" name="add_room" disabled>Add Room</button>
</form>
<?php endif; ?>

<?php if($needsProfile): ?>
<div class="overlay" style="display:flex;">
    <div class="popup">
        <form method="POST">
            <input name="name" placeholder="Name" required>
            <input name="surname" placeholder="Surname" required>
            <button name="save_profile">Save</button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php
if(isset($_POST['add_room'])){
    if($_SESSION['role'] !== 'admin'){
        die("No permission");
    }

    $name = trim($_POST['room_name']);

    if($name === ""){
        $msg = "Room name cannot be empty";
    } elseif($room->exists($name)){
        $msg = "This room already exists";
    } else {
        $room->add($name);
        $log->add("Admin added room: $name");

        header("Location: main.php");
        exit;
    }
}
?>

<html>
<head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

<h2><?php echo $_SESSION['user']; ?></h2>
<p><?php echo $msg; ?></p>

<div style="margin-bottom: 15px;">
    <?php if($_SESSION['role'] === 'admin'): ?>
        <a href="log_page.php" class="nav-btn">View Logs</a>
    <?php endif; ?>

    <a href="login/logout.php" class="nav-btn logout">Logout</a>
</div>

<?php 
$result = $room->getAll();
while($r=$result->fetch_assoc()): ?>
<div class="room" onclick="openPopup(<?php echo $r['id']; ?>)"><?php echo $r['name']; ?></div>
<?php endwhile; ?>

<?php
if(isset($_POST['add_room'])){
    if($_SESSION['role'] !== 'admin'){
        die("No permission");
    }

    $room->add($_POST['room_name']);
    $log->add("Admin added room");

    header("Location: main.php");
    exit;
}

if(isset($_POST['create_user']) && $_SESSION['role'] === 'admin'){
    $user->register($_POST['new_user'], $_POST['new_pass']);
    $log->add("Admin created user");
}

$user_id = ($_SESSION['role'] === 'admin') 
    ? $_POST['user_id'] 
    : $_SESSION['user_id'];
?>

<div id="overlay" class="overlay" onclick="closePopup()">
    <div class="popup" onclick="event.stopPropagation()">
        <form method="POST">
            <input type="hidden" name="room_id" id="room_id">
            <input type="datetime-local" name="start">
            <input type="datetime-local" name="end">
            <button name="book">Book</button>
        </form>
    </div>
</div>

<label>Time:</label>
<select name="time" required>
<?php
for ($h = 6; $h <= 22; $h++) {
    foreach (["00", "30"] as $m) {
        $time = sprintf("%02d:%s", $h, $m);
        echo "<option value='$time'>$time</option>";
    }
}
?>
</select>

<input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">

<?php if($_SESSION['role'] === 'admin'): ?>
    <input type="date" name="date" min="<?php echo date('Y-m-d'); ?>">
<?php endif; ?>

<?php if($_SESSION['role'] === 'admin'): ?>
<form method="POST">
    <input name="new_user" placeholder="Username">
    <input name="new_pass" placeholder="Password">
    <button name="create_user">Create User</button>
</form>

<select name="user_id">

<?php
$res = $db->conn->query("SELECT id, username FROM users");
while($u = $res->fetch_assoc()){
    echo "<option value='{$u['id']}'>{$u['username']}</option>";
}
?>

<?php endif; ?>

<?php while($r=$result->fetch_assoc()): ?>
    <div class="room" onclick="openPopup(<?php echo $r['id']; ?>)">
        <?php echo $r['name']; ?>
    </div>

    <?php if($_SESSION['role'] === 'admin'): ?>
        <form method="POST">
            <input type="hidden" name="delete_room_id" value="<?php echo $r['id']; ?>">
            <button name="delete_room">Delete</button>
        </form>
    <?php endif; ?>

<?php endwhile; ?>

<h2>Hello, <?php echo $_SESSION['name']; ?></h2>


</select>

</body>
</html>
