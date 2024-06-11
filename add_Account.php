<?php

require_once('classes/database.php');
$con = new database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Basic validation
    if (empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    if ($password !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    // Assuming $userAccount is an instance of UserAccount class
    $con->signup($firstname, $lastname, $email, $username, $password);

    echo json_encode(['status' => 'success', 'message' => 'Account created successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}