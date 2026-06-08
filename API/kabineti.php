<?php

require '../mysql/datubaze.php';
require_once '../klases/modelis.php';
require '../klases/atslega.php';

$db = new datubaze();
$atslega = new atslega($db);
$date = $_GET['date'] ?? date('Y-m-d');
$result = $atslega->getAll();

$atslegas = [];
$now = date('Y-m-d H:i:s');


while($row = $result->fetch_assoc()){

    $stmt = $db->conn->prepare("
        SELECT
            raksti.start_laiks,
            raksti.beigu_laiks,
            lietotaji.nosaukums,
            lietotaji.uzvards
        FROM raksti
        LEFT JOIN lietotaji ON lietotaji.id = raksti.lietotajs_id
        WHERE raksti.atslega_id = ?
        AND DATE(raksti.start_laiks) = ?
        ORDER BY raksti.start_laiks ASC
        LIMIT 1
    ");

    $stmt->bind_param("is",$row['id'],$date);
    $stmt->execute();

    $booking = $stmt->get_result()->fetch_assoc();

    $status = "Pieejams";

    if($booking){
        if(strtotime($booking['beigu_laiks']) < time()){
            $status = "Nodots";
        }
        elseif(strtotime($booking['start_laiks']) > time()){
            $status = "Rezervēts";
        }
        else{
            $status = "Aizņemts";
    }
    }

    $atslegas[] = [
        'id' => $row['id'],
        'nosaukums' => $row['nosaukums'],
        'lietotajs' => $booking
            ? $booking['nosaukums'].' '.$booking['uzvards']
            : null,
        'start' => $booking['start_laiks'] ?? null,
        'end' => $booking['beigu_laiks'] ?? null,
        'status' => $status
        
    ];
}

header('Content-Type: application/json');
echo json_encode($atslegas);