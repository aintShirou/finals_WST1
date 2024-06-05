<?php
require_once ('classes/database.php');

$con= new database();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = $con->getProductDetails($product_id);

    // Check if the product details were fetched successfully
    if ($product) {
        // Output the product details as HTML
        echo "<p><strong>Product Brand:</strong> " . htmlspecialchars($product['product_brand']) . "</p>";
        echo "<p><strong>Product Name:</strong> " . htmlspecialchars($product['product_name']) . "</p>";
        echo "<p><strong>Stock:</strong> <input type='number' class='form-control' id='addStock' value='".htmlspecialchars($product['stocks'])."'></p>";
    } else {
        echo "Failed to fetch product details";
    }
}