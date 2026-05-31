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

// Check if status_history table exists
$stmt = $pdo->prepare("SHOW TABLES LIKE 'center_status_history'");
$stmt->execute();
if($stmt->rowCount() == 0) {
    // Create status history table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `center_status_history` (
            `history_id` INT(11) NOT NULL AUTO_INCREMENT,
            `center_id` VARCHAR(50) NOT NULL,
            `old_status` VARCHAR(50),
            `new_status` VARCHAR(50) NOT NULL,
            `changed_by` VARCHAR(100),
            `changed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`history_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

$stmt = $pdo->prepare("
    SELECT * FROM center_status_history 
    WHERE center_id = :center_id 
    ORDER BY changed_at DESC 
    LIMIT 20
");
$stmt->bindParam(":center_id", $center_id);
$stmt->execute();

$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["success" => true, "history" => $history]);
?>