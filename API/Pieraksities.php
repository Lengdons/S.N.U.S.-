<?php
session_start();
header('Content-Type: application/json');

require '../mysql/database.php';
require '../classes/user.php';

$db = new database();
$user = new user($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $user->login($username, $password);

    if ($result === true) {
        echo json_encode(["status" => "success", "message" => "Login successful"]);
    } elseif ($result === "INACTIVE" || $result === "EXPIRED") {
        echo json_encode(["status" => "error", "message" => "Account no longer active"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid login credentials"]);
    }
}
?>





