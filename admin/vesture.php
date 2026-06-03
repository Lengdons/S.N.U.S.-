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

$result = $zurnals->all();

if(isset($_POST['clear_zurnali'])){
    if($_SESSION['loma'] !== 'admin'){
        die("No permission");
    }

    $zurnals->clear();

    header("Location: vesture.php");
    exit;
}

if(isset($_POST['delete_selected']) && !empty($_POST['zurnals_ids'])){
    $ids = $_POST['zurnals_ids'];

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $types = str_repeat('i', count($ids));

    $stmt = $db->conn->prepare("DELETE FROM zurnali WHERE id IN ($placeholders)");

    $stmt->bind_param($types, ...$ids);
    $stmt->execute();

    header("Location: vesture.php");
    exit;
}
?>

<link rel="stylesheet" href="../style.css">
<script src="../script.js"></script>

<h2>Sistēmas žurnāls</h2>

<div>
    <a href="../sakums/sakumlapa.php" class="nav-btn">Atslēgas</a>
</div>

<form method="POST">

    <div>

        <button name="delete_selected" class="danger-btn">
            Dzēst izvēlētos rakstus
        </button>

        <button name="clear_zurnali" class="danger-btn"
            onclick="return confirm('Dzēst visu žurnālu?');">
            Dzēst visu
        </button>
    </div>

    <a href="../export/export_vesture.php" class="nav-btn">
    Eksportēt žurnālu kā CSV failu
    </a>
  

    <table class="zurnali-table">
        <thead>
            <tr>
                <th>Darbība</th>
                <th>Datums</th>
            </tr>
        </thead>

        <tbody>
        <?php while($l = $result->fetch_assoc()): ?>
            <tr onclick="toggleRow(this, 'zurnals_ids[]')" data-id="<?php echo $l['id']; ?>">
                <td>
                    <?php echo htmlspecialchars($l['darbiba']); ?>
                    <input type="hidden" name="zurnals_ids[]" value="<?php echo $l['id']; ?>" disabled>
                </td>
                <td><?php echo $l['veidota']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</form>