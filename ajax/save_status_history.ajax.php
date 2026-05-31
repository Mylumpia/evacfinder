<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../models/connection.php";

header('Content-Type: application/json');

if(!isset($_POST["center_id"]) || !isset($_POST["new_status"])) {
    echo json_encode(["success" => false]);
    exit;
}

$center_id = $_POST["center_id"];
$new_status = $_POST["new_status"];
$old_status = isset($_POST["old_status"]) ? $_POST["old_status"] : null;
$changed_by = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "System";

$db = new Connection();
$pdo = $db->connect();

// Check if table exists
$stmt = $pdo->prepare("SHOW TABLES LIKE 'center_status_history'");
$stmt->execute();
if($stmt->rowCount() == 0) {
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
    INSERT INTO center_status_history (center_id, old_status, new_status, changed_by) 
    VALUES (:center_id, :old_status, :new_status, :changed_by)
");
$stmt->bindParam(":center_id", $center_id);
$stmt->bindParam(":old_status", $old_status);
$stmt->bindParam(":new_status", $new_status);
$stmt->bindParam(":changed_by", $changed_by);
$stmt->execute();

echo json_encode(["success" => true]);
?>