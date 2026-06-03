<?php
session_start();
header('Content-Type: application/json');

// Gatekeeper un admin pārbaude
if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    echo json_encode(["status" => "error", "message" => "Unauthorized or No Permission"]);
    exit;
}

require '../mysql/datubaze.php';
require '../klases/atslega.php';
require '../klases/zurnals.php';
$db = new datubaze();
$atslega = new atslega($db);
$zurnals = new zurnals($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nosaukums = trim($_POST['atslega_nosaukums'] ?? '');
    $nosaukums = ucwords(strtolower($nosaukums));

    if($nosaukums === ""){
        echo json_encode(["status" => "error", "message" => "atslega nosaukums cannot be empty"]);
    } elseif($atslega->exists($nosaukums)){
        echo json_encode(["status" => "error", "message" => "atslega already exists"]);
    } else {
        $atslega->add($nosaukums);
        $zurnals->add($_SESSION['nosaukums']." ".$_SESSION['uzvards']." added atslega: ". $nosaukums);
        echo json_encode(["status" => "success", "message" => "atslega added successfully"]);
    }
}
?>