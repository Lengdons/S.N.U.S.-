<?php
session_start();
require_once 'mysql/database.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    die("No permission");
}

$db = new Database();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="bookings.csv"');

$output = fopen("php://output", "w");

// headers
fputcsv($output, ['ID', 'Room', 'User', 'Start Time', 'End Time']);

$result = $db->conn->query("
    SELECT 
        bookings.id,
        rooms.name AS room,
        CONCAT(users.name, ' ', users.surname) AS user,
        bookings.start_time,
        bookings.end_time
    FROM bookings
    JOIN rooms ON rooms.id = bookings.room_id
    JOIN users ON users.id = bookings.user_id
    ORDER BY bookings.id DESC
");

while($row = $result->fetch_assoc()){
    fputcsv($output, $row);
}

fclose($output);
exit;