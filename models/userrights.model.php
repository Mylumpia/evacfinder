<?php
require_once "connection.php";

class ModelUserRights {
    static public function mdlGetUserCredentials($table, $item, $value) {
        $stmt = (new Connection)->connect()->prepare(
            "SELECT * FROM $table WHERE $item = :item"
        );
        $stmt->bindParam(":item", $value, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function mdlGenerateUserId() {
        $db = (new Connection)->connect();
        $stmt = $db->prepare("SELECT MAX(CAST(userid AS UNSIGNED)) AS max_userid FROM userrights");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextId = isset($result['max_userid']) && is_numeric($result['max_userid']) ? ((int)$result['max_userid'] + 1) : 1;
        return str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    static public function mdlGenerateLguId() {
        $db = (new Connection)->connect();
        $stmt = $db->prepare("SELECT MAX(id) AS max_id FROM lgu_users");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextId = isset($result['max_id']) && is_numeric($result['max_id']) ? ((int)$result['max_id'] + 1) : 1;
        return 'LGU' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    // This method is redundant - you can remove it since mdlGetUserCredentials does the same thing
    static public function mdlGetUserLogin($username, $password){
        $encryptpass = $password;
        $stmt = (new Connection)->connect()->prepare("SELECT userid, username, password FROM userrights WHERE username = :username AND password = :password");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $encryptpass, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    static public function mdlCreateUser($data) {
        $stmt = (new Connection)->connect()->prepare(
            "INSERT INTO userrights (userid, email, password, `Type`) VALUES (:userid, :email, :password, :type)"
        );
        $stmt->bindParam(":userid", $data["userid"], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $data["password"], PDO::PARAM_STR);
        $stmt->bindParam(":type", $data["type"], PDO::PARAM_STR);

        return $stmt->execute();
    }

    static public function mdlCreateLguRegistration($data) {
        $db = (new Connection)->connect();

        try {
            $db->beginTransaction();

            $stmtUser = $db->prepare(
                "INSERT INTO userrights (userid, email, password, `Type`) VALUES (:userid, :email, :password, :type)"
            );
            $stmtUser->bindParam(":userid", $data["userid"], PDO::PARAM_STR);
            $stmtUser->bindParam(":email", $data["email"], PDO::PARAM_STR);
            $stmtUser->bindParam(":password", $data["password"], PDO::PARAM_STR);
            $stmtUser->bindParam(":type", $data["type"], PDO::PARAM_STR);
            $stmtUser->execute();

            $stmtLgu = $db->prepare(
                "INSERT INTO lgu_users (lgu_id, lgu_office_name, office_email_address, department, province, region, position_role, first_name, last_name, phone_number, password) VALUES (:lgu_id, :lgu_office_name, :office_email_address, :department, :province, :region, :position_role, :first_name, :last_name, :phone_number, :password)"
            );
            $stmtLgu->bindParam(":lgu_id", $data["lgu_id"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":lgu_office_name", $data["lgu_office_name"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":office_email_address", $data["office_email_address"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":department", $data["department"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":province", $data["province"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":region", $data["region"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":position_role", $data["position_role"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":first_name", $data["first_name"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":last_name", $data["last_name"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":phone_number", $data["phone_number"], PDO::PARAM_STR);
            $stmtLgu->bindParam(":password", $data["password"], PDO::PARAM_STR);
            $stmtLgu->execute();

            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            throw $e;
        }
    }

    static public function mdlCreatePublicRegistration($data, $personalData) {
        $db = (new Connection)->connect();

        try {
            $db->beginTransaction();

            $stmtUser = $db->prepare(
                "INSERT INTO userrights (userid, email, password, `Type`) VALUES (:userid, :email, :password, :type)"
            );
            $stmtUser->bindParam(":userid", $data["userid"], PDO::PARAM_STR);
            $stmtUser->bindParam(":email", $data["email"], PDO::PARAM_STR);
            $stmtUser->bindParam(":password", $data["password"], PDO::PARAM_STR);
            $stmtUser->bindParam(":type", $data["type"], PDO::PARAM_STR);
            $stmtUser->execute();

            $stmtPersonal = $db->prepare(
                "INSERT INTO personal_users (user_id, first_name, last_name, middle_initial, extension, date_of_birth, sex, email_address, phone_number, region, account_type, password, status) VALUES (:user_id, :first_name, :last_name, :middle_initial, :extension, :date_of_birth, :sex, :email_address, :phone_number, :region, :account_type, :password, :status)"
            );
            $stmtPersonal->bindParam(":user_id", $personalData["user_id"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":first_name", $personalData["first_name"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":last_name", $personalData["last_name"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":middle_initial", $personalData["middle_initial"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":extension", $personalData["extension"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":date_of_birth", $personalData["date_of_birth"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":sex", $personalData["sex"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":email_address", $personalData["email_address"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":phone_number", $personalData["phone_number"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":region", $personalData["region"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":account_type", $personalData["account_type"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":password", $personalData["password"], PDO::PARAM_STR);
            $stmtPersonal->bindParam(":status", $personalData["status"], PDO::PARAM_STR);
            $stmtPersonal->execute();

            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            throw $e;
        }
    }

    static public function mdlUpdatePassword($userid, $password, $email) {
        $db = (new Connection)->connect();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("UPDATE userrights SET password = :password WHERE userid = :userid");
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->bindParam(":userid", $userid, PDO::PARAM_STR);
            $stmt->execute();

            $userData = self::mdlGetUserCredentials('userrights', 'userid', $userid);
            if (!empty($userData) && isset($userData['Type'])) {
                if ($userData['Type'] === 'lgu') {
                    $stmtLgu = $db->prepare("UPDATE lgu_users SET password = :password WHERE office_email_address = :email");
                    $stmtLgu->bindParam(":password", $password, PDO::PARAM_STR);
                    $stmtLgu->bindParam(":email", $email, PDO::PARAM_STR);
                    $stmtLgu->execute();
                } elseif ($userData['Type'] === 'public') {
                    $stmtPersonal = $db->prepare("UPDATE personal_users SET password = :password WHERE email_address = :email");
                    $stmtPersonal->bindParam(":password", $password, PDO::PARAM_STR);
                    $stmtPersonal->bindParam(":email", $email, PDO::PARAM_STR);
                    $stmtPersonal->execute();
                }
            }

            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
    }

    static public function mdlUpdateLastLogin($userid, $timestamp) {
        $db = (new Connection)->connect();
        // Ensure column exists (MySQL supports IF NOT EXISTS)
        try {
            $db->exec("ALTER TABLE userrights ADD COLUMN IF NOT EXISTS last_login DATETIME NULL");
        } catch (PDOException $e) {
            // ignore - some MySQL versions may not support IF NOT EXISTS, try safer fallback
            try {
                $db->exec("ALTER TABLE userrights ADD COLUMN last_login DATETIME NULL");
            } catch (PDOException $ex) {
                // ignore if still fails (column may already exist or insufficient privileges)
            }
        }

        $stmt = $db->prepare("UPDATE userrights SET last_login = :last_login WHERE userid = :userid");
        $stmt->bindParam(":last_login", $timestamp, PDO::PARAM_STR);
        $stmt->bindParam(":userid", $userid, PDO::PARAM_STR);
        return $stmt->execute();
    }
}