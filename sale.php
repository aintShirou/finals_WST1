<?php

require_once('classes/database.php');
    $con = new database();  
   
    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynrax Auto Supply | Total Sales</title>

    <!-- Style -->
    <link rel="stylesheet" href="style.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap-4.5.3-dist/css/bootstrap.css">

    <!-- Boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    
    <div class="main-container">

        <div class="aside">
            <div class="navbar-logo">
                <a href="index.php"><img src="import/Dynrax Web Finals.png"></a>
            </div>
        
            <div class="navbar-toggle">
                <span></span>
            </div>
        
            <ul class="nav">
                <li><a href="index.php" style="text-decoration:none;"><i class="bx bx-home"></i>Home</a></li>
                <li><a href="product.php" style="text-decoration:none;"><i class="bx bx-package"></i>Order</a></li>
                <li><a href="transaction.php" style="text-decoration:none;"><i class="bx bx-cart"></i>Transaction</a></li>
                <li><a href="stock.php" style="text-decoration:none;"><i class="bx bx-store"></i>Stock</a></li>
                <li><a href="sale.php" style="text-decoration:none;" class="active"><i class="bx bx-dollar"></i>Total Sale</a></li>
            </ul>
        
          </div>

        <div class="main-content">

            <section class="sales section" id="sales">

                <div class="title-product">
                  <h1>Total Sales</h1>
                </div>
      
                <div class="container-fluid">
                  <div class="row mr-1">
                    <div class="col-md-7">
                        <div class="item-sales-title">
                          <h3>Items Sales</h3>
                        </div>
      
                        <!-- Sales per Item -->
      
                        <div class="item-sales-chart">
                          <canvas id="linechart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-5">
                      <div class="item-sales-title">
                        <h3>Sales By Category</h3>
                      </div>
      
                      <!-- Sales per Category -->
      
                      <div class="item-sales-pie">
                        <canvas id="pieschart"></canvas>
                      </div>
                    </div>
                  </div>
                  <div class="row mr-1">
                    <div class="col md-3">
                      <div class="total-income">
                        <i class='bx bx-money'></i>
      
                        <!-- View Total income of the Store -->
      
                        <h4>Total Income</h4>
                        <h1>₱15,000.00</h1>
                      </div>
                    </div>
                    <div class="col md-3">
                      <div class="inquiry-succsess">
                        <i class='bx bx-line-chart' ></i>
      
                        <!-- View Total Success Rate?? -->
      
                        <h4>Inquiry Success Rate</h4>
                        <h1>56%</h1>
                      </div>
                    </div>
                    <div class="col md-3">
                      <div class="new-customer">
                        <i class='bx bx-user' ></i>
      
                        <!-- View Total Customers per Day -->
      
                        <h4>Number of New Customers</h4>
                        <h1>42</h1>
                      </div>
                    </div>
                    <div class="col md-3">
                      <div class="complete-order">
                        <i class='bx bxs-check-square' ></i>
      
                        <!-- View Total Completed Orders -->
      
                        <h4>Number of Completed Orders Today</h4>
                          <?php
                            $total_orders = $con->completedOrdersforToday();
                          ?>
                          <h1><?php echo $total_orders['total'];?></h1>
                        </div>
                    </div>
                  </div>
                </div>
      
              </section>
            
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="bootstrap-4.5.3-dist/js/bootstrap.js"></script>
    <script src="script.js"></script>

</body>
</html>