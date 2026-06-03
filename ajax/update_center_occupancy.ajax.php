<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "../models/connection.php";

// Set header to return JSON
header('Content-Type: application/json');

if(isset($_POST["center_id"]) && isset($_POST["current_occupants"])) {
    $center_id = $_POST["center_id"];
    $current_occupants = (int)$_POST["current_occupants"];
    
    // Update the center's current occupants
    $db = new Connection();
    $pdo = $db->connect();
    
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Update current occupants
        $stmt = $pdo->prepare("UPDATE centers SET current_occupants = :current_occupants WHERE center_id = :center_id");
        $stmt->bindParam(":center_id", $center_id);
        $stmt->bindParam(":current_occupants", $current_occupants);
        $stmt->execute();
        
        // Get capacity to check if center is full
        $stmt = $pdo->prepare("SELECT capacity, current_occupants FROM centers WHERE center_id = :center_id");
        $stmt->bindParam(":center_id", $center_id);
        $stmt->execute();
        $center = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($center) {
            // Update status based on occupancy
            $newStatus = ($center['current_occupants'] >= $center['capacity']) ? 'Full' : 'Active';
            $stmt2 = $pdo->prepare("UPDATE centers SET status = :status WHERE center_id = :center_id");
            $stmt2->bindParam(":status", $newStatus);
            $stmt2->bindParam(":center_id", $center_id);
            $stmt2->execute();
        }
        
        $pdo->commit();
        // Save history snapshot
        require_once "../controllers/history.controller.php";
        require_once "../models/history.model.php";
        require_once "../models/centers.model.php";

        $center = ModelCenters::mdlGetCenterById($center_id);

        if($center) {
            $historyData = array(
                "center_id"            => $center["center_id"],
                "center_name"          => $center["center_name"],
                "category"             => $center["category"],
                "status"               => $center["status"],
                "barangay"             => $center["barangay"],
                "city"                 => $center["city"],
                "province"             => $center["province"],
                "address"              => $center["address"],
                "capacity"             => $center["capacity"],
                "max_persons"          => $center["max_persons"],
                "current_occupants"    => $center["current_occupants"],
                "contact_number"       => $center["contact_number"],
                "contact_person"       => $center["contact_person"],
                "date_established"     => $center["date_established"],
                "facilities"           => $center["facilities"],
                "remarks"              => $center["remarks"],
                "encodedby"            => $_SESSION['userid'],
                "latitude"             => $center["latitude"],
                "longitude"            => $center["longitude"],
                "estimated_capacity"   => $center["estimated_capacity"],
                "accessibility"        => $center["accessibility"],
                "available_facilities" => $center["available_facilities"],
                "assigned_lgu_user_id" => $center["assigned_lgu_user_id"],
                "action_made"          => "Occupancy Updated"
            );

            ControllerHistory::ctrSaveHistory($historyData);
        }

        echo json_encode(["success" => true, "message" => "Occupancy updated successfully"]);
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Update error: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
    
} else {
    echo json_encode(["success" => false, "message" => "Missing required parameters"]);
}
?>