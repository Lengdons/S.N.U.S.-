<?php
session_start();

require_once '../mysql/datubaze.php';
require_once '../klases/lietotajs.php';

$db = new datubaze();
$lietotajs = new lietotajs($db);

$msg = "";

if(isset($_POST['registreties'])){
    $epasts = $_POST['epasts'];
    $parole = $_POST['parole'];

    $result = $lietotajs->registreties($epasts,$parole);

    if($result === true){
        header("Location: ../index.php");
        exit;
    } else {
        $msg = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../style.css">
    <script src="../script.js"></script>
    <meta charset="UTF-8">
    <title>Reģistrēties</title>
</head>
<body>

<form method="POST">
    <h4>Reģistrēties</h4>

    <input type="email" id="epasts" name="epasts" placeholder="Epasts" required>

    <input type="password" id="parole" name="parole" placeholder="Parole" required>

    <div class="parole-wrapper">

    <div id="paroleBubble" class="parole-bubble">
        <p id="len" class="invalid">• Parolei jābūt vismaz 8 rakstzīmēm</p>
        <p id="upper" class="invalid">• Parolei jāsatur vismaz viens lielais burts</p>
        <p id="num" class="invalid">• Parolei jāsatur vismaz viens cipars</p>
        <p id="sym" class="invalid">• Parole jāsatur vismaz viens simbols !@#$%</p>
    </div>

    </div>

    <button id="registretiesBtn" name="registreties" disabled>Izveidot kontu</button>
    <a href="../index.php">Atpakaļ</a>

    <p><?php echo $msg; ?></p>
</form>

</body>
</html>