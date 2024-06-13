<?php 

date_default_timezone_set('Asia/Manila');

class database{

    function opencon(){
        return new PDO('mysql:host=localhost;dbname=das_finals','root','');
    }

  
    function addProduct($product_category, $product_brand, $product_name, $product_quantity, $product_price, $product_image_path){ 
        $con = $this->opencon();
        $stmt = $con->prepare("INSERT INTO product (cat_id, product_brand, product_name, stocks, price, item_image) VALUES (?,?,?,?,?,?)");
        $stmt->execute([ $product_category, $product_brand, $product_name, $product_quantity, $product_price, $product_image_path]);
        
        // Get the ID of the last inserted row
        $last_id = $con->lastInsertId();

        // // Return the last inserted ID
        return $last_id;
    }

   
    function addCat($category){
        $con = $this->opencon();
        $stmt = $con->prepare("INSERT INTO category (cat_type) VALUES (?)");
        $result = $stmt->execute([$category]);
        return $result;
    }
 


    function viewCat(){
    $con = $this->opencon();
    return $stmt = $con->query("SELECT cat_id, cat_type FROM category") ->fetchAll();
    }

    function viewProducts(){
        $con = $this->opencon();
        return $stmt = $con->query("SELECT * FROM product") ->fetchAll();
    }


