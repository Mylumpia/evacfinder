<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "../controllers/centers.controller.php";
require_once "../models/centers.model.php";

if(isset($_POST["center_id"])) {
    $centerId = $_POST["center_id"];
    
    $db = new Connection();
    $pdo = $db->connect();
    
    $stmt = $pdo->prepare("SELECT * FROM centers WHERE center_id = :center_id");
    $stmt->bindParam(":center_id", $centerId);
    $stmt->execute();
    
    $center = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($center) {
        echo json_encode($center);
    } else {
        echo json_encode(['error' => 'Center not found']);
    }
} else {
    echo json_encode(['error' => 'No center ID provided']);
}
?>