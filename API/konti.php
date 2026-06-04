<?php
header('Content-Type: application/json');

require_once '../mysql/datubaze.php';

$db = new datubaze();

$result = $db->conn->query("
    SELECT id, epasts, nosaukums, uzvards
    FROM lietotaji WHERE aktivs = 1 AND loma = 'lietotajs'
");

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);