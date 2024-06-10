<?php
require_once('classes/database.php');
$con = new database();

if (!$con) {
    die("Database connection failed");
}

$transactions = $con->exportAllTransactions();
if (empty($transactions)) {
    die("No transactions found to export");
}

$filename = "transactions_" . date('YmdHis') . ".csv";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w'); // Open output stream

// Set column headers
fputcsv($output, ['#', 'Customer Name', 'Payment Method', 'Date', 'Price']);

$rowNum = 1;
foreach ($transactions as $transaction) {
    // Format the date in a more universally recognized format (ISO 8601)
    $formattedDate = date('Y-m-d', strtotime($transaction['paymentdate']));
    fputcsv($output, [
        $rowNum,
        ucwords($transaction['customer_name']),
        ucwords($transaction['payment_method']),
        $formattedDate, // Use the formatted date
        'PHP ' . number_format($transaction['total_purchases'], 2)
    ]);
    $rowNum++;
}

fclose($output); // Close output stream
exit;