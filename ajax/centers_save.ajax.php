<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "../controllers/centers.controller.php";
require_once "../models/centers.model.php";


class evacCenter{
    
    public $trans_type; 
    public $encodedby;
    public $center_name;
    public $category;
    public $status;
    public $barangay;
    public $city;
    public $province;
    public $address;
    public $capacity;
    public $max_persons;
    public $current_occupants;
    public $contact_number;
    public $contact_person;
    public $alternate_contact;
    public $date_established;
    public $facilities;
    public $hazard_type;
    public $remarks;

    public function saveEvacCenter(){
        $trans_type = $this->trans_type;
        $encodedby = $this->encodedby;
        $center_name = $this->center_name;
        $category = $this->category;
        $status = $this->status;
        $barangay = $this->barangay;
        $city = $this->city;
        $province = $this->province;
        $address = $this->address;
        $capacity = $this->capacity;
        $max_persons = $this->max_persons;
        $current_occupants = $this->current_occupants;
        $contact_number = $this->contact_number;
        $contact_person = $this->contact_person;
        $alternate_contact = $this->alternate_contact;
        $date_established = $this->date_established;
        $facilities = $this->facilities;
        $hazard_type = $this->hazard_type;
        $remarks = $this->remarks;

        $data = array(
            "center_name"=>$center_name,
            "category"=>$category,
            "status"=>$status,
            "barangay"=>$barangay,
            "city"=>$city,
            "province"=>$province,
            "address"=>$address,
            "capacity"=>$capacity,
            "max_persons"=>$max_persons,
            "current_occupants"=>$current_occupants,
            "contact_number"=>$contact_number,
            "contact_person"=>$contact_person,
            "alternate_contact"=>$alternate_contact,
            "date_established"=>$date_established,
            "facilities"=>$facilities,
            "hazard_type"=>$hazard_type,
            "remarks"=>$remarks,
            "encodedby"=>$encodedby
        );        

        if ($trans_type == "New") {
            $answer = (new ControllerCenters)->ctrSaveCenters($data);
            echo $answer;
        }
    }
}

$save_evacCenter = new evacCenter();

$save_evacCenter -> trans_type = $_POST["trans_type"];
$save_evacCenter -> encodedby = $_POST["encodedby"];
$save_evacCenter -> center_name = $_POST["center_name"];
$save_evacCenter -> category = $_POST["category"];
$save_evacCenter -> status = $_POST["status"];
$save_evacCenter -> barangay = $_POST["barangay"];
$save_evacCenter -> city = $_POST["city"];
$save_evacCenter -> province = $_POST["province"];
$save_evacCenter -> address = $_POST["address"];
$save_evacCenter->capacity = !empty($_POST["capacity"]) ? (int)$_POST["capacity"] : 0;
$save_evacCenter->max_persons = !empty($_POST["max_persons"]) ? (int)$_POST["max_persons"] : 0;
$save_evacCenter->current_occupants = !empty($_POST["current_occupants"]) ? (int)$_POST["current_occupants"] : 0;
$save_evacCenter -> contact_number = $_POST["contact_number"];
$save_evacCenter -> contact_person = $_POST["contact_person"];
$save_evacCenter -> alternate_contact = $_POST["alternate_contact"];
$save_evacCenter -> date_established = $_POST["date_established"];
$save_evacCenter->facilities  = isset($_POST["facilities"])  ? implode(",", $_POST["facilities"])  : "";
$save_evacCenter->hazard_type = isset($_POST["hazard_type"]) ? implode(",", $_POST["hazard_type"]) : "";
$save_evacCenter -> remarks = $_POST["remarks"]; 
$save_evacCenter -> saveEvacCenter();