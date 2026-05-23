<?php
class Room {
    private $conn;
    public function __construct($db){ $this->conn=$db->conn; }
    public function getAll(){ return $this->conn->query("SELECT * FROM rooms"); }
    public function add($name){
        $stmt=$this->conn->prepare("INSERT INTO rooms(name) VALUES(?)");
        $stmt->bind_param("s",$name);
        $stmt->execute();
    }
}
?>