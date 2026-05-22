<?php
require_once "connection.php";

class ModelCenters{
    static public function mdlSaveCenters($data){
        $db = new Connection();
        $pdo = $db->connect();

        try{
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Generate center ID
            $stmt = $pdo->prepare("SELECT MAX(id) as max_id FROM centers");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $next_id = ($result['max_id'] + 1);
            $center_code = 'EvacC' . str_pad($next_id, 5, '0', STR_PAD_LEFT);

            error_log("Generated center_id: " . $center_code);

            $stmt = $pdo->prepare("
                INSERT INTO centers(
                    center_id, center_name, category, status,
                    barangay, city, province, address, capacity,
                    max_persons, current_occupants, contact_number,
                    contact_person, alternate_contact, date_established,
                    facilities, hazard_type, remarks, encodedby,
                    latitude, longitude, estimated_capacity, accessibility, available_facilities
                ) VALUES (
                    :center_id, :center_name, :category, :status,
                    :barangay, :city, :province, :address, :capacity,
                    :max_persons, :current_occupants, :contact_number,
                    :contact_person, :alternate_contact, :date_established,
                    :facilities, :hazard_type, :remarks, :encodedby,
                    :latitude, :longitude, :estimated_capacity, :accessibility, :available_facilities
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
            $stmt->bindParam(":capacity", $data["capacity"], PDO::PARAM_INT);
            $stmt->bindParam(":max_persons", $data["max_persons"], PDO::PARAM_INT);
            $stmt->bindParam(":current_occupants", $data["current_occupants"], PDO::PARAM_INT);
            $stmt->bindParam(":contact_number", $data["contact_number"], PDO::PARAM_STR);
            $stmt->bindParam(":contact_person", $data["contact_person"], PDO::PARAM_STR);
            $stmt->bindParam(":alternate_contact", $data["alternate_contact"], PDO::PARAM_STR);
            
            if (empty($data["date_established"])) {
                $stmt->bindValue(":date_established", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(":date_established", $data["date_established"], PDO::PARAM_STR);
            }

            $stmt->bindParam(":facilities", $data["facilities"], PDO::PARAM_STR);
            $stmt->bindParam(":hazard_type", $data["hazard_type"], PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":encodedby", $data["encodedby"], PDO::PARAM_INT);
            $stmt->bindParam(":latitude", $data["latitude"], PDO::PARAM_STR);
            $stmt->bindParam(":longitude", $data["longitude"], PDO::PARAM_STR);
            $stmt->bindParam(":estimated_capacity", $data["estimated_capacity"], PDO::PARAM_INT);
            $stmt->bindParam(":accessibility", $data["accessibility"], PDO::PARAM_STR);
            $stmt->bindParam(":available_facilities", $data["available_facilities"], PDO::PARAM_STR);

            if($stmt->execute()){
                $pdo->commit();
                error_log("Center saved successfully with ID: " . $center_code);
                return $center_code;
            } else {
                $pdo->rollBack();
                error_log("Failed to execute insert statement");
                return "error";
            }
        }catch (PDOException $e){
            $pdo->rollBack();
            error_log("PDO Exception: " . $e->getMessage());
            if($e->errorInfo[1] == 1062){
                return "existing";
            }
            return "error";
        }    
    }

    static public function mdlGetCenters($item = null, $value = null) {
        $db = new Connection();
        $pdo = $db->connect();
        
        if ($item != null) {
            $stmt = $pdo->prepare("SELECT * FROM centers WHERE $item = :value ORDER BY center_name");
            $stmt->bindParam(":value", $value);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->prepare("SELECT center_id, center_name FROM centers WHERE status = 'Active' ORDER BY center_name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    static public function mdlGetCenterSummary() {
        $db = new Connection();
        $pdo = $db->connect();

        $stmt = $pdo->prepare(
            "SELECT 
                COUNT(*) AS total_centers,
                COALESCE(SUM(capacity), 0) AS total_capacity,
                COALESCE(SUM(current_occupants), 0) AS currently_occupied,
                COALESCE(SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END), 0) AS active_centers
            FROM centers"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function mdlGetActiveCenters() {
        $db = new Connection();
        $pdo = $db->connect();

        $stmt = $pdo->prepare("SELECT center_id, center_name, category, barangay, city, province, address, capacity, current_occupants, status FROM centers WHERE status = 'Active' ORDER BY center_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlGetAllCenters() {
        $db = new Connection();
        $pdo = $db->connect();

        $stmt = $pdo->prepare("SELECT center_id, center_name, category, barangay, city, province, address, capacity, current_occupants, status FROM centers ORDER BY center_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // NEW METHOD: Get center by ID
    static public function mdlGetCenterById($center_id) {
        $db = new Connection();
        $pdo = $db->connect();
        
        $stmt = $pdo->prepare("SELECT * FROM centers WHERE center_id = :center_id");
        $stmt->bindParam(":center_id", $center_id, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // NEW METHOD: Update center
    static public function mdlUpdateCenter($data) {
        $db = new Connection();
        $pdo = $db->connect();
        
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->prepare("
                UPDATE centers SET
                    center_name = :center_name,
                    category = :category,
                    status = :status,
                    address = :address,
                    barangay = :barangay,
                    city = :city,
                    province = :province,
                    capacity = :capacity,
                    estimated_capacity = :estimated_capacity,
                    current_occupants = :current_occupants,
                    contact_number = :contact_number,
                    contact_person = :contact_person,
                    latitude = :latitude,
                    longitude = :longitude,
                    accessibility = :accessibility,
                    available_facilities = :available_facilities,
                    remarks = :remarks
                WHERE center_id = :center_id
            ");
            
            $stmt->bindParam(":center_id", $data["center_id"], PDO::PARAM_STR);
            $stmt->bindParam(":center_name", $data["center_name"], PDO::PARAM_STR);
            $stmt->bindParam(":category", $data["category"], PDO::PARAM_STR);
            $stmt->bindParam(":status", $data["status"], PDO::PARAM_STR);
            $stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
            $stmt->bindParam(":barangay", $data["barangay"], PDO::PARAM_STR);
            $stmt->bindParam(":city", $data["city"], PDO::PARAM_STR);
            $stmt->bindParam(":province", $data["province"], PDO::PARAM_STR);
            $stmt->bindParam(":capacity", $data["capacity"], PDO::PARAM_INT);
            $stmt->bindParam(":estimated_capacity", $data["estimated_capacity"], PDO::PARAM_INT);
            $stmt->bindParam(":current_occupants", $data["current_occupants"], PDO::PARAM_INT);
            $stmt->bindParam(":contact_number", $data["contact_number"], PDO::PARAM_STR);
            $stmt->bindParam(":contact_person", $data["contact_person"], PDO::PARAM_STR);
            $stmt->bindParam(":latitude", $data["latitude"], PDO::PARAM_STR);
            $stmt->bindParam(":longitude", $data["longitude"], PDO::PARAM_STR);
            $stmt->bindParam(":accessibility", $data["accessibility"], PDO::PARAM_STR);
            $stmt->bindParam(":available_facilities", $data["available_facilities"], PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Update error in mdlUpdateCenter: " . $e->getMessage());
            return false;
        }
    }

    // NEW METHOD: Update center fields (for inline editing)
    static public function mdlUpdateCenterFields($center_id, $fields) {
        $db = new Connection();
        $pdo = $db->connect();
        
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $setClause = "";
            $params = [":center_id" => $center_id];
            
            foreach ($fields as $key => $value) {
                $setClause .= "$key = :$key, ";
                $params[":$key"] = $value;
            }
            
            $setClause = rtrim($setClause, ", ");
            
            $stmt = $pdo->prepare("UPDATE centers SET $setClause WHERE center_id = :center_id");
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Update fields error: " . $e->getMessage());
            return false;
        }
    }
}
?>