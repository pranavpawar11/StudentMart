function fetchOrders() {
    fetch('php/fetch_orders.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayOrders(data.orders);
            } else {
                console.error('Failed to fetch orders:', data.message);
            }
        })
        .catch(error => console.error('Error fetching orders:', error));
}

function displayOrders(orders) {
    const ordersList = document.querySelector('.orders-list');
    ordersList.innerHTML = '';

    orders.forEach(order => {
        const card = document.createElement('div');
        card.classList.add('order-card');

        // Product image
        const image = document.createElement('img');
        image.src = 'path_to_your_images/' + order.img1; // Example: 'images/' + order.img1;
        image.alt = 'Product Image';
        image.classList.add('order-image');
        card.appendChild(image);

        // Product details
        const details = document.createElement('div');
        details.classList.add('order-details');

        // Product name (could be a link to product details page)
        const productName = document.createElement('div');
        productName.classList.add('product-name');
        productName.textContent = order.product_name;
        details.appendChild(productName);

        // Product price
        const productPrice = document.createElement('div');
        productPrice.classList.add('product-price');
        productPrice.textContent = 'Price: $' + order.product_price;
        details.appendChild(productPrice);

        // Product description (shortened for display)
        const productDesc = document.createElement('div');
        productDesc.classList.add('product-description');
        productDesc.textContent = order.product_description.substring(0, 100) + '...'; // Display first 100 characters
        details.appendChild(productDesc);

        // View details button (opens modal with full product info)
        const viewDetailsBtn = document.createElement('button');
        viewDetailsBtn.textContent = 'View Details';
        viewDetailsBtn.classList.add('view-details-btn');
        viewDetailsBtn.addEventListener('click', function () {
            openProductModal(order);
        });
        details.appendChild(viewDetailsBtn);

        card.appendChild(details);
        ordersList.appendChild(card);
    });
}

// Function to open modal with full product information
function openProductModal(order) {
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('modal-content');

    // Construct the modal content (use details from 'order' object and fetch additional details if needed)
    modalContent.innerHTML = `
<h2>${order.product_name}</h2>
<p><strong>Price:</strong> $${order.product_price}</p>
<p><strong>Description:</strong> ${order.product_description}</p>
<p><strong>Seller:</strong> ${order.seller_id}</p>
<p><strong>Category:</strong> ${order.category}</p>
<!-- Add more details as needed -->
`;

    modal.style.display = 'block';

    // Close modal when clicking on close button (X)
    const closeBtn = document.getElementsByClassName('close')[0];
    closeBtn.onclick = function () {
        modal.style.display = 'none';
    }

    // Close modal when clicking outside of it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
}
