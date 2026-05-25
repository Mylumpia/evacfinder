<?php
require_once "connection.php";

class ModelAnnouncement {

    static public function mdlSaveAnnouncement($data) {
        $db  = new Connection();
        $pdo = $db->connect();

        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Generate announcement ID
            $stmt = $pdo->prepare("SELECT MAX(id) AS max_id FROM announcements");
            $stmt->execute();
            $result  = $stmt->fetch(PDO::FETCH_ASSOC);
            $next_id = ($result['max_id'] ?? 0) + 1;
            $announcement_code = 'ANN' . str_pad($next_id, 5, '0', STR_PAD_LEFT);

            error_log("Generated announcement_id: " . $announcement_code);

            $stmt = $pdo->prepare("
                INSERT INTO announcements (
                    announcement_id,
                    ann_title,
                    ann_type,
                    ann_desc,
                    encodedby,
                    date_created
                ) VALUES (
                    :announcement_id,
                    :ann_title,
                    :ann_type,
                    :ann_desc,
                    :encodedby,
                    NOW()
                )
            ");

            $stmt->bindParam(":announcement_id", $announcement_code,  PDO::PARAM_STR);
            $stmt->bindParam(":ann_type",        $data["ann_type"],    PDO::PARAM_STR);
            $stmt->bindParam(":ann_desc",        $data["ann_desc"],    PDO::PARAM_STR);
            $stmt->bindParam(":ann_title",       $data["ann_title"],   PDO::PARAM_STR);
            $stmt->bindParam(":encodedby",       $data["encodedby"],   PDO::PARAM_INT);

            if ($stmt->execute()) {
                $pdo->commit();
                error_log("Announcement saved successfully with ID: " . $announcement_code);
                return $announcement_code;
            } else {
                $pdo->rollBack();
                error_log("Failed to execute announcement insert statement");
                return "error";
            }

        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("PDO Exception in mdlSaveAnnouncement: " . $e->getMessage());
            if ($e->errorInfo[1] == 1062) {
                return "existing";
            }
            return "error";
        }
    }

    static public function mdlGetAnnouncements() {
        $db  = new Connection();
        $pdo = $db->connect();

        try {
            $stmt = $pdo->prepare("
                SELECT announcement_id, ann_title, ann_type, ann_desc, encodedby, date_created 
                FROM announcements 
                ORDER BY date_created DESC 
                LIMIT 10
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDO Exception in mdlGetAnnouncements: " . $e->getMessage());
            return [];
        }
    }

}
?>