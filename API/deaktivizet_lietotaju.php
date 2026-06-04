<?php
session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';

if (!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin') {
    echo json_encode([
        "status" => "error",
        "message" => "Nav piekļuves"
    ]);
    exit;
}

$db = new datubaze();

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Nederīgs ID"
    ]);
    exit;
}

$stmt = $db->conn->prepare("
    UPDATE lietotaji
    SET aktivs = 0
    WHERE id = ?
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Lietotājs deaktivizēts"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "DB kļūda"
    ]);
}