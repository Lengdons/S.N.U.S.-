<?php

session_start();

require '../mysql/datubaze.php';
require '../klases/rezerve.php';

$db = new datubaze();
$rezerve = new rezerve($db);

if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    header("Location: ../index.php");
    exit;
}

if(isset($_POST['delete_selected']) && !empty($_POST['rezerve_ids'])){

    $ids = $_POST['rezerve_ids'];

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $types = str_repeat('i', count($ids));

    $stmt = $db->conn->prepare("
        DELETE FROM raksti
        WHERE id IN ($placeholders)
    ");

    $stmt->bind_param($types, ...$ids);
    $stmt->execute();

    header("Location: pieraksti.php");
    exit;
}

if(isset($_POST['clear_raksti'])){
    $db->conn->query("DELETE FROM raksti");

    header("Location: pieraksti.php");
    exit;
}

$result = $db->conn->query("
SELECT
    raksti.id,
    atslegas.nosaukums AS atslega_nosaukums,
    lietotaji.vards,
    lietotaji.uzvards,
    raksti.start_laiks,
    raksti.beigu_laiks
FROM raksti
JOIN atslegas ON atslegas.id = raksti.atslega_id
JOIN lietotaji ON lietotaji.id = raksti.lietotajs_id
ORDER BY raksti.id DESC
");

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../style.css">
        <script src="../script.js"></script>
    </head>
<body>

<div>
    <a href="../sakums/sakumlapa.php" class="nav-btn">Atslegas</a>
</div>

<tr>
    <td>
        <form method="POST">


    <button name="clear_raksti"
        class="danger-btn"
        onclick="return confirm('Dzest visus rakstus?');">
        Dzēst visu
    </button>

    <button name="delete_selected" class="danger-btn">
        Dzēst izvēlētos pierakstus
    </button>
    
    <a href="../export/export_pieraksti.php" class="nav-btn">
        Eksportēt rakstus kā CSV failu
    </a>

    <table class="zurnali-table">

        <thead>
            <tr>
                <th>Atslēga</th>
                <th>Lietotājs</th>
                <th>Sākuma laiks</th>
                <th>Beigu laiks</th>
            </tr>
        </thead>

        <tbody>

        <?php while($row = $result->fetch_assoc()): ?>

        <tr onclick="toggleRow(this, 'rezerve_ids[]')">

            <td>
                <?php echo htmlspecialchars($row['atslega_nosaukums'] ?? '[Deleted atslega]'); ?>

                <input
                    type="hidden"
                    name="rezerve_ids[]"
                    value="<?php echo $row['id']; ?>"
                    disabled>
            </td>

            <td><?php echo htmlspecialchars($row['vards'].' '.$row['uzvards']); ?></td>
            <td><?php echo $row['start_laiks']; ?></td>
            <td><?php echo $row['beigu_laiks']; ?></td>

        </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</form>
    </td>

</tr>

</body>
</html>