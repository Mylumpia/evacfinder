<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once __DIR__ . "/../models/connection.php";
require_once __DIR__ . "/../models/userrights.model.php";

session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== 'ok') {
    echo json_encode(["success" => false, "message" => "Not authenticated"]);
    exit;
}

$currentUserId = $_SESSION['userid'] ?? null;
$currentEmail = $_SESSION['email'] ?? null;

$newPassword = trim($_POST['newPassword'] ?? '');
$confirmPassword = trim($_POST['confirmPassword'] ?? '');

if ($newPassword === '' || $confirmPassword === '') {
    echo json_encode(["success" => false, "message" => "Please enter both password fields."]);
    exit;
}
if ($newPassword !== $confirmPassword) {
    echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    exit;
}
if (strlen($newPassword) < 8) {
    echo json_encode(["success" => false, "message" => "Password must be at least 8 characters."]);
    exit;
}
if (!preg_match('/[0-9]/', $newPassword)) {
    echo json_encode(["success" => false, "message" => "Password must include at least one number."]);
    exit;
}
if (!preg_match('/[A-Z]/', $newPassword)) {
    echo json_encode(["success" => false, "message" => "Password must include at least one uppercase letter."]);
    exit;
}
if (!preg_match('/[\W_]/', $newPassword)) {
    echo json_encode(["success" => false, "message" => "Password must include at least one symbol."]);
    exit;
}

if (!$currentUserId) {
    echo json_encode(["success" => false, "message" => "Unable to identify your account."]);
    exit;
}

try {
    $updated = ModelUserRights::mdlUpdatePassword($currentUserId, $newPassword, $currentEmail);
    if ($updated) {
        echo json_encode(["success" => true, "message" => "Password updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Unable to update password. Please try again."]);
    }
} catch (Exception $e) {
    error_log("Password update error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Server error occurred."]);
}
