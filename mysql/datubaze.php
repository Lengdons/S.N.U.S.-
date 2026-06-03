<?php
class Datubaze {
    public $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost","root","","atslegas_sis");
        if ($this->conn->connect_error) die("DB Error");
    }
}
?>