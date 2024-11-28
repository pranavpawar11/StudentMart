function toggleLike(productId) {
    console.log("Toggle button clicked");
    const likeIcon = document.getElementById(`like-icon-${productId}`);
    const isLiked = likeIcon.src.includes('icon-heart-02.png');

    if (isLiked) {
        unlikeProduct(productId);
    } else {
        likeProduct(productId);
    }
}

function likeProduct(productId) {
    console.log("Post liked");
    fetch('php/like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId: productId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`like-icon-${productId}`).src = 'images/icons/icon-heart-02.png'; // Liked icon
                Swal.fire("Success", "Product added to wishlist!", "success");
            } else {
                console.error('Error:', data.message);
                Swal.fire("Error", data.message, "error");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire("Error", "An error occurred while adding to wishlist.", "error");
        });
}

function unlikeProduct(productId) {
    console.log("Post unliked");
    fetch('php/unlike.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId: productId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`like-icon-${productId}`).src = 'images/icons/icon-heart-01.png'; // Unliked icon
                Swal.fire("Success", "Product removed from wishlist!", "success");
            } else {
                console.error('Error:', data.message);
                Swal.fire("Error", data.message, "error");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire("Error", "An error occurred while removing from wishlist.", "error");
        });
}

function fetchWishlist() {
    fetch('php/fetch_wishlist.php')
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); });
            }
            return response.json();
        })
        .then(data => {
            const wishlistContainer = document.querySelector('.header-cart-wrapitem');
            const totalContainer = document.querySelector('.header-cart-total');

            if (data.length > 0) {
                let total = 0;

                // Create a document fragment to improve performance
                const fragment = document.createDocumentFragment();

                data.forEach(item => {
                    const { product_id, product_name, product_price, img1, date_added } = item;
                    total += parseFloat(product_price);

                    // Create wishlist item element
                    const wishlistItem = document.createElement('li');
                    wishlistItem.classList.add('header-cart-item', 'flex-w', 'flex-t', 'm-b-12');
                    wishlistItem.id = `wishlist-item-${product_id}`;

                    wishlistItem.innerHTML = `
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
                            <div class="wishlist-item-info">
                                <a href="product-detail.php?id=${product_id}" class="header-cart-item-info-btn">
                                    <i class="far fa-eye"></i> <!-- Icon for displaying product info -->
                                </a>
                            </div>
                            <div class="wishlist-item-delete">
                                <button class="header-cart-item-delete" onclick="removeFromWishlist(${product_id})">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `;

                    fragment.appendChild(wishlistItem);
                });

                wishlistContainer.innerHTML = `
                <div class="wishlist-pdf-header" style="font-size: 1.5em; color: #333; font-weight: bold; margin-bottom: 15px;">
                    Products
                </div>
            `;
                wishlistContainer.appendChild(fragment);

                // Update total
                // totalContainer.textContent = `Total: ₹${total.toFixed(2)}`;
            } else {
                // Display message if wishlist is empty
                wishlistContainer.innerHTML = '<li class="header-cart-item flex-w flex-t m-b-12">Your wishlist is empty.</li>';
                // totalContainer.textContent = 'Total: ₹0.00';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.header-cart-wrapitem').innerHTML = `<li class="header-cart-item flex-w flex-t m-b-12">Error loading wishlist: ${error.message}</li>`;
            // document.querySelector('.header-cart-total').innerHTML = '';
        });
}




function removeFromWishlist(productId) {
    fetch('php/unlike.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId: productId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`wishlist-item-${productId}`).remove();
                // Optionally, update the total price after removal
                fetchWishlist(); // Update the wishlist after removal
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

