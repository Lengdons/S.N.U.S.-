<?php
session_start();

require_once '../mysql/datubaze.php';
require_once '../klases/zurnals.php';

$db = new datubaze();
$zurnals = new zurnals($db);

if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    header("Location: ../index.php");
    exit;
}

// DELETE lietotajs
if(isset($_POST['delete_lietotajs_id'])){
    
    $stmt = $db->conn->prepare("SELECT vards, uzvards, epasts FROM lietotaji WHERE id = ?");
    $stmt->bind_param("i", $_POST['delete_lietotajs_id']);
    $stmt->execute();
    $lietotajsData = $stmt->get_result()->fetch_assoc();

    $stmt = $db->conn->prepare("UPDATE lietotaji SET aktivs = 0 WHERE id = ?");
    $stmt->bind_param("i", $_POST['delete_lietotajs_id']);
    $stmt->execute();
    
    $zurnals->add($_SESSION['vards']." ".$_SESSION['uzvards']." deactivated lietotajs: ".$lietotajsData['vards']." ".$lietotajsData['uzvards']);
    header("Location: konti.php");
    exit;
}

// GET lietotaji
$result = $db->conn->query("
    SELECT id, vards, uzvards, epasts
    FROM lietotaji WHERE loma != 'admin' AND aktivs = 1 AND loma != 'temp'
    ORDER BY id DESC
");
?>

<link rel="stylesheet" href="../style.css">

<h2>Accounts</h2>

<div>
    <a href="../sakums/sakumlapa.php" class="nav-btn">Atslegas</a>
</div>

<table class="zurnali-table">
    <thead>
        <tr>
            <th>vards</th>
            <th>uzvards</th>
            <th>epasts</th>
            <th>darbiba</th>
        </tr>
    </thead>

    <tbody>
        <?php while($u = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($u['vards']) ?></td>
            <td><?= htmlspecialchars($u['uzvards']) ?></td>
            <td><?= htmlspecialchars($u['epasts']) ?></td>

            <td>
                <form method="POST"
                      onsubmit="return confirm('Delete this account?');">
                    <input type="hidden" name="delete_lietotajs_id" value="<?= $u['id'] ?>">
                    <button class="danger-btn">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>