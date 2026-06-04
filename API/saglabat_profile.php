<?php
session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';

$db = new datubaze();

if (!isset($_SESSION['id'])) {
    echo json_encode(["status" => "error", "message" => "Nav pieslēgts"]);
    exit;
}

$nosaukums = trim($_POST['nosaukums'] ?? '');
$uzvards = trim($_POST['uzvards'] ?? '');

if ($nosaukums === '' || $uzvards === '') {
    echo json_encode(["status" => "error", "message" => "Aizpildi visus laukus"]);
    exit;
}

$stmt = $db->conn->prepare("
    UPDATE lietotaji 
    SET nosaukums = ?, uzvards = ? 
    WHERE id = ?
");

$stmt->bind_param("ssi", $nosaukums, $uzvards, $_SESSION['id']);

if ($stmt->execute()) {

    $_SESSION['nosaukums'] = $nosaukums;
    $_SESSION['uzvards'] = $uzvards;
    $_SESSION['vajag_profile'] = false;

    echo json_encode(["status" => "success", "message" => "Profils saglabāts"]);
} else {
    echo json_encode(["status" => "error", "message" => "Neizdevās saglabāt"]);
}