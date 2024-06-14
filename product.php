<?php
require_once('classes/database.php');
session_start();

// Initialize the database connection
$con = new database();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dynrax Auto Supply | Products</title>

    <!-- Style -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap-4.5.3-dist/css/bootstrap.css">

    <!-- Boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="./package/dist/sweetalert2.css">

</head>

<style>
.pagination {
    display: flex;
    justify-content: center;
    padding: 20px 0;
}

.pagination a {
    color: white;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    margin: 0 4px;
}

.pagination a.active {
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {background-color: #ddd;}
</style>
<body>

      <!-- Header -->
          
      <!-- End Header -->

    <div class="maint-container">

        <div class="aside">
            <div class="navbar-logo">
                <a href="index.php"><img src="import/Dynrax Web Finals.png"></a>
            </div>
        
            <div class="navbar-toggle">
                <span></span>
            </div>
        
            <ul class="nav">
                <li><a href="index.php" style="text-decoration:none;"><i class="bx bx-home"></i>Home</a></li>
                <li><a href="product.php" style="text-decoration:none;" class="active"><i class="bx bx-package"></i>Order</a></li>
                <li><a href="transaction.php" style="text-decoration:none;"><i class="bx bx-cart"></i>Transaction</a></li>
                <li><a href="stock.php" style="text-decoration:none;"><i class="bx bx-store"></i>Stock</a></li>
                <li><a href="sale.php" style="text-decoration:none;"><i class="bx bx-dollar"></i>Total Sale</a></li>
            </ul>
        
          </div>
    
        <div class="main-content">
    
            <section class="product section" id="product">

            <?php include('includes/header.php'); ?>
    
                <div class="title-product">
                  <h1>Products</h1>
                </div>
      
                <!-- Chart of Sales -->
      
                <div class="products">
                  <div class="container-fluid">
        
                      <!-- To add item for Customer Order -->
                        <div class="item-view">
                          <div class="product-detail">
      
                            <div class="items">
                              <h2>Customer Order</h2>
                            </div>
      
                            <!-- Customer Order Form -->
                            
                            <!-- Bago -->
                            <!-- <form class="order-form" method="post"> -->
                            <div class="container-fluid">
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="orders">
                                      <div class="searchbar">
                                        <div class="row">
                                            <div class="col-md-6">
                                            <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
                                            </div>
                                            <div class="col-md-6">
                                              <div class="mb-3">
                                              <select class="form-select" id="stockCategory" onchange="changeCategory()">
                                                    <option value="0">All</option>
                                                    <?php 
                                                    $category = $con->viewCat();
                                                    foreach($category as $cat){
                                                        echo "<option value='{$cat['cat_id']}'>{$cat['cat_type']}</option>";
                                                    }
                                                    ?>
                                              </select>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="container-fluid my-5">
                                          <div class="card-container">
                                                <!-- To be filled automatically -->
                                          </div>
                                          <div class="pagination-container">
                                              <!-- To be filled automatically -->
                                          </div>
                                          </div>
                                        </div>
                                      </div>
                                          
                                        


                                  <div class="col-md-6">
                                    <div class="checkout">
                                      <!-- Start of your new form -->
                                      <form id="myForm" method="post">
                                        <div class="row">
                                          <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Enter Customer Name" name="customer_name">
                                          </div>
                                          <div class="col-md-6">
                                            <div class="mb-3">
                                              <select class="form-select" id="paymentmethod" name="payment_method">
                                                <option value="0">Select Payment</option>
                                                <option value="1">Cash</option>
                                                <option value="2">Debit/Credit</option>
                                                <option value="3">E-Wallet</option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-12 my-3">
                                            <input type="number" class="form-control" id="amountPaid" placeholder="Enter amount paid">
                                          </div>
                                        </div>
                                        <div class="head"><p>My Cart</p></div>
                                        <div id="cartItem">Your cart is Empty</div>
                                        <div class="foot">
                                          <h3>Total</h3>
                                          <h2 id="total">₱ 0.00</h2>
                                          <h2 id="changeDisplay">Change: ₱0.00</h2>
                                          <input type="hidden" id="cartItemsInput" name="cart_items">
                                          <!-- Your original form's submit button -->
                                          <button class="checkouts" type="submit" id="submitButton" name="checkout">Checkout</button>
                                        </div>
                                      </form>
                                      <!-- End of your new form -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <!-- </form> -->
                            <!-- hangang Dito -->
                          </div>
                        </div>

                  </div>
                </div>
                
              </section>
        </div>
    </div>

    <!-- Add Account Modal -->
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
          <button type="submit" class="btn btn-danger" id="submitBtn" name="addAccountButton" disabled>Add Account</button>
        </div>
      </form>
    </div>
  </div>
</div>



    <!-- AJAX libary -->
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>





<script>
  document.addEventListener("DOMContentLoaded", function () {
  let cart = [];

  // Delegate the click event from the document to the add-button
  document.addEventListener('click', event => {
    const addButton = event.target.closest('.add-button'); // Find the closest parent which is an add-button
    if (addButton) {
      const itemId = addButton.dataset.itemId;
      const itemPrice = parseFloat(addButton.dataset.price);
      const itemTitle = addButton.dataset.title;
      const itemBrand = addButton.dataset.brand;
      const itemImageUrl = addButton.dataset.imageUrl;
      const itemStock = parseInt(addButton.dataset.stock); // Get the stock from the button

      if (itemStock <= 0) {
        alert("This product is out of stock.");
        return; // Exit if the product is out of stock
      }

      let existingItem = cart.find(item => item.product_id === itemId);

      if (existingItem) {
        if (existingItem.quantity < existingItem.stock) {
          existingItem.quantity++;
        } else {
          alert("You've reached the maximum available stock for this product.");
        }
      } else {
        cart.push({
          product_id: itemId,
          price: itemPrice,
          product_name: itemTitle,
          product_brand: itemBrand,
          item_image: itemImageUrl,
          quantity: 1,
          stock: itemStock // Store the stock in the cart item
        });
      }

      updateCartDisplay();
    }
  });
    
      function updateCartDisplay() {
        const cartItemContainer = document.getElementById('cartItem');
        const totalContainer = document.getElementById('total');
        const cartItemsInput = document.getElementById('cartItemsInput');
    
        // Clear the cart display
        cartItemContainer.innerHTML = '';
        let total = 0;
    
        // Add each item in the cart to the display
        cart.forEach((item, index) => {
          let itemElement = document.createElement('div');
          itemElement.innerHTML = `
            <div class="cart-item">
              <img src="${item.item_image}" alt="${item.product_name}">
              <div class="item-details">
                <h4>${item.product_name}</h4>
                <p>${item.product_brand}</p>
                <p>₱${item.price.toFixed(2)}</p>
                <div class="quantity-controls">
                  <button class="decrement" data-index="${index}">-</button>
                  <span>${item.quantity}</span>
                  <button class="increment" data-index="${index}">+</button>
                </div>
              </div>
            </div>
          `;
    
          cartItemContainer.appendChild(itemElement);
    
          // Add the item's price to the total
          total += item.price * item.quantity;
    
          // Add event listeners to the increment and decrement buttons
          itemElement.querySelector('.increment').addEventListener('click', incrementItem);
          itemElement.querySelector('.decrement').addEventListener('click', decrementItem);
        });
    
        // Update the total price display
        totalContainer.textContent = '₱ ' + total.toFixed(2);
    
        // Save the cart items to the hidden input field
        cartItemsInput.value = JSON.stringify(cart);
      }
      
    
      function incrementItem(event) {
        const index = parseInt(event.target.dataset.index);
        if (cart[index].quantity < cart[index].stock) {
          cart[index].quantity++;
          updateCartDisplay();
        } else {
          alert("You've reached the maximum available stock for this product.");
        }
      }
    
      function decrementItem(event) {
        const index = parseInt(event.target.dataset.index);
        if (cart[index].quantity > 1) {
          cart[index].quantity--;
        } else {
          cart.splice(index, 1);
        }
        updateCartDisplay();
      }
    });
    </script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchProducts();

    document.addEventListener('click', function(e) {
        if (e.target.matches('.page-link')) {
            e.preventDefault();
            const page = e.target.getAttribute('href').split('page=')[1];
            fetchProducts(page);
        }
    });
});

