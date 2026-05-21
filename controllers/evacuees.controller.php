<?php
class ControllerEvacuees {
    static public function ctrSaveEvacuee($data) {
        $answer = (new ModelEvacuees)->mdlSaveEvacuee($data);
        return $answer;
    }
    
    static public function ctrGetEvacuees($item, $value) {
        $answer = ModelEvacuees::mdlGetEvacuees($item, $value);
        return $answer;
    }
}
?>