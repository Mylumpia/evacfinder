<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

if(!isset($_POST["center_id"])) {
    echo json_encode(["success" => false, "evacuees" => []]);
    exit;
}

$center_id = $_POST["center_id"];

$db = new Connection();
$pdo = $db->connect();

$stmt = $pdo->prepare("
    SELECT e.* 
    FROM evacuees e 
    WHERE e.evacuation_center_id = :center_id 
    ORDER BY e.arrival_date DESC, e.last_name, e.first_name
");
$stmt->bindParam(":center_id", $center_id);
$stmt->execute();

$evacuees = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["success" => true, "evacuees" => $evacuees]);
?>