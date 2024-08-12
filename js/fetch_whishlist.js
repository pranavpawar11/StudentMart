document.addEventListener('DOMContentLoaded', function() {
    fetchWishlist();
});

function fetchWishlist() {
    fetch('php/fetch_wishlist.php')
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); });
            }
            return response.json();
        })
        .then(data => {
            if (data.length > 0) {
                let wishlistContent = '';
                let total = 0;
                data.forEach(item => {
                    const { product_id, product_name, product_price, img1, date_added } = item;
                    total += parseFloat(product_price);
                    wishlistContent += `
                        <li class="header-cart-item flex-w flex-t m-b-12">
                            <div class="header-cart-item-img">
                                <img src="${img1}" alt="IMG">
                            </div>
                            <div class="header-cart-item-txt p-t-8">
                                <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                    ${product_name}
                                </a>
                                <span class="header-cart-item-info">
                                    1 x ₹${product_price}
                                </span>
                            </div>
                        </li>
                    `;
                });
                
                document.querySelector('.header-cart-wrapitem').innerHTML = wishlistContent;
                document.querySelector('.header-cart-total').innerHTML = `Total: ₹${total.toFixed(2)}`;
            } else {
                document.querySelector('.header-cart-wrapitem').innerHTML = '<li class="header-cart-item flex-w flex-t m-b-12">Your wishlist is empty.</li>';
                document.querySelector('.header-cart-total').innerHTML = 'Total: ₹0.00';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.header-cart-wrapitem').innerHTML = `<li class="header-cart-item flex-w flex-t m-b-12">Error loading wishlist: ${error.message}</li>`;
            document.querySelector('.header-cart-total').innerHTML = '';
        });
}