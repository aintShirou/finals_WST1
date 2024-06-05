<?php

require_once('classes/database.php');
$con = new database();  


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $productBrand = $_POST['productBrand'];
    $productName = $_POST['productName'];
    $productQuantity = $_POST['productQuantity'];
    $productPrice = $_POST['productPrice'];

    if (!$id || !$productBrand || !$productName ||!$productQuantity || !$productPrice) {
        echo 'Please fill in all fields';
        exit;
    }
    
    try {
        $result = $con->updateProduct($id, $productBrand, $productName, $productQuantity, $productPrice);

        if ($result) {
            echo 'Product updated successfully';
        } else {
            echo 'Failed to update product';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}