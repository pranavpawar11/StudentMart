function fetchCart() {
    fetch('php/fetch_cart.php')
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); });
            }
            return response.json();
        })
        .then(data => {
            const cartContainer = document.querySelector('.header-cart-wrapitem');  // Target the cart container
            const totalContainer = document.querySelector('.header-cart-total');

            if (data.length > 0) {
                let total = 0;

                // Create a document fragment to improve performance
                const fragment = document.createDocumentFragment();

                data.forEach(item => {
                    const { product_id, product_name, product_price, img1, date_added } = item;
                    total += parseFloat(product_price);

                    // Create cart item element
                    const cartItem = document.createElement('li');
                    cartItem.classList.add('header-cart-item', 'flex-w', 'flex-t', 'm-b-12');
                    cartItem.id = `cart-item-${product_id}`;

                    cartItem.innerHTML = `
                        <div class="header-cart-item-img">
                            <img src="${img1}" alt="${product_name}">
                        </div>
                        <div class="header-cart-item-details p-t-8">
                            <div class="header-cart-item-name m-b-5">
                                <a href="product-detail.php?id=${product_id}" class="hov-cl1 trans-04">${product_name}</a>
                            </div>
                            <div class="header-cart-item-info">
                                <span>Price: ₹${product_price}</span>
                            </div>
                        </div>
                        <div class="header-cart-item-actions">
                            <div class="cart-item-info">
                                <a href="product-detail.php?id=${product_id}" class="header-cart-item-info-btn">
                                    <i class="far fa-eye"></i> <!-- Icon for displaying product info -->
                                </a>
                            </div>
                            <div class="cart-item-delete">
                                <button class="header-cart-item-delete" onclick="removeFromCart(${product_id})">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `;

                    fragment.appendChild(cartItem);
                });

                cartContainer.innerHTML = '';
                cartContainer.appendChild(fragment);

                // Update total
                totalContainer.textContent = `Total: ₹${total.toFixed(2)}`;
            } else {
                // Display message if cart is empty
                cartContainer.innerHTML = '<li class="header-cart-item flex-w flex-t m-b-12">Your cart is empty.</li>';
                totalContainer.textContent = 'Total: ₹0.00';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.header-cart-wrapitem').innerHTML = `<li class="header-cart-item flex-w flex-t m-b-12">Error loading cart: ${error.message}</li>`;
            document.querySelector('.header-cart-total').innerHTML = '';
        });
}

function removeFromCart(productId) {
    fetch('php/remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId: productId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`cart-item-${productId}`).remove();
                // Optionally, update the total price after removal
                fetchCart(); // Update the cart after removal
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}
