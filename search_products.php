<?php

require_once('classes/database.php');
$con = new database(); 

$searchQuery = $_POST['search'];
$selectedCategory = $_POST['category'];

$products = $con->searchProducts($searchQuery);

foreach($products as $product) {
    echo "<div class='card'>";
    echo "<img src='". $product['item_image']. "' class='card-img-top' alt='Item Image'>";
    echo "<div class='card-body'>";
    echo "<h4 class='card-title'>". $product['product_brand']. "</h4>";
    echo "<h5 class='card-title'>". $product['product_name']. "</h5>";
    echo "<p class='card-text'>Price: PHP ". $product['price']. "</p>";
    echo "<p class='card-text'>Stocks: ". $product['stocks']. "</p>";
    echo "<div class='d-flex justify-content-between align-items-center'>";
    echo "<input type='hidden' name='id' value='". $product['product_id']. "'>";
    echo "<a type='submit' class='btn btn-success' name='editButton' data-toggle='modal' data-target='#editProductModal'>";
    echo "<i class='bx bxs-edit' style='font-size: 25px; vertical-align: middle;'></i>";
    echo "</a>";
    echo "<button type='submit' class='btn btn-danger' name='delete'>";
    echo "<input type='hidden' name='id' value='". $product['product_id']. "'>";
    echo "<i class='bx bx-trash' style='font-size: 25px; vertical-align: middle;'></i>";
    echo "</button>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}