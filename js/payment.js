// Function to fetch subscriptions dynamically
function fetchSubscriptions() {
    fetch('php/get_subscriptions.php')
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let subscriptionList = document.getElementById('subscription-list');
                subscriptionList.innerHTML = ''; // Clear existing content

                // Loop through each subscription and display it
                data.forEach(subscription => {
                    let subscriptionItem = `
                        <div class="subscription-card">
                            <h4>${subscription.name}</h4>
                            <p>Price: ₹${subscription.price}</p>
                            <p>Duration: ${subscription.duration} Days</p>
                            <button class="buy-now-btn" id="buy-now-btn" data-subscription-id="${subscription.id}" onclick="buySubscription(event)">Buy Now</button>
                        </div>
                    `;
                    subscriptionList.innerHTML += subscriptionItem;
                });
            } else {
                alert('No subscriptions available.');
            }
        })
        .catch(error => {
            console.error('Error fetching subscriptions:', error);
        });
}

// Function to initiate Razorpay payment
function buySubscription(event) {
    const subscriptionId = event.target.getAttribute('data-subscription-id');

    // Fetch subscription details from the displayed subscriptions
    const subscription = subscriptions.find(sub => sub.id === parseInt(subscriptionId));

    // Send the subscription details to the backend to create the Razorpay order
    fetch('php/payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `subscription_id=${subscription.id}&amount=${subscription.price}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // If the order is created successfully, open Razorpay checkout
            const options = {
                "key": "rzp_test_umea8flScA3xwG", // Replace with your Razorpay key
                "amount": subscription.price * 100, // Convert to paise
                "currency": "INR",
                "name": "Subscription Purchase",
                "description": "Buy subscription plan",
                "image": "https://your-logo-url.com/logo.png", // Add your logo URL here
                "order_id": data.order_id, // Use the Razorpay order ID received from the backend
                "handler": function (response) {
                    // Handle the response after payment
                    alert("Payment successful! Payment ID: " + response.razorpay_payment_id);
                    // Send this payment_id and subscription_id to your server for processing
                    updateSubscriptionStatus(subscription.id, response.razorpay_payment_id);
                },
                "prefill": {
                    "name": "John Doe",
                    "email": "john@example.com",
                    "contact": "9999999999"
                },
                "notes": {
                    "subscription_id": subscription.id
                },
                "theme": {
                    "color": "#F37254"
                }
            };

            const razorpay = new Razorpay(options);
            razorpay.open();
        } else {
            alert('Error creating Razorpay order: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

// Call fetchSubscriptions on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchSubscriptions();
});
