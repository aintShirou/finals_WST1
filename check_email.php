<?php

require_once('classes/database.php');
$con = new database(); 


if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $con = new database();

    $query = $con->opencon()->prepare("SELECT email FROM admin WHERE email = ?");
    $query->execute([$email]);
    $existingUser = $query->fetch();

    if ($existingUser) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
}