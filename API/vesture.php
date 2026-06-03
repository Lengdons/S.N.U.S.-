<?php
header('Content-Type: application/json');

require '../mysql/datubaze.php';

$db = new datubaze();

$result = $db->conn->query("
    SELECT
        zurnali.datums,
        CONCAT(lietotaji.nosaukums, ' ', lietotaji.uzvards) AS lietotajs,
        zurnali.darbiba,
        atslegas.nosaukums AS kabinets
    FROM zurnali
    LEFT JOIN lietotaji ON lietotaji.id = zurnali.lietotajs_id
    LEFT JOIN atslegas ON atslegas.id = zurnali.atslega_id
    ORDER BY zurnali.datums DESC
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);