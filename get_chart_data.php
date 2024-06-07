<?php

require_once('classes/database.php');
$con = new database();  

header('Content-Type: application/json');

try {
    $result = $con->getChartData();

    $labels = [];
    $data = [];

    foreach ($result as $row) {
        $labels[] = $row['payment_date'];
        $data[] = $row['Total'];
    }

    $dataset1 = [
        'label' => 'Sales Amount',
        'data' => $data,
        'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
        'borderColor' => 'rgba(255, 99, 132, 1)',
        'borderWidth' => 3
    ];

    $response = [
        'labels' => $labels,
        'datasets' => [$dataset1]
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}