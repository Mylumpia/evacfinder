<?php
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

if(!isset($_POST["ann_title"]) || !isset($_POST["ann_desc"])) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

$db = new Connection();
$pdo = $db->connect();

try {
    // Generate announcement ID
    $stmt = $pdo->prepare("SELECT MAX(id) as max_id FROM announcements");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_id = ($result['max_id'] ?? 0) + 1;
    $announcement_code = 'ANN' . str_pad($next_id, 5, '0', STR_PAD_LEFT);
    
    $encodedby = isset($_SESSION['userid']) ? $_SESSION['userid'] : 'system';
    
    $stmt = $pdo->prepare("
        INSERT INTO announcements (announcement_id, ann_title, ann_type, ann_desc, encodedby, date_created)
        VALUES (:announcement_id, :ann_title, :ann_type, :ann_desc, :encodedby, NOW())
    ");
    $stmt->bindParam(":announcement_id", $announcement_code);
    $stmt->bindParam(":ann_title", $_POST["ann_title"]);
    $stmt->bindParam(":ann_type", $_POST["ann_type"]);
    $stmt->bindParam(":ann_desc", $_POST["ann_desc"]);
    $stmt->bindParam(":encodedby", $encodedby);
    $stmt->execute();
    
    echo json_encode(["success" => true, "message" => "Announcement posted successfully"]);
    
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>