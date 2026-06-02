<?php
require_once __DIR__ . "/../models/history.model.php";

class ControllerHistory {

    static public function ctrSaveHistory($data) {
        $answer = ModelHistory::mdlSaveHistory($data);
        return $answer;
    }

    static public function ctrGetHistory($center_id) {
        $answer = ModelHistory::mdlGetHistory($center_id);
        return $answer;
    }
}
?>