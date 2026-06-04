<?php
session_start();
header('Content-Type: application/json'); 

if(!isset($_SESSION['id'])){
    echo json_encode([
        "status" => "error",
        "message" => "Jāpieslēdzas sistēmai"
    ]);
    exit;
}

// 2. Datubāzes savienojums
require '../mysql/datubaze.php';
$db = new datubaze();
require '../klases/atslega.php';
require '../klases/rezerve.php';
require '../klases/zurnals.php';

$atslega = new atslega($db);
$rezerve = new rezerve($db);
$zurnals = new zurnals($db);

// 3. G uz JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $start_date = $_POST['start_date'] ?? null;           // Rekur tev vajadzīgie mainīgie
    $end_date   = $_POST['end_date'] ?? null;             //
    $start_laiks = $_POST['start_laiks'] ?? null;           //
    $beigu_laiks   = $_POST['beigu_laiks'] ?? null;             //
    $atslega_id    = $_POST['atslega_id'] ?? null;              //

    if (!$start_date || !$end_date || !$start_laiks || !$beigu_laiks || !$atslega_id) {
        echo json_encode(["status" => "error", "message" => "Nederīgs laika intervāls"]);
        exit;
    }

    $booklietotajsId = $_SESSION['id'];
    
    // Admin check
    if ($_SESSION['loma'] === 'admin') {
        $booklietotajsId = $_POST['book_lietotajs_id'] ?? null;
        if (!$booklietotajsId) {
            echo json_encode(["status" => "error", "message" => "Izvēlēties lietotāju"]);
            exit;
        }
    }

    $start = $start_date . " " . $start_laiks . ":00";
    $end   = $end_date . " " . $beigu_laiks . ":00";

    if (strtotime($start) >= strtotime($end)) {
        echo json_encode(["status" => "error", "message" => "Beigu laikam jābūt lielākam par sākuma laiku"]);
        exit;
    } elseif (strtotime($start) < time() - 15*60) {
        echo json_encode(["status" => "error", "message" => "Nevar rezervēt pāri laikam"]);
        exit;
    }

    // Pats raksti
    if ($rezerve->isAvailable($atslega_id, $start, $end)) {
        $rezerve->book($booklietotajsId, $atslega_id, $start, $end);
        
        // Saglabāšana
        $actor = trim($_SESSION['nosaukums']." ".$_SESSION['uzvards']);
        $zurnals->add($actor . " rezerveta atslega ID " . $atslega_id . " no " . $start . " - " . $end);

        echo json_encode(["status" => "success", "message" => "Atslēga rezervēta"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Atslēga rezervēta tajā laiku robežā"]);
    }
}
?>
