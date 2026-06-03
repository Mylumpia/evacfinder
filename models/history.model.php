<?php
require_once __DIR__ . "/connection.php";

class ModelHistory {

    static public function mdlSaveHistory($data) {
        $db = new Connection();
        $pdo = $db->connect();

        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("
                INSERT INTO history (
                    center_id, center_name, category, status,
                    barangay, city, province, address, capacity,
                    max_persons, current_occupants, contact_number,
                    contact_person, date_established, facilities,
                    remarks, encodedby, latitude, longitude,
                    estimated_capacity, accessibility, available_facilities,
                    history_date, action_made, assigned_lgu_user_id
                ) VALUES (
                    :center_id, :center_name, :category, :status,
                    :barangay, :city, :province, :address, :capacity,
                    :max_persons, :current_occupants, :contact_number,
                    :contact_person, :date_established, :facilities,
                    :remarks, :encodedby, :latitude, :longitude,
                    :estimated_capacity, :accessibility, :available_facilities,
                    NOW(), :action_made, :assigned_lgu_user_id
                )
            ");

            $stmt->bindParam(":center_id",            $data["center_id"],            PDO::PARAM_STR);
            $stmt->bindParam(":center_name",          $data["center_name"],          PDO::PARAM_STR);
            $stmt->bindParam(":category",             $data["category"],             PDO::PARAM_STR);
            $stmt->bindParam(":status",               $data["status"],               PDO::PARAM_STR);
            $stmt->bindParam(":barangay",             $data["barangay"],             PDO::PARAM_STR);
            $stmt->bindParam(":city",                 $data["city"],                 PDO::PARAM_STR);
            $stmt->bindParam(":province",             $data["province"],             PDO::PARAM_STR);
            $stmt->bindParam(":address",              $data["address"],              PDO::PARAM_STR);
            $stmt->bindParam(":capacity",             $data["capacity"],             PDO::PARAM_INT);
            $stmt->bindParam(":max_persons",          $data["max_persons"],          PDO::PARAM_INT);
            $stmt->bindParam(":current_occupants",    $data["current_occupants"],    PDO::PARAM_INT);
            $stmt->bindParam(":contact_number",       $data["contact_number"],       PDO::PARAM_STR);
            $stmt->bindParam(":contact_person",       $data["contact_person"],       PDO::PARAM_STR);
            $stmt->bindParam(":facilities",           $data["facilities"],           PDO::PARAM_STR);
            $stmt->bindParam(":remarks",              $data["remarks"],              PDO::PARAM_STR);
            $stmt->bindParam(":encodedby",            $data["encodedby"],            PDO::PARAM_INT);
            $stmt->bindParam(":estimated_capacity",   $data["estimated_capacity"],   PDO::PARAM_INT);
            $stmt->bindParam(":accessibility",        $data["accessibility"],        PDO::PARAM_STR);
            $stmt->bindParam(":available_facilities", $data["available_facilities"], PDO::PARAM_STR);
            $stmt->bindParam(":action_made",          $data["action_made"],          PDO::PARAM_STR);
            $stmt->bindParam(":assigned_lgu_user_id", $data["assigned_lgu_user_id"], PDO::PARAM_STR);

            if (empty($data["date_established"])) {
                $stmt->bindValue(":date_established", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(":date_established", $data["date_established"], PDO::PARAM_STR);
            }

            if (empty($data["latitude"])) {
                $stmt->bindValue(":latitude", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(":latitude", $data["latitude"], PDO::PARAM_STR);
            }

            if (empty($data["longitude"])) {
                $stmt->bindValue(":longitude", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(":longitude", $data["longitude"], PDO::PARAM_STR);
            }

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("History save error: " . $e->getMessage());
            return false;
        }
    }
static public function mdlGetHistory($center_id) {
        $db = new Connection();
        $pdo = $db->connect();

        try {
            $stmt = $pdo->prepare("
                SELECT h.*,
                    COALESCE(
                        CONCAT(l.first_name, ' ', l.last_name),
                        CONCAT(p.first_name, ' ', p.last_name),
                        'Unknown'
                    ) as changed_by_name
                FROM history h
                -- 1. Match '00006' from history directly to '00006' in userrights
                LEFT JOIN userrights u ON LPAD(h.encodedby, 5, '0') = u.userid
                -- 2. Link using the exact schema column names
                LEFT JOIN lgu_users l ON u.email = l.office_email_address
                LEFT JOIN personal_users p ON u.email = p.email_address
                WHERE h.center_id = :center_id
                ORDER BY h.history_date ASC
            ");
            $stmt->bindParam(":center_id", $center_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("History fetch error: " . $e->getMessage());
            return [];
        }
    }
}
?>