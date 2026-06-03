<?php
session_start();
require_once '../mysql/datubaze.php';

if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    die("No permission");
}

$db = new datubaze();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="raksti.csv"');

$output = fopen("php://output", "w");

// headers
fputcsv($output, ['ID', 'Atslēga', 'Lietotājs', 'Sākumu laiks', 'Beigu laiks']);

$result = $db->conn->query("
    SELECT 
        raksti.id,
        atslegas.nosaukums AS atslega,
        CONCAT(lietotaji.nosaukums, ' ', lietotaji.uzvards) AS lietotajs,
        raksti.start_laiks,
        raksti.beigu_laiks
    FROM raksti
    JOIN atslegas ON atslegas.id = raksti.atslega_id
    JOIN lietotaji ON lietotaji.id = raksti.lietotajs_id
    ORDER BY raksti.id DESC
");

while($row = $result->fetch_assoc()){
    fputcsv($output, $row);
}

fclose($output);
exit;