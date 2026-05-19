<?php
require_once "controllers/template.controller.php";

require_once "controllers/userrights.controller.php";
require_once "models/userrights.model.php";

require_once "controllers/centers.controller.php";
require_once "models/centers.model.php";

$template = new ControllerTemplate();
$template->ctrTemplate();