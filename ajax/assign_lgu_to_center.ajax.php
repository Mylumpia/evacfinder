<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../models/centers.model.php";
require_once "../models/connection.php";

header('Content-Type: application/json');

if(isset($_POST["center_id"]) && isset($_POST["lgu_user_id"])) {
    $assigned_by = isset($_SESSION["userid"]) ? $_SESSION["userid"] : null;
    $result = ModelCenters::mdlAssignLGUToCenter($_POST["center_id"], $_POST["lgu_user_id"], $assigned_by);
    
    if($result) {
        echo json_encode(["success" => true, "message" => "LGU user assigned successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to assign LGU user"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing required parameters"]);
}
?>