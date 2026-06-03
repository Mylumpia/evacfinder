<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../controllers/centers.controller.php";
require_once __DIR__ . "/../models/centers.model.php";

// Set header to return JSON
header('Content-Type: application/json');

if(isset($_POST["center_id"]) && isset($_POST["center_name"])) {
    
    $data = array(
        "center_id" => $_POST["center_id"],
        "center_name" => $_POST["center_name"],
        "category" => $_POST["category"],
        "status" => isset($_POST["status"]) ? $_POST["status"] : "",
        "address" => $_POST["address"],
        "barangay" => $_POST["barangay"],
        "city" => $_POST["city"],
        "province" => $_POST["province"],
        "capacity" => isset($_POST["capacity"]) ? (int)$_POST["capacity"] : 0,
        "estimated_capacity" => isset($_POST["estimated_capacity"]) && $_POST["estimated_capacity"] !== "" ? (int)$_POST["estimated_capacity"] : (isset($_POST["capacity"]) ? (int)$_POST["capacity"] : 0),
        "current_occupants" => isset($_POST["current_occupants"]) ? (int)$_POST["current_occupants"] : 0,
        "contact_number" => isset($_POST["contact_number"]) ? $_POST["contact_number"] : "",
        "contact_person" => isset($_POST["contact_person"]) ? $_POST["contact_person"] : "",
        "latitude" => isset($_POST["latitude"]) && $_POST["latitude"] !== "" ? $_POST["latitude"] : null,
        "longitude" => isset($_POST["longitude"]) && $_POST["longitude"] !== "" ? $_POST["longitude"] : null,
        "accessibility" => isset($_POST["accessibility"]) ? $_POST["accessibility"] : "",
        "available_facilities" => isset($_POST["available_facilities"]) ? $_POST["available_facilities"] : "",
        "remarks" => isset($_POST["remarks"]) ? $_POST["remarks"] : ""
    );
    
    $result = ModelCenters::mdlUpdateCenter($data);

    if($result) {
        // Fetch full center data for history snapshot
        $center = ModelCenters::mdlGetCenterById($data["center_id"]);

        if($center) {
            require_once "../controllers/history.controller.php";
            require_once "../models/history.model.php";

            $historyData = array(
                "center_id"            => $center["center_id"],
                "center_name"          => $center["center_name"],
                "category"             => $center["category"],
                "status"               => $center["status"],
                "barangay"             => $center["barangay"],
                "city"                 => $center["city"],
                "province"             => $center["province"],
                "address"              => $center["address"],
                "capacity"             => $center["capacity"],
                "max_persons"          => $center["max_persons"],
                "current_occupants"    => $center["current_occupants"],
                "contact_number"       => $center["contact_number"],
                "contact_person"       => $center["contact_person"],
                "date_established"     => $center["date_established"],
                "facilities"           => $center["facilities"],
                "remarks"              => $center["remarks"],
                "encodedby"            => $_SESSION['userid'],
                "latitude"             => $center["latitude"],
                "longitude"            => $center["longitude"],
                "estimated_capacity"   => $center["estimated_capacity"],
                "accessibility"        => $center["accessibility"],
                "available_facilities" => $center["available_facilities"],
                "assigned_lgu_user_id" => $center["assigned_lgu_user_id"],
                "action_made"          => "Updated"
            );

            ControllerHistory::ctrSaveHistory($historyData);
        }

        echo json_encode(["success" => true, "message" => "Center updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update center"]);
    }


} else {
    echo json_encode(["success" => false, "message" => "Missing required parameters"]);
}
?>