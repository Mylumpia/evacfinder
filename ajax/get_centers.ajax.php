<?php
require_once "../controllers/centers.controller.php";
require_once "../models/centers.model.php";

header('Content-Type: application/json');

if(isset($_POST["action"]) && $_POST["action"] == "get_centers") {
    $centers = ModelCenters::mdlGetCenters();
    echo json_encode($centers);
} else {
    // Return empty array instead of nothing
    echo json_encode([]);
}
?>