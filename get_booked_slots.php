<?php

require 'mysql/database.php';

$db = new database();

$room_id = (int)$_GET['room_id'];
$date = $_GET['date'];

function getBookedSlots($db, $room_id, $date){

    $booked = [];

    $dayStart = strtotime($date . " 00:00:00");
    $dayEnd   = strtotime($date . " 23:59:59");

    $stmt = $db->conn->prepare("
        SELECT start_time, end_time
        FROM bookings
        WHERE room_id = ?
        AND start_time <= ?
        AND end_time >= ?
    ");

    $endDateTime   = date('Y-m-d H:i:s', $dayEnd);
    $startDateTime = date('Y-m-d H:i:s', $dayStart);

    $stmt->bind_param(
        "iss",
        $room_id,
        $endDateTime,
        $startDateTime
    );

    $stmt->execute();

    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){

        $start = strtotime($row['start_time']);
        $end   = strtotime($row['end_time']);

        $start = max($start, $dayStart);
        $end   = min($end, $dayEnd);

        while($start < $end){

            $booked[] = date("H:i", $start);

            $start = strtotime("+30 minutes", $start);
        }
    }

    return array_values(array_unique($booked));
}

header('Content-Type: application/json');
echo json_encode(getBookedSlots($db, $room_id, $date));
exit;

?>