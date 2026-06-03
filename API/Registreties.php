<?php
session_start();
header('Content-Type: application/json');

require '../mysql/database.php';
require '../classes/user.php';

$db = new database();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // piemēram 30 dienu konts
    $expiresAt = date(
        'Y-m-d H:i:s',
        strtotime('+30 days')
    );

    $result = $user->register(
        $email,
        $password,
        $expiresAt
    );

    if ($result === true) {

        echo json_encode([
            "status" => "success",
            "message" => "Registration successful"
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => $result
        ]);

    }
}
?>