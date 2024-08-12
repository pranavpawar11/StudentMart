$(document).ready(function () {
$('.js-addcart-detail').each(function () {
    var nameProduct = $(this).closest('.col-lg-5').find('.js-name-detail').html();
    var productId = $(this).data('product-id');

    $(this).on('click', function () {
        addToCart(productId, nameProduct);
    });
});

// Function to add a product to the cart
function addToCart(productId, nameProduct) {
    fetch('php/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId: productId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                swal(nameProduct, data.message, "success");
            } else {
                swal(nameProduct, data.message, "warning");
            }
        })
        .catch(error => console.error('Error:', error));
}
});
