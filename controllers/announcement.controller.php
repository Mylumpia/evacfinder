<?php
class ControllerAnnouncement {

    static public function ctrSaveAnnouncement($data) {
        $answer = (new ModelAnnouncement)->mdlSaveAnnouncement($data);
        return $answer;
    }

    static public function ctrGetAnnouncements($searchTerm = '') {
        return (new ModelAnnouncement)->mdlGetAnnouncements($searchTerm);
    }
   
    static public function ctrUpdateAnnouncement($data) {
        return (new ModelAnnouncement)->mdlUpdateAnnouncement($data);
    }

    

}
?>