<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../controllers/announcement.controller.php";
require_once "../models/announcement.model.php";

class Announcement {

    public $trans_type;
    public $encodedby;
    public $ann_type;
    public $ann_desc;
    public $ann_title;

    public function saveAnnouncement() {
        error_log("saveAnnouncement called - trans_type: " . $this->trans_type);

        $data = array(
            "ann_type"  => $this->ann_type,
            "ann_desc"  => $this->ann_desc,
            "ann_title"  => $this->ann_title,
            "encodedby" => $this->encodedby,
        );

        error_log("Data to save: " . print_r($data, true));

        if ($this->trans_type == "New") {
            $answer = (new ControllerAnnouncement)->ctrSaveAnnouncement($data);
            error_log("Answer from controller: " . $answer);
            echo $answer;
        } else {
            echo "error: invalid trans_type";
        }
    }

}

// Check if POST data exists
if (empty($_POST)) {
    echo "error: no POST data";
    exit;
}

$save_announcement = new Announcement();

$save_announcement->trans_type = isset($_POST["trans_type"]) ? $_POST["trans_type"] : "";
$save_announcement->encodedby  = isset($_POST["encodedby"])  ? $_POST["encodedby"]  : "";
$save_announcement->ann_title  = isset($_POST["ann_title"])  ? $_POST["ann_title"]  : "";
$save_announcement->ann_type   = isset($_POST["ann_type"])   ? $_POST["ann_type"]   : "";
$save_announcement->ann_desc   = isset($_POST["ann_desc"])   ? $_POST["ann_desc"]   : "";

$save_announcement->saveAnnouncement();
?>