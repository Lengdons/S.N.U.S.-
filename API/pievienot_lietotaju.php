<?php
session_start();
header('Content-Type: application/json');

require '../mysql/datubaze.php';

$db = new datubaze();

if (!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Nav piekļuves"]);
    exit;
}

$epasts = trim($_POST['epasts'] ?? '');
$parole = trim($_POST['parole'] ?? '');
$dienas = (int)($_POST['beigu_term'] ?? 0);

if ($epasts === '' || $parole === '' || $dienas <= 0) {
    echo json_encode(["status" => "error", "message" => "Nepilni dati"]);
    exit;
}

if (!filter_var($epasts, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Nederīgs e-pasts"]);
    exit;
}

if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,}$/', $parole)) {
    echo json_encode([
        "status" => "error",
        "message" => "Parolei jābūt vismaz 8 simboliem, ar lielo burtu, ciparu un speciālo simbolu"
    ]);
    exit;
}

if ($dienas < 1 || $dienas > 365) {
    echo json_encode(["status" => "error", "message" => "Nederīgs dienu skaits"]);
    exit;
}

// check duplicate
$check = $db->conn->prepare("SELECT id FROM lietotaji WHERE epasts = ?");
$check->bind_param("s", $epasts);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "E-pasts jau eksistē"]);
    exit;
}

// expiry date
$beigu_term = date('Y-m-d H:i:s', strtotime("+$dienas days"));

// hash password
$hash = password_hash($parole, PASSWORD_BCRYPT);

// insert
$stmt = $db->conn->prepare("
    INSERT INTO lietotaji (epasts, parole, loma, beigu_term, aktivs)
    VALUES (?, ?, 'temp', ?, 1)
");

$stmt->bind_param("sss", $epasts, $hash, $beigu_term);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Lietotājs izveidots"]);
} else {
    echo json_encode(["status" => "error", "message" => "Kļūda DB"]);
}