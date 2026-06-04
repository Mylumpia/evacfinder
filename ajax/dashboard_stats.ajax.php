<?php
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

$db = new Connection();
$pdo = $db->connect();

try {
    // Get total centers
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM centers");
    $stmt->execute();
    $totalCenters = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get active centers
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM centers WHERE status = 'Active'");
    $stmt->execute();
    $activeCenters = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get total active evacuees
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM evacuees WHERE evacuee_status = 'Active'");
    $stmt->execute();
    $totalEvacuees = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get total capacity
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(capacity), 0) as total FROM centers");
    $stmt->execute();
    $totalCapacity = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get occupancy trend for last 7 days
    $trendDates = [];
    $trendValues = [];
    
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} days"));
        $trendDates[] = date('M d', strtotime($date));
        
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total FROM evacuees 
            WHERE evacuee_status = 'Active' 
            AND DATE(created_at) <= :date
        ");
        $stmt->bindParam(":date", $date);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        $trendValues[] = $count['total'];
    }
    
    // Get status distribution
    $stmt = $pdo->prepare("
        SELECT status, COUNT(*) as count FROM centers 
        GROUP BY status
    ");
    $stmt->execute();
    $statusDist = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $statusDist[$row['status']] = $row['count'];
    }
    
    echo json_encode([
        "success" => true,
        "total_centers" => $totalCenters['total'],
        "active_centers" => $activeCenters['total'],
        "total_evacuees" => $totalEvacuees['total'],
        "total_capacity" => $totalCapacity['total'],
        "occupancy_trend" => [
            "dates" => $trendDates,
            "values" => $trendValues
        ],
        "status_distribution" => $statusDist
    ]);
    
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>