<?php

require_once('classes/database.php');
$con = new database(); 

$searchQuery = $_POST['search'];
// $selectedCategory = $_POST['category'];

$products = $con->searchProducts($searchQuery);

foreach($products as $product) {
    echo "<div class='view-products'>\n";
    echo "<div class='product-boxs'>\n";
    echo "<img class='product-image' src='". $product['item_image'] ."'>\n";
    echo "<p class='product-brand'>". $product['product_brand'] ."</p>\n";
    echo "<p class='product-title'>". $product['product_name'] ."</p>\n";
    echo "<h2 class='product-price'>₱". $product['price'] ."</h2>\n";
    echo "<div class='checkoutbtn'>\n";
    echo "<button type='button' class='add-button'
        data-item-id='". $product['product_id'] ."'
        data-image-url='". $product['item_image'] ."' 
        data-brand='". $product['product_brand'] ."' 
        data-title='". $product['product_name'] ."' 
        data-price='". $product['price'] ."'>
        Add to Cart
    </button>\n";
    echo "</div>\n";
    echo "</div>\n";
    echo "</div>\n";
}
