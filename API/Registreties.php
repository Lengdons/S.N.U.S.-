<?php
session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';
require '../klases/lietotajs.php';

$db = new datubaze();
$lietotajs = new lietotajs($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $epasts = $_POST['epasts'] ?? '';
    $parole = $_POST['parole'] ?? '';

    // piemēram 30 dienu konts
    $beigu_term = date(
        'Y-m-d H:i:s',
        strtotime('+30 days')
    );

    $result = $lietotajs->registreties(
        $epasts,
        $parole,
        $beigu_term
    );

    if ($result === true) {

        echo json_encode([
            "status" => "success",
            "message" => "Reģistrācija veiksmīga"
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => $result
        ]);

    }
}
?>