function fetchProducts(page = 1) {
    fetch(`fetch_product.php?page=${page}`)
        .then(response => response.json())
        .then(data => {
            if (!data.error) {
                document.querySelector('.card-container').innerHTML = data.products;
                document.querySelector('.pagination-container').innerHTML = data.pagination;
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error('Error fetching products:', error));
}
</script>

<script>
$(document).ready(function(){
  $('#searchInput').on('input', function() {
    var searchQuery = $('#searchInput').val();

    if (searchQuery === '') {
      // If search bar is cleared, fetch initial products without reloading the page
      fetchProducts();
    } else {
      // If there is a search query, perform the search
      $.ajax({
        url: 'search_product_orders.php',
        type: 'post',
        data: {search: searchQuery},
        success: function(response) {
          $('.card-container').html(response);
        }
      });
    }
  });
});
</script>

<script>
document.getElementById('stockCategory').addEventListener('change', function() {
  // Adjusted to check if the "All" option (with value "0") is selected
  if (this.value === "0") {
    // Code to revert to the initial page
    document.querySelector('.card-container').innerHTML = '<p>Loading initial products...</p>';
    fetchInitialProducts(); // Call the function to fetch and display all initial products
    return; // Exit the function early
  }

  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'get_products.php?cat_id=' + this.value, true);
  document.querySelector('.card-container').innerHTML = '<p>Loading products...</p>';

  xhr.onload = function() {
    if (this.status == 200) {
      try {
        var products = JSON.parse(this.responseText);
        var output = '';
        if (products.length > 0) {
          for (let i = 0; i < products.length; i++) {
            output += `<div class="card mb-4">
              <img src="${products[i].item_image}" class="card-img-top" alt="${products[i].product_name}">
              <div class="card-bodys">
                <h5 class="card-titles">${products[i].product_name}</h5>
                <p class="card-texts">${products[i].product_brand}</p>
                <h2 class="card-prices">₱${products[i].price}</h2>
                <div class="checkoutbtns">
                  <button type="button" class="add-button"
                    data-item-id="${products[i].product_id}"
                    data-image-url="${products[i].item_image}"
                    data-brand="${products[i].product_brand}"
                    data-title="${products[i].product_name}"
                    data-price="${products[i].price}"
                    data-stock="${products[i].stocks}">Add to Cart</button>
                </div>
              </div>
            </div>`;
          }
        } else {
          output = '<p>No products found.</p>';
        }
        document.querySelector('.card-container').innerHTML = output;
      } catch (e) {
        document.querySelector('.card-container').innerHTML = '<p>Error parsing product data. Please try again.</p>';
      }
    } else {
      document.querySelector('.card-container').innerHTML = '<p>Error loading products. Please try again.</p>';
    }
  };

  xhr.onerror = function() {
    document.querySelector('.card-container').innerHTML = '<p>Network error. Please check your connection and try again.</p>';
  };

  xhr.send();
});

function fetchInitialProducts() {
  // Implement the logic to fetch and display all initial products
  // This could be similar to the existing AJAX call but without the category filter
  // For demonstration, let's assume it's a simple fetch to a PHP script that returns all products
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'get_products.php', true); // Assuming 'get_products.php' returns all products when no cat_id is provided
  xhr.onload = function() {
    if (this.status == 200) {
      try {
        var products = JSON.parse(this.responseText);
        var output = '';
        for (let i = 0; i < products.length; i++) {
          // Same logic to build the output with all products
        }
        document.querySelector('.card-container').innerHTML = output;
      } catch (e) {
        document.querySelector('.card-container').innerHTML = '<p>Error parsing product data. Please try again.</p>';
      }
    } else {
      document.querySelector('.card-container').innerHTML = '<p>Error loading products. Please try again.</p>';
    }
  };
  xhr.onerror = function() {
    document.querySelector('.card-container').innerHTML = '<p>Network error. Please check your connection and try again.</p>';
  };
  xhr.send();
}
</script>




