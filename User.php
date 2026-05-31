<?php
class User {
    private $conn;
    public function __construct($db){ $this->conn=$db->conn; }

    public function register($email,$pass,$days=null){

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "Invalid email";
    }

    if(!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,}$/', $pass)){
        return "Password must be 8+ chars, include uppercase, number, symbol";
    }

    // check duplicate email
    $check = $this->conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();

    if($check->get_result()->num_rows > 0){
        return "Email already exists";
    }

    $pass = password_hash($pass, PASSWORD_BCRYPT);

    $expiresAt = null;

    if($days !== null){
        $expiresAt = date('Y-m-d H:i:s', strtotime("+$days days"));
    }

    $stmt = $this->conn->prepare("
        INSERT INTO users(email,password,role, expires_at, is_active)
        VALUES(?,?,'temp', ?, 1)
    ");

    $stmt->bind_param("sss", $email, $pass, $expiresAt);

    return $stmt->execute() ? true : "Registration failed";
}

    public function login($u,$p){
        $stmt = $this->conn->prepare("
            SELECT * 
            FROM users 
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $u);
        $stmt->execute();
        $res = $stmt->get_result();

        if (!$res || $res->num_rows === 0) {
            return false;
        }

        $row = $res->fetch_assoc();

        // check password
        if (!password_verify($p, $row['password'])) {
            return false;
        }

        // optional: check if account is inactive
        if (isset($row['is_active']) && $row['is_active'] == 0) {
            return "ACCOUNT_DISABLED";
        }

        // login success
        $_SESSION['user'] = $u;
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];

        return true;
    }
}
?>