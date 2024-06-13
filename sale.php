<?php

require_once('classes/database.php');
    $con = new database();  
   
    session_start();

    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynrax Auto Supply | Total Sales</title>

    <!-- Style -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

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

            <?php include('includes/header.php'); ?>

                <div class="title-product">
                  <h1>Total Sales</h1>
                </div>
      
                <div class="container-fluid">
                  <div class="row mr-1">
                    <div class="col-lg-7 col-md-12 col-sm-12">
                        <div class="item-sales-title">
                          <h3>Items Sales</h3>
                        </div>
      
                        <!-- Sales per Item -->
      
                        <div class="item-sales-chart">
                          <canvas id="linechart"></canvas>
                          <input type="month" style="background-color: #FF5757; color: white; border: none; padding: 5px; width: 9rem; border-radius: 3px;"  onchange="filterChart(this)">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 col-m-12">
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
                      <div class="new-customer">
                        <i class='bx bx-user' ></i>
      
                        <!-- View Total Customers per Day -->
                        <?php
                            $new_customer = $con->newCustomers();
                          ?>
                        <h4>Number of New Customers</h4>
                        <h1><?php echo $new_customer['total_customers'];?></h1>
                      </div>
                    </div>
                    <div class="col md-3">
                      <div class="total-income">
                        <i class='bx bx-money'></i>
      
                        <!-- View Total income of the Store -->
      
                        <h4>Total Sales Today</h4>
                        <?php
                            $total_sales = $con->salesfortoday();
                          ?>
                        <h1>₱<?php echo $total_sales['total_sales'];?></h1>
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

    <div class="modal fade" id="addaccountModal" tabindex="-1" aria-labelledby="addaccountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark">
      <div class="modal-header" style="color: #fff;">
        <h5 class="modal-title" id="addaccountModalLabel">Add Account</h5>
      </div>
      <form id="addAccountForm" method="post">
        <div class="modal-body" style="color: #fff;">
          <div class="mb-3">
            <label for="firstname" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstname" name="firstname">
          </div>
          <div class="mb-3">
            <label for="lastname" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastname" name="lastname">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">UserName</label>
            <input type="text" class="form-control" id="username" name="username">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" id="addAccountButton" name="addAccountButton">Add Account</button>
        </div>
      </form>
    </div>
  </div>
</div>

   
<script>
  $(document).ready(function() {
    $('#addAccountForm').submit(function(e) {
      e.preventDefault(); // Prevent default form submission
  
      var formData = $(this).serialize(); // Serialize form data
  
      $.ajax({
        type: "POST",
        url: "add_Account.php", // Server-side script to process the form
        data: formData,
        success: function(response) {
          // Handle success. 'response' is what's returned from the server
          alert("Account added successfully");
          $('#addaccountModal').modal('hide'); // Hide the modal
          // Optionally, refresh the page or part of it to show the new account
        },
        error: function() {
          // Handle error
          alert("An error occurred. Please try again.");
        }
      });
    });
  });
</script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/adapters/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="bootstrap-4.5.3-dist/js/bootstrap.js"></script>
    <script src="script.js?v=<?php echo time();?>"></script>

</body>
</html>