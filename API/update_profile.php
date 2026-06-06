<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    echo json_encode(["status" => "error", "message" => "Unauthorized or No Permission"]);
    exit;
}

require '../mysql/datubaze.php';
require_once '../klases/modelis.php';
require '../klases/zurnals.php';
$db = new datubaze();
$zurnals = new zurnals($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vards = trim($_POST['vards'] ?? '');
    $uzvards = trim($_POST['uzvards'] ?? '');

    if($vards && $uzvards){
        $stmt = $db->conn->prepare("UPDATE lietotaji SET vards=?, uzvards=? WHERE id=?");
        $stmt->bind_param("ssi", $vards, $uzvards, $_SESSION['lietotajs_id']);
        
        if($stmt->execute()){
            
            // UI refresh's
            $_SESSION['name'] = $name;
            $_SESSION['uzvards'] = $uzvards;
            
            $zurnals->add($name." ".$uzvards." has joined the system");
            echo json_encode(["status" => "success", "message" => "Profils izmaiņas veiktas"]);
        } else {
            echo json_encode(["status" => "error", "message" => "datubaze error"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Lūdzu aizpildi visas ailes"]);
    }
}
?>