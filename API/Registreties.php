<?php
session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';
require_once '../klases/modelis.php';
require '../klases/lietotajs.php';

$db = new datubaze();
$lietotajs = new lietotajs($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $epasts = $_POST['epasts'] ?? '';
    $parole = $_POST['parole'] ?? '';

    $result = $lietotajs->registreties(
        $epasts,
        $parole,
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