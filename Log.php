<?php
class Log {
    private $conn;

    public function __construct($db){
        $this->conn = $db->conn;
    }

    public function add($t){
        $stmt = $this->conn->prepare("INSERT INTO logs(action) VALUES(?)");
        $stmt->bind_param("s", $t);
        $stmt->execute();
    }

    public function all(){
        return $this->conn->query("SELECT * FROM logs ORDER BY created_at DESC");
    }

     public function clear(){
        return $this->conn->query("DELETE FROM logs");
    }
}
?>