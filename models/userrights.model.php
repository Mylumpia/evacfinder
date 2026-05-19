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

    static public function mdlGetUserLogin($username, $password){
		$encryptpass = $password;
		$stmt = (new Connection)->connect()->prepare("SELECT userid, username, password FROM userrights WHERE (username = '$username') AND (password = '$encryptpass')");
		$stmt -> execute();
		return $stmt -> fetch();
	}
}