<?php
session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';

$db = new datubaze();

$result = $db->conn->query("
    SELECT
        raksti.id,
        atslegas.nosaukums,
        lietotaji.nosaukums AS vards,
        lietotaji.uzvards,
        raksti.start_laiks,
        raksti.beigu_laiks
    FROM raksti
    LEFT JOIN atslegas ON atslegas.id = raksti.atslega_id
    LEFT JOIN lietotaji ON lietotaji.id = raksti.lietotajs_id
    ORDER BY raksti.id DESC
");

if(!$result){
    die($db->conn->error);
}
$data = [];

while($row = $result->fetch_assoc()){

    $data[] = [
        "id" => $row["id"],
        "kabinets" => $row["nosaukums"],
        "lietotajs" => $row["vards"] . " " . $row["uzvards"],
        "start_laiks" => $row["start_laiks"],
        "beigu_laiks" => $row["beigu_laiks"]
    ];
}

echo json_encode($data);