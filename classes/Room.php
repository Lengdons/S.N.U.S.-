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
    public function exists($name){
    $stmt = $this->conn->prepare("SELECT id FROM rooms WHERE name=?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
    }

    public function delete($id){
    $stmt = $this->conn->prepare("DELETE FROM rooms WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
}

?>

