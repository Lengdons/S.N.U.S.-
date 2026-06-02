<?php
class User {
    
    private $conn;
    public function __construct($db){ $this->conn=$db->conn; }

    public function register($epasts,$pass,$expiresAt=null){

    if(!filter_var($epasts, FILTER_VALIDATE_EMAIL)){
        return "Invalid epasts";
    }

    if(!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,}$/', $pass)){
        return "parole must be 8+ chars, include uppercase, number, symbol";
    }

    // check duplicate epasts
    $check = $this->conn->prepare("SELECT id FROM lietotaji WHERE epasts=?");
    $check->bind_param("s", $epasts);
    $check->execute();

    if($check->get_result()->num_rows > 0){
        return "epasts already exists";
    }

    $pass = password_hash($pass, PASSWORD_BCRYPT);

    $stmt = $this->conn->prepare("
        INSERT INTO lietotaji(epasts,parole,loma, beigu_term, aktivs)
        VALUES(?,?,'temp', ?, 1)
    ");

    $stmt->bind_param("sss", $epasts, $pass, $expiresAt);

    return $stmt->execute() ? true : "Registration failed";
}



    public function login($u,$p){
        $stmt = $this->conn->prepare("SELECT * FROM lietotaji WHERE epasts = ? LIMIT 1");

        $stmt->bind_param("s", $u);
        $stmt->execute();
        $res = $stmt->get_result();

        if (!$res || $res->num_rows === 0) {
            return false;
        }

        $row = $res->fetch_assoc();

        // check parole
        if (!password_verify($p, $row['parole'])) {
            return false;
        }

         // checks if account is inactive
        if((int)$row['aktivs'] === 0){
        return "INACTIVE";
        }

        // 3. check expiry
        if(!empty($row['beigu_term']) && strtotime($row['beigu_term']) < time()){

        // auto-disable expired account
        $up = $this->conn->prepare("
            UPDATE lietotaji 
            SET aktivs = 0 
            WHERE id = ?
        ");
        $up->bind_param("i", $row['id']);
        $up->execute();

        return "EXPIRED";
        }

        // login success
        $_SESSION['user'] = $u;
        $_SESSION['lietotajs_id'] = $row['id'];
        $_SESSION['loma'] = $row['loma'];

        return true;
    }
}
?>