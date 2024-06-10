<?php
require_once('classes/database.php');
$con = new database();

if (!$con) {
    die("Database connection failed");
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="orders.csv"');

$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, ['Order ID', 'Customer Name', 'Product', 'Unit Price', 'Quantity', 'Total Price']);

// Fetch orders, including customer names and quantities
$orders = $con->viewOrdersExcel(); // This method now fetches customer names, quantities, and calculates total price

foreach ($orders as $order) {
    fputcsv($output, [
        $order['order_id'],
        $order['customer_name'],
        $order['product'],
        'PHP ' . number_format($order['unit_price'], 2),
        $order['quantity'],
        'PHP ' . number_format($order['total_price'], 2) 
    ]);
}

fclose($output);
