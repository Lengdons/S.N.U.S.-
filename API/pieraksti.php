<?php
session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';

$db = new datubaze();

$sql = "
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
";

$result = $db->conn->query($sql);

if (!$result) {
    echo json_encode([
        "error" => $db->conn->error
    ]);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);