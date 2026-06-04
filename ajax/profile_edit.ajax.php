<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../models/userrights.model.php';
session_start();

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === 'ok') {
    $userid = $_SESSION['userid'] ?? '';
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $officeEmail = trim($_POST['officeEmail'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $officeNumber = trim($_POST['officeNumber'] ?? '');
    $newPassword = trim($_POST['newPassword'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');

    if ($userid === '') {
        $response['message'] = 'Unable to identify user session.';
        echo json_encode($response);
        exit;
    }

    if ($firstName === '' || $lastName === '' || $email === '') {
        $response['message'] = 'Please provide name and email.';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
        echo json_encode($response);
        exit;
    }

    $userData = ModelUserRights::mdlGetUserCredentials('userrights', 'userid', $userid);
    if (!empty($userData) && isset($userData['Type']) && $userData['Type'] === 'lgu') {
        if ($officeEmail === '') {
            $response['message'] = 'Please provide an office email address.';
            echo json_encode($response);
            exit;
        }
    }

    // Validate office email if supplied
    if ($officeEmail !== '' && !filter_var($officeEmail, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid office email address.';
        echo json_encode($response);
        exit;
    }

    if ($officeEmail !== '' && strtolower($officeEmail) === strtolower($email)) {
        $response['message'] = 'Office email must be different from account email.';
        echo json_encode($response);
        exit;
    }

    if ($newPassword !== '' || $confirmPassword !== '') {
        if ($newPassword === '' || $confirmPassword === '') {
            $response['message'] = 'Please fill both password fields to change your password.';
            echo json_encode($response);
            exit;
        }
        if ($newPassword !== $confirmPassword) {
            $response['message'] = 'Passwords do not match.';
            echo json_encode($response);
            exit;
        }
        if (strlen($newPassword) < 8) {
            $response['message'] = 'Password must be at least 8 characters.';
            echo json_encode($response);
            exit;
        }
        if (!preg_match('/[0-9]/', $newPassword)) {
            $response['message'] = 'Password must include at least one number.';
            echo json_encode($response);
            exit;
        }
        if (!preg_match('/[A-Z]/', $newPassword)) {
            $response['message'] = 'Password must include at least one uppercase letter.';
            echo json_encode($response);
            exit;
        }
        if (!preg_match('/[\W_]/', $newPassword)) {
            $response['message'] = 'Password must include at least one symbol.';
            echo json_encode($response);
            exit;
        }
    }

    // Check for email uniqueness
    $existing = ModelUserRights::mdlGetUserCredentials('userrights', 'email', $email);
    if (!empty($existing) && isset($existing['userid']) && $existing['userid'] !== $userid) {
        $response['message'] = 'That email address is already in use by another account.';
        echo json_encode($response);
        exit;
    }

    $passwordToSave = ($newPassword !== '') ? $newPassword : null;
    $updated = ModelUserRights::mdlUpdateUserProfile($userid, $email, $firstName, $lastName, $contact, $officeEmail !== '' ? $officeEmail : null, $officeNumber !== '' ? $officeNumber : null, $passwordToSave);

    if ($updated) {
        $displayContact = $contact;
        if ($displayContact === '' && $officeNumber !== '') {
            $displayContact = $officeNumber;
        }

        // Return the updated values so the client can update UI without reload
        $response = [
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => [
                'name' => trim($firstName . ' ' . $lastName),
                'email' => $email,
                'officeEmail' => $officeEmail,
                'officeNumber' => $officeNumber,
                'contact' => $displayContact
            ]
        ];
        // Also update session values server-side
        $_SESSION['email'] = $email;
        $_SESSION['username'] = trim($firstName . ' ' . $lastName);
    } else {
        $msg = ModelUserRights::$lastError ?? '';
        $response['message'] = 'Unable to update profile.' . (!empty($msg) ? ' Error: ' . $msg : '');
    }
}

echo json_encode($response);
