<?php
session_start();

if(isset($_SESSION['lietotajs'])){
    header("Location: sakums/sakumlapa.php");
    exit;
}

require_once 'mysql/datubaze.php';
require_once 'klases/lietotajs.php';

$db = new datubaze();
$lietotajs = new lietotajs($db);

$msg = "";


// registreties

if (isset($_POST['registreties'])) {
    $u = $_POST['epasts'];
    $p = $_POST['parole'];

    if ($lietotajs->registreties($u, $p)) {
        $msg = "Account created! You can now login.";
    } else {
        $msg = "Registration failed.";
    }
}

// LOGIN

if (isset($_POST['login'])) {

    $result = $lietotajs->login($_POST['epasts'], $_POST['parole']);

    if ($result === true) {
        header("Location: sakums/sakumlapa.php");
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

    <h2>atslega raksts Login</h2>

    <div class="msg"><?php echo $msg; ?></div>

    <!-- LOGIN -->
    <form method="POST">
        <h4>Login</h4>
        <input type="text" name="epasts" placeholder="epasts" required>
        <input type="password" name="parole" placeholder="parole" required>
        <button name="login">Login</button>
    </form>
        <a href="login/registreties.php">Don't have an account?<a>

    <hr>

</div>

</body>
</html>