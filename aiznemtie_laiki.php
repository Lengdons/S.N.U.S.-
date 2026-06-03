<?php

<<<<<<<< HEAD:API/palig-funkcijas.php
function getBookedSlots($db, $room_id, $date){
========
require 'mysql/datubaze.php';

$db = new datubaze();

$atslega_id = (int)$_GET['atslega_id'];
$date = $_GET['date'];

function getBookedSlots($db, $atslega_id, $date){

>>>>>>>> origin/merge:aiznemtie_laiki.php
    $booked = [];

    $dayStart = strtotime($date . " 00:00:00");
    $dayEnd   = strtotime($date . " 23:59:59");

    $stmt = $db->conn->prepare("
        SELECT start_laiks, beigu_laiks
        FROM raksti
        WHERE atslega_id = ?
        AND start_laiks <= ?
        AND beigu_laiks >= ?
    ");

    $endDateTime   = date('Y-m-d H:i:s', $dayEnd);
    $startDateTime = date('Y-m-d H:i:s', $dayStart);

<<<<<<<< HEAD:API/palig-funkcijas.php
    $stmt->bind_param("iss", $room_id, $endDateTime, $startDateTime);
========
    $stmt->bind_param(
        "iss",
        $atslega_id,
        $endDateTime,
        $startDateTime
    );

>>>>>>>> origin/merge:aiznemtie_laiki.php
    $stmt->execute();
    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){
<<<<<<<< HEAD:API/palig-funkcijas.php
        $start = strtotime($row['start_time']);
        $end = strtotime($row['end_time']);
========

        $start = strtotime($row['start_laiks']);
        $end   = strtotime($row['beigu_laiks']);
>>>>>>>> origin/merge:aiznemtie_laiki.php

        $start = max($start, $dayStart);
        $end   = min($end, $dayEnd);

        while($start < $end){
            $booked[] = date("H:i", $start);
            $start = strtotime("+30 minutes", $start);
        }
    }

    return array_unique($booked);
}

<<<<<<<< HEAD:API/palig-funkcijas.php
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


========
header('Content-Type: application/json');
echo json_encode(getBookedSlots($db, $atslega_id, $date));
exit;
>>>>>>>> origin/merge:aiznemtie_laiki.php

?>