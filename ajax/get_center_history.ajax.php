<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

if(!isset($_POST["center_id"])) {
    echo json_encode(["success" => false, "history" => []]);
    exit;
}

$center_id = $_POST["center_id"];

$db = new Connection();
$pdo = $db->connect();

// Check if center_history table exists, if not create it
$stmt = $pdo->prepare("SHOW TABLES LIKE 'center_history'");
$stmt->execute();
if($stmt->rowCount() == 0) {
    // Create history table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `center_history` (
            `history_id` INT(11) NOT NULL AUTO_INCREMENT,
            `center_id` VARCHAR(50) NOT NULL,
            `action_type` VARCHAR(50) NOT NULL,
            `description` TEXT,
            `performed_by` VARCHAR(100),
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`history_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

$stmt = $pdo->prepare("
    SELECT * FROM center_history 
    WHERE center_id = :center_id 
    ORDER BY created_at DESC 
    LIMIT 50
");
$stmt->bindParam(":center_id", $center_id);
$stmt->execute();

$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["success" => true, "history" => $history]);
?>