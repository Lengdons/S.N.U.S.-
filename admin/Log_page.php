<?php
session_start();

require_once '../mysql/database.php';
require_once '../classes/log.php';

$db = new database();
$log = new log($db);

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../index.php");
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

if(isset($_POST['delete_selected']) && !empty($_POST['log_ids'])){
    $ids = $_POST['log_ids'];

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $types = str_repeat('i', count($ids));

    $stmt = $db->conn->prepare("DELETE FROM logs WHERE id IN ($placeholders)");

    $stmt->bind_param($types, ...$ids);
    $stmt->execute();

    header("Location: log_page.php");
    exit;
}
?>

<link rel="stylesheet" href="../style.css">
<script src="../script.js"></script>

<h2>System Logs</h2>

<div>
    <a href="../home/main.php" class="nav-btn">Rooms</a>
</div>

<form method="POST">

    <div>

        <button name="delete_selected" class="danger-btn">
            Delete selected
        </button>

        <button name="clear_logs" class="danger-btn"
            onclick="return confirm('Delete ALL logs?');">
            Clear ALL
        </button>
    </div>

    <a href="../export/export_logs.php" class="nav-btn">
    Download Logs CSV
    </a>
  

    <table class="logs-table">
        <thead>
            <tr>
                <th>Action</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
        <?php while($l = $result->fetch_assoc()): ?>
            <tr onclick="toggleRow(this, 'log_ids[]')" data-id="<?php echo $l['id']; ?>">
                <td>
                    <?php echo htmlspecialchars($l['action']); ?>
                    <input type="hidden" name="log_ids[]" value="<?php echo $l['id']; ?>" disabled>
                </td>
                <td><?php echo $l['created_at']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</form>