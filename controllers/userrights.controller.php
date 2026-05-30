<?php
class ControllerUserRights {
    static public function ctrUserLogin() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_POST["loginEmail"]) && isset($_POST["loginPass"])) {
            $email = trim($_POST["loginEmail"]);
            $password = $_POST["loginPass"];
            
            $table = 'userrights';
            $item = 'email';
            $value = $email;
            $answer = ModelUserRights::mdlGetUserCredentials($table, $item, $value);

            if (!empty($answer) && 
                $answer["email"] == $email && 
                $answer["password"] == $password) {

                $_SESSION["loggedIn"] = "ok";
                $_SESSION["userid"]   = $answer["userid"];
                $_SESSION["email"] = $answer["email"];
                $_SESSION["username"] = $answer["email"];

                // Record current login timestamp (UTC) in DB and session
                $now = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
                try {
                    ModelUserRights::mdlUpdateLastLogin($answer["userid"], $now);
                    $_SESSION['last_login'] = $now;
                } catch (Exception $e) {
                    // non-fatal: continue without blocking login if update fails
                }

                // Redirect to home page after successful login
                header("Location: ?route=home");
                exit();

            } else {
                return "Incorrect email or password.";
            }
        }
        return null;
    }

    static public function ctrUserRegister() {
        if (isset($_POST["btn_register_submit"])) {
            $email = trim($_POST["email"] ?? '');
            $password = $_POST["password"] ?? '';
            $confirmPassword = $_POST["confirmPassword"] ?? '';
            $accountType = $_POST["accountType"] ?? 'public';
            $firstName = trim($_POST['firstName'] ?? '');
            $lastName = trim($_POST['lastName'] ?? '');
            $middleInitial = trim($_POST['middleInitial'] ?? '');
            $extension = trim($_POST['extension'] ?? '');
            $dateOfBirth = trim($_POST['dateOfBirth'] ?? '');
            $sex = trim($_POST['sex'] ?? '');
            $phoneNumber = trim($_POST['phoneNumber'] ?? '');
            $region = trim($_POST['region'] ?? '');

            if (!$email || !$password || !$confirmPassword || !$firstName || !$lastName || !$dateOfBirth || !$sex || !$phoneNumber || !$region) {
                return "Please fill in all required fields.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return "Please enter a valid email address.";
            }

            if ($password !== $confirmPassword) {
                return "Password and Confirm Password must match.";
            }

            $typeValue = ($accountType === 'lgu') ? 'lgu' : 'public';

            $existingUser = ModelUserRights::mdlGetUserCredentials('userrights', 'email', $email);
            if (!empty($existingUser)) {
                return "This email is already registered.";
            }

            if ($typeValue === 'public') {
                $existingPublic = ModelUserRights::mdlGetUserCredentials('personal_users', 'email_address', $email);
                if (!empty($existingPublic)) {
                    return "This email is already registered.";
                }
            }

            $userid = ModelUserRights::mdlGenerateUserId();
            $data = [
                'userid' => $userid,
                'email' => $email,
                'password' => $password,
                'type' => $typeValue
            ];

            try {
                if ($typeValue === 'public') {
                    $personalData = [
                        'user_id' => $userid,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'middle_initial' => $middleInitial,
                        'extension' => $extension,
                        'date_of_birth' => $dateOfBirth,
                        'sex' => $sex,
                        'email_address' => $email,
                        'phone_number' => $phoneNumber,
                        'region' => $region,
                        'account_type' => 'Public User',
                        'password' => $password,
                        'status' => 'Active'
                    ];
                    $result = ModelUserRights::mdlCreatePublicRegistration($data, $personalData);
                } else {
                    $result = ModelUserRights::mdlCreateUser($data);
                }

                if ($result) {
                    return true;
                }
            } catch (Exception $e) {
                return "Registration failed: " . $e->getMessage();
            }

            return "Unable to register. Please try again later.";
        }

        return null;
    }

    static public function ctrLguRegister() {
        if (isset($_POST["btn_lgu_submit"])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $storedData = $_SESSION['lgu_registration'] ?? [];
            $email = trim($_POST['email'] ?? $storedData['email'] ?? '');
            $password = $_POST['password'] ?? $storedData['password'] ?? '';
            $firstName = trim($_POST['firstName'] ?? $storedData['firstName'] ?? '');
            $lastName = trim($_POST['lastName'] ?? $storedData['lastName'] ?? '');
            $officeEmail = trim($_POST['lguOfficeEmail'] ?? '');
            $lguOfficeName = trim($_POST['lguOfficeName'] ?? '');
            $lguPhone = trim($_POST['lguPhone'] ?? '');
            $lguDepartment = trim($_POST['lguDepartment'] ?? '');
            $lguRegion = trim($_POST['lguRegion'] ?? '');
            $lguProvince = trim($_POST['lguProvince'] ?? '');
            $lguPosition = trim($_POST['lguPosition'] ?? '');

            if (empty($storedData) && (!$email || !$password || !$firstName || !$lastName)) {
                return "Please complete the first registration step before submitting LGU details.";
            }

            if (!$email || !$password || !$firstName || !$lastName || !$lguOfficeName || !$lguPhone || !$lguDepartment || !$lguRegion || !$lguProvince || !$lguPosition) {
                return "Please fill in all required fields for LGU registration.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return "Please enter a valid email address.";
            }

            if ($officeEmail && !filter_var($officeEmail, FILTER_VALIDATE_EMAIL)) {
                return "Please enter a valid office email address.";
            }

            if (empty($officeEmail)) {
                $officeEmail = $email;
            }

            $existingUser = ModelUserRights::mdlGetUserCredentials('userrights', 'email', $email);
            if (!empty($existingUser)) {
                return "This email is already registered.";
            }

            $existingLgu = ModelUserRights::mdlGetUserCredentials('lgu_users', 'office_email_address', $officeEmail);
            if (!empty($existingLgu)) {
                return "This LGU office email is already registered.";
            }

            $userid = ModelUserRights::mdlGenerateUserId();
            $lguId = ModelUserRights::mdlGenerateLguId();

            $data = [
                'userid' => $userid,
                'email' => $email,
                'password' => $password,
                'type' => 'lgu',
                'lgu_id' => $lguId,
                'lgu_office_name' => $lguOfficeName,
                'office_email_address' => $officeEmail,
                'department' => $lguDepartment,
                'province' => $lguProvince,
                'region' => $lguRegion,
                'position_role' => $lguPosition,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone_number' => $lguPhone,
            ];

            try {
                $result = ModelUserRights::mdlCreateLguRegistration($data);
                if ($result) {
                    return true;
                }
            } catch (Exception $e) {
                return "Registration failed: " . $e->getMessage();
            }

            return "Unable to register. Please try again later.";
        }

        return null;
    }
}