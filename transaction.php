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
    <title>Dynrax Auto Supply | Transaction</title>

    <!-- Style -->
    <link rel="stylesheet" href="style.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap-4.5.3-dist/css/bootstrap.css">

    <!-- Boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>

    <!-- Header -->
          
    <!-- End Header -->
    
    <div class="main-container">

        <!-- Aside Navbar -->
      
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
                    <li><a href="transaction.php" style="text-decoration:none;" class="active"><i class="bx bx-cart"></i>Transaction</a></li>
                    <li><a href="stock.php" style="text-decoration:none;"><i class="bx bx-store"></i>Stock</a></li>
                    <li><a href="sale.php" style="text-decoration:none;"><i class="bx bx-dollar"></i>Total Sale</a></li>
                </ul>
            
            </div>

        <div class="main-content">

            <section class="transaction section" id="transaction">

            <?php include('includes/header.php'); ?>

                <div class="title-product">
                  <h1>Transactions</h1>
                </div>
      
                <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Transaction of the Customer -->
                        <div class="transactions">
                            <div class="title-trans">
                              <h2>Transaction</h2>
                              <button class="btn btn-danger" onclick="exportToExcel()">Export to Excel</button>
                            </div>
                            <!-- Table for Transaction -->
                            <div class="table-trans">
                              <table class="table">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Payment Method</th>
                                    <th>Date</th>
                                    <th>Price</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $records_per_page = 14;
                                  $trans_page = isset($_GET['trans_page']) ? $_GET['trans_page'] : 1;
                                  $start_from_trans = ($trans_page > 1) ? ($trans_page-1) * $records_per_page : 0;  

                                  $total_trans = $con->getTotalTransactions();
                                  $counter_trans = $total_trans - (($trans_page-1) * $records_per_page);

                                  $total_trans_pages = ceil($total_trans / $records_per_page);

                                  $trans = $con->viewTransactions($start_from_trans, $records_per_page);
                                  foreach($trans as $transaction){
                                  ?>
                                  <tr>
                                    <td><a href="#" data-toggle="modal" data-target="#ORModal"><?php echo $counter_trans--;?></a></td>
                                    <td><?php echo ucwords($transaction['customer_name']);?></td>
                                    <td><?php echo ucwords($transaction['payment_method']);?></td>
                                    <td><?php echo ucwords($transaction['paymentdate']);?></td>
                                    <td>PHP <?php echo ($transaction['total_purchases']);?></td>
                                  </tr>
                                  <?php
                                  }
                                  ?>
                                </tbody>
                              </table>
                              <?php
                              $total_trans_pages = ceil($total_trans / $records_per_page);

                              for ($i=1; $i<=$total_trans_pages; $i++) {
                                echo "<a href='transaction.php?trans_page=".$i."'>".$i."</a> ";
                            }
                              ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="order-recent">
                        <div class="title-trans">
                          <h2>Recent Order</h2>
                          <button class="btn btn-danger" onclick="exportOrderToExcel()">Export to Excel</button>
                        </div>
                      <!-- Recent Purchases -->
                      <div class="recent-pur">
                          <table class="table">
                              <thead>
                                  <tr>
                                      <th>Order ID</th>
                                      <th>Product</th>
                                      <th>Price</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                  $records_per_page = 14;
                                  $order_page = isset($_GET['order_page']) ? $_GET['order_page'] : 1;
                                  $start_from_order = ($order_page > 1) ? ($order_page-1) * $records_per_page : 0;

                                  $total_orders = $con->getTotalOrders();
                                  $counter_order = $total_orders - (($order_page-1) * $records_per_page);

                                  $orders = $con->viewOrders($start_from_order, $records_per_page);
                                  foreach($orders as $order){
                                  ?>
                                  <tr>
                                      <td><a href="#" data-toggle="modal" data-target="#productModal" data-id="<?php echo $order['order_id'];?>"><?php echo ucwords($order['order_id']);?></a></td>
                                      <td><?php echo ucwords($order['product']);?></td>
                                      <td>PHP <?php echo ucwords($order['total_price']);?></td>
                                  </tr>
                                  <?php
                                  }
                                  ?>
                              </tbody>
                          </table>
                      </div>
                        <?php
                        $total_order_pages = ceil($total_orders / $records_per_page);

                        for ($i=1; $i<=$total_order_pages; $i++) {
                          echo "<a href='transaction.php?order_page=".$i."'>".$i."</a> ";
                      }
                        ?>
                      </div>
                    </div>
      
                        </div>
                    </div>
                  </div>
                </div>
      
              </section>

        </div>
        
    </div>

    <!-- Modal For Retrieving Order ID details-->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark">
          <div class="modal-header">
            <h5 class="modal-title text-white" id="productModalLabel">Customer Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-white">
            <!-- Customer and item details will be displayed here -->
          
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- You can add additional buttons here if needed -->
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for OR -->
    <div class="modal fade" id="ORModal" tabindex="-1" aria-labelledby="ORModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="ORModalLabel">Order Receipt</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-white">
        <p>Order Receipt:</p>
        <table class="table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Product Brand</th>
              <th>Price</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>NAME</td>
              <td>BRAND</td>
              <td>₱1234</td>
              <td>14</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td>Total:</td>
              <td></td>
            </tr>
            <tr>
              <td>Payment Method:</td>
              <td></td>
            </tr>
            <tr>
              <td>Cash:</td>
              <td></td>
            </tr>
            <tr>
              <td>Change:</td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Print</button>
      </div>
    </div>
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

    <!-- AJAX Libary -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

      <script>
        $(document).ready(function() {
          $('#productModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget); // Button that triggered the modal
            let orderId = button.data('id'); // Extract info from data-* attributes
        
            // Make an AJAX request to fetch the order details
            $.ajax({
              url: 'get_order_details.php',
              type: 'GET',
              data: {id: orderId},
              success: function (data) {
                $('#productModal .modal-body').html(data);
              },
              error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
              }
            });
          });
        });
      </script>
  
  <script>
    function exportToExcel() {
      fetch('export_transaction.php')
        .then(response => {
          if (response.ok) return response.blob();
          throw new Error('Network response was not ok.');
        })
        .then(blob => {
          // Create a new URL for the blob
          const url = window.URL.createObjectURL(blob);
          // Create a new <a> element for the download
          const a = document.createElement('a');
          a.href = url;
          a.download = 'transactions.csv'; // Specify the file name for download
          document.body.appendChild(a); // Append <a> to <body>
          a.click(); // Simulate click on <a> to start download
          window.URL.revokeObjectURL(url); // Clean up URL object
          a.remove(); // Remove <a> from <body>
        })
        .catch(error => {
          console.error('There was an error:', error);
        });
    }
  </script>

