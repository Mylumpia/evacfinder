<?php
class ControllerUserRights {
    static public function ctrUserLogin() {
        if (isset($_POST["loginUser"])) {
            $encryptpass = $_POST["loginPass"];
            $table = 'userrights';
            $item = 'username';
            $value = $_POST["loginUser"];
            $answer = (new ModelUserRights)->mdlGetUserCredentials($table, $item, $value);

            if (!empty($answer) && 
                $answer["username"] == $_POST["loginUser"] && 
                $answer["password"] == $encryptpass) {

                $_SESSION["loggedIn"] = "ok";
                $_SESSION["userid"]   = $answer["userid"];

                echo '<script>
					window.location = "home";
				</script>';

            } else {
                echo '<br><div style="text-align:center;" class="alert alert-danger">
                    Incorrect username or password.
                </div>';
            }
        }
    }
}