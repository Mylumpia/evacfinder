<?php
require_once "../models/announcement.model.php";
require_once "../controllers/announcement.controller.php";

header('Content-Type: application/json');

// Check if search term is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$data = ControllerAnnouncement::ctrGetAnnouncements($searchTerm);
echo json_encode($data);
?>