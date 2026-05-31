<?php
session_start();

if(isset($_SESSION['user'])){
    header("Location: main.php");
    exit;
}

require_once 'mysql/database.php';
require_once 'user.php';

$db = new database();
$user = new user($db);

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

    $result = $user->login($_POST['username'], $_POST['password']);

    if ($result === true) {
        header("Location: main.php");
        exit;
    }

    if ($result === "INACTIVE" || $result === "EXPIRED") {
        $msg = "Account no longer active";
    } else {
        $msg = "Invalid login";
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
        <a href="login/register.php">Don't have an account?<a>

    <hr>

</div>

</body>
</html>