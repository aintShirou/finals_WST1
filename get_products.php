<?php
require_once('classes/database.php');
$con = new database();

$categoryId = isset($_GET['cat_id']) ? $_GET['cat_id'] : null;
$products = $con->viewProducts1($categoryId);

header('Content-Type: application/json');
echo json_encode($products);
