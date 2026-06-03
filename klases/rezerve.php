<?php
class rezerve {
    private $conn;
    public function __construct($db){ $this->conn=$db->conn; }

    public function isAvailable($atslega,$start,$end){
        $stmt=$this->conn->prepare("SELECT * FROM raksti WHERE atslega_id=? AND (start_laiks < ? AND beigu_laiks > ?)");
        $stmt->bind_param("iss",$atslega,$end,$start);
        $stmt->execute();
        return $stmt->get_result()->num_rows===0;
    }

    public function book($lietotajs,$atslega,$start,$end){
        $stmt=$this->conn->prepare("INSERT INTO raksti(lietotajs_id,atslega_id,start_laiks,beigu_laiks) VALUES(?,?,?,?)");
        $stmt->bind_param("iiss",$lietotajs,$atslega,$start,$end);
        $stmt->execute();
    }
    public function delete($id){

    $stmt = $this->conn->prepare("
        DELETE FROM raksti
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

}
?>