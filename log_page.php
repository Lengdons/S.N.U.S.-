<?php
session_start();

require_once 'mysql/Database.php';
require_once 'Log.php';

$db = new Database();
$log = new Log($db);

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: index.php");
    exit;
}

$result = $log->all();
?>

<link rel="stylesheet" href="style.css">
<h2>System Logs</h2>


<div style="margin-bottom: 15px;">
    <a href="main.php" class="nav-btn">Rooms</a>
</div>


<?php while($l = $result->fetch_assoc()): ?>
    <?php echo $l['action']; ?> - <?php echo $l['created_at']; ?><br>
<?php endwhile; ?>