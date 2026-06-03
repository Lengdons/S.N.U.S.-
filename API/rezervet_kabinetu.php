<?php
session_start();
header('Content-Type: application/json'); 

// 1. Getekeeper's
if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    echo json_encode(["status" => "error", "message" => "Nav privilēģijas"]);
    exit;
}

// 2. Datubāzes savienojums
require '../mysql/datubaze.php';
$db = new datubaze();
require '../klases/atslega.php';
require '../klases/booking.php';
require '../klases/zurnals.php';

$atslega = new atslega($db);
$booking = new booking($db);
$zurnals = new zurnals($db);

// 3. G uz JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $start_date = $_POST['start_date'] ?? null;           // Rekur tev vajadzīgie mainīgie
    $end_date   = $_POST['end_date'] ?? null;             //
    $start_time = $_POST['start_time'] ?? null;           //
    $end_time   = $_POST['end_time'] ?? null;             //
    $atslega_id    = $_POST['atslega_id'] ?? null;              //

    if (!$start_date || !$end_date || !$start_time || !$end_time || !$atslega_id) {
        echo json_encode(["status" => "error", "message" => "Nederīgs laika intervāls"]);
        exit;
    }

    $booklietotajsId = $_SESSION['lietotajs_id'];
    
    // Admin check
    if ($_SESSION['role'] === 'admin') {
        $booklietotajsId = $_POST['book_lietotajs_id'] ?? null;
        if (!$booklietotajsId) {
            echo json_encode(["status" => "error", "message" => "Izvēlēties lietotāju"]);
            exit;
        }
    }

    $start = $start_date . " " . $start_time . ":00";
    $end   = $end_date . " " . $end_time . ":00";

    if (strtotime($start) >= strtotime($end)) {
        echo json_encode(["status" => "error", "message" => "Bieug laikam jābūt lielākam par sākuma laiku"]);
        exit;
    } elseif (strtotime($start) < time() - 15*60) {
        echo json_encode(["status" => "error", "message" => "Nevar rezervēt pāri laikam"]);
        exit;
    }

    // Pats raksti
    if ($booking->isAvailable($atslega_id, $start, $end)) {
        $booking->book($booklietotajsId, $atslega_id, $start, $end);
        
        // Saglabāšana
        $actor = $_SESSION['name'] . " " . $_SESSION['uzvards'];
        $zurnals->add($actor . " booked atslega ID " . $atslega_id . " from " . $start . " - " . $end);

        echo json_encode(["status" => "success", "message" => "Atslēga rezervēta"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Atslēga rezervēta tajā laiku robežā"]);
    }
}
?>
