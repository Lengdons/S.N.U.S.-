<?php
session_start();

require_once 'mysql/Database.php';
require_once 'User.php';

$db = new Database();
$user = new User($db);

$msg = "";

if(isset($_POST['register'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $user->register($email,$password);

    if($result === true){
        header("Location: index.php");
        exit;
    } else {
        $msg = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>

<form method="POST">
    <h4>Register</h4>

    <input type="email" id="email" name="email" placeholder="Email" required>

    <input type="password" id="password" name="password" placeholder="Password" required>

    <div id="requirements">
        <p id="len" class="invalid">• At least 8 characters</p>
        <p id="upper" class="invalid">• One uppercase letter</p>
        <p id="num" class="invalid">• One number</p>
        <p id="sym" class="invalid">• One special character</p>
    </div>

    <button id="registerBtn" name="register" disabled>Create account</button>
    <a href="index.php">back</a>

    <p><?php echo $msg; ?></p>
</form>

</body>
</html>