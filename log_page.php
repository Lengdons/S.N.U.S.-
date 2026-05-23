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

if(isset($_POST['clear_logs'])){
    if($_SESSION['role'] !== 'admin'){
        die("No permission");
    }

    $log->clear();

    header("Location: log_page.php");
    exit;
}
?>

<link rel="stylesheet" href="style.css">
<h2>System Logs</h2>


<div style="margin-bottom: 15px;">
    <a href="main.php" class="nav-btn">Rooms</a>
</div>
<form method="POST" onsubmit="return confirm('Delete ALL logs?');">
    <button name="clear_logs" class="danger-btn">Clear Logs</button>
</form>


<?php while($l = $result->fetch_assoc()): ?>
    <?php echo $l['action']; ?> - <?php echo $l['created_at']; ?><br>
<?php endwhile; ?>