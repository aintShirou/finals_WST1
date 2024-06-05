<?php
header('Content-Type: application/json');

require_once('classes/database.php');
$db = new database();  

// Query the database for total sales per day
$sql = 'SELECT DATE(sale_date) as date, SUM(sale_amount) as total_sales FROM sales GROUP BY DATE(sale_date)';
$data = $db->query($sql);  // Use the query method in your Database class

// Format the data as needed
$labels = array_column($data, 'date');
$datasets = [
    [
        'label' => 'Total Sales',
        'data' => array_column($data, 'total_sales'),
        'borderColor' => '#FF66C4',
        'borderWidth' => 2
    ]
];

// Return the data as a JSON object
echo json_encode(['labels' => $labels, 'datasets' => $datasets]);







