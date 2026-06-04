<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

if(!isset($_POST["center_id"]) || !isset($_POST["new_status"])) {
    echo json_encode(["success" => false, "message" => "Missing required parameters"]);
    exit;
}

$center_id = $_POST["center_id"];
$new_status = $_POST["new_status"];
$performed_by = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "System";

$db = new Connection();
$pdo = $db->connect();

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();
    
    // Get current status and info
    $stmt = $pdo->prepare("SELECT status, center_name FROM centers WHERE center_id = :center_id");
    $stmt->bindParam(":center_id", $center_id);
    $stmt->execute();
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    $old_status = $current['status'];
    $center_name = $current['center_name'];
    
    $message = "";
    
    // When changing to INACTIVE - clear all evacuees from this center
    if ($new_status == 'Inactive') {
        // Get all evacuees in this center (both active and departed)
        $stmt = $pdo->prepare("SELECT evacuee_id, first_name, last_name, evacuee_status FROM evacuees WHERE evacuation_center_id = :center_id");
        $stmt->bindParam(":center_id", $center_id);
        $stmt->execute();
        $allEvacuees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $evacueeCount = count($allEvacuees);
        
        if ($evacueeCount > 0) {
            // Update all evacuees - remove them from this center
            $updateStmt = $pdo->prepare("UPDATE evacuees SET evacuation_center_id = NULL WHERE evacuation_center_id = :center_id");
            $updateStmt->bindParam(":center_id", $center_id);
            $updateStmt->execute();
            
            $message = $evacueeCount . " evacuee(s) were cleared from this center.";
        } else {
            $message = "No evacuees to clear.";
        }
    }
    
    // When changing to ACTIVE from INACTIVE - fresh start
    if ($new_status == 'Active' && $old_status == 'Inactive') {
        $message = "Center reactivated for new operations.";
    }
    
    // Update center status
    $centerStmt = $pdo->prepare("UPDATE centers SET status = :status WHERE center_id = :center_id");
    $centerStmt->bindParam(":status", $new_status);
    $centerStmt->bindParam(":center_id", $center_id);
    $centerStmt->execute();
    
    // Log center status change
    $historyStmt = $pdo->prepare("
        INSERT INTO center_history (center_id, action_type, description, performed_by) 
        VALUES (:center_id, 'CENTER_UPDATED', :description, :performed_by)
    ");
    $description = "Center status changed from " . $old_status . " to " . $new_status . ". " . $message;
    $historyStmt->bindParam(":center_id", $center_id);
    $historyStmt->bindParam(":description", $description);
    $historyStmt->bindParam(":performed_by", $performed_by);
    $historyStmt->execute();
    
    // Log in status history table
    $statusHistoryStmt = $pdo->prepare("
        INSERT INTO center_status_history (center_id, old_status, new_status, changed_by) 
        VALUES (:center_id, :old_status, :new_status, :changed_by)
    ");
    $statusHistoryStmt->bindParam(":center_id", $center_id);
    $statusHistoryStmt->bindParam(":old_status", $old_status);
    $statusHistoryStmt->bindParam(":new_status", $new_status);
    $statusHistoryStmt->bindParam(":changed_by", $performed_by);
    $statusHistoryStmt->execute();
    
    $pdo->commit();
    
    $finalMessage = "Center status changed to " . $new_status . ". " . $message;
    echo json_encode(["success" => true, "message" => $finalMessage]);
    
} catch(Exception $e) {
    $pdo->rollBack();
    error_log("Change status error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>