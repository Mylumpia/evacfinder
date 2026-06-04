<?php
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

$db = new Connection();
$pdo = $db->connect();

$today = date('Y-m-d');

try {
    // Get all centers
    $stmt = $pdo->prepare("SELECT center_id, center_name, status FROM centers");
    $stmt->execute();
    $centers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalEvacuees = 0;
    $centersCount = count($centers);
    
    foreach ($centers as $center) {
        $center_id = $center['center_id'];
        
        // Get today's arrivals
        $stmtArr = $pdo->prepare("SELECT COUNT(*) as count FROM evacuees WHERE evacuation_center_id = :center_id AND DATE(arrival_date) = :today");
        $stmtArr->bindParam(":center_id", $center_id);
        $stmtArr->bindParam(":today", $today);
        $stmtArr->execute();
        $arrivals = $stmtArr->fetch(PDO::FETCH_ASSOC);
        
        // Get today's departures
        $stmtDep = $pdo->prepare("SELECT COUNT(*) as count FROM evacuees WHERE evacuation_center_id = :center_id AND DATE(departure_date) = :today");
        $stmtDep->bindParam(":center_id", $center_id);
        $stmtDep->bindParam(":today", $today);
        $stmtDep->execute();
        $departures = $stmtDep->fetch(PDO::FETCH_ASSOC);
        
        // Get current active count
        $stmtAct = $pdo->prepare("SELECT COUNT(*) as count FROM evacuees WHERE evacuation_center_id = :center_id AND evacuee_status = 'Active'");
        $stmtAct->bindParam(":center_id", $center_id);
        $stmtAct->execute();
        $active = $stmtAct->fetch(PDO::FETCH_ASSOC);
        
        $totalEvacuees += $active['count'];
        
        // Save to daily report table
        $stmtInsert = $pdo->prepare("
            INSERT INTO daily_center_reports (center_id, center_name, report_date, active_evacuees, arrivals_today, departures_today, center_status)
            VALUES (:center_id, :center_name, :report_date, :active, :arrivals, :departures, :status)
            ON DUPLICATE KEY UPDATE
                active_evacuees = :active2,
                arrivals_today = :arrivals2,
                departures_today = :departures2,
                center_status = :status2
        ");
        
        $stmtInsert->bindParam(":center_id", $center_id);
        $stmtInsert->bindParam(":center_name", $center['center_name']);
        $stmtInsert->bindParam(":report_date", $today);
        $stmtInsert->bindParam(":active", $active['count']);
        $stmtInsert->bindParam(":arrivals", $arrivals['count']);
        $stmtInsert->bindParam(":departures", $departures['count']);
        $stmtInsert->bindParam(":status", $center['status']);
        $stmtInsert->bindParam(":active2", $active['count']);
        $stmtInsert->bindParam(":arrivals2", $arrivals['count']);
        $stmtInsert->bindParam(":departures2", $departures['count']);
        $stmtInsert->bindParam(":status2", $center['status']);
        $stmtInsert->execute();
    }
    
    echo json_encode([
        "success" => true,
        "date" => date('F d, Y'),
        "total_centers" => $centersCount,
        "total_evacuees" => $totalEvacuees,
        "message" => "End of day report saved successfully"
    ]);
    
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>