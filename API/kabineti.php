<?php

require '../mysql/database.php';
require '../classes/Room.php';

$db = new database();
$room = new Room($db);
$date = $_GET['date'] ?? date('Y-m-d');
$result = $room->getAll();

$rooms = [];
$now = date('Y-m-d H:i:s');


while($row = $result->fetch_assoc()){

    $stmt = $db->conn->prepare("
        SELECT
            bookings.start_time,
            bookings.end_time,
            users.name,
            users.surname
        FROM bookings
        LEFT JOIN users ON users.id = bookings.user_id
        WHERE bookings.room_id = ?
        AND DATE(bookings.start_time) = ?
        ORDER BY bookings.start_time ASC
        LIMIT 1
    ");

    $stmt->bind_param("is",$row['id'],$date);
    $stmt->execute();

    $booking = $stmt->get_result()->fetch_assoc();

    $occupied = false;

    if($booking){
        $occupied =
            strtotime($booking['start_time']) <= strtotime($now)
            &&
            strtotime($booking['end_time']) > strtotime($now);
    }

    $rooms[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'user' => $booking
            ? $booking['name'].' '.$booking['surname']
            : null,
        'start' => $booking['start_time'] ?? null,
        'end' => $booking['end_time'] ?? null,
        'occupied' => $occupied
        
    ];
}

header('Content-Type: application/json');
echo json_encode($rooms);