<?php
require_once "../models/centers.model.php";
require_once "../models/connection.php";

header('Content-Type: application/json');

$lguUsers = ModelCenters::mdlGetAvailableLGUUsers();
echo json_encode($lguUsers);
?>