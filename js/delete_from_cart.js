function removeFromCart(productId) {
    if (confirm('Are you sure you want to remove this product from the cart?')) {
        fetch('php/remove_from_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Item removed");
               
                // Remove the product row from the table
                const productRow = document.getElementById(`product-${productId}`);
                if (productRow) {
                    const productName = productRow.querySelector('.column-2').innerText;
                    productRow.remove();

                    // Display SweetAlert notification
                    swal(productName, "is removed from cart!", "success");
                }

                // Check if the cart is empty
                if (document.querySelectorAll('.table_row').length === 0) {
                    document.querySelector('.table-shopping-cart').innerHTML = `
                        <tr class="table_head">
                            <th class="column-1">Product</th>
                            <th class="column-2">Name</th>
                            <th class="column-3">Price</th>
                            <th class="column-4">Discount</th>
                            <th class="column-5">Total</th>
                            <th class="column-6">Actions</th>
                        </tr>
                        <tr class="table_row">
                            <td colspan="6" class="column-1">Cart is Empty</td>
                        </tr>`;
                    document.getElementById('address-block').style.display = 'none';
                    document.querySelector('.flex-w.flex-sb-m').style.display = 'none';
                }
            } else {
                alert('Failed to remove product from cart.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
