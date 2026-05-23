<?php
class User {
    private $conn;
    public function __construct($db){ $this->conn=$db->conn; }

    public function register($email,$pass){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "Invalid email";
    }

    if(!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,}$/', $pass)){
        return "Password must be 8+ chars, include uppercase, number, symbol";
    }

    $pass = md5($pass);

    $stmt = $this->conn->prepare("INSERT INTO users(email,password,role) VALUES(?,?,'user')");
    $stmt->bind_param("ss",$email,$pass);

    return $stmt->execute() ? true : "User exists";
    }

    public function login($u,$p){
        $p=md5($p);
        $stmt=$this->conn->prepare("SELECT * FROM users WHERE email=? AND password=?");
        $stmt->bind_param("ss",$u,$p);
        $stmt->execute();
        $res=$stmt->get_result();
        if($res->num_rows){
            $row=$res->fetch_assoc();
            $_SESSION['user']=$u;
            $_SESSION['user_id']=$row['id'];
            $_SESSION['role']=$row['role'];
            return true;
        }
        return false;
    }
}
?>