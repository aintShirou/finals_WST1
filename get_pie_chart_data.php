<?php

require_once('classes/database.php');
$con = new database();

header('Content-Type: application/json');

try {
    $result = $con->getPieChartData(); // Ensure you have this method in your database class

    $labels = [];
    $data = [];

    foreach ($result as $row) {
        $labels[] = $row['CategoryName']; 
        $data[] = $row['TotalProductsBought']; 
    }

    $dataset = [
        'label' => 'Sales by Category',
        'data' => $data,
        'backgroundColor' => [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
        ],
        'borderColor' => [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
        ],
        'borderWidth' => 1
    ];

    $response = [
        'labels' => $labels,
        'datasets' => [$dataset]
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}