<script>
    function exportOrderToExcel() {
      fetch('export_orders.php')
        .then(response => {
          if (response.ok) return response.blob();
          throw new Error('Network response was not ok.');
        })
        .then(blob => {
          // Create a new URL for the blob
          const url = window.URL.createObjectURL(blob);
          // Create a new <a> element for the download
          const a = document.createElement('a');
          a.href = url;
          a.download = 'orders.csv'; // Specify the file name for download
          document.body.appendChild(a); // Append <a> to <body>
          a.click(); // Simulate click on <a> to start download
          window.URL.revokeObjectURL(url); // Clean up URL object
          a.remove(); // Remove <a> from <body>
        })
        .catch(error => {
          console.error('There was an error:', error);
        });
    }
  </script>

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

<!-- Checking for Email -->
<script>
$(document).ready(function(){
    $('#email').on('input', function(){
        var email = $(this).val();
        if(email.length > 0) {
            $.ajax({
                url: 'check_email.php',                
                method: 'POST',
                data: {email: email},
                dataType: 'json',
                success: function(response) {
                    if(response.exists) {
                        $('#email').removeClass('is-valid').addClass('is-invalid');
                        $('#emailFeedback').text('Email is already taken.');
                        $('#nextButton').prop('disabled', true); // Disable the Next button
                    } else {
                        $('#email').removeClass('is-invalid').addClass('is-valid');
                        $('#emailFeedback').text('');
                        $('#nextButton').prop('disabled', false); // Enable the Next button
                    }
                }
            });
        } else {
            $('#email').removeClass('is-valid is-invalid');
            $('#emailFeedback').text('');
        }
    });
});
</script>

<!-- Checking for Username-->
<script>
$(document).ready(function(){
    $('#username').on('input', function(){
        var username = $(this).val();
        if(username.length > 0) {
            $.ajax({
                url: 'check_username.php',
                method: 'POST',
                data: {username: username},
                dataType: 'json',
                success: function(response) {
                    if(response.exists) {
                        $('#username').removeClass('is-valid').addClass('is-invalid');
                        $('#usernameFeedback').text('Username is already taken.');
                        $('#nextButton').prop('disabled', true); // Disable the Next button
                    } else {
                        $('#username').removeClass('is-invalid').addClass('is-valid');
                        $('#usernameFeedback').text('');
                        $('#nextButton').prop('disabled', false); // Enable the Next button
                    }
                }
            });
        } else {
            $('#username').removeClass('is-valid is-invalid');
            $('#usernameFeedback').text('');
            $('#nextButton').prop('disabled', false); // Enable the Next button if username is empty
        }
    });
});

</script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="bootstrap-4.5.3-dist/js/bootstrap.js"></script>
    <script src="script.js"></script>

</body>
</html>