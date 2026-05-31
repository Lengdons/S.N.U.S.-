<?php
session_start();
require_once 'mysql/database.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    die("No permission");
}

$db = new Database();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="logs.csv"');

$output = fopen("php://output", "w");

// headers
fputcsv($output, ['ID', 'Action', 'Created At']);

$result = $db->conn->query("SELECT id, action, created_at FROM logs ORDER BY id DESC");

while($row = $result->fetch_assoc()){
    fputcsv($output, $row);
}

fclose($output);
exit;