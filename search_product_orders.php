<?php

require_once('classes/database.php');
$con = new database(); 

$searchQuery = $_POST['search'];
// $selectedCategory = $_POST['category'];

$products = $con->searchProducts($searchQuery);

foreach($products as $product) {
    echo "<div class='col-md-4'>\n";
    echo "  <div class='card mb-4'>\n";
    echo "    <img src='" . $product['item_image'] . "' class='card-img-top' alt='" . $product['product_name'] . "'>\n";
    echo "    <div class='card-body'>\n";
    echo "      <h5 class='card-title'>" . $product['product_name'] . "</h5>\n";
    echo "      <p class='card-text'>" . $product['product_brand'] . "</p>\n";
    echo "      <h2 class='card-price'>₱" . $product['price'] . "</h2>\n";
    echo "      <div class='checkoutbtns'>\n";
    echo "        <button type='button' class='add-button' data-item-id='" . $product['product_id'] . "' data-image-url='" . $product['item_image'] . "' data-brand='" . $product['product_brand'] . "' data-title='" . $product['product_name'] . "' data-price='" . $product['price'] . "'>Add to Cart</button>\n";
    echo "      </div>\n";
    echo "    </div>\n";
    echo "  </div>\n";
    echo "</div>\n";
}
