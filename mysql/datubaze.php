<?php
class Datubaze {
    public $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost","root","","atslega_raksts");
        if ($this->conn->connect_error) die("DB Error");
    }
}
?>