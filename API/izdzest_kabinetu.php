<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    echo json_encode(["status" => "error", "message" => "Unauthorized or No Permission"]);
    exit;
}

require '../mysql/datubaze.php';
require '../klases/atslega.php';
require '../klases/zurnals.php';
$db = new datubaze();
$atslega = new atslega($db);
$zurnals = new zurnals($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atslegaId = $_POST['atslega_id'] ?? null;

    if ($atslegaId) {

        // Paņem ID/vardu pirms izdzeshanas - vēsturei 
        
        $stmt = $db->conn->prepare("SELECT nosaukums FROM atslegas WHERE id = ?");
        $stmt->bind_param("i", $atslegaId);
        $stmt->execute();
        $atslegaData = $stmt->get_result()->fetch_assoc();

        $atslega->delete($atslegaId);
        $zurnals->add($_SESSION['vards']." ".$_SESSION['uzvards']." removed atslega: ". $atslegaData['nosaukums']);
        
        echo json_encode(["status" => "success", "message" => "atslega deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No atslega ID provided"]);
    }
}
?>