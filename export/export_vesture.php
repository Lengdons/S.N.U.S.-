<?php
session_start();
require_once '../mysql/datubaze.php';

if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    die("No permission");
}

$db = new datubaze();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="zurnali.csv"');

$output = fopen("php://output", "w");

// headers
fputcsv($output, ['ID', 'Darbība', 'Veidota']);

$result = $db->conn->query("SELECT id, darbiba, veidota FROM zurnali ORDER BY id DESC");

while($row = $result->fetch_assoc()){
    fputcsv($output, $row);
}

fclose($output);
exit;