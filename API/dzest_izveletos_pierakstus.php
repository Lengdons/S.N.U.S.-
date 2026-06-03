<?php

session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';

if(!isset($_SESSION['loma']) ||
   $_SESSION['loma'] !== 'admin'){
    exit;
}

$db = new datubaze();

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$ids = $data['ids'] ?? [];

foreach($ids as $id){

    $stmt = $db->conn->prepare(
        "DELETE FROM raksti WHERE id=?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
}

echo json_encode([
    "status" => "success"
]);

