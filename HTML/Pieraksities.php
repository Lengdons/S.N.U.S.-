<?php
session_start();

if(isset($_SESSION['user'])){
    header("Location: home/FrontP.php");
    exit;
}

require_once '../mysql/database.php';
require_once '../classes/user.php';

$db = new database();
$user = new user($db);

$msg = "";



// LOGIN

if (isset($_POST['login'])) {

    $result = $user->login($_POST['username'], $_POST['password']);

    if ($result === true) {
        header("Location: home/FrontP.php");
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
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.N.U.S - Saņemšanas & Nodošanas Uzskaites Sistēma</title> 
     <link rel="stylesheet2" href="Pierakstities.css">

    </head>
<body>

<!--             -->
<header> 


    </header>

<!--   Sarokošanās ar php un html    -->
<form method="POST">
    
    <input type="text" name="username" placeholder="Lietotājvārds">
    <input type="password" name="password" placeholder="Parole">
    
    <button type="submit" name="login" class="nav-item btn-login">Pierakstīties</button>

   <div style="color: red; text-align: center;">
    <?php echo $msg; ?>
</div>

</form>


</body>
</html>
    









