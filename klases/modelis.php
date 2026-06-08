<?php

abstract class modelis {

    protected $conn;

    public function __construct($db){
        $this->conn = $db->conn;
    }

    abstract public function getTips();

    public function info(){
        return "Tips: " . $this->getTips();
    }
}