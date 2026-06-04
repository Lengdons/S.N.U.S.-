<?php

require 'mysql/datubaze.php';

$db = new datubaze();

$atslega_id = (int)$_GET['atslega_id'];
$date = $_GET['date'];

function getBookedSlots($db, $atslega_id, $date){

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


    $stmt->bind_param("iss", $room_id, $endDateTime, $startDateTime);

    $stmt->bind_param(
        "iss",
        $atslega_id,
        $endDateTime,
        $startDateTime
    );

    $stmt->execute();
    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){

        $start = strtotime($row['start_time']);
        $end = strtotime($row['end_time']);


        $start = strtotime($row['start_laiks']);
        $end   = strtotime($row['beigu_laiks']);


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

    $stmt = $db->conn->prepare(" SELECT raksti.beigu_laiks, lietotaji.vards, lietotaji.uzvards 
        FROM raksti JOIN lietotaji on lietotaji.id = raksti.lietotajs_id WHERE atslega_id = ?
        AND start_laiks <= ?
        AND beigu_laiks > ?
        ORDER BY beigu_laiks ASC
        LIMIT 1
    ");

    $stmt->bind_param("iss", $room_id, $now, $now);
    $stmt->execute();

    $res = $stmt->get_result();

    if($row = $res->fetch_assoc()){

        return ['aiznemts' => true, 'until' => $row['beigu_laiks'], 'user' => $row['vards'].' '.$row['uzvards']];
    }

    return ['aiznemts' => false, 'until' => null, 'user' => null];
}



header('Content-Type: application/json');
echo json_encode(getBookedSlots($db, $atslega_id, $date));
exit;
?>