<?php

require_once('classes/database.php');
$con = new database(); 

$searchQuery = $_POST['search'];

$products = $con->searchProducts($searchQuery);

if(empty($products)) {
    // If no products are found, display a message
    echo "<p>No products found.</p>";
} else {
    // If products are found, display them
    foreach($products as $product) {
        echo "<div class='col-md-4'>\n";
        echo "  <div class='card mb-4'>\n";
        echo "    <img src='" . htmlspecialchars($product['item_image']) . "' class='card-img-top' alt='" . htmlspecialchars($product['product_name']) . "'>\n";
        echo "    <div class='card-bodys'>\n";
        echo "      <h5 class='card-titles'>" . htmlspecialchars($product['product_name']) . "</h5>\n";
        echo "      <p class='card-texts'>" . htmlspecialchars($product['product_brand']) . "</p>\n";
        echo "      <h2 class='card-prices'>₱" . htmlspecialchars($product['price']) . "</h2>\n";
        echo "      <div class='checkoutbtns'>\n";
        echo "        <button type='button' class='add-button' data-item-id='" . htmlspecialchars($product['product_id']) . "' data-image-url='" . htmlspecialchars($product['item_image']) . "' data-brand='" . htmlspecialchars($product['product_brand']) . "' data-title='" . htmlspecialchars($product['product_name']) . "' data-price='" . htmlspecialchars($product['price']) . "' data-stock='" . htmlspecialchars($product['stocks']) . "'>Add to Cart</button>\n";
        echo "      </div>\n";
        echo "    </div>\n";
        echo "  </div>\n";
        echo "</div>\n";
    }
}