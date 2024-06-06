<?php

require_once('classes/database.php');
    $con = new database();  



    if(isset($_POST['addstock'])){

    }
   
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynrax Auto Supply</title>

    <!-- Style -->
    <link rel="stylesheet" href="style.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap-4.5.3-dist/css/bootstrap.css">

    <!-- Boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>

    <!-- Header -->
    <header class="header">
    <nav class="navbar navbar-expand-lg">
      <a class="navbar-brand" href="#">
        <img src="import/Dynrax Web Finals.png" alt="Dynrax Auto Supply" height="40">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Welcome, Username
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Add Admin</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>
    <!-- End Header -->

    <!-- Main Container -->

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
            <li><a href="index.php" style="text-decoration:none;" class="active"><i class="bx bx-home"></i>Home</a></li>
            <li><a href="product.php" style="text-decoration:none;"><i class="bx bx-package"></i>Order</a></li>
            <li><a href="transaction.php" style="text-decoration:none;"><i class="bx bx-cart"></i>Transaction</a></li>
            <li><a href="stock.php" style="text-decoration:none;"><i class="bx bx-store"></i>Stock</a></li>
            <li><a href="sale.php" style="text-decoration:none;"><i class="bx bx-dollar"></i>Total Sale</a></li>
        </ul>
    
      </div>

      <!-- Main Content -->
      <div class="main-content">

        <!-- Home Section -->
        <section class="home active section" id="home">

            <!-- Analytics -->

            <div class="container-fluid">
              <div class="row">
                <div class="col-md-3">
                  <div class="box">
                    <h3>Total Sales</h3>
                    <h1>999</h1>
                    <p><span>100%</span> +₱2.8k this week</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="box">
                    <h3>Total Customers</h3>
                    <h1>999</h1>
                    <p><span>100%</span> +₱2.8k this week</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="box">
                    <h3>Transaction History</h3>
                    <h1>999</h1>
                    <p><span>100%</span> +₱2.8k this week</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="box">
                    <h3>Product in Stocks</h3>
                    <h1>999</h1>
                    <p><span>100%</span> +₱2.8k this week</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Low Stock of Product -->

            <div class="container-fluid">
              <div class="row mr-1">
                <div class="col-md-6">
                  <div class="titles-home">
                    <h3>Low Quantity</h3>
                  </div>

                  <div class="container-fluid">
                    <div class="card-container">
                      <div class="lowstock">
                        <div class="row low-stock-products">
                          <div class="col-md-6 low-stock-product-card">

                            <?php
                              $lowstocks = $con->lowStocks();
                              foreach($lowstocks as $lowstock){
                                ?>
                            <div class="product-card">
                                <img class="product-images" src="<?php echo $lowstock['item_image'];?>" alt="Product Image">
                                <div class="product-details">
                                    <h4 class="product-names"><?php echo $lowstock['product_brand'];?></h4>
                                    <h4 class="product-name"><?php echo $lowstock['product_name'];?></h4>
                                    <p class="product-quantitys">Only <strong><?php echo $lowstock['stocks'];?></strong> left in stock!</p>
                                    <a class="product-link" href="#" data-toggle="modal" data-target="#editstockModal">Add Stock</a>
                                </div>
                            </div>
                            <?php
                              }
                              ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <!-- Top Product -->

                <div class="col-md-6">
                  <div class="titles-home">
                    <h3>Top Product</h3>
                  </div>
                  <div class="container-fluid">
                    <div class="card-container">
                      <div class="topproduct">
                        <div class="row low-stock-products">
                          <div class="col-md-6 low-stock-product-card">
                            <div class="product-card">
                               <?php
                                $top = $con->topProduct();
                                  ?>
                                <img class="product-images" src="<?php echo $top['item_image'];?>" alt="Product Image">
                                <div class="product-details">
                                    <h4 class="products-brand"><?php echo $top['product_brand'];?></h4>
                                    <h4 class="products-name"><?php echo $top['product_name'];?></h4>
                                    <p class="products-sale">Product-Sales <?php echo $top['total_sales'];?> </p>
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
              </div>
            </div>

        </section>

      </div>

    </div>

    <!-- Edit Stock Only -->

    <div class="modal fade" id="editstockModal" tabindex="-1" aria-labelledby="editstockModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-dark">
          <div class="modal-header" style="color: #fff;">
            <h5 class="modal-title" id="editstockModalLabel">Add Stock</h5>
          </div>
          <div class="modal-body" style="color: #fff;">
            <form id="productForm">
              <!-- The form fields will go here -->
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#editstockModal').on('shown.bs.modal', function(event) {
      const product_id = $(event.relatedTarget).data('product-id');
      $.ajax({
        type: 'GET',
        url: 'getproductdetails.php', // assume this script returns product details
        data: { productId: product_id }, // pass the product ID as a parameter
        success: function(response) {
          $('#product_brand').text(response.brand);
          $('#product_name').text(response.name);
          $('#stock').text(response.stocks);
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    });

    $('#save-changes').on('click', function() {
      const add_stock = $('#add_stock').val();
      const product_id = $(event.relatedTarget).data('product-id');
      $.ajax({
        type: 'POST',
        url: 'addproductstock.php', // assume this script updates the stock quantity
        data: { productId: product_id, quantity: add_stock }, // pass the product ID and quantity as parameters
        success: function(response) {
          if (response === true) {
            console.log('Stock updated successfully!');
            $('#editstockModal').modal('hide');
          } else {
            console.log('Error updating stock!');
          }
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    });
  });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
  $('#editstockModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let productId = button.data('id'); // Extract info from data-* attributes

    // Make an AJAX request to fetch the product details
    $.ajax({
      url: 'fetch_product.php',
      type: 'GET',
      data: {id: productId},
      success: function (data) {
        $('#productForm').html(data);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error('AJAX error:', textStatus, errorThrown);
      }
    });
  });
});
</script>

    <script>
      $(document).ready(function() {
        $('.dropdown-toggle').dropdown();
      });
    </script>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
    <script src="additem.js"></script>

</body>
</html>