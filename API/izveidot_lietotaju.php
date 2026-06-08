<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    echo json_encode(["status" => "error", "message" => "Nav privilēģijas"]);
    exit;
}

require '../mysql/datubaze.php';
require_once '../klases/modelis.php';
require '../klases/lietotajs.php';
require '../klases/zurnals.php';
$db = new datubaze();
$lietotajs = new lietotajs($db);
$zurnals = new zurnals($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $epasts = $_POST['epasts'] ?? '';
    $parole = $_POST['parole'] ?? '';
    $durationDays = (int)($_POST['duration_days'] ?? 7);

    // Limitu uzstādījums
    if ($durationDays > 365) $durationDays = 365;
    if ($durationDays < 1) $durationDays = 1;

    $expiresAt = date('Y-m-d H:i:s', strtotime("+$durationDays days"));
    
    $result = $lietotajs->registreties($epasts, $parole, $beigu_term);

    if ($result === true) {
        $zurnals->add($_SESSION['vards']." created user: " . $epasts);
        echo json_encode(["status" => "success", "message" => "Lietotājs izveidots"]);
    } else {
        echo json_encode(["status" => "error", "message" => $result]);
    }
}
?>