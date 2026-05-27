<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../controllers/evacuees.controller.php";
require_once "../models/evacuees.model.php";

class evacueeRegistration {
    
    public $trans_type;
    public $encodedby;
    public $registration_date;
    public $last_name;
    public $first_name;
    public $middle_name;
    public $extension_name;
    public $relation_to_head;
    public $sex;
    public $birth_date;
    public $age;
    public $civil_status;
    public $occupation;
    public $contact_number;
    public $complete_address;
    public $emergency_contact_person;
    public $emergency_contact_number;
    public $condition_pregnant;
    public $condition_lactating;
    public $condition_elderly;
    public $condition_pwd;
    public $condition_4ps;
    public $pwd_type;
    public $health_status;
    public $emergency_medical_condition;
    public $medications_taken;
    public $known_allergies;
    public $evacuation_center_id;
    public $arrival_date;
    public $departure_date;
    public $evacuee_status;
    public $registered_by_lgu_id;

    public function saveEvacuee() {
        error_log("saveEvacuee called - trans_type: " . $this->trans_type);
        
        $data = array(
            "registration_date" => $this->registration_date,
            "last_name" => $this->last_name,
            "first_name" => $this->first_name,
            "middle_name" => $this->middle_name,
            "extension_name" => $this->extension_name,
            "relation_to_head" => $this->relation_to_head,
            "sex" => $this->sex,
            "birth_date" => $this->birth_date,
            "age" => $this->age,
            "civil_status" => $this->civil_status,
            "occupation" => $this->occupation,
            "contact_number" => $this->contact_number,
            "complete_address" => $this->complete_address,
            "emergency_contact_person" => $this->emergency_contact_person,
            "emergency_contact_number" => $this->emergency_contact_number,
            "condition_pregnant" => $this->condition_pregnant,
            "condition_lactating" => $this->condition_lactating,
            "condition_elderly" => $this->condition_elderly,
            "condition_pwd" => $this->condition_pwd,
            "condition_4ps" => $this->condition_4ps,
            "pwd_type" => $this->pwd_type,
            "health_status" => $this->health_status,
            "emergency_medical_condition" => $this->emergency_medical_condition,
            "medications_taken" => $this->medications_taken,
            "known_allergies" => $this->known_allergies,
            "evacuation_center_id" => $this->evacuation_center_id,
            "arrival_date" => $this->arrival_date,
            "departure_date" => $this->departure_date,
            "evacuee_status" => $this->evacuee_status,
            "encodedby" => $this->encodedby,
            "registered_by_lgu_id" => $this->registered_by_lgu_id
        );

        error_log("Data to save: " . print_r($data, true));

        if ($this->trans_type == "New") {
            $answer = (new ControllerEvacuees)->ctrSaveEvacuee($data);
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

$save_evacuee = new evacueeRegistration();

$save_evacuee->trans_type = isset($_POST["trans_type"]) ? $_POST["trans_type"] : "";
$save_evacuee->encodedby = isset($_POST["encodedby"]) ? $_POST["encodedby"] : "";
$save_evacuee->registration_date = isset($_POST["registration_date"]) ? $_POST["registration_date"] : "";
$save_evacuee->last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
$save_evacuee->first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : "";
$save_evacuee->middle_name = isset($_POST["middle_name"]) ? $_POST["middle_name"] : "";
$save_evacuee->extension_name = isset($_POST["extension_name"]) ? $_POST["extension_name"] : "";
$save_evacuee->relation_to_head = isset($_POST["relation_to_head"]) ? $_POST["relation_to_head"] : "";
$save_evacuee->sex = isset($_POST["sex"]) ? $_POST["sex"] : "";
$save_evacuee->birth_date = isset($_POST["birth_date"]) && $_POST["birth_date"] !== "" ? $_POST["birth_date"] : null;
$save_evacuee->age = isset($_POST["age"]) && $_POST["age"] !== "" ? (int)$_POST["age"] : null;
$save_evacuee->civil_status = isset($_POST["civil_status"]) ? $_POST["civil_status"] : "";
$save_evacuee->occupation = isset($_POST["occupation"]) ? $_POST["occupation"] : "";
$save_evacuee->contact_number = isset($_POST["contact_number"]) ? $_POST["contact_number"] : "";
$save_evacuee->complete_address = isset($_POST["complete_address"]) ? $_POST["complete_address"] : "";
$save_evacuee->emergency_contact_person = isset($_POST["emergency_contact_person"]) ? $_POST["emergency_contact_person"] : "";
$save_evacuee->emergency_contact_number = isset($_POST["emergency_contact_number"]) ? $_POST["emergency_contact_number"] : "";
$save_evacuee->condition_pregnant = isset($_POST["condition_pregnant"]) ? (int)$_POST["condition_pregnant"] : 0;
$save_evacuee->condition_lactating = isset($_POST["condition_lactating"]) ? (int)$_POST["condition_lactating"] : 0;
$save_evacuee->condition_elderly = isset($_POST["condition_elderly"]) ? (int)$_POST["condition_elderly"] : 0;
$save_evacuee->condition_pwd = isset($_POST["condition_pwd"]) ? (int)$_POST["condition_pwd"] : 0;
$save_evacuee->condition_4ps = isset($_POST["condition_4ps"]) ? (int)$_POST["condition_4ps"] : 0;
$save_evacuee->pwd_type = isset($_POST["pwd_type"]) ? $_POST["pwd_type"] : "";
$save_evacuee->health_status = isset($_POST["health_status"]) ? $_POST["health_status"] : "";
$save_evacuee->emergency_medical_condition = isset($_POST["emergency_medical_condition"]) ? $_POST["emergency_medical_condition"] : "";
$save_evacuee->medications_taken = isset($_POST["medications_taken"]) ? $_POST["medications_taken"] : "";
$save_evacuee->known_allergies = isset($_POST["known_allergies"]) ? $_POST["known_allergies"] : "";
$save_evacuee->evacuation_center_id = isset($_POST["evacuation_center_id"]) && $_POST["evacuation_center_id"] !== "" ? $_POST["evacuation_center_id"] : null;
$save_evacuee->arrival_date = isset($_POST["arrival_date"]) && $_POST["arrival_date"] !== "" ? $_POST["arrival_date"] : null;
$save_evacuee->departure_date = isset($_POST["departure_date"]) && $_POST["departure_date"] !== "" ? $_POST["departure_date"] : null;
$save_evacuee->evacuee_status = isset($_POST["evacuee_status"]) ? $_POST["evacuee_status"] : "Active";
$save_evacuee->registered_by_lgu_id = isset($_SESSION["userid"]) ? $_SESSION["userid"] : null;

$save_evacuee->saveEvacuee();
?>