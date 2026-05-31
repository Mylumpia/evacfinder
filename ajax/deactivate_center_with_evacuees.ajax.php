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
    
    // Get all active evacuees in this center
    $stmt = $pdo->prepare("SELECT evacuee_id, first_name, last_name FROM evacuees WHERE evacuation_center_id = :center_id AND evacuee_status = 'Active'");
    $stmt->bindParam(":center_id", $center_id);
    $stmt->execute();
    $activeEvacuees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $evacueeCount = count($activeEvacuees);
    
    // Update all active evacuees to 'Departed' status
    if ($evacueeCount > 0) {
        $updateStmt = $pdo->prepare("UPDATE evacuees SET evacuee_status = 'Departed', departure_date = CURDATE() WHERE evacuation_center_id = :center_id AND evacuee_status = 'Active'");
        $updateStmt->bindParam(":center_id", $center_id);
        $updateStmt->execute();
        
        // Log each evacuee status change in history
        foreach ($activeEvacuees as $evacuee) {
            $historyStmt = $pdo->prepare("
                INSERT INTO center_history (center_id, action_type, description, performed_by) 
                VALUES (:center_id, 'EVACUEE_DEPARTED', :description, :performed_by)
            ");
            $description = "Evacuee " . $evacuee['first_name'] . " " . $evacuee['last_name'] . " was automatically departed due to center deactivation";
            $historyStmt->bindParam(":center_id", $center_id);
            $historyStmt->bindParam(":description", $description);
            $historyStmt->bindParam(":performed_by", $performed_by);
            $historyStmt->execute();
        }
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
    $description = "Center status changed to " . $new_status . ". " . $evacueeCount . " active evacuee(s) were automatically departed.";
    $historyStmt->bindParam(":center_id", $center_id);
    $historyStmt->bindParam(":description", $description);
    $historyStmt->bindParam(":performed_by", $performed_by);
    $historyStmt->execute();
    
    // Also log in status history table
    $statusHistoryStmt = $pdo->prepare("
        INSERT INTO center_status_history (center_id, old_status, new_status, changed_by) 
        VALUES (:center_id, (SELECT status FROM centers WHERE center_id = :center_id2), :new_status, :changed_by)
    ");
    $statusHistoryStmt->bindParam(":center_id", $center_id);
    $statusHistoryStmt->bindParam(":center_id2", $center_id);
    $statusHistoryStmt->bindParam(":new_status", $new_status);
    $statusHistoryStmt->bindParam(":changed_by", $performed_by);
    $statusHistoryStmt->execute();
    
    $pdo->commit();
    
    echo json_encode([
        "success" => true, 
        "message" => "Center deactivated successfully. " . $evacueeCount . " evacuee(s) were automatically marked as departed."
    ]);
    
} catch(Exception $e) {
    $pdo->rollBack();
    error_log("Deactivate center error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>