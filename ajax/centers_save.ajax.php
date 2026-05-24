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
    public $latitude;
    public $longitude;
    public $estimated_capacity;
    public $accessibility;
    public $available_facilities;

    public function saveEvacCenter(){
        error_log("saveEvacCenter called - trans_type: " . $this->trans_type);
        
        $data = array(
            "center_name"=>$this->center_name,
            "category"=>$this->category,
            "status"=>$this->status,
            "barangay"=>$this->barangay,
            "city"=>$this->city,
            "province"=>$this->province,
            "address"=>$this->address,
            "capacity"=>$this->capacity,
            "max_persons"=>$this->max_persons,
            "current_occupants"=>$this->current_occupants,
            "contact_number"=>$this->contact_number,
            "contact_person"=>$this->contact_person,
            "alternate_contact"=>$this->alternate_contact,
            "date_established"=>$this->date_established,
            "facilities"=>$this->facilities,
            "hazard_type"=>$this->hazard_type,
            "remarks"=>$this->remarks,
            "encodedby"=>$this->encodedby,
            "latitude"=>$this->latitude,
            "longitude"=>$this->longitude,
            "estimated_capacity"=>$this->estimated_capacity,
            "accessibility"=>$this->accessibility,
            "available_facilities"=>$this->available_facilities
        );        

        error_log("Data to save: " . print_r($data, true));

        if ($this->trans_type == "New") {
            $answer = (new ControllerCenters)->ctrSaveCenters($data);
            error_log("Answer from controller: " . $answer);
            echo $answer;
        } else {
            echo "error: invalid trans_type";
        }
    }
}

// Check if POST data exists
if(empty($_POST)) {
    echo "error: no POST data";
    exit;
}

$save_evacCenter = new evacCenter();

$save_evacCenter->trans_type = isset($_POST["trans_type"]) ? $_POST["trans_type"] : "";
$save_evacCenter->encodedby = isset($_POST["encodedby"]) ? $_POST["encodedby"] : "";
$save_evacCenter->center_name = isset($_POST["center_name"]) ? $_POST["center_name"] : "";
$save_evacCenter->category = isset($_POST["category"]) ? $_POST["category"] : "";
$save_evacCenter->status = isset($_POST["status"]) ? $_POST["status"] : "";
$save_evacCenter->barangay = isset($_POST["barangay"]) ? $_POST["barangay"] : "";
$save_evacCenter->city = isset($_POST["city"]) ? $_POST["city"] : "";
$save_evacCenter->province = isset($_POST["province"]) ? $_POST["province"] : "";
$save_evacCenter->address = isset($_POST["address"]) ? $_POST["address"] : "";
$save_evacCenter->capacity = isset($_POST["capacity"]) && $_POST["capacity"] !== "" ? (int)$_POST["capacity"] : 0;
$save_evacCenter->max_persons = isset($_POST["max_persons"]) && $_POST["max_persons"] !== "" ? (int)$_POST["max_persons"] : 0;
$save_evacCenter->current_occupants = isset($_POST["current_occupants"]) && $_POST["current_occupants"] !== "" ? (int)$_POST["current_occupants"] : 0;
$save_evacCenter->contact_number = isset($_POST["contact_number"]) ? $_POST["contact_number"] : "";
$save_evacCenter->contact_person = isset($_POST["contact_person"]) ? $_POST["contact_person"] : "";
$save_evacCenter->alternate_contact = isset($_POST["alternate_contact"]) ? $_POST["alternate_contact"] : "";
$save_evacCenter->date_established = isset($_POST["date_established"]) && $_POST["date_established"] !== "" ? $_POST["date_established"] : null;
$save_evacCenter->facilities = isset($_POST["facilities"]) ? $_POST["facilities"] : "";
$save_evacCenter->hazard_type = isset($_POST["hazard_type"]) ? $_POST["hazard_type"] : "";
$save_evacCenter->remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : "";
$save_evacCenter->latitude = isset($_POST["latitude"]) && $_POST["latitude"] !== "" ? $_POST["latitude"] : null;
$save_evacCenter->longitude = isset($_POST["longitude"]) && $_POST["longitude"] !== "" ? $_POST["longitude"] : null;
$save_evacCenter->estimated_capacity = isset($_POST["estimated_capacity"]) && $_POST["estimated_capacity"] !== "" ? (int)$_POST["estimated_capacity"] : null;
$save_evacCenter->accessibility = isset($_POST["accessibility"]) ? $_POST["accessibility"] : "";
$save_evacCenter->available_facilities = isset($_POST["available_facilities"]) ? $_POST["available_facilities"] : "";

$save_evacCenter->saveEvacCenter();
?>