<script>
document.addEventListener("DOMContentLoaded", function() {
  // Listen for input events on the amount paid field
  document.getElementById('amountPaid').addEventListener('input', function() {
    const totalAmount = parseFloat(document.getElementById('total').textContent.replace('₱ ', ''));
    const amountPaid = parseFloat(this.value);

    // Calculate the change
    const change = amountPaid - totalAmount;

    // Display the change
    // Ensure there's a place in your HTML to display the change. For example:
    // <div id="changeDisplay">Change: ₱0.00</div>
    if (!isNaN(change) && change >= 0) {
      document.getElementById('changeDisplay').textContent = 'Change: ₱' + change.toFixed(2);
    } else {
      document.getElementById('changeDisplay').textContent = 'Change: ₱0.00';
    }
  });
});
</script>


<script src="./package/dist/sweetalert2.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById('myForm');

      form.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting immediately

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to proceed with the checkout?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
          if (result.isConfirmed) {
            // User confirmed, now submit form data using AJAX
            const formData = new FormData(form); // Assuming 'form' is your form element

            fetch('submit.php', { // Your server-side script to handle the submission
              method: 'POST',
              body: formData,
            })
            .then(response => response.json()) // Assuming the server responds with JSON
            .then(data => {
              // Handle server response here
              if(data.success) {
                Swal.fire('Success!', 'Your transaction has been recorded.', 'success')
                .then(() => {
                  window.location.reload(); // Reload the page after showing success message
                });
              } else {
                Swal.fire('Error!', 'There was a problem with your transaction.', 'error');
              }
            })
            .catch((error) => {
              // Handle any error that occurred during the fetch operation
              console.error('Error:', error);
              Swal.fire('Error!', 'There was a problem with your transaction.', 'error');
            });
          }
        });
      });
    });
    </script>  

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="bootstrap-4.5.3-dist/js/bootstrap.js"></script>
    <script src="script.js"></script>
    

</body>
</html>