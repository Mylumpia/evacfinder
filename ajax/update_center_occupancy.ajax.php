<?php
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