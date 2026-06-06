<?php
require_once 'modelis.php';

class atslega extends modelis {

    public function getTips(){
        return "Atslēga";
    }

    public function getAll(){ return $this->conn->query("SELECT * FROM atslegas ORDER BY id DESC"); }
    public function add($nosaukums){
        $stmt=$this->conn->prepare("INSERT INTO atslegas(nosaukums) VALUES(?)");
        $stmt->bind_param("s",$nosaukums);
        $stmt->execute();
    }
    public function exists($nosaukums){
    $stmt = $this->conn->prepare("SELECT id FROM atslegas WHERE nosaukums=?");
    $stmt->bind_param("s", $nosaukums);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
    }

    public function delete($id){
    $stmt = $this->conn->prepare("DELETE FROM atslegas WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
}

?>

