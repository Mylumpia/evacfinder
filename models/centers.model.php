<?php
require_once "connection.php";
class ModelCenters{
    static public function mdlSaveCenters($data){
        $db = new Connection();
        $pdo = $db->connect();

        try{
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $center_id = $pdo->prepare("
                SELECT CONCAT('EvacC', LPAD((MAX(id)+1), 5, '0')) as gen_id FROM centers
            ");
            $center_id->execute();
            $center_id = $center_id->fetch(PDO::FETCH_ASSOC);
            $center_code = $center_id['gen_id'];

            $check = $pdo->prepare("SELECT center_id FROM centers WHERE center_id = :center_id");
            $check->bindParam(":center_id", $center_code, PDO::PARAM_STR);
            $check->execute();

            if($check->rowCount() > 0){
                $pdo->rollBack();
                return "existing";
            }

            $stmt = $pdo->prepare("
                INSERT INTO centers(
                center_id, center_name, category, status,
                barangay, city, province, address, capacity,
                max_persons, current_occupants, contact_number,
                contact_person, alternate_contact, date_established,
                facilities, hazard_type, remarks, encodedby
                ) VALUES (
                :center_id, :center_name, :category, :status,
                :barangay, :city, :province, :address, :capacity,
                :max_persons, :current_occupants, :contact_number,
                :contact_person, :alternate_contact, :date_established,
                :facilities, :hazard_type, :remarks, :encodedby
                )   
            ");

            $stmt->bindParam(":center_id", $center_code, PDO::PARAM_STR);
            $stmt->bindParam(":center_name", $data["center_name"], PDO::PARAM_STR);
            $stmt->bindParam(":category", $data["category"], PDO::PARAM_STR);
            $stmt->bindParam(":status", $data["status"], PDO::PARAM_STR);
            $stmt->bindParam(":barangay", $data["barangay"], PDO::PARAM_STR);
            $stmt->bindParam(":city", $data["city"], PDO::PARAM_STR);
            $stmt->bindParam(":province", $data["province"], PDO::PARAM_STR);
            $stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
            $stmt->bindParam(":capacity", $data["capacity"], PDO::PARAM_STR);
            $stmt->bindParam(":max_persons", $data["max_persons"], PDO::PARAM_STR);
            $stmt->bindParam(":current_occupants", $data["current_occupants"], PDO::PARAM_STR);
            $stmt->bindParam(":contact_number", $data["contact_number"], PDO::PARAM_STR);
            $stmt->bindParam(":contact_person", $data["contact_person"], PDO::PARAM_STR);
            $stmt->bindParam(":alternate_contact", $data["alternate_contact"], PDO::PARAM_STR);
            
            if (empty($data["date_established"])) {
                $stmt->bindValue(":date_established", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":date_established", $data["date_established"], PDO::PARAM_STR);
            }

            $stmt->bindParam(":facilities", $data["facilities"], PDO::PARAM_STR);
            $stmt->bindParam(":hazard_type", $data["hazard_type"], PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":encodedby", $data["encodedby"], PDO::PARAM_STR);

            $stmt->execute();
            $pdo->commit();
            return $center_code;
        }catch (PDOException $e){
            $pdo->rollBack();
            // If duplicate entry error (MySQL error code 1062)
            if($e->errorInfo[1] == 1062){
                return "existing";
            }
            return "error";
        }    
    }

    
}