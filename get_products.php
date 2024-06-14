<?php
require_once('classes/database.php');
$con = new database();

// Check if 'cat_id' is provided and not equal to "0"
$categoryId = isset($_GET['cat_id']) && $_GET['cat_id'] !== "0" ? $_GET['cat_id'] : null;

// Fetch products, if $categoryId is null, it should fetch all products
$products = $con->viewProducts1($categoryId);

header('Content-Type: application/json');
echo json_encode($products);