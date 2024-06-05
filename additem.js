document.addEventListener("DOMContentLoaded", function () {
    const addToCartButtons = document.querySelectorAll('.add-cart-button button');
    const cartItem = document.getElementById('cartItem');
    const total = document.getElementById('total');
    const checkoutButton = document.getElementById('checkoutButton');
      

    let cart = []; // Array to store cart items

    // Attach event listeners to each "Add to Cart" button
    addToCartButtons.forEach(button => {
        button.addEventListener('click', handleAddToCart);
    });

    function updateCart() {
        // Clear cart content before adding new items
        cartItem.innerHTML = '';

        // Check if cart is empty
        if (cart.length === 0) {
            cartItem.textContent = 'Your cart is Empty';
            total.textContent = '₱ 0.00';
            checkoutButton.disabled = true; // Disable checkout button if cart is empty
            return;
        }

        checkoutButton.disabled = false; // Enable checkout button if cart has items

        let totalPrice = 0;
        for (const item of cart) {
            const productBox = document.createElement('div');
            productBox.classList.add('product-box');

            const image = document.createElement('img');
            image.classList.add('product-image');
            image.src = item.imageUrl; // Replace with your image source logic

            const details = document.createElement('div');
            details.classList.add('product-details');

            const brand = document.createElement('p');
            brand.classList.add('product-brand');
            brand.textContent = item.brand;


            const title = document.createElement('p');
            title.classList.add('product-title');
            title.textContent = item.title;

            const price = document.createElement('h2');
            price.classList.add('product-price');
            price.textContent = `₱${item.price}`;

            const quantityContainer = document.createElement('div');
            quantityContainer.classList.add('quantity-container');

            const quantityInput = document.createElement('input');
            quantityInput.type = 'text'; // Change input type to text
            quantityInput.value = item.quantity;
            quantityInput.dataset.itemId = item.product_id; // Change 'id' to 'product_id'

            const addButton = document.createElement('button');
            addButton.textContent = '+';
            addButton.classList.add('quantity-btn', 'add-button'); // Add 'add-button' class
            addButton.dataset.itemId = item.product_id; // Change 'id' to 'product_id'

            const minusButton = document.createElement('button');
            minusButton.textContent = '-';
            minusButton.classList.add('quantity-btn', 'minus-button'); // Add 'minus-button' class
            minusButton.dataset.itemId = item.product_id; // Change 'id' to 'product_id'

            quantityContainer.appendChild(minusButton);
            quantityContainer.appendChild(quantityInput);
            quantityContainer.appendChild(addButton);

            details.appendChild(brand);
            details.appendChild(title);
            details.appendChild(price);
            details.appendChild(quantityContainer);

            productBox.appendChild(image);
            productBox.appendChild(details);

            cartItem.appendChild(productBox);

            totalPrice += item.price * item.quantity;

            // Add event listeners for quantity buttons
            addButton.addEventListener('click', handleQuantityIncrement);
            minusButton.addEventListener('click', handleQuantityDecrement);
            quantityInput.addEventListener('change', handleQuantityChange);
        }

        total.textContent = `₱${totalPrice.toFixed(2)}`; // Format total price to two decimal places
    }

    function handleAddToCart(event) {
        const button = event.target;
        const productBox = button.closest('.product-boxs');
        const product_id = parseInt(button.dataset.id); // Get the actual product id
        const brand = productBox.querySelector('.product-brand').textContent;
        const title = productBox.querySelector('.product-title').textContent;
        const price = parseFloat(productBox.querySelector('.product-price').textContent.slice(1)); // Extract price value
        const imageUrl = productBox.querySelector('.product-image').src; // Extract image source
    
        // Add item to cart or update quantity if already exists
        const existingItem = cart.find(item => item.product_id === product_id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ product_id, brand, title, price, quantity: 1, imageUrl }); // Use 'product_id' instead of 'id'
        }
    
        // Save the cart items to local storage
        sessionStorage.setItem('cartItems', JSON.stringify(cart));
    
        // Add or update hidden input field
        let input = document.querySelector('.order-form input[name="product_' + product_id + '"]');
        if (!input) {
            input = document.createElement("input");
            input.type = "hidden";
            input.name = "product_" + product_id;
            document.querySelector(".order-form").appendChild(input);
        }
        input.value = existingItem ? existingItem.quantity : 1;
    
        updateCart();
    }
function handleQuantityIncrement(event) {
    const button = event.target;
    const itemId = parseInt(button.dataset.itemId); // Change 'id' to 'itemId'

    const itemIndex = cart.findIndex(item => item.product_id === itemId);

    if (itemIndex!== -1) {
        cart[itemIndex].quantity++;
        const input = document.querySelector(`input[data-item-id="${itemId}"]`);
        input.value = cart[itemIndex].quantity;
        const hiddenInput = document.querySelector(`input[name="product_${itemId}"]`);
        hiddenInput.value = cart[itemIndex].quantity; // Update hidden input field
        updateCart();
        sessionStorage.setItem('cartItems', JSON.stringify(cart)); // Save the cart items to local storage
    } else {
        console.error(`Item with id ${itemId} not found in the cart`);
    }
}

function handleQuantityDecrement(event) {
    const button = event.target;
    const itemId = parseInt(button.dataset.itemId); // Change 'id' to 'itemId'
    
    const itemIndex = cart.findIndex(item => item.product_id === itemId);

    if (cart[itemIndex].quantity > 1) {
        cart[itemIndex].quantity--;
        const input = document.querySelector(`input[data-item-id="${itemId}"]`);
        input.value = cart[itemIndex].quantity;
        const hiddenInput = document.querySelector(`input[name="product_${itemId}"]`);
        hiddenInput.value = cart[itemIndex].quantity; // Update hidden input field
    } else {
        cart.splice(itemIndex, 1);
    }
    
    updateCart();
    sessionStorage.setItem('cartItems', JSON.stringify(cart)); // Save the cart items to local storage
}

function handleQuantityChange(event) {
    const input = event.target;
    const itemId = parseInt(input.dataset.itemId); // Change 'id' to 'itemId'
    const newQuantity = parseInt(input.value);

    if (newQuantity > 0) {
        const itemIndex = cart.findIndex(item => item.product_id === itemId);
        cart[itemIndex].quantity = newQuantity;
        const hiddenInput = document.querySelector(`input[name="product_${itemId}"]`);
        hiddenInput.value = newQuantity; // Update hidden input field
    } else {
        handleRemoveItem({ target: { dataset: { itemId: itemId } } }); // Simulate remove button click
    }

    updateCart();
    sessionStorage.setItem('cartItems', JSON.stringify(cart)); // Save the cart items to local storage
}
function handleRemoveItem(event) {
    const button = event.target;
    const itemId = parseInt(button.dataset.itemId); // Change 'id' to 'itemId'

    const itemIndex = cart.findIndex(item => item.product_id === itemId);

    if (itemIndex !== -1) {
        cart.splice(itemIndex, 1);
    }

    updateCart();
}
});