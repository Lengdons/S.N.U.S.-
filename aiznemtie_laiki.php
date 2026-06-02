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

    $stmt->bind_param(
        "iss",
        $atslega_id,
        $endDateTime,
        $startDateTime
    );

    $stmt->execute();

    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){

        $start = strtotime($row['start_laiks']);
        $end   = strtotime($row['beigu_laiks']);

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
echo json_encode(getBookedSlots($db, $atslega_id, $date));
exit;

?>