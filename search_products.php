<?php

require_once('classes/database.php');
$con = new database(); 

$searchQuery = $_POST['search'];

// The selectedCategory variable and its handling are removed
$products = $con->searchProducts($searchQuery);

foreach($products as $product) {
    $productImage = $product['item_image'];
    $productBrand = $product['product_brand'];
    $productName = $product['product_name'];
    $price = $product['price'];
    $stocks = $product['stocks'];
    $productId = $product['product_id'];

    // Use heredoc syntax for cleaner output
    echo <<<HTML
    <div class='card'>
        <img src='$productImage' class='card-img-top' alt='Item Image'>
        <div class='card-body'>
            <h4 class='card-title'>$productBrand</h4>
            <h5 class='card-title'>$productName</h5>
            <p class='card-text'>Price: PHP $price</p>
            <p class='card-text'>Stocks: $stocks</p>
            <div class='d-flex justify-content-between align-items-center'>
                <input type='hidden' name='id' value='$productId'>
                <a type='submit' class='btn btn-success' name='editButton' data-toggle='modal' data-target='#editProductModal'>
                    <i class='bx bxs-edit' style='font-size: 25px; vertical-align: middle;'></i>
                </a>
                <button type='submit' class='btn btn-danger' name='delete'>
                    <input type='hidden' name='id' value='$productId'>
                    <i class='bx bx-trash' style='font-size: 25px; vertical-align: middle;'></i>
                </button>
            </div>
        </div>
    </div>
HTML;
}