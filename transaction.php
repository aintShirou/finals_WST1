<?php 
  require_once('classes/database.php');
  $con = new database();


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

                <div class="title-product">
                  <h1>Transactions</h1>
                </div>
      
                <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Transaction of the Customer -->
                        <div class="transactions">
                            <h2>Transaction</h2>
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
                                    <td><?php echo $counter_trans--;?></td>
                                    <td><?php echo ucwords($transaction['customer_name']);?></td>
                                    <td><?php echo ucwords($transaction['payment_method']);?></td>
                                    <td><?php echo ucwords($transaction['paymentdate']);?></td>
                                    <td>PHP <?php echo ucwords($transaction['total_purchases']);?></td>
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
    <h2>Recent Purchase</h2>
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
  

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="bootstrap-4.5.3-dist/js/bootstrap.js"></script>
    <script src="script.js"></script>

</body>
</html>