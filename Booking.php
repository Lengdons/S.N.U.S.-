<?php
class Booking {
    private $conn;
    public function __construct($db){ $this->conn=$db->conn; }

    public function isAvailable($room,$start,$end){
        $stmt=$this->conn->prepare("SELECT * FROM bookings WHERE room_id=? AND (start_time < ? AND end_time > ?)");
        $stmt->bind_param("iss",$room,$end,$start);
        $stmt->execute();
        return $stmt->get_result()->num_rows===0;
    }

    public function book($user,$room,$start,$end){
        $stmt=$this->conn->prepare("INSERT INTO bookings(user_id,room_id,start_time,end_time) VALUES(?,?,?,?)");
        $stmt->bind_param("iiss",$user,$room,$start,$end);
        $stmt->execute();
    }
}
?>