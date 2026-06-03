<?php
session_start();
header('Content-Type: application/json');

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
    $roomId = $_POST['room_id'] ?? null;

    if ($roomId) {

        // Paņem ID/vardu pirms izdzeshanas - vēsturei 
        
        $stmt = $db->conn->prepare("SELECT name FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $roomId);
        $stmt->execute();
        $roomData = $stmt->get_result()->fetch_assoc();

        $room->delete($roomId);
        $log->add($_SESSION['name']." ".$_SESSION['surname']." removed room: ". $roomData['name']);
        
        echo json_encode(["status" => "success", "message" => "Room deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No room ID provided"]);
    }
}
?>