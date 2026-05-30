<?php
require_once "../models/connection.php";

header('Content-Type: application/json');

if(isset($_POST["center_id"])) {
    $centerId = $_POST["center_id"];
    
    $db = new Connection();
    $pdo = $db->connect();
    
    $stmt = $pdo->prepare("SELECT * FROM evacuees WHERE evacuation_center_id = :center_id ORDER BY arrival_date DESC, last_name, first_name");
    $stmt->bindParam(":center_id", $centerId);
    $stmt->execute();
    
    $evacuees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'evacuees' => $evacuees]);
} else {
    echo json_encode(['success' => false, 'message' => 'No center ID provided']);
}
?>