<?php
    
    require_once('classes/database.php');
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
                                            <?php 
                                              $categoryId = isset($_GET['cat_id']) && $_GET['cat_id']!= '0'? filter_input(INPUT_GET, 'cat_id', FILTER_SANITIZE_NUMBER_INT) : null;
                                              $page = isset($_GET['page'])? filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) : 1;
                                              $records_per_page = 2;
                                              $products = $con->viewProducts1($categoryId, $page, $records_per_page);
                                              foreach($products as $product) {
                                          ?>                      
                                            <div class="col-md-4">
                                              <div class="card mb-4">
                                                <img src="<?php echo $product['item_image'];?>" class="card-img-top" alt="<?php echo $product['product_name'];?>">
                                                <div class="card-bodys">
                                                  <h5 class="card-titles"><?php echo $product['product_name'];?></h5>
                                                  <p class="card-texts"><?php echo $product['product_brand'];?></p>
                                                  <h2 class="card-prices">₱<?php echo $product['price'];?></h2>
                                                  <div class="checkoutbtns">
                                                    <button type="button" class="add-button"
                                                    data-item-id="<?php echo $product['product_id'];?>"
                                                    data-image-url="<?php echo $product['item_image'];?>" 
                                                    data-brand="<?php echo $product['product_brand'];?>" 
                                                    data-title="<?php echo $product['product_name'];?>" 
                                                    data-price="<?php echo $product['price'];?>">
                                                    Add to Cart
                                                  </button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                            <?php
                                              }
                                          ?>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="pagination">
                                    <?php
                                        $total_products = $con->getProductCount($categoryId);
                                        $total_pages = ceil($total_products / $records_per_page);

                                        for ($i = 1; $i <= $total_pages; $i++) {
                                            $class = ($page == $i) ? 'class="active"' : '';
                                            echo "<a href='product.php?cat_id=$categoryId&page=$i' $class>$i</a> ";
                                        }
                                      ?>
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

    <?php include("modal.php")?>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
  let cart = [];

  // Listen for click events on the document
  document.addEventListener('click', event => {
    // Check if the clicked element is an "Add to Cart" button
    if (event.target.matches('.add-button')) {
      const itemId = event.target.dataset.itemId;
      const itemPrice = parseFloat(event.target.dataset.price);
      const itemTitle = event.target.dataset.title;
      const itemBrand = event.target.dataset.brand;
      const itemImageUrl = event.target.dataset.imageUrl;

      // Check if the item is already in the cart
      let existingItem = cart.find(item => item.product_id === itemId);

      if (existingItem) {
        // If the item is already in the cart, increment the quantity
        existingItem.quantity++;
      } else {
        // If the item is not in the cart, add it
        cart.push({
          product_id: itemId,
          price: itemPrice,
          product_name: itemTitle,
          product_brand: itemBrand,
          item_image: itemImageUrl,
          quantity: 1
        });
      }

      // Update the cart display and total price
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
        cart[index].quantity++;
        updateCartDisplay();
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

    <!-- AJAX libary -->
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
  $('#searchInput').on('input', function() {
    var searchQuery = $('#searchInput').val();

    $.ajax({
      url: 'search_product_orders.php',
      type: 'post',
      data: {search: searchQuery},
      success: function(response) {
        $('.card-container').html(response);
      }
    });
  });
});
</script>

<script>
document.getElementById('stockCategory').addEventListener('change', function() {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'get_products.php?cat_id=' + this.value, true);

  // Display loading indicator
  document.querySelector('.card-container').innerHTML = '<p>Loading products...</p>';

  xhr.onload = function() {
    if (this.status == 200) {
        var products = JSON.parse(this.responseText);
        var output = '';
        if (products.length > 0) {
            for (var i in products) {
                output += '<div class="card mb-4">' +
                    '<img src="' + products[i].item_image + '" class="card-img-top" alt="' + products[i].product_name + '">' +
                    '<div class="card-bodys">' +
                    '<h5 class="card-titles">' + products[i].product_name + '</h5>' +
                    '<p class="card-texts">' + products[i].product_brand + '</p>' +
                    '<h2 class="card-prices">₱' + products[i].price + '</h2>' +
                    '<div class="checkoutbtns">' +
                    '<button type="button" class="add-button" ' +
                    'data-item-id="' + products[i].product_id + '" ' +
                    'data-image-url="' + products[i].item_image + '" ' +
                    'data-brand="' + products[i].product_brand + '" ' +
                    'data-title="' + products[i].product_name + '" ' +
                    'data-price="' + products[i].price + '">Add to Cart</button>' +
                    '</div></div></div>';
            }
        } else {
            output = '<p>No products found.</p>';
        }
        document.querySelector('.card-container').innerHTML = output;
    } else {
        // Handle errors
        document.querySelector('.card-container').innerHTML = '<p>Error loading products. Please try again.</p>';
    }
};

  xhr.onerror = function() {
    // Handle network errors
    document.querySelector('.card-container').innerHTML = '<p>Network error. Please check your connection and try again.</p>';
  };

  xhr.send();
});
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
                Swal.fire('Error!', 'There was a problem with your submission.', 'error');
              }
            })
            .catch((error) => {
              // Handle any error that occurred during the fetch operation
              console.error('Error:', error);
              Swal.fire('Error!', 'There was a problem with your submission.', 'error');
            });
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