<?php
session_start();
header('Content-Type: application/json');

// Gatekeeper un admin pārbaude
if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin'){
    echo json_encode(["status" => "error", "message" => "Unauthorized or No Permission"]);
    exit;
}

require '../mysql/database.php';
require '../classes/room.php';
require '../classes/log.php';
$db = new database();
$room = new room($db);
$log = new log($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['room_name'] ?? '');
    $name = ucwords(strtolower($name));

    if($name === ""){
        echo json_encode(["status" => "error", "message" => "Room name cannot be empty"]);
    } elseif($room->exists($name)){
        echo json_encode(["status" => "error", "message" => "Room already exists"]);
    } else {
        $room->add($name);
        $log->add($_SESSION['name']." ".$_SESSION['surname']." added room: ". $name);
        echo json_encode(["status" => "success", "message" => "Room added successfully"]);
    }
}
?>