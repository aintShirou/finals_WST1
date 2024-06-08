<?php
require_once('classes/database.php'); // Adjust the path as necessary to include your database class

$con = new database(); // Assuming your class is named Database

// Check if product ID is provided
if(isset($_POST['id'])) {
    $productId = $_POST['id'];
    $result = $con->delete($productId);

    if($result) {
        echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete product.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No product ID provided.']);
}
