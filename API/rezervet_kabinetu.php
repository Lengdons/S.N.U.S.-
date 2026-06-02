<?php
session_start();
header('Content-Type: application/json'); 

// 1. Getekeeper's
if(!isset($_SESSION['user'])){
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

// 2. Datubāzes savienojums
require '../mysql/database.php';
$db = new database();
require '../classes/room.php';
require '../classes/booking.php';
require '../classes/log.php';

$room = new room($db);
$booking = new booking($db);
$log = new log($db);

// 3. G uz JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $start_date = $_POST['start_date'] ?? null;           // Rekur tev vajadzīgie mainīgie
    $end_date   = $_POST['end_date'] ?? null;             //
    $start_time = $_POST['start_time'] ?? null;           //
    $end_time   = $_POST['end_time'] ?? null;             //
    $room_id    = $_POST['room_id'] ?? null;              //

    if (!$start_date || !$end_date || !$start_time || !$end_time || !$room_id) {
        echo json_encode(["status" => "error", "message" => "Invalid time selection"]);
        exit;
    }

    $bookUserId = $_SESSION['user_id'];
    
    // Admin check
    if ($_SESSION['role'] === 'admin') {
        $bookUserId = $_POST['book_user_id'] ?? null;
        if (!$bookUserId) {
            echo json_encode(["status" => "error", "message" => "Select a user"]);
            exit;
        }
    }

    $start = $start_date . " " . $start_time . ":00";
    $end   = $end_date . " " . $end_time . ":00";

    if (strtotime($start) >= strtotime($end)) {
        echo json_encode(["status" => "error", "message" => "End time must be after start time"]);
        exit;
    } elseif (strtotime($start) < time() - 15*60) {
        echo json_encode(["status" => "error", "message" => "Cannot book past time"]);
        exit;
    }

    // Pats bookings
    if ($booking->isAvailable($room_id, $start, $end)) {
        $booking->book($bookUserId, $room_id, $start, $end);
        
        // Saglabāšana
        $actor = $_SESSION['name'] . " " . $_SESSION['surname'];
        $log->add($actor . " booked Room ID " . $room_id . " from " . $start . " - " . $end);

        echo json_encode(["status" => "success", "message" => "Room booked successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Room already booked in that range"]);
    }
}
?>
