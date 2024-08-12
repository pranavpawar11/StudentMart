//    function selectToBuy(productId, productName, productPrice, productImg, productDescription, sellerId) {
//         document.getElementById('selected-product-name').innerText = productName;
//         document.getElementById('selected-product-price').innerText = productPrice.toFixed(2) + ' ₹';
//         document.getElementById('selected-product-img').src = productImg;
//         document.getElementById('selected-product-total').innerText = (productPrice - 20).toFixed(2) + ' ₹';
//         document.getElementById('selected-product-description').innerText = productDescription;
//         document.getElementById('selected-product-id').value = productId; // Update hidden field
//         document.getElementById('address-block').style.display = 'block';
//     }

    

//     function sendBuyRequest(productId, totalPrice) {
//         const addressField = document.getElementById('address');
//         const messageField = document.getElementById('message');
        
//         // Validate address and message fields
//         if (!addressField || !messageField || !addressField.value.trim() || !messageField.value.trim()) {
//             swal("Error", "Please enter both address and message", "error");
//             return;
//         }

//         const address = addressField.value.trim();
//         const message = messageField.value.trim();

//         fetch('php/add_request.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded'
//             },
//             body: `product_id=${productId}&address=${encodeURIComponent(address)}&total_price=${totalPrice}&message=${encodeURIComponent(message)}`
//         })
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Network response was not ok');
//             }
//             return response.json();
//         })
//         .then(data => {
//             if (data.success) {
//                 swal("Order Placed", data.message, "success");
//             } else {
//                 swal("Failed", data.message, "error");
//             }
//         })
//         .catch(error => {
//             swal("Error", "An error occurred. Please try again.", "error");
//         });
//     }
