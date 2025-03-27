function fetchOrders() {
    fetch('php/my_orders.php') // Update this to the correct path
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.orders)
                renderOrders(data.orders);
            } else {
                console.error('Error fetching orders:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function renderOrders(orders) {
    const ordersContainer = document.getElementById('ordersContainer');
    ordersContainer.innerHTML = ''; // Clear existing orders

    orders.forEach(order => {
        const orderCard = document.createElement('div');
        orderCard.classList.add('order-card');

        const orderImage = document.createElement('img');
        orderImage.src = order.img1; // Ensure this path is correct
        orderImage.alt = 'Product Image';
        orderImage.classList.add('order-image');

        const orderDetails = document.createElement('div');
        orderDetails.classList.add('order-details');

        const orderHeader = document.createElement('div');
        orderHeader.classList.add('order-header');

        const orderTitle = document.createElement('h3');
        orderTitle.textContent = order.product_name;

        const orderStatus = document.createElement('div');
        orderStatus.classList.add('order-status', getStatusClass(order.status));
        orderStatus.textContent = getStatusText(order.tracking_status);

        orderHeader.appendChild(orderTitle);
        orderHeader.appendChild(orderStatus);

        const orderInfo = document.createElement('div');
        orderInfo.classList.add('order-info');

        const orderDates = document.createElement('div');

        const orderDate = document.createElement('p');
        orderDate.innerHTML = `<strong>Order Date:</strong> ${formatDate(order.order_date)}`;

        const deliveryDate = document.createElement('p');
        deliveryDate.innerHTML = `<strong>${order.status === 'completed' ? 'Delivery Date:' : 'Expected Delivery:'}</strong> ${formatDate(order.complete_date)}`;

        const totalAmount = document.createElement('p');
        totalAmount.innerHTML = `<strong>Total Amount:</strong> ${order.total_price} Rs`;

        orderDates.appendChild(orderDate);
        orderDates.appendChild(deliveryDate);
        orderDates.appendChild(totalAmount);

        const orderActions = document.createElement('div');
        orderActions.classList.add('order-actions');

        // const viewButton = document.createElement('button');
        // viewButton.classList.add('btn', 'btn-details');
        // viewButton.textContent = 'View Order';
        // viewButton.onclick = () => viewOrder(order.product_id);

        const detailsButton = document.createElement('button');
        detailsButton.classList.add('btn', 'btn-details');
        detailsButton.textContent = 'View Details';
        detailsButton.onclick = () => viewOrder(order.product_id);;

        // orderActions.appendChild(viewButton);
        orderActions.appendChild(detailsButton);

        orderInfo.appendChild(orderDates);
        orderInfo.appendChild(orderActions);

        orderDetails.appendChild(orderHeader);
        orderDetails.appendChild(orderInfo);

        orderCard.appendChild(orderImage);
        orderCard.appendChild(orderDetails);

        ordersContainer.appendChild(orderCard);
    });
}

function getStatusClass(status) {
    // console.log(status);

    switch (status) {
        case 'completed': return 'status-completed';
        case 'pending': return 'status-processing';
        case 'approved': return 'status-processing';
        // Add more cases as needed
        default: return 'sas';
    }
}

function getStatusText(status) {
    switch (status) {
        case 'placed': return 'Placed';
        case 'shipped': return 'Shipped';
        case 'out_for_delivery' : return 'Out for Delivery';
        case 'delivered': return 'Delivered';
        // Add more cases as needed
        default: return status;
    }
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString(undefined, options);
}

function viewOrder(orderId) {
    window.open(`product-detail.php?id=${orderId}`,'_blank')
    // console.log('Viewing order:', orderId);
}

function showOrderDetails(orderId) {
    // Implement show order details functionality
    console.log('Showing details for order:', orderId);
}
