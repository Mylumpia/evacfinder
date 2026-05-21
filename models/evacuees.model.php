<?php
require_once "connection.php";

class ModelEvacuees {
    
    static public function mdlSaveEvacuee($data) {
        $db = new Connection();
        $pdo = $db->connect();

        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Generate evacuee ID
            $stmt = $pdo->prepare("SELECT MAX(id) as max_id FROM evacuees");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $next_id = ($result['max_id'] + 1);
            $evacuee_code = 'Evac' . str_pad($next_id, 5, '0', STR_PAD_LEFT);

            error_log("Generated evacuee_id: " . $evacuee_code);

            $stmt = $pdo->prepare("
                INSERT INTO evacuees(
                    evacuee_id, registration_date, last_name, first_name, 
                    middle_name, extension_name, relation_to_head, sex, 
                    birth_date, age, civil_status, occupation, 
                    contact_number, complete_address, emergency_contact_person, 
                    emergency_contact_number, condition_pregnant, condition_lactating, 
                    condition_elderly, condition_pwd, condition_4ps, 
                    pwd_type, health_status, emergency_medical_condition, 
                    medications_taken, known_allergies, evacuation_center_id, 
                    arrival_date, departure_date, evacuee_status, encodedby
                ) VALUES (
                    :evacuee_id, :registration_date, :last_name, :first_name,
                    :middle_name, :extension_name, :relation_to_head, :sex,
                    :birth_date, :age, :civil_status, :occupation,
                    :contact_number, :complete_address, :emergency_contact_person,
                    :emergency_contact_number, :condition_pregnant, :condition_lactating,
                    :condition_elderly, :condition_pwd, :condition_4ps,
                    :pwd_type, :health_status, :emergency_medical_condition,
                    :medications_taken, :known_allergies, :evacuation_center_id,
                    :arrival_date, :departure_date, :evacuee_status, :encodedby
                )
            ");

            $stmt->bindParam(":evacuee_id", $evacuee_code, PDO::PARAM_STR);
            $stmt->bindParam(":registration_date", $data["registration_date"], PDO::PARAM_STR);
            $stmt->bindParam(":last_name", $data["last_name"], PDO::PARAM_STR);
            $stmt->bindParam(":first_name", $data["first_name"], PDO::PARAM_STR);
            $stmt->bindParam(":middle_name", $data["middle_name"], PDO::PARAM_STR);
            $stmt->bindParam(":extension_name", $data["extension_name"], PDO::PARAM_STR);
            $stmt->bindParam(":relation_to_head", $data["relation_to_head"], PDO::PARAM_STR);
            $stmt->bindParam(":sex", $data["sex"], PDO::PARAM_STR);
            $stmt->bindParam(":birth_date", $data["birth_date"], PDO::PARAM_STR);
            $stmt->bindParam(":age", $data["age"], PDO::PARAM_INT);
            $stmt->bindParam(":civil_status", $data["civil_status"], PDO::PARAM_STR);
            $stmt->bindParam(":occupation", $data["occupation"], PDO::PARAM_STR);
            $stmt->bindParam(":contact_number", $data["contact_number"], PDO::PARAM_STR);
            $stmt->bindParam(":complete_address", $data["complete_address"], PDO::PARAM_STR);
            $stmt->bindParam(":emergency_contact_person", $data["emergency_contact_person"], PDO::PARAM_STR);
            $stmt->bindParam(":emergency_contact_number", $data["emergency_contact_number"], PDO::PARAM_STR);
            $stmt->bindParam(":condition_pregnant", $data["condition_pregnant"], PDO::PARAM_INT);
            $stmt->bindParam(":condition_lactating", $data["condition_lactating"], PDO::PARAM_INT);
            $stmt->bindParam(":condition_elderly", $data["condition_elderly"], PDO::PARAM_INT);
            $stmt->bindParam(":condition_pwd", $data["condition_pwd"], PDO::PARAM_INT);
            $stmt->bindParam(":condition_4ps", $data["condition_4ps"], PDO::PARAM_INT);
            $stmt->bindParam(":pwd_type", $data["pwd_type"], PDO::PARAM_STR);
            $stmt->bindParam(":health_status", $data["health_status"], PDO::PARAM_STR);
            $stmt->bindParam(":emergency_medical_condition", $data["emergency_medical_condition"], PDO::PARAM_STR);
            $stmt->bindParam(":medications_taken", $data["medications_taken"], PDO::PARAM_STR);
            $stmt->bindParam(":known_allergies", $data["known_allergies"], PDO::PARAM_STR);
            $stmt->bindParam(":evacuation_center_id", $data["evacuation_center_id"], PDO::PARAM_STR);
            $stmt->bindParam(":arrival_date", $data["arrival_date"], PDO::PARAM_STR);
            $stmt->bindParam(":departure_date", $data["departure_date"], PDO::PARAM_STR);
            $stmt->bindParam(":evacuee_status", $data["evacuee_status"], PDO::PARAM_STR);
            $stmt->bindParam(":encodedby", $data["encodedby"], PDO::PARAM_STR);

            if($stmt->execute()){
                $pdo->commit();
                error_log("Evacuee saved successfully with ID: " . $evacuee_code);
                return $evacuee_code;
            } else {
                $pdo->rollBack();
                error_log("Failed to execute insert statement");
                return "error";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("PDO Exception in mdlSaveEvacuee: " . $e->getMessage());
            return "error";
        }
    }

    static public function mdlGetEvacuees($item = null, $value = null) {
        $db = new Connection();
        $pdo = $db->connect();
        
        if ($item != null) {
            $stmt = $pdo->prepare("SELECT * FROM evacuees WHERE $item = :value ORDER BY last_name, first_name");
            $stmt->bindParam(":value", $value);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM evacuees ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>