<?php
session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';

$db = new datubaze();

$sql = "
    SELECT *
    FROM zurnali
    ORDER BY id DESC
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