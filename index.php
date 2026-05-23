<?php
session_start();

if(isset($_SESSION['user'])){
    header("Location: main.php");
    exit;
}

require_once 'mysql/Database.php';
require_once 'User.php';

$db = new Database();
$user = new User($db);

$msg = "";

/*
-------------------------
HANDLE REGISTER
-------------------------
*/
if (isset($_POST['register'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    if ($user->register($u, $p)) {
        $msg = "Account created! You can now login.";
    } else {
        $msg = "Registration failed.";
    }
}

/*
-------------------------
HANDLE LOGIN
-------------------------
*/
if (isset($_POST['login'])) {
    if ($user->login($_POST['username'], $_POST['password'])) {
        header("Location: main.php");
        exit;
    } else {
        $msg = "Invalid login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>

<div class="box">

    <h2>Room Booking Login</h2>

    <div class="msg"><?php echo $msg; ?></div>

    <!-- LOGIN -->
    <form method="POST">
        <h4>Login</h4>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>
        <a href="acc.php">Don't have an account?<a>

    <hr>

</div>

</body>
</html>