<?php
class ControllerUserRights {
    static public function ctrUserLogin() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_POST["loginUser"]) && isset($_POST["loginPass"])) {
            $username = $_POST["loginUser"];
            $password = $_POST["loginPass"];
            
            $table = 'userrights';
            $item = 'username';
            $value = $username;
            $answer = ModelUserRights::mdlGetUserCredentials($table, $item, $value);

            if (!empty($answer) && 
                $answer["username"] == $username && 
                $answer["password"] == $password) {

                $_SESSION["loggedIn"] = "ok";
                $_SESSION["userid"]   = $answer["userid"];
                $_SESSION["username"] = $answer["username"];

                // Redirect to home page after successful login
                header("Location: ?route=home");
                exit();

            } else {
                return "Incorrect username or password.";
            }
        }
        return null;
    }
}