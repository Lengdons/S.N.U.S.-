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
    <title>Registreties</title>
</head>
<body>

<form method="POST">
    <h4>Registreties</h4>

    <input type="email" id="epasts" name="epasts" placeholder="epasts" required>

    <input type="password" id="parole" name="parole" placeholder="parole" required>

    <div class="parole-wrapper">

    <div id="paroleBubble" class="parole-bubble">
        <p id="len" class="invalid">• At least 8 characters</p>
        <p id="upper" class="invalid">• One uppercase letter</p>
        <p id="num" class="invalid">• One number</p>
        <p id="sym" class="invalid">• One special character</p>
    </div>

    </div>

    <button id="registretiesBtn" name="registreties" disabled>Create account</button>
    <a href="../index.php">back</a>

    <p><?php echo $msg; ?></p>
</form>

</body>
</html>