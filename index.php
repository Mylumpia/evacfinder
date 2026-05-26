<?php
require_once "controllers/template.controller.php";

require_once "controllers/userrights.controller.php";
require_once "models/userrights.model.php";

require_once "controllers/centers.controller.php";
require_once "models/centers.model.php";

require_once "controllers/evacuees.controller.php";
require_once "models/evacuees.model.php";

$template = new ControllerTemplate();
$template->ctrTemplate();