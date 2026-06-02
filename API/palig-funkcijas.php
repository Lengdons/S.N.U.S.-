<?php

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

    $stmt->bind_param("iss", $room_id, $endDateTime, $startDateTime);
    $stmt->execute();
    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){
        $start = strtotime($row['start_time']);
        $end = strtotime($row['end_time']);

        $start = max($start, $dayStart);
        $end   = min($end, $dayEnd);

        while($start < $end){
            $booked[] = date("H:i", $start);
            $start = strtotime("+30 minutes", $start);
        }
    }

    return array_unique($booked);
}

function getRoomStatus($db, $room_id){

    $now = date('Y-m-d H:i:s');

    $stmt = $db->conn->prepare(" SELECT bookings.end_time, users.name, users.surname 
        FROM bookings JOIN users on users.id = bookings.user_id WHERE room_id = ?
        AND start_time <= ?
        AND end_time > ?
        ORDER BY end_time ASC
        LIMIT 1
    ");

    $stmt->bind_param("iss", $room_id, $now, $now);
    $stmt->execute();

    $res = $stmt->get_result();

    if($row = $res->fetch_assoc()){

        return ['occupied' => true, 'until' => $row['end_time'], 'user' => $row['name'].' '.$row['surname']];
    }

    return ['occupied' => false, 'until' => null, 'user' => null];
}



?>