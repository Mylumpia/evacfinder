<?php
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

if(isset($_POST["evacuee_id"]) && isset($_POST["evacuee_status"])) {
    $evacueeId = $_POST["evacuee_id"];
    $status = $_POST["evacuee_status"];
    $transferCenterId = isset($_POST["transfer_center_id"]) && $_POST["transfer_center_id"] != "" ? $_POST["transfer_center_id"] : null;
    $oldCenterId = isset($_POST["center_id"]) ? $_POST["center_id"] : null;
    $remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : "";
    
    $db = new Connection();
    $pdo = $db->connect();
    
    try {
        $pdo->beginTransaction();
        
        // Get evacuee current info
        $stmt = $pdo->prepare("SELECT evacuation_center_id FROM evacuees WHERE evacuee_id = :evacuee_id");
        $stmt->bindParam(":evacuee_id", $evacueeId);
        $stmt->execute();
        $evacuee = $stmt->fetch(PDO::FETCH_ASSOC);
        $actualOldCenterId = $evacuee ? $evacuee['evacuation_center_id'] : $oldCenterId;
        
        // Update evacuee status
        $stmt = $pdo->prepare("UPDATE evacuees SET evacuee_status = :status, departure_date = CASE WHEN :status IN ('Departed', 'Transferred', 'Deceased', 'Missing') THEN NOW() ELSE departure_date END WHERE evacuee_id = :evacuee_id");
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":evacuee_id", $evacueeId);
        $stmt->execute();
        
        // If transferred, update center
        if ($status == 'Transferred' && $transferCenterId) {
            // Update evacuee's center
            $stmt = $pdo->prepare("UPDATE evacuees SET evacuation_center_id = :new_center_id WHERE evacuee_id = :evacuee_id");
            $stmt->bindParam(":new_center_id", $transferCenterId);
            $stmt->bindParam(":evacuee_id", $evacueeId);
            $stmt->execute();
            
            // Decrease old center occupancy
            if ($actualOldCenterId) {
                $stmt = $pdo->prepare("UPDATE centers SET current_occupants = current_occupants - 1 WHERE center_id = :center_id AND current_occupants > 0");
                $stmt->bindParam(":center_id", $actualOldCenterId);
                $stmt->execute();
            }
            
            // Increase new center occupancy
            $stmt = $pdo->prepare("UPDATE centers SET current_occupants = current_occupants + 1 WHERE center_id = :center_id");
            $stmt->bindParam(":center_id", $transferCenterId);
            $stmt->execute();
        } elseif ($status == 'Departed' && $actualOldCenterId) {
            // Decrease center occupancy
            $stmt = $pdo->prepare("UPDATE centers SET current_occupants = current_occupants - 1 WHERE center_id = :center_id AND current_occupants > 0");
            $stmt->bindParam(":center_id", $actualOldCenterId);
            $stmt->execute();
        }
        
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}
?>