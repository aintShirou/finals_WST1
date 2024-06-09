<?php

require_once('classes/database.php');
    $con = new database();  

    
    if(isset($_POST['save'])){
      $product_id = $_POST['product_id']; 
      $quantity = $_POST['stock'];
      if($con->addProductStock($product_id, $quantity)){
          echo "Stock updated successfully.";
      } else {
          echo "Failed to update stock.";
      }
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

     <!-- Sweet Alert -->
     <link rel="stylesheet" href="./package/dist/sweetalert2.css">

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
                  <?php
                    $total = $con->getTotalSales();
                      ?>
                  <div class="box">
                    <h3>Total Sales</h3>
                    <h1><?php echo number_format($total['total'], 0);?><h1>
                    <p><span>100%</span> +₱2.8k this week</p>
                  </div>
                </div>
                <div class="col-md-3">
                <?php
                    $cust = $con->getTotalCustomers();
                      ?>
                  <div class="box">
                    <h3>Total Customers</h3>
                    <h1><?php echo $cust['total'];?></h1>
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
                <?php
                    $totalp = $con->countTotalProductStocks();
                      ?>
                  <div class="box">
                    <h3>Product in Stocks</h3>
                    <h1><?php echo $totalp['total'];?></h1>
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
                            <?php
                              $lowstocks = $con->lowStocks();
                              foreach($lowstocks as $lowstock){
                                ?>
                            <div class="product-card">
                                <div class="productimgs">
                                <img class="product-images" src="<?php echo $lowstock['item_image'];?>" alt="Product Image">
                                </div>
                                <div class="product-details">
                                    <h4 class="product-names"><?php echo $lowstock['product_brand'];?></h4>
                                    <h4 class="product-name"><?php echo $lowstock['product_name'];?></h4>
                                    <p class="product-quantitys">Only <strong><?php echo $lowstock['stocks'];?></strong> left in stock!</p>
                                    <input type="hidden" name="product_id" value="<?php echo $lowstock['product_id'];?>">
                                    <a class="product-link" href="#" data-toggle="modal" data-target="#editstockModal" data-product-id="<?php echo $lowstock['product_id']; ?>" data-product-brand="<?php echo $lowstock['product_brand']; ?>" data-product-name="<?php echo $lowstock['product_name']; ?>">Add Stock</a>
                                </div>
                            </div>
                            <?php
                              }
                              ?>

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
        <div id="productDetails">
          <h6>Brand: <span id="productBrand"></span></h6>
          <h6>Name: <span id="productName"></span></h6>
        </div>
        <form method="post" id="stockForm">
          <!-- Hidden input for product ID -->
          <input type="hidden" id="modalProductIdInput" name="product_id">
          
          <div class="form-group">
            <label for="newStockLevel">Stock to Add:</label>
            <input type="number" class="form-control" id="newStockLevel" name="newStockLevel">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" id="save-changes">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="./package/dist/sweetalert2.min.js"></script>

      <script>
      document.addEventListener('DOMContentLoaded', function () {
        var productLinks = document.querySelectorAll('.product-link');
        productLinks.forEach(function(link) {
          link.addEventListener('click', function() {
            var productId = this.getAttribute('data-product-id');
            var productBrand = this.getAttribute('data-product-brand');
            var productName = this.getAttribute('data-product-name');
      
            // Set the values in the modal
            document.getElementById('modalProductIdInput').value = productId; // Assuming you have this input in your modal
            document.getElementById('productBrand').textContent = productBrand;
            document.getElementById('productName').textContent = productName;
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
$(document).ready(function(){
  $('#save-changes').click(function(){
    var productId = $('#modalProductIdInput').val();
    var newStockLevel = $('#newStockLevel').val();

    $.ajax({
      url: 'update_stock.php',
      type: 'POST',
      data: {
        product_id: productId,
        newStockLevel: newStockLevel
      },
      success: function(response) {
        var data = JSON.parse(response);
        if(data.status === 'success') {
          Swal.fire({
            title: 'Success!',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.value) {
              window.location.reload(); // Reload the page
            }
          });
          // Close the modal if you have one
          $('#editstockModal').modal('hide');
        } else {
          Swal.fire({
            title: 'Error!',
            text: data.message,
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      },
      error: function() {
        Swal.fire({
          title: 'Error!',
          text: 'There was an error updating the stock.',
          icon: 'error',
          confirmButtonText: 'OK'
        });
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