<?php

session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';

if(!isset($_SESSION['loma']) ||
   $_SESSION['loma'] !== 'admin'){
    exit;
}

$db = new datubaze();

$db->conn->query(
    "DELETE FROM zurnali"
);

echo json_encode([
    "status" => "success"
]);