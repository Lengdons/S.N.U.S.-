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
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');

    if($name && $surname){
        $stmt = $db->conn->prepare("UPDATE users SET name=?, surname=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $surname, $_SESSION['user_id']);
        
        if($stmt->execute()){
            
            // UI refresh's
            $_SESSION['name'] = $name;
            $_SESSION['surname'] = $surname;
            
            $log->add($name." ".$surname." has joined the system");
            echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Please fill in all fields"]);
    }
}
?>