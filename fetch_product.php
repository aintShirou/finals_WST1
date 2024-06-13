<?php
require_once('classes/database.php');
session_start();

$response = ['products' => '', 'pagination' => ''];

try {
    $con = new database();
    $connection = $con->opencon();

    if (!$connection) {
        throw new Exception('Database connection failed.');
    }

    $recordsPerPage = 2;
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($currentPage - 1) * $recordsPerPage;

    $totalQuery = $connection->prepare("SELECT COUNT(*) AS total FROM product");
    $totalQuery->execute();
    $totalRecords = $totalQuery->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $recordsPerPage);

    $query = $connection->prepare("SELECT * FROM product LIMIT :offset, :recordsPerPage");
    $query->bindParam(':offset', $offset, PDO::PARAM_INT);
    $query->bindParam(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
    $query->execute();
    $products = $query->fetchAll(PDO::FETCH_ASSOC);

   
    $productHTML = ''; 
    
    foreach ($products as $product) {
        $productHTML .= "  <div class='card mb-4'>\n";
        $productHTML .= "    <img src='" . htmlspecialchars($product['item_image'], ENT_QUOTES, 'UTF-8') . "' class='card-img-top' alt='" . htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') . "'>\n";
        $productHTML .= "    <div class='card-bodys'>\n";
        $productHTML .= "      <h5 class='card-titles'>" . htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') . "</h5>\n"; 
        $productHTML .= "      <p class='card-texts'>" . htmlspecialchars($product['product_brand'], ENT_QUOTES, 'UTF-8') . "</p>\n"; 
        $productHTML .= "      <h2 class='card-prices'>₱" . htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') . "</h2>\n"; 
        $productHTML .= "      <div class='checkoutbtns'>\n"; 
        $productHTML .= "        <button type='button' class='add-button' data-item-id='" . htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') . "' data-image-url='" . htmlspecialchars($product['item_image'], ENT_QUOTES, 'UTF-8') . "' data-brand='" . htmlspecialchars($product['product_brand'], ENT_QUOTES, 'UTF-8') . "' data-title='" . htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') . "' data-price='" . htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') . "' data-stock='" . htmlspecialchars($product['stocks'], ENT_QUOTES, 'UTF-8') . "'>Add to Cart</button>\n";
        $productHTML .= "      </div>\n";
        $productHTML .= "    </div>\n";
        $productHTML .= "  </div>\n";
    }
    
    $response['products'] = $productHTML; 

     
    $paginationHtml = '';
if ($totalPages > 1) {
    $paginationHtml .= '<nav><ul class="pagination justify-content-center">';
    if ($currentPage > 1) {
        $paginationHtml .= '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a></li>';
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i == $currentPage ? ' active' : '';
        $paginationHtml .= '<li class="page-item' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
    if ($currentPage < $totalPages) {
        $paginationHtml .= '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a></li>';
    }
    $paginationHtml .= '</ul></nav>';
}
$response['pagination'] = $paginationHtml;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);