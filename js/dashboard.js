// Function to toggle sidebar visibility
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
}

// Function to handle collapse toggling for main menu items
function handleMainMenuToggle(target) {
    let collapses = document.querySelectorAll('.collapse');

    collapses.forEach(collapse => {
        if (collapse.id === target) {
            collapse.classList.toggle('show');
        } else {
            collapse.classList.remove('show');
        }
    });
}

// Add event listeners to main menu items to handle collapse toggling
document.querySelectorAll('.nav-link.dropdown-toggle').forEach(link => {
    link.addEventListener('click', function (event) {
        let target = link.getAttribute('data-target');
        handleMainMenuToggle(target);
    });
});

// Add event listener to sub menu items to prevent collapse
document.querySelectorAll('.collapse .nav-link').forEach(link => {
    link.addEventListener('click', function (event) {
        event.stopPropagation(); // Prevent collapse from hiding
    });
});

function loadContent(target) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'includes/' + target + '.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.querySelector('.content-area').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

document.addEventListener('DOMContentLoaded', function () {
    // Initial load
    loadContent('home');

    // Attach event listeners to navigation links
    var navLinks = document.querySelectorAll('.nav-link[data-target]');
    navLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            var target = link.getAttribute('data-target');
            if (target) {
                loadContent(target);
                // Update active class
                navLinks.forEach(function (l) { l.classList.remove('active'); });
                link.classList.add('active');
            }
        });
    });
});

function updateProductStatus(productId, newStatus) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Update button text and style based on new status
                        var button = document.getElementById('button_' + productId);
                        if (newStatus === 'available') {
                            button.textContent = 'Make Unavailable';
                            button.classList.remove('btn-danger');
                            button.classList.add('btn-success');
                        } else {
                            button.textContent = 'Make Available';
                            button.classList.remove('btn-success');
                            button.classList.add('btn-danger');
                        }
                        // Update onclick handler to toggle status
                        button.onclick = function () {
                            updateProductStatus(productId, (newStatus === 'available') ? 'sold' : 'available');
                        };
                    } else {
                        console.error('Failed to update product status:', response.message);
                    }
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                }
            } else {
                console.error('Failed to update product status. Error status:', xhr.status);
            }
        }
    };
    xhr.open('GET', 'includes/update_product_status.php?action=' + encodeURIComponent(newStatus) + '&id=' + encodeURIComponent(productId), true);
    xhr.send();
}


document.addEventListener('DOMContentLoaded', function () {
    // Event listener for approve button
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('approve-request')) {
            var requestId = event.target.getAttribute('data-request-id');
            if (confirm('Are you sure you want to approve this request?')) {
                fetch('includes/approve_request.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ requestId: requestId })
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            alert('Request approved successfully');
                            window.location.reload();
                        } else {
                            alert('Failed to approve request: ' + response.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to approve request');
                    });
            }
        }
    });

    // Event listener for decline button
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('decline-request')) {
            var requestId = event.target.getAttribute('data-request-id');
            if (confirm('Are you sure you want to decline this request?')) {
                fetch('includes/decline_request.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ requestId: requestId })
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            alert('Request declined successfully');
                            window.location.reload();
                        } else {
                            alert('Failed to decline request: ' + response.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to decline request');
                    });
            }
        }
    });
});


//  approved 
$(document).ready(function () {
    // Event listener for chat with buyer button
    $(document).on('click', '.chat-with-buyer', function () {
        var buyerPhone = $(this).data('buyer-phone');
        var whatsappLink = 'https://api.whatsapp.com/send?phone=' + buyerPhone + '&text=Hello%20from%20Seller'; // Customize the message as needed
        window.open(whatsappLink, '_blank');
    });

    // Event listener for complete request button
    $(document).on('click', '.complete-request', function () {
        var requestId = $(this).data('request-id');
        $.ajax({
            url: 'includes/complete_request.php',
            method: 'POST',
            data: { requestId: requestId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Optional: Update UI or show success message
                    alert('Request completed successfully');
                    // Reload page or update content as needed
                    // window.location.reload();
                } else {
                    alert('Failed to complete request: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('Failed to complete request');
            }
        });
    });
});



//  decline request re approve 

function approveRequest(requestId) {
    // console.log(Approve button clicked for request ID: ${requestId}); // Debug statement

    fetch('includes/approve_declined.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'requestId': requestId
        })
    })
        .then(response => response.json())
        .then(data => {
            console.log('Response from server:', data); // Debug statement
            if (data.success) {
                alert('Request approved successfully');
                location.reload(); // Reload the page on success
            } else {
                alert('Failed to approve request: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error during fetch request:', error); // Debug statement
            alert('Failed to process request. Please try again later.');
        });
}