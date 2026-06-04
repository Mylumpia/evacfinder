<?php
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

$db = new Connection();
$pdo = $db->connect();

try {
    $stmt = $pdo->prepare("
        SELECT center_id, center_name, category, status, barangay, city, 
               capacity, 
               (SELECT COUNT(*) FROM evacuees WHERE evacuation_center_id = centers.center_id AND evacuee_status = 'Active') as current_occupants
        FROM centers 
        ORDER BY center_name
        LIMIT 10
    ");
    $stmt->execute();
    $centers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "success" => true,
        "centers" => $centers
    ]);
    
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>