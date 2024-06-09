<?php
// Include your Database class file
require_once('classes/database.php');
$con = new database();


if(isset($_POST['product_id']) && isset($_POST['newStockLevel'])) {
    $productId = $_POST['product_id'];
    $newStockLevel = $_POST['newStockLevel'];

    // Call the addProductStock method
    $result = $con->addProductStock($productId, $newStockLevel);

    if($result) {
        echo json_encode(['status' => 'success', 'message' => 'Stock updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update stock.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
