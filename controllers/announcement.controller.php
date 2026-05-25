<?php
class ControllerAnnouncement {

    static public function ctrSaveAnnouncement($data) {
        $answer = (new ModelAnnouncement)->mdlSaveAnnouncement($data);
        return $answer;
    }

    static public function ctrGetAnnouncements() {
        return (new ModelAnnouncement)->mdlGetAnnouncements();
    }

}
?>