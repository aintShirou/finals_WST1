<?php
    
    require_once('classes/database.php');
    $con = new database();  
   
  
    if(isset($_POST['addproduct'])){
      $product_name = $_POST['productName'];
      $product_brand = $_POST['productBrand'];
      $product_category = $_POST['productCategory'];
      $product_quantity = $_POST['productQuantity'];
      $product_price = $_POST['productPrice'];
      $product_image = $_FILES['productImage'];

      // Handle file upload
      $target_dir = "uploads/";
      $original_file_name = basename($_FILES["productImage"]["name"]);

      // NEW CODE: Initialize $new_file_name with $original_file_name
      $new_file_name = $original_file_name; 

      $target_file = $target_dir . $original_file_name;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
      $uploadOk = 1;

      // Check if file already exists and rename if necessary
      if (file_exists($target_file)) {
        // Generate a unique file name by appending a timestamp
        $new_file_name = pathinfo($original_file_name, PATHINFO_FILENAME) . '_' . time() . '.' . $imageFileType;
        $target_file = $target_dir . $new_file_name;
      } else {
        // Update $target_file with the original file name
        $target_file = $target_dir . $original_file_name;
      }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
      // Check if file is an actual image or fake image
      $check = getimagesize($_FILES["productImage"]["tmp_name"]);
      if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
      }

      // Check file size
      if ($_FILES["productImage"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
      }

      // Allow certain file formats
      if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
      } 

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
      } else {
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file)) {
          echo "The file " . htmlspecialchars($new_file_name) . " has been uploaded.";

          // Save the product data and the path to the product image in the database
          $product_image_path = 'uploads/'.$new_file_name; // Save the new file name (without directory)
          
          $productID = $con->addProduct($product_category, $product_brand, $product_name, $product_quantity, $product_price, $product_image_path);

          if ($productID) {
            // Product addition successful, redirect to index page
            header('location:stock.php');
            exit; // Stop further execution
          } else {
            // Product addition failed, display error message
            echo "Sorry, there was an error adding the product.";
          }
        } else {
          // File upload failed, display error message
          echo "Sorry, there was an error uploading your file.";
        }
      }
    }

  
    if(isset($_POST['addCat'])){
      $category = $_POST['category'];

      $result = $con->addCat($category);

      if($result) {
        header('location:stock.php');
      } else {
        echo "Failed to add category.";
      }
    }

    if(isset($_POST['delete'])){
      $productid = $_POST['id'];
      if($con->delete($productid)){
          header('location:stock.php');
  } else {
      echo 'Something went wrong';
  }
  }

  ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynrax Auto Supply | Stock</title>

    <!-- Style -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap-4.5.3-dist/css/bootstrap.css">

    <!-- Boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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

    <div class="main-container">

        <div class="aside">
            <div class="navbar-logo">
                <a href="index.php"><img src="import/Dynrax Web Finals.png"></a>
            </div>
        
            <div class="navbar-toggle">
                <span></span>
            </div>
        
            <ul class="nav">
                <li><a href="index.php" style="text-decoration:none"><i class="bx bx-home"></i>Home</a></li>
                <li><a href="product.php" style="text-decoration:none;"><i class="bx bx-package"></i>Order</a></li>
                <li><a href="transaction.php" style="text-decoration:none;"><i class="bx bx-cart"></i>Transaction</a></li>
                <li><a href="stock.php" style="text-decoration:none;" class="active"><i class="bx bx-store"></i>Stock</a></li>
                <li><a href="sale.php" style="text-decoration:none;"><i class="bx bx-dollar"></i>Total Sale</a></li>
            </ul>
        
          </div>

          <div class="main-content">

            <section class="stock section" id="stock">

                <div class="title-product">
                  <h1>Stocks</h1>
                </div>
      
                <div class="stocks">
      
                    <div class="stockhead">
                      <div class="row">
                        <div class="col-md-10">
                          <div class="search-cat">
                              <div class="search-bar">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
                              </div>
                              <div class="stock-category">
                                  <label for="stockCategory" class="form-label"></label>
                                  <select class="form-select" id="stockCategory">
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
                        <div class="col-md-2">
                        <div class="select-option">
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle" type="button" id="stockCategoryDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-plus" style="font-size: 35px;"></i>
                              </button>
                            
                              <div class="dropdown-menu" aria-labelledby="stockCategoryDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addProductModal"> Add Product</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addcategoryModal">Add Category</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                
    <!-- Card View for Products -->
                  <div class="productcardview">
                    <div class="container-fluid my-5">
                      <div class="card-container">
                      <?php 
                            $categoryId = isset($_GET['cat_id']) ? $_GET['cat_id'] : null;
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $records_per_page = 2;
                            $products = $con->viewProducts1($categoryId, $page, $records_per_page);
                            foreach($products as $product) {
                        ?>
                        <form method="post">
                          <div class="card">
                            <!-- Image -->
                            <img src="<?php echo $product['item_image']; ?>" class="card-img-top" alt="Item Image">
                            <div class="card-body">
                              <!-- Item Brand -->
                              <h4 class="card-title"><?php echo $product['product_brand']; ?></h4>
                              <!-- Item Name -->
                              <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                              <!-- Item Price -->
                              <p class="card-text">Price: PHP <?php echo $product['price']; ?></p>
                              <!-- Item Quantity -->
                              <p class="card-text">Stocks: <?php echo $product['stocks']; ?></p>
                              <div class="d-flex justify-content-between align-items-center">
                              <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                                <a type="submit" class="btn btn-success" name="editButton" data-toggle="modal" data-target="#editProductModal">
                                  <i class='bx bxs-edit' style="font-size: 25px; vertical-align: middle;"></i>
                                </a>
                                <button type="submit" class="btn btn-danger" name="delete">
                                  <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                                  <i class='bx bx-trash' style="font-size: 25px; vertical-align: middle;"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </form>
                        <?php 
                          } // End of the loop
                        ?>
                      </div>
                    </div>
                  </div>
      
                  <div class="pagination">
    <?php
        // Get the total number of products
        $total_products = $con->getProductCount($categoryId);

        // Calculate the total number of pages
        $total_pages = ceil($total_products / $records_per_page);

        // Display the pagination links
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='stock.php?cat_id=$categoryId&page=$i'>$i</a> ";
        }
    ?>
