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

    $result = $lietotajs->login($epasts, $parole);

    if ($result === true) {
        echo json_encode(["status" => "success", "message" => "Veiskmīga pieslēgšanās"]);
    } elseif ($result === "INACTIVE" || $result === "EXPIRED") {
        echo json_encode(["status" => "error", "message" => "Konts nav aktīvs"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Nepareizi ievades dati"]);
    }
}
?>





