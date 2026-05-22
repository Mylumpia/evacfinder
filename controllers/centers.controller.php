<?php
class ControllerCenters{
    static public function ctrSaveCenters($data){
        $answer = (new ModelCenters)->mdlSaveCenters($data);
		return $answer;
    }
}