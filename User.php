<?php
class User {
    private $conn;
    public function __construct($db){ $this->conn=$db->conn; }

    public function register($u,$p){
        $p=md5($p);
        $stmt=$this->conn->prepare("INSERT INTO users(username,password,role) VALUES(?,?,'user')");
        $stmt->bind_param("ss",$u,$p);
        return $stmt->execute();
    }

    public function login($u,$p){
        $p=md5($p);
        $stmt=$this->conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
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