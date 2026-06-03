<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    echo json_encode(["status" => "error", "message" => "Unauthorized or No Permission"]);
    exit;
}

require '../mysql/datubaze.php';
require '../klases/zurnals.php';
$db = new datubaze();
$zurnals = new zurnals($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->conn->prepare("UPDATE lietotaji SET aktivs = 0 WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['lietotajs_id']);
    
    if($stmt->execute()){
        $zurnals->add($_SESSION['vards']." ".$_SESSION['uzvards']." is no longer amongus");
        
        // Izbeidz sesiju - lietotāju uzreiz izmet
        session_unset();
        session_destroy();
        
        // šeit atmet lietotāju atpakaļ uz pierkastīties.php (jeb login logu)
        
        echo json_encode(["status" => "success", "message" => "Account deactivated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to deactivate account"]);
    }
}
?>