</div>
      
                </div>
      
              </section>
            
          </div>

    </div>

    <!-- Add Product Modal -->

    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark">
          <div class="modal-header">
            <h5 class="modal-title text-white" id="addProductModalLabel">Add Product</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="productImage" class="form-label text-white">Product Image</label>
                <div class="input-group">
                  <input type="file" class="form-control d-none" id="productImage" name="productImage" aria-describedby="inputGroupFileAddon" onchange="previewImage()">
                  <label class="input-group-text" for="productImage">
                    <i class="bx bx-image" style="font-size: 1.5rem; color: #fff;"></i>
                  </label>
                </div>
              </div>
              <div class="mb-3">
                <img id="preview" src="#" alt="Preview Image" style="max-width: 150px; display: none;">
              </div>
              <div class="mb-3">
                <label for="productBrand" class="form-label text-white">Brand</label>
                <input type="text" class="form-control" id="productBrand" name="productBrand">
              </div>
              <div class="mb-3">
                <label for="productName" class="form-label text-white">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName">
              </div>
              <div class="mb-3">
                <label for="productCategory" class="form-label text-white">Category</label>
                <select class="form-select" id="productCategory" name="productCategory">
                  <option value="selected">Select Category </option>
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
              <div class="mb-3">
                <label for="productQuantity" class="form-label text-white">Quantity</label>
                <input type="number" class="form-control" id="productQuantity" name="productQuantity">
              </div>
              <div class="mb-3">
                <label for="productPrice" class="form-label text-white">Price</label>
                <input type="text" class="form-control" id="productPrice" name="productPrice">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger" name="addproduct">Add Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!--Add Category Modal-->

    <div class="modal fade" id="addcategoryModal" tabindex="-1" aria-labelledby="addcategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content bg-dark">
            <div class="modal-header" style="color: #fff;">
              <h5 class="modal-title" id="addcategoryModalLabel">Add Category</h5>
            </div>
            <form method="post">
              <div class="modal-body" style="color: #fff;">
                <div class="mb-3">
                  <label for="addcategory" class="form-label">Category</label>
                  <input type="text" class="form-control" id="addcategory" name="category">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" name="addCat">Add Category</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    
      <!-- Edit Product Modal -->

      <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content bg-dark">
            <div class="modal-header" style="color: #fff;">
              <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
            </div>
            <form id="editProductForm" method="post">
              <div class="modal-body" style="color: #fff;">
                <input type="hidden" id="editProductId" name="id" value="<?php echo $product['product_id']; ?>">
                <div class="mb-3">
                  <label for="editProductBrand" class="form-label">Brand</label>
                  <input type="text" class="form-control" id="editProductBrand" name="editProductBrand" value="<?php echo $product['product_brand']; ?>">
                </div>
                <div class="mb-3">
                  <label for="editProductName" class="form-label">Product Name</label>
                  <input type="text" class="form-control" id="editProductName" name="editProductName" value="<?php echo $product['product_name']; ?>">
                </div>
                <div class="mb-3">
                  <label for="editProductPrice" class="form-label">Price</label>
                  <input type="text" class="form-control" id="editProductPrice" name="editProductPrice" value="<?php echo $product['price']; ?>">
                </div>
                <div class="mb-3">
                  <label for="editProductQuantity" class="form-label">Quantity</label>
                  <input type="number" class="form-control" id="editProductQuantity" name="editProductQuantity" value="<?php echo $product['stocks']; ?>">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" id="saveChangesButton" name="saveChangesButton">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>


