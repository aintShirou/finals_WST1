<?php

require_once('classes/database.php');
$con = new database();  

    session_start();

    if (!isset($_SESSION['user'])) {
      header('location:login.php');
      exit();
    }

 
    
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
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap-4.5.3-dist/css/bootstrap.css">

    <!-- Boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

     <!-- Sweet Alert -->
     <link rel="stylesheet" href="./package/dist/sweetalert2.css">

</head>
<body>

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

        <?php include('includes/header.php'); ?>

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
                      <?php
                        $totalSalesThisWeek = $con->getTotalSalesDifference();
                        $percentage = $con->getSalesPercentage();
                        ?>
                    <p><span><?php echo round($percentage); ?>%</span> +₱<?php echo $totalSalesThisWeek; ?> this week</p>
                  </div>
                </div>
                <div class="col-md-3">
                <?php
                    $cust = $con->getTotalCustomers();
                      ?>
                  <div class="box">
                    <h3>Total Customers</h3>
                    <h1><?php echo $cust['total'];?></h1>
                    <?php
                       $weeklyCust = $con->weeklyCustomerCount();
                       $custPercentage = $con->customerPercentage();
                       ?>
                    <p><span><?php echo round($custPercentage); ?>%</span> <?php echo $weeklyCust; ?> customers this week</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="box">
                    <h3>Orders Completed</h3>
                    <?php
                      $orders = $con->totalOrdersCompleted();
                      ?>
                    <h1></span> <?php echo $orders; ?></h1>
                    <?php
                      $weeklyOrders = $con->weeklyOrders();
                      $orderPercentage = $con->orderPercentage();
                      ?>
                    <p><span><?php echo round($orderPercentage); ?>%</span> <?php echo $weeklyOrders; ?> orders this week</p>

                  </div>
                </div>
                <div class="col-md-3">
                <?php
                    $totalp = $con->countTotalProductStocks();
                      ?>
                  <div class="box">
                    <h3>Product in Stocks</h3>
                    <h1><?php echo $totalp['total'];?></h1>
                    <!-- <p><span>100%</span> +₱2.8k this week</p> -->
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
                  <div class="lowstock">
                    <div class="container-fluid">
                    <div class="card-container">
                      <?php
                      $lowstocks = $con->lowStocks();
                      foreach($lowstocks as $lowstock){
                        ?>
                    <div class="card">
                       
                        <img class="card-img" src="<?php echo $lowstock['item_image'];?>" alt="Product Image">
                        
                        <div class="product-details">
                            <h4 class="product-names"><?php echo $lowstock['product_brand'];?></h4>
                            <h4 class="product-name"><?php echo $lowstock['product_name'];?></h4>
                            <p class="product-quantitys">Only <strong><?php echo $lowstock['stocks'];?></strong> left in stock!</p>
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
                  <div class="topproduct">
                  <div class="container-fluid">
                    <div class="card-container">
                        <div class="row">
                          <div class="col-md-6">
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
          <h6>Brand: <span id="productnames"></span></h6>
          <h6>Name: <span id="productname"></span></h6>
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

<!-- ADD Account -->

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
            <input type="text" class="form-control" id="firstname" name="firstname" required>
          </div>
          <div class="mb-3">
            <label for="lastname" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastname" name="lastname" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
              <div class="valid-feedback">Looks good!</div>
              <div class="invalid-feedback">Please enter valid email.</div>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
              <div class="valid-feedback">Looks good!</div>
              <div class="invalid-feedback">Username is already taken.</div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
              <div class="valid-feedback">Looks good!</div>
              <div class="invalid-feedback">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one special character.</div>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Password doesn't match</div>
          </div>
          <!-- <div id="passwordError" class="mb-3" style="color: red; display: none;"></div> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" id="submitBtn" name="addAccountButton">Add Account</button>
        </div>
      </form>
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
            document.getElementById('modalProductIdInput').value = productId; 
            document.getElementById('productnames').textContent = productBrand;
            document.getElementById('productname').textContent = productName;
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

<!-- Validation of Password -->
<script>
$(document).ready(function() {
    // Initially disable the "Add Account" button
    $('#submitBtn').prop('disabled', true);

    // Validate individual input
    function validateInput(input) {
      if (input.name === 'password') {
        return validatePassword(input);
      } else if (input.name === 'confirmPassword') {
        return validateConfirmPassword(input);
      } else {
        if (input.checkValidity()) {
          input.classList.remove("is-invalid");
          input.classList.add("is-valid");
          return true;
        } else {
          input.classList.remove("is-valid");
          input.classList.add("is-invalid");
          return false;
        }
      }
    }

   
    function validatePassword(passwordInput) {
      const password = passwordInput.value;
      const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
      if (regex.test(password)) {
        passwordInput.classList.remove("is-invalid");
        passwordInput.classList.add("is-valid");
        return true;
      } else {
        passwordInput.classList.remove("is-valid");
        passwordInput.classList.add("is-invalid");
        return false;
      }
    }

    // Validate confirm password
    function validateConfirmPassword(confirmPasswordInput) {
      const passwordInput = document.querySelector("input[name='password']");
      const password = passwordInput.value;
      const confirmPassword = confirmPasswordInput.value;

      if (password === confirmPassword && password !== '') {
        confirmPasswordInput.classList.remove("is-invalid");
        confirmPasswordInput.classList.add("is-valid");
        return true;
      } else {
        confirmPasswordInput.classList.remove("is-valid");
        confirmPasswordInput.classList.add("is-invalid");
        return false;
      }
    }

    // Attach input event listeners to all form inputs for validation
    $('input').on('input', function() {
      const allValid = Array.from(document.querySelectorAll('input')).every(input => validateInput(input));
      $('#submitBtn').prop('disabled', !allValid);
    });

    // Prevent form submission on Enter key
    document.addEventListener("keydown", (event) => {
      if (event.key === 'Enter') {
        event.preventDefault(); // Prevent form submission
      }
    });

    // Form submission with AJAX
    $('#addAccountForm').submit(function(e) {
      e.preventDefault(); // Prevent default form submission

      // Check if all inputs are valid before submitting
      const allValid = Array.from(document.querySelectorAll('input')).every(input => validateInput(input));
      if (!allValid) {
        alert("Please correct the errors before submitting.");
        return;
      }

      var formData = $(this).serialize(); // Serialize form data

      $.ajax({
        type: "POST",
        url: "add_Account.php", // Server-side script to process the form
        data: formData,
        success: function(response) {
          // Handle success. 'response' is what's returned from the server
          alert("Account added successfully");
          $('#addaccountModal').modal('hide'); 
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
    <script src="additem.js"></script>

</body>
</html>