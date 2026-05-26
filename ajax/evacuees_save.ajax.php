<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../controllers/announcement.controller.php";
require_once "../models/announcement.model.php";

class Announcement {

    public $trans_type;
    public $encodedby;
    public $ann_title;
    public $ann_type;
    public $ann_desc;

    public function saveAnnouncement() {
        $data = array(
            "ann_title" => $this->ann_title,
            "ann_type"  => $this->ann_type,
            "ann_desc"  => $this->ann_desc,
            "encodedby" => $this->encodedby,
        );

        if ($this->trans_type == "New") {
            $answer = (new ControllerAnnouncement)->ctrSaveAnnouncement($data);
            echo $answer;
        } else {
            echo "error: invalid trans_type";
        }
    }
}

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