<?php
session_start();
require '../config/Database.php';
require '../classes/User.php';
$db=new Database();
$user=new User($db);
if(isset($_POST['reg'])){
    $user->register($_POST['u'],$_POST['p']);
    echo "Account created";
}
?>
<form method="POST">
<input name="u"><input name="p" type="password">
<button name="reg">Register</button>
</form>