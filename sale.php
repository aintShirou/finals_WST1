<?php

require_once('classes/database.php');
    $con = new database();  
   
    session_start();

    if (!isset($_SESSION['user'])) {
      header('location:login.php');
      exit();
    }

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

   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/adapters/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="bootstrap-4.5.3-dist/js/bootstrap.js"></script>
    <script src="script.js?v=<?php echo time();?>"></script>

</body>
</html>