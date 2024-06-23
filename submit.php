<?php

require_once('classes/database.php');
$con = new database(); 

$response = ['success' => false, 'message' => ''];

if (isset($_POST['customer_name']) && isset($_POST['payment_method']) && isset($_POST['cart_items'])) {
    try {
        $customer_name = $_POST['customer_name'];
        $payment_method = $_POST['payment_method'];
        $amount_paid = $_POST['amountpaid'];
        $cart_items = json_decode($_POST['cart_items'], true);

        if (empty($cart_items)) {
            throw new Exception('Cart is empty');
        }

        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];

            // Insert the order into the database
            $order_id = $con->insertOrders($customer_name, $product_id, $quantity);

            // Insert the transaction into the database
            $con->insertTransaction($order_id, $payment_method, date('Y-m-d H:i:s'), $item['price'] * $quantity, $amount_paid);

            // Subtract product bought from the available stocks
            $con->updateProductStock($product_id, $quantity);
        }

        $response['success'] = true;
        $response['message'] = 'Checkout successful!';
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Required fields are missing.';
}

echo json_encode($response); 

