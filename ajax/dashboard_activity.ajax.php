<?php
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

$db = new Connection();
$pdo = $db->connect();

try {
    $stmt = $pdo->prepare("
        SELECT action_type, description, performed_by, created_at
        FROM center_history 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "success" => true,
        "activities" => $activities
    ]);
    
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>