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
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>

<form method="POST">
    <h4>Register</h4>

    <input type="email" name="email" placeholder="email" required>
    <input type="password" name="password" placeholder="Password" required>

    <button name="register">Create account</button>
    <a href="index.php">back</a>

    <p><?php echo $msg; ?></p>
</form>

</body>
</html>