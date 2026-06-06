<?php
session_start();
header('Content-Type: application/json');

// Gatekeeper un admin pārbaude
if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    echo json_encode(["status" => "error", "message" => "Unauthorized or No Permission"]);
    exit;
}

require '../mysql/datubaze.php';
require_once '../klases/modelis.php';
require '../klases/atslega.php';
require '../klases/zurnals.php';
$db = new datubaze();
$atslega = new atslega($db);
$zurnals = new zurnals($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nosaukums = trim($_POST['room_name'] ?? '');
    $nosaukums = ucwords(strtolower($nosaukums));

    if($nosaukums === ""){
        echo json_encode(["status" => "error", "message" => "Atslegas nosakums nevar but tukss"]);
    } elseif($atslega->exists($nosaukums)){
        echo json_encode(["status" => "error", "message" => "Atslega jau ir"]);
    } else {
        $atslega->add($nosaukums);
        $zurnals->add($_SESSION['lietotajs']." pievienoja atslegu: ". $nosaukums);
        echo json_encode(["status" => "success", "message" => "Atslega pievienota"]);
    }
}
?>