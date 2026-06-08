<?php
require_once 'modelis.php';

class zurnals extends modelis {

    public function getTips(){
        return "Žurnāls";
    }

    public function add($t){
        $stmt = $this->conn->prepare("INSERT INTO zurnali(darbiba) VALUES(?)");
        $stmt->bind_param("s", $t);
        $stmt->execute();
    }

    public function all(){
        return $this->conn->query("SELECT * FROM zurnali ORDER BY veidota DESC");
    }

     public function clear(){
        return $this->conn->query("DELETE FROM zurnali");
    }
}
?>