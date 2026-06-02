<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin'){
    echo json_encode(["status" => "error", "message" => "Unauthorized or No Permission"]);
    exit;
}

require '../mysql/database.php';
require '../classes/user.php';
require '../classes/log.php';
$db = new database();
$user = new user($db);
$log = new log($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $durationDays = (int)($_POST['duration_days'] ?? 7);

    // Limitu uzstādījums
    if ($durationDays > 365) $durationDays = 365;
    if ($durationDays < 1) $durationDays = 1;

    $expiresAt = date('Y-m-d H:i:s', strtotime("+$durationDays days"));
    
    $result = $user->register($email, $password, $expiresAt);

    if ($result === true) {
        $log->add($_SESSION['name']." created user: " . $email);
        echo json_encode(["status" => "success", "message" => "User created successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => $result]);
    }
}
?>