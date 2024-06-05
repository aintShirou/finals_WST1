<?php
    
    require_once('classes/database.php');
    $con = new database();  


    if (isset($_POST['checkout'])) {
      try {
          // Get the form data
          $customer_name = $_POST['customer_name'];
          $payment_method = $_POST['payment_method'];
          $cart_items = json_decode($_POST['cart_items'], true);
  
          // Check if cart items are empty
          if (empty($cart_items)) {
              throw new Exception('Cart is empty');
          }
  
          // Loop through the cart items and save each one to the database
          foreach ($cart_items as $item) {
              $product_id = $item['product_id'];
              $quantity = $item['quantity'];
  
              // Insert the order into the database
              $order_id = $con->insertOrders($customer_name, $product_id, $quantity);
  
              // Insert the transaction into the database
              $con->insertTransaction($order_id, $payment_method, date('Y-m-d H:i:s'), $item['price'] * $quantity);
  
              // Subtract product bought from the available stocks
              $con->updateProductStock($product_id, $quantity);
          }
          header("Location: product.php");
          exit();
      } catch (Exception $e) {
          // Handle the exception
          echo 'Error: '. $e->getMessage();
      }
  }

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

</head>
<body>

      <!-- Header -->
          
      <!-- End Header -->

    <div class="maint-container">

        <div class="aside">
            <div class="navbar-logo">
                <a href="index.html"><img src="import/Dynrax Web Finals.png"></a>
            </div>
        
            <div class="navbar-toggle">
                <span></span>
            </div>
        
            <ul class="nav">
                <li><a href="index.html" style="text-decoration:none;"><i class="bx bx-home"></i>Home</a></li>
                <li><a href="product.html" style="text-decoration:none;" class="active"><i class="bx bx-package"></i>Order</a></li>
                <li><a href="transaction.php" style="text-decoration:none;"><i class="bx bx-cart"></i>Transaction</a></li>
                <li><a href="stock.html" style="text-decoration:none;"><i class="bx bx-store"></i>Stock</a></li>
                <li><a href="sale.html" style="text-decoration:none;"><i class="bx bx-dollar"></i>Total Sale</a></li>
            </ul>
        
          </div>
    
        <div class="main-content">
    
            <section class="product section" id="product">
    
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
                            <form class="order-form" method="post">
                              <div class="container-fluid">
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="orders">
                                      <div class="searchbar">
                                        <div class="row">
                                            <div class="col-md-6">
                                              <input type="text" class="form-control" placeholder="Search products..." name="search_products">
                                            </div>
                                            <div class="col-md-6">
                                              <div class="mb-3">
                                                <select class="form-select" id="productCategory" name="product_category">
                                                  <option value="0">Select Category</option>
                                                  <?php 
                                                    $category = $con->viewCat();
                                                    foreach($category as $cat){
                                                    ?>
                                                      <option value="<?php echo $cat['cat_id'];?>"><?php echo $cat['cat_type'];?></option>
                                                    <?php
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
                                          $products = $con->viewProducts();
                                          foreach($products as $product) {
                                            ?>
                                            <div class="view-products">
                                              <div class="product-boxs">
                                                <img class="product-image" src="<?php echo $product['item_image']; ?>">
                                                <p class="product-brand"><?php echo $product['product_brand']; ?></p>
                                                <p class="product-title"><?php echo $product['product_name']; ?></p>
                                                <h2 class="product-price">₱<?php echo $product['price']; ?></h2>
                                              <div class="checkoutbtn">
                                              <button type="button" class="add-button"
                                              data-item-id="<?php echo $product['product_id']; ?>"
                                              data-image-url="<?php echo $product['item_image']; ?>" 
                                              data-brand="<?php echo $product['product_brand']; ?>" 
                                              data-title="<?php echo $product['product_name']; ?>" 
                                              data-price="<?php echo $product['price']; ?>">
                                              Add to Cart
                                            </button>
                                              </div>
                                            </div>
                                          </div>
                                          <?php
                                          }
                                          ?>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="checkout">
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
                                      <div class="head"><p>My Cart</p></div>
                                      <div id="cartItem">Your cart is Empty</div>
                                      <div class="foot">
                                        <h3>Total</h3>
                                        <h2 id="total">₱ 0.00</h2>
                                        <input type="hidden" id="cartItemsInput" name="cart_items">
                                        <button id="checkoutButton" type="submit" name="checkout">Checkout</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </form>
                            <!-- hangang Dito -->
                          </div>
                        </div>

                  </div>
                </div>
                
              </section>
    
        </div>

    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
      let cart = [];
    
      // Listen for click events on the "Add to Cart" buttons
      document.querySelectorAll('.add-button').forEach(button => {
        button.addEventListener('click', event => {
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
        });
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



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="bootstrap-4.5.3-dist/js/bootstrap.js"></script>
    <script src="script.js"></script>
    

</body>
</html>