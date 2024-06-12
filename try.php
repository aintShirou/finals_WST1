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