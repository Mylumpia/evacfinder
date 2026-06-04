<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

$exclude_center_id = isset($_POST["exclude_center_id"]) ? $_POST["exclude_center_id"] : null;

$db = new Connection();
$pdo = $db->connect();

if($exclude_center_id) {
    $stmt = $pdo->prepare("
        SELECT center_id, center_name, capacity, current_occupants, latitude, longitude
        FROM centers 
        WHERE center_id != :exclude_center_id AND status = 'Active'
        ORDER BY center_name
    ");
    $stmt->bindParam(":exclude_center_id", $exclude_center_id);
} else {
    $stmt = $pdo->prepare("
        SELECT center_id, center_name, capacity, current_occupants, latitude, longitude
        FROM centers 
        WHERE status = 'Active'
        ORDER BY center_name
    ");
}
$stmt->execute();

$centers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["success" => true, "centers" => $centers]);
?>