
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
$db = new Database();
$room = new Room($db);
$booking = new Booking($db);   // ← THIS LINE
$log = new Log($db);
$msg="";

if(isset($_POST['book'])){
    $start=$_POST['start'];
    $end=$_POST['end'];
    if(strtotime($start)<time()){
        $msg="Cannot book past time";
    }elseif($booking->isAvailable($_POST['room_id'],$start,$end)){
        $booking->book($_SESSION['user_id'],$_POST['room_id'],$start,$end);
        $log->add("User booked room");
        $msg="Room booked successfully";
    } else $msg="Room unavailable";
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

<div id="pop" class="popup">
<form method="POST">
<input type="hidden" name="room_id" id="room_id">
Start:<input type="datetime-local" name="start" min="<?php echo date('Y-m-d\\TH:i'); ?>">
End:<input type="datetime-local" name="end" min="<?php echo date('Y-m-d\\TH:i'); ?>">
<button name="book">Book</button>
</form>
</div>

</body>
</html>
