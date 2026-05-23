<?php
class Database {
    public $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost","root","","room_booking");
        if ($this->conn->connect_error) die("DB Error");
    }
}
?>