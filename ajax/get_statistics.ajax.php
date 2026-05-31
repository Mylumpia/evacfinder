<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

$db = new Connection();
$pdo = $db->connect();

try {
    // Get currently occupied (count active evacuees)
    $stmt = $pdo->prepare("SELECT COUNT(*) AS currently_occupied FROM evacuees WHERE evacuee_status = 'Active'");
    $stmt->execute();
    $currentlyOccupied = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "success" => true,
        "currently_occupied" => $currentlyOccupied['currently_occupied']
    ]);
    
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>