<!-- AJAX Libary -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- AJAX for Updating Product Details-->
<script>
$(document).ready(function() {
  $('#editProductForm').submit(function(event) {
    event.preventDefault();

    var productId = $('#editProductId').val();
    var productBrand = $('#editProductBrand').val();
    var productName = $('#editProductName').val();
    var productPrice = $('#editProductPrice').val();
    var productQuantity = $('#editProductQuantity').val();

    $.ajax({
      url: 'edit_product.php',
      type: 'post',
      data: {
        id: productId,
        productBrand: productBrand,
        productName: productName,
        productPrice: productPrice,
        productQuantity: productQuantity
      },
      success: function(response) {
        // Handle the response from the server
        alert(response);
      }
    });
  });
});
</script>
<!-- Script for Retrieving Data when Edit Button is clicked -->
<script>
$(document).ready(function() {
  $(document).on('click', 'a[name="editButton"]', function(e) {
    e.preventDefault();

    var card = $(this).closest('.card');
    var productBrand = card.find('h4.card-title').text();
    var productName = card.find('h5.card-title').text();
    var productPrice = card.find('.card-text').first().text().replace('Price: PHP ', '');
    var productQuantity = card.find('.card-text').last().text().replace('Stocks: ', '');
    var productId = $(this).siblings('input[name="id"]').val();

    // Set the values in the modal
    $('#editProductId').val(productId); 
    $('#editProductBrand').val(productBrand);
    $('#editProductName').val(productName);
    $('#editProductPrice').val(productPrice);
    $('#editProductQuantity').val(productQuantity);
  });
});
</script>


<script>
$(document).ready(function(){
  $('#searchInput, #stockCategory').on('input change', function() {
    var searchQuery = $('#searchInput').val();
    var selectedCategory = $('#stockCategory').val();

    $.ajax({
      url: 'search_products.php',
      type: 'post',
      data: {search: searchQuery, category: selectedCategory},
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
  xhr.onload = function() {
    if (this.status == 200) {
      var products = JSON.parse(this.responseText);
      var output = '';
      for(var i in products) {
        output += '<form method="post"><div class="card">' +
              '<img src="' + products[i].item_image + '" class="card-img-top" alt="Item Image">' +
              '<div class="card-body">' +
              '<h4 class="card-title">' + products[i].product_brand + '</h4>' +
              '<h5 class="card-title">' + products[i].product_name + '</h5>' +
              '<p class="card-text">Price: PHP ' + products[i].price + '</p>' +
              '<p class="card-text">Stocks: ' + products[i].stocks + '</p>' +
              '<div class="d-flex justify-content-between align-items-center">' +
              '<input type="hidden" name="id" value="' + products[i].product_id + '">' +
              '<a type="submit" class="btn btn-success" name="editButton" data-toggle="modal" data-target="#editProductModal">' +
              '<i class=\'bx bxs-edit\' style="font-size: 25px; vertical-align: middle;"></i></a>' +
              '<button type="submit" class="btn btn-danger" name="delete">' +
              '<input type="hidden" name="id" value="' + products[i].product_id + '">' +
              '<i class=\'bx bx-trash\' style="font-size: 25px; vertical-align: middle;"></i></button>' +
              '</div></div></div></form>';
      }
      document.querySelector('.card-container').innerHTML = output;
    }
  }
  xhr.send();
});
</script>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script src="script.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
</body>
</html>