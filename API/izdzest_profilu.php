<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['user'])){
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

require '../mysql/database.php';
require '../classes/log.php';
$db = new database();
$log = new log($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->conn->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    
    if($stmt->execute()){
        $log->add($_SESSION['name']." ".$_SESSION['surname']." is no longer amongus");
        
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