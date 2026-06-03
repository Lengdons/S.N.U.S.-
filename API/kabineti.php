<?php

require '../mysql/database.php';
require '../classes/Room.php';

$db = new database();
$room = new Room($db);

$result = $room->getAll();
$rooms = [];

while($row = $result->fetch_assoc()){$rooms[]=$row;}

header('Content-Type: application/json');
echo json_encode($rooms);

?>