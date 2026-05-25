<?php
require_once "../models/announcement.model.php";
require_once "../controllers/announcement.controller.php";

header('Content-Type: application/json');

$data = ControllerAnnouncement::ctrGetAnnouncements();
echo json_encode($data);