    function viewProducts1($categoryId = null, $page = 1, $records_per_page = 10) {
        $con = $this->opencon();

        // Calculate the starting record of the current page
        $start_from = ($page - 1) * $records_per_page;

        if ($categoryId) {
            $stmt = $con->prepare("SELECT * FROM product WHERE cat_id = :cat_id LIMIT :start_from, :records_per_page");
            $stmt->bindParam(':cat_id', $categoryId);
            $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
            $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        } else {
            $stmt = $con->prepare("SELECT * FROM product LIMIT :start_from, :records_per_page");
            $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
            $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
function getProductCount($categoryId = null) {
    $con = $this->opencon();

    if ($categoryId) {
        $stmt = $con->prepare("SELECT COUNT(*) FROM product WHERE cat_id = :cat_id");
        $stmt->bindParam(':cat_id', $categoryId);
    } else {
        $stmt = $con->prepare("SELECT COUNT(*) FROM product");
    }

    $stmt->execute();
    return $stmt->fetchColumn();
}
        


    
    function updateProduct($id, $product_brand, $product_name, $product_quantity, $product_price){
        try{
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("UPDATE product SET product_brand=?, product_name=?, price=?, stocks=? WHERE product_id=?");
            $query->execute([$product_brand, $product_name, $product_price, $product_quantity, $id]);
            $con->commit();
            return true;
        } catch(PDOException $e) {
            $con->rollBack();
            return false;
        }
    }


function delete($productId){
    try{
        $con = $this->opencon();
        $con->beginTransaction();

        $query = $con->prepare("DELETE FROM product WHERE product_id = ?");
        $query->execute([$productId]);

        $con->commit();
        return true;

    } catch (PDOException $e) {
        $con->rollBack();
        return false;
    }
}



function lowStocks(){
    $con = $this->opencon();
    return $stmt = $con->query("SELECT * FROM product WHERE stocks < 35") ->fetchAll();
}

//Funtion on products.php

function insertOrders($customer_name, $product_id, $quantity_ordered){
    $con = $this->opencon();
    $query = $con->prepare("INSERT INTO orders (customer_name, product_id, quantity_ordered) VALUES (?, ?, ?)");
    if (!$query->execute([$customer_name, $product_id, $quantity_ordered])) {
        throw new Exception($query->errorInfo()[2]);
    }
    return $con->lastInsertId();
}

function insertTransaction($order_id, $payment_method, $payment_date, $total){
    $con = $this->opencon();
    $query = $con->prepare("INSERT INTO transactions (order_id, payment_method, paymentdate, payment_total) VALUES (?, ?, ?, ?)");
    if (!$query->execute([$order_id, $payment_method, $payment_date, $total])) {
        throw new Exception($query->errorInfo()[2]);
    }
}

function updateProductStock($product_id, $quantity){
    try{
        $con = $this->opencon();
        $con->beginTransaction();
        $query = $con->prepare("UPDATE product SET stocks = stocks - ? WHERE product_id = ?");
        $query->execute([$quantity, $product_id]);
        $con->commit();
        return true;
    } catch(PDOException $e) {
        $con->rollBack();
        return false;
    }
}



// Pagination for Transaction Records

function viewTransactions($start_from, $records_per_page) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT
    orders.customer_name,
    transactions.trans_id,
    transactions.payment_method,
    DATE(transactions.paymentdate) AS paymentdate,
    COUNT(orders.order_id) AS total_orders,
    SUM(transactions.payment_total) AS total_purchases
FROM
    transactions
INNER JOIN orders ON transactions.order_id = orders.order_id
GROUP BY
    orders.customer_name,
    transactions.paymentdate
ORDER BY
    `transactions`.`paymentdate`
DESC LIMIT :start_from, :records_per_page");
    $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
    $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}


function exportAllTransactions() {
    $con = $this->opencon();
    $sql = "SELECT
        orders.customer_name,
        transactions.trans_id,
        transactions.payment_method,
        DATE(transactions.paymentdate) AS paymentdate,
        COUNT(orders.order_id) AS total_orders,
        SUM(transactions.payment_total) AS total_purchases
    FROM
        transactions
    INNER JOIN orders ON transactions.order_id = orders.order_id
    GROUP BY
        orders.customer_name,
        transactions.paymentdate
    ORDER BY
        `transactions`.`paymentdate` DESC";
    
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}


function viewOrdersExcel(){
    $con = $this->opencon();
    $sql = "SELECT
                orders.order_id,
                CONCAT(product.product_brand, ' ', product.product_name) as product,
                orders.customer_name,
                orders.quantity_ordered as quantity, 
                product.price as unit_price, 
                (orders.quantity_ordered * product.price) as total_price 
            FROM
                orders
                INNER JOIN product ON orders.product_id = product.product_id
                ORDER BY
                orders.order_id";
    
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function getTotalTransactions(){
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT COUNT(*) as total FROM (
        SELECT
            orders.customer_name,
            transactions.trans_id,
            transactions.payment_method,
            DATE(transactions.paymentdate) AS paymentdate,
            COUNT(orders.order_id) AS total_orders,
            SUM(transactions.payment_total) AS total_purchases
        FROM
            transactions
        INNER JOIN orders ON transactions.order_id = orders.order_id
        GROUP BY
            orders.customer_name,
            transactions.paymentdate
        ORDER BY
            `transactions`.`paymentdate` DESC
    ) as subquery");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

// Pagination for Orders Made by Customers
function viewOrders($start_from, $records_per_page){
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT product.product_id, CONCAT(product.product_brand , ' ', product.product_name) as product, product.price, orders.order_id, orders.quantity_ordered, (product.price * orders.quantity_ordered) AS total_price,  transactions.paymentdate FROM transactions
                INNER JOIN orders ON transactions.order_id = orders.order_id
                INNER JOIN product ON orders.product_id = product.product_id GROUP BY order_id ORDER BY orders.`order_id` DESC LIMIT :start_from, :records_per_page");
    $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
    $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getTotalOrders(){
    $con = $this->opencon();
    $stmt = $con->query("SELECT COUNT(*) FROM `orders`");
    return $stmt->fetchColumn();
}


function getOrderDetails($order_id){
    try{
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT product.product_id, CONCAT(product.product_brand , ' ', product.product_name) as product, product.price, orders.customer_name, orders.order_id, orders.quantity_ordered, product.price, orders.quantity_ordered FROM `orders` INNER JOIN product ON orders.product_id = product.product_id WHERE orders.order_id = ?");
        $stmt->execute([$order_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return false;
    }
}

function totalCompletedOrders(){
    $con = $this->opencon();
        $stmt = $con->query("SELECT COUNT(*) as total FROM (
            SELECT transactions.paymentdate
            FROM transactions
            GROUP BY transactions.paymentdate
        ) as grouped_transactions");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function completedOrdersforToday(){
   $con = $this->opencon();
    $stmt = $con->query("SELECT COUNT(*) as total FROM (
        SELECT transactions.paymentdate
        FROM transactions
        WHERE DATE(transactions.paymentdate) = CURDATE()
        GROUP BY transactions.paymentdate
    ) as grouped_transactions");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



function searchProducts($searchQuery = null) {
    $con = $this->opencon();
    $sql = "SELECT * FROM product WHERE product_name LIKE :searchQuery OR product_brand LIKE :searchQuery";

    $stmt = $con->prepare($sql);

    $searchParam = "%$searchQuery%";
    $stmt->bindParam(':searchQuery', $searchParam);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// functions in index.php
function addProductStock($product_id, $quantity){
    try{
        $con = $this->opencon();
        $con->beginTransaction();
        $query = $con->prepare("UPDATE product SET stocks = stocks + ? WHERE product_id = ?");
        $query->execute([$quantity, $product_id]);
        $con->commit();
        return true;
    } catch(PDOException $e) {
        $con->rollBack();
        return false;
    }
}

function getProductDetails($product_id){
    try{
    $con = $this->opencon();
    $query = $con->prepare("SELECT * FROM product");
    $query->execute([$product_id]);
    return $query->fetch();
} catch (PDOException $e) {
    return [];
}
}

function getTotalSales(){
    $con = $this->opencon();
    $stmt = $con->query("SELECT SUM(payment_total) as total FROM transactions");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getTotalSalesDifference(){
    try {
        $con = $this->opencon();
        
        // Query to get this week's total sales
        $stmtThisWeek = $con->prepare("SELECT SUM(payment_total) AS total_this_week 
                                       FROM transactions 
                                       WHERE DATE(paymentdate) >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) 
                                       AND DATE(paymentdate) <= CURDATE()");
        $stmtThisWeek->execute();
        $result = $stmtThisWeek->fetch(PDO::FETCH_ASSOC);
        $totalSalesThisWeek = $result['total_this_week'] ?? 0;

        return $totalSalesThisWeek; // Return the total sales for this week
    } catch (PDOException $e) {
        // Optionally handle the exception
        error_log("Error in getTotalSalesDifference: " . $e->getMessage());
        return 0; // Return 0 or handle as appropriate
    }
}

function topProduct(){
    $con = $this->opencon();
    $stmt = $con->query("SELECT
    product.product_id,
    product.item_image,
    product.product_brand,
    product.product_name,
    SUM(orders.quantity_ordered) AS total_sales
FROM
    orders
INNER JOIN product ON orders.product_id = product.product_id
GROUP BY
    product.product_id");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getTotalCustomers(){
    $con = $this->opencon();
    $stmt = $con->query("SELECT COUNT(*) AS total
FROM (
    SELECT DISTINCT orders.customer_name, transactions.paymentdate
    FROM orders
    INNER JOIN transactions ON transactions.order_id = orders.order_id
    GROUP BY transactions.paymentdate, orders.customer_name
) AS grouped_customers");
    return $stmt->fetch(PDO::FETCH_ASSOC);

}

function weeklyCustomerCount(){
    try {
        $con = $this->opencon();
        $stmt = $con->query("SELECT COUNT(*) AS total_customers_this_week
        FROM (
            SELECT DISTINCT orders.customer_name
            FROM orders
            INNER JOIN transactions ON transactions.order_id = orders.order_id
            WHERE transactions.paymentdate >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE) - 1 DAY
            AND transactions.paymentdate < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE) - 8 DAY
            GROUP BY orders.customer_name
        ) AS grouped_customers;");
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return the total customers this week
        return $result['total_customers_this_week'];
    } catch (PDOException $e) {
        // Handle exception
        error_log("Error in weeklyCustomerCount: " . $e->getMessage());
        return 0; // Return 0 or handle as appropriate
    }
}
function customerPercentage(){
    try {
        $con = $this->opencon();
        
        // Calculate total unique customers
        $totalCustomersStmt = $con->query("SELECT COUNT(*) AS total
FROM (
    SELECT DISTINCT orders.customer_name, transactions.paymentdate
    FROM orders
    INNER JOIN transactions ON transactions.order_id = orders.order_id
    GROUP BY transactions.paymentdate, orders.customer_name
) AS grouped_customers");
        $totalCustomersResult = $totalCustomersStmt->fetch(PDO::FETCH_ASSOC);
        $totalCustomers = $totalCustomersResult['total'];
        
        // Calculate unique customers this week
        $customersThisWeekStmt = $con->query("SELECT COUNT(*) AS total_customers_this_week
        FROM (
            SELECT DISTINCT orders.customer_name
            FROM orders
            INNER JOIN transactions ON transactions.order_id = orders.order_id
            WHERE transactions.paymentdate >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE) - 1 DAY
            AND transactions.paymentdate < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE) - 8 DAY
            GROUP BY orders.customer_name
        ) AS grouped_customers;");
        $customersThisWeekResult = $customersThisWeekStmt->fetch(PDO::FETCH_ASSOC);
        $customersThisWeek = $customersThisWeekResult['total_customers_this_week'];
        
        // Calculate the percentage
        if ($totalCustomers > 0) {
            $percentage = ($customersThisWeek / $totalCustomers) * 100;
        } else {
            // Handle division by zero if there are no total customers
            $percentage = 0;
        }
        
        return $percentage;
    } catch (PDOException $e) {
        // Handle exception
        error_log("Error in weeklyCustomerPercentage: " . $e->getMessage());
        return 0; // Return 0 or handle as appropriate
    }
}

function countTotalProductStocks(){
    $con = $this->opencon();
    $stmt = $con->query("SELECT SUM(stocks) as total FROM product");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Chart functions
    function getChartData(){
        $con = $this->opencon();
        $stmt = $con->query("SELECT DATE(paymentdate) AS payment_date, SUM(payment_total) AS Total FROM transactions GROUP BY DATE(paymentdate)");
        return $stmt->fetchAll();
    }

    function getPieChartData(){
        $con = $this->opencon();
        $stmt = $con->query("SELECT 
    category.cat_type AS CategoryName, 
    SUM(orders.quantity_ordered) AS TotalProductsBought
FROM 
    orders
INNER JOIN 
    product ON orders.product_id = product.product_id
INNER JOIN 
    category ON product.cat_id = category.cat_id
GROUP BY 
    category.cat_type");
        return $stmt->fetchAll();
    }


    function getProductCountWithSearch($searchTerm = '', $categoryId = null) {
        $con = $this->opencon(); // Ensure consistent connection handling
        $query = "SELECT COUNT(*) FROM products WHERE name LIKE :searchTerm";
        
        // If a category ID is provided, add it to the query
        if ($categoryId !== null) {
            $query .= " AND category_id = :categoryId";
        }
    
        $stmt = $con->prepare($query); // Use the connection directly
    
        // Bind the search term with wildcards for partial matching
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        
        // If a category ID is provided, bind it to the statement
        if ($categoryId !== null) {
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        }
    
        $stmt->execute();
    
        // Fetch the count from the database
        $count = $stmt->fetchColumn();
    
        return $count;
    }

    function signup($firstname, $lastname, $email, $username, $password){
        $con = $this->opencon();
    
        // Check if the user already exists
        $checkUser = $con->prepare("SELECT * FROM admin WHERE email = ? OR user = ?");
        $checkUser->execute([$email, $username]);
        if ($checkUser->rowCount() > 0) {
            // User exists
            return ['status' => 'error', 'message' => 'User already exists.'];
        }
    
        // Proceed with inserting the new user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $stmt = $con->prepare("INSERT INTO admin (first_name, last_name, email, user, pass) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$firstname, $lastname, $email, $username, $passwordHash])) {
            // Success
            return ['status' => 'success', 'message' => 'Account created successfully.'];
        } else {
            // Insertion failed
            return ['status' => 'error', 'message' => 'Failed to create account.'];
        }
    }


    function check($username, $password) {
        // Open database connection
        $con = $this->opencon();
    
        // Prepare the SQL query
        $stmt = $con->prepare("SELECT * FROM admin WHERE user = ?");
        $stmt->execute([$username]);
    
        // Fetch the user data as an associative array
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // If a user is found, verify the password
        if ($user && password_verify($password, $user['pass'])) {
            return $user;
        }
    
        // If no user is found or password is incorrect, return false
        return false;
    }


    function salesfortoday() {
        $con = $this->opencon();
        // Prepare the SQL query to sum the total sales for today
        $stmt = $con->prepare("SELECT SUM(payment_total) AS total_sales FROM transactions WHERE DATE(paymentdate) = CURRENT_DATE");
    
        // Execute the statement
        if ($stmt->execute()) { // Check if the execution was successful
            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Ensure there's a result and return it, otherwise return a default value
            return $result ? $result : ['total_sales' => 0];
        } else {
            // Handle error or return a default value in case of failure
            return ['total_sales' => 0];
        }
}

   function newCustomers(){
    $con = $this->opencon();
    // Prepare the SQL query to count the total customers for today
    $stmt = $con->prepare("SELECT
    COUNT(DISTINCT orders.customer_name) AS total_customers
FROM
    transactions
INNER JOIN orders ON transactions.order_id = orders.order_id
WHERE
    DATE(transactions.paymentdate) = CURRENT_DATE");
    
    // Execute the statement
    if ($stmt->execute()) { // Check if the execution was successful
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Ensure there's a result and return it, otherwise return a default value
        return $result ? $result : ['total_customers' => 0];
    } else {
        // Handle error or return a default value in case of failure
        return ['total_customers' => 0];
    }
   }

function getSalesPercentage() {
    $con = $this->opencon();
    
    // Prepare and execute the total sales query
    $totalsalesStmt = $con->prepare("SELECT SUM(payment_total) as total FROM transactions");
    $totalsalesStmt->execute();
    $totalsalesResult = $totalsalesStmt->fetch(PDO::FETCH_ASSOC);
    $totalSales = $totalsalesResult['total'];
    
    // Prepare and execute the sales this week query
    $salesthisweekStmt = $con->prepare("SELECT SUM(payment_total) AS total_this_week 
                                        FROM transactions 
                                        WHERE DATE(paymentdate) >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) 
                                        AND DATE(paymentdate) <= CURDATE()");
    $salesthisweekStmt->execute();
    $salesthisweekResult = $salesthisweekStmt->fetch(PDO::FETCH_ASSOC);
    $salesThisWeek = $salesthisweekResult['total_this_week'];
    
    // Calculate the percentage
    if ($totalSales > 0) {
        $percentage = ($salesThisWeek / $totalSales) * 100;
    } else {
        // Handle division by zero if there are no total sales
        $percentage = 0;
    }

    return $percentage;
}

 function totalOrdersCompleted(){
    $con = $this->opencon();
    $stmt = $con->query("SELECT COUNT(*) as total FROM orders");
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'];
 }

 function weeklyOrders(){
    $con = $this->opencon();
    $stmt = $con->query("SELECT
    COUNT(orders.order_id) AS total
FROM
    transactions
INNER JOIN  orders on transactions.order_id = orders.order_id
WHERE
    DATE(transactions.paymentdate) >= DATE_SUB(
        CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) AND DATE(paymentdate) <= CURDATE()");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
 }


function orderPercentage(){
    $con = $this->opencon();
    
    // Fetch total number of orders
    $totalOrdersStmt = $con->prepare("SELECT COUNT(*) as total FROM orders");
    $totalOrdersStmt->execute();
    $totalOrdersResult = $totalOrdersStmt->fetch(PDO::FETCH_ASSOC);
    $totalOrders = $totalOrdersResult['total'];
    
    // Fetch number of orders this week
    $ordersThisWeekStmt = $con->prepare("SELECT
    COUNT(orders.order_id) AS total_this_week
FROM
    transactions
INNER JOIN  orders on transactions.order_id = orders.order_id
WHERE
    DATE(transactions.paymentdate) >= DATE_SUB(
        CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) AND DATE(transactions.paymentdate) <= CURDATE()");
    $ordersThisWeekStmt->execute();
    $ordersThisWeekResult = $ordersThisWeekStmt->fetch(PDO::FETCH_ASSOC);
    $ordersThisWeek = $ordersThisWeekResult['total_this_week']; // Corrected the key to match the SQL alias
    
    // Calculate the percentage of orders this week compared to total orders
    if ($totalOrders > 0) {
        $percentage = ($ordersThisWeek / $totalOrders) * 100;
    } else {
        // Handle division by zero if there are no total orders
        $percentage = 0;
    }

    return $percentage;
}
}