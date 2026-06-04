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

        // Get total centers
        $stmt = $pdo->prepare("SELECT COUNT(*) AS total_centers FROM centers");
        $stmt->execute();
        $totalCenters = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get total capacity (sum of capacity column)
        $stmt = $pdo->prepare("SELECT COALESCE(SUM(capacity), 0) AS total_capacity FROM centers");
        $stmt->execute();
        $totalCapacity = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get currently occupied (count active evacuees across all centers)
        $stmt = $pdo->prepare("SELECT COUNT(*) AS currently_occupied FROM evacuees WHERE evacuee_status = 'Active'");
        $stmt->execute();
        $currentlyOccupied = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get active centers count
        $stmt = $pdo->prepare("SELECT COUNT(*) AS active_centers FROM centers WHERE status = 'Active'");
        $stmt->execute();
        $activeCenters = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return array(
            'total_centers' => $totalCenters['total_centers'],
            'total_capacity' => $totalCapacity['total_capacity'],
            'currently_occupied' => $currentlyOccupied['currently_occupied'],
            'active_centers' => $activeCenters['active_centers']
        );
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

    static public function mdlGetCenterById($center_id) {
        $db = new Connection();
        $pdo = $db->connect();
        
        $stmt = $pdo->prepare("SELECT * FROM centers WHERE center_id = :center_id");
        $stmt->bindParam(":center_id", $center_id, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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

    // Get centers with assigned LGU user info
    static public function mdlGetCentersWithLGU() {
        $db = new Connection();
        $pdo = $db->connect();

        $stmt = $pdo->prepare("
            SELECT c.*, 
                   u.email as assigned_lgu_email,
                   CONCAT(l.first_name, ' ', l.last_name) as assigned_lgu_name,
                   l.phone_number as assigned_lgu_phone
            FROM centers c
            LEFT JOIN userrights u ON c.assigned_lgu_user_id = u.userid
            LEFT JOIN lgu_users l ON u.email = l.office_email_address
            ORDER BY c.center_name
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get available LGU users (not assigned to any center)
    static public function mdlGetAvailableLGUUsers() {
        $db = new Connection();
        $pdo = $db->connect();

        $stmt = $pdo->prepare("
            SELECT u.userid, u.email, l.first_name, l.last_name, l.phone_number, l.position_role
            FROM userrights u
            INNER JOIN lgu_users l ON u.email = l.office_email_address
            WHERE u.Type = 'lgu' 
            AND u.userid NOT IN (SELECT assigned_lgu_user_id FROM centers WHERE assigned_lgu_user_id IS NOT NULL AND assigned_lgu_user_id != '')
            AND u.status = 'Active'
            ORDER BY l.last_name, l.first_name
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Assign LGU user to center
    static public function mdlAssignLGUToCenter($center_id, $lgu_user_id, $assigned_by = null) {
        $db = new Connection();
        $pdo = $db->connect();
        
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            
            // Update centers table
            $stmt = $pdo->prepare("UPDATE centers SET assigned_lgu_user_id = :lgu_user_id WHERE center_id = :center_id");
            $stmt->bindParam(":center_id", $center_id);
            $stmt->bindParam(":lgu_user_id", $lgu_user_id);
            $stmt->execute();
            
            // Record assignment in assignments table
            $stmt2 = $pdo->prepare("
                INSERT INTO lgu_center_assignments (lgu_user_id, center_id, assigned_by, status)
                VALUES (:lgu_user_id, :center_id, :assigned_by, 'Active')
                ON DUPLICATE KEY UPDATE assigned_date = CURRENT_TIMESTAMP, status = 'Active', assigned_by = :assigned_by
            ");
            $stmt2->bindParam(":lgu_user_id", $lgu_user_id);
            $stmt2->bindParam(":center_id", $center_id);
            $stmt2->bindParam(":assigned_by", $assigned_by);
            $stmt2->execute();
            
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Assignment error: " . $e->getMessage());
            return false;
        }
    }

    // Get center report with evacuees (FIXED - removed created_at)
    static public function mdlGetCenterReport($center_id) {
        $db = new Connection();
        $pdo = $db->connect();
        
        // Get center details (only use columns that exist)
        $stmt = $pdo->prepare("
            SELECT c.*, 
                   u.email as assigned_lgu_email,
                   CONCAT(l.first_name, ' ', l.last_name) as assigned_lgu_name,
                   l.phone_number as assigned_lgu_phone
            FROM centers c
            LEFT JOIN userrights u ON c.assigned_lgu_user_id = u.userid
            LEFT JOIN lgu_users l ON u.email = l.office_email_address
            WHERE c.center_id = :center_id
        ");
        $stmt->bindParam(":center_id", $center_id);
        $stmt->execute();
        $center = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get ACTIVE evacuees only
        $stmt2 = $pdo->prepare("
            SELECT e.*, 
                   CONCAT(l.first_name, ' ', l.last_name) as encoded_by_name
            FROM evacuees e
            LEFT JOIN lgu_users l ON e.encodedby = l.lgu_id
            WHERE e.evacuation_center_id = :center_id 
            AND e.evacuee_status = 'Active'
            ORDER BY e.arrival_date DESC, e.last_name, e.first_name
        ");
        $stmt2->bindParam(":center_id", $center_id);
        $stmt2->execute();
        $evacuees = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        // Get DEPARTED evacuees (Departed, Transferred, Missing, Deceased)
        $stmt3 = $pdo->prepare("
            SELECT e.*, 
                CONCAT(l.first_name, ' ', l.last_name) as encoded_by_name
            FROM evacuees e
            LEFT JOIN lgu_users l ON e.encodedby = l.lgu_id
            WHERE e.evacuation_center_id = :center_id 
            AND e.evacuee_status IN ('Departed', 'Transferred', 'Missing', 'Deceased')
            ORDER BY e.departure_date DESC, e.last_name, e.first_name
        ");
        $stmt3->bindParam(":center_id", $center_id);
        $stmt3->execute();
        $departedEvacuees = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        
        // Get statistics from active evacuees only
        $stats = [
            'total_evacuees' => count($evacuees),
            'total_departed' => count($departedEvacuees),
            'male' => 0,
            'female' => 0,
            'pwd' => 0,
            'elderly' => 0,
            'pregnant' => 0,
            'lactating' => 0,
            'children' => 0
        ];
        
        foreach ($evacuees as $evacuee) {
            if ($evacuee['sex'] == 'Male') $stats['male']++;
            if ($evacuee['sex'] == 'Female') $stats['female']++;
            if ($evacuee['condition_pwd']) $stats['pwd']++;
            if ($evacuee['condition_elderly']) $stats['elderly']++;
            if ($evacuee['condition_pregnant']) $stats['pregnant']++;
            if ($evacuee['condition_lactating']) $stats['lactating']++;
            if ($evacuee['age'] && $evacuee['age'] < 18) $stats['children']++;
        }
        
        return [
            'center' => $center, 
            'evacuees' => $evacuees, 
            'departed_evacuees' => $departedEvacuees,
            'statistics' => $stats
        ];
    }
}
?>