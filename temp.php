<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="css/dashboard.css">

</head>

<body>
  <button class="menu-toggle d-md-none" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>
  <div class="d-flex">
    <div class="sidebar bg-dark">

      <div class="logo text-white py-4 px-3 d-flex align-items-center justify-content-center">
        <!-- <button onclick="history.back()" type="button" class="btn text-white" style="width: auto;">
          <i class="fa-solid fa-arrow-left"></i> Back
        </button> -->
        <i class="fas fa-shopping-cart mr-2"></i>
        <span>My Dashboard</span>
      </div>
      <nav class="nav flex-column">
        <a href="#" class="nav-link active text-white py-3 px-4" data-target="home" onclick="loadContent('home')"><i
            class="fas fa-home mr-2"></i> Home</a>
        <a href="#" class="nav-link text-white py-3 px-4 dropdown-toggle" data-toggle="collapse"
          data-target="#productMenu" aria-expanded="false"><i class="fas fa-box mr-2"></i> Products</a>
        <div id="productMenu" class="collapse">
          <a href="#" class="nav-link text-white py-3 px-5" data-target="add_product"
            onclick="loadContent('add_product')"><i class="fas fa-plus mr-2"></i> Add Book</a>
          <a href="#" class="nav-link text-white py-3 px-5" data-target="display_products"
            onclick="loadContent('display_products')"><i class="fas fa-list mr-2"></i> Display Books</a>
        </div>

        <a href="#" class="nav-link text-white py-3 px-4 dropdown-toggle" data-toggle="collapse" data-target="#pdfMenu"
          aria-expanded="false"><i class="fas fa-box mr-2"></i> PDFs</a>
        <div id="pdfMenu" class="collapse">
          <a href="#" class="nav-link text-white py-3 px-5" data-target="add_pdf" onclick="loadContent('add_pdf')"><i
              class="fas fa-plus mr-2"></i> Add PDF</a>
          <a href="#" class="nav-link text-white py-3 px-5" data-target="display_pdfs"
            onclick="loadContent('display_pdfs')"><i class="fas fa-list mr-2"></i> Display PDFs</a>
        </div>

        <a href="#" class="nav-link text-white py-3 px-4 dropdown-toggle" data-toggle="collapse"
          data-target="#requestMenu" aria-expanded="false"><i class="fas fa-clipboard-list mr-2"></i> Buyer Requests</a>
        <div id="requestMenu" class="collapse">
          <a href="#" class="nav-link text-white py-3 px-5" data-target="user_completed_requests"
            onclick="loadContent('user_completed_requests')"><i class="fas fa-check-double mr-2"></i>
            Completed</a>
          <a href="#" class="nav-link text-white py-3 px-5" data-target="user_pending_requests"
            onclick="loadContent('user_pending_requests')"><i class="fas fa-clock mr-2"></i> Pending</a>
        </div>

        <a href="#" class="nav-link text-white py-3 px-4" data-target="notifications"
          onclick="loadContent('notifications')"><i class="fas fa-bell mr-2"></i> Notifications</a>
        <a href="#" class="nav-link text-white py-3 px-4" data-target="settings" onclick="loadContent('settings')"><i
            class="fas fa-cog mr-2"></i> Settings</a>
        <!-- Other navigation links -->
      </nav>
    </div>

    <div class="main-content">
      <header class="bg-light  px-3 d-flex justify-content-between align-items-center">
        <div class="logo_mobile text-black py-4 px-3  align-items-center justify-content-center">
          <i class="fas fa-shopping-cart mr-2"></i>
          <span>My Dashboard</span>
        </div>

        <div class="user-info d-flex flex-row align-items-center ml-auto">
          <i class="fas fa-bell mr-3"></i>
          <div class="user-avatar">
            <img src="images/icons/user.jpg" alt="User Avatar" class="rounded-circle">
          </div>
        </div>
      </header>
      <div class="content-area">
        <!-- Content from dynamically loaded pages will appear here -->
      </div>
    </div>
  </div>

  <!-- Product Details Modal -->
  <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <!-- Modal content dynamically loaded via JavaScript -->
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="js/dashboard.js"></script>


  <script>

    function markAsRead(notificationId, button) {
      // Find the notification card with the matching data-id attribute
      var card = document.querySelector('.notification-card[data-id="' + notificationId + '"]');

      if (card) {
        fetch('includes/mark_notification_as_read.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `notification_id=${notificationId}`
        })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            console.log('Response:', data);
            if (data.status === 'success') {
              card.classList.remove('unread');
              card.classList.add('read');
              button.disabled = true;
              button.textContent = 'Read';
            } else {
              console.error('Server Error:', data.message);
              alert('Failed to mark notification as read. Please try again.');
            }
          })
          .catch(error => {
            console.error('Fetch Error:', error);
            alert('An error occurred. Please try again.');
          });
      } else {
        console.error('Notification card not found for ID:', notificationId);
      }
    }

  </script>

  <script>
    function notifyProduct(requestId) {
      fetch('includes/notify_product.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `request_id=${requestId}`
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          console.log('Response:', data);
          if (data.status === 'success') {
            alert('Notification status updated successfully.');
          } else {
            alert('The notification has already been sent.');
          }
        })
        .catch(error => {
          console.error('Fetch Error:', error);
          alert('An error occurred. Please try again.');
        });
    }
  </script>

  <script>
    function openPdfModal(filePath) {
      var viewer = document.getElementById('pdfViewer');
      viewer.src = filePath; // Set the PDF source
      $('#pdfModal').modal('show'); // Show the modal
    }

    // Clear the PDF source when the modal is closed
    $('#pdfModal').on('hidden.bs.modal', function () {
      $(this).find('#pdfViewer').attr('src', ''); // Clear the PDF source
    });
  </script>

  <script>
    function approveRequest(orderId) {
      if (confirm('Are you sure you want to approve this order?')) {
        fetch('includes/approve_order.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ order_id: orderId })
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Order approved successfully');
              location.reload();
            } else {
              alert('Failed to approve order');
            }
          })
          .catch(error => console.error('Error:', error));
      }
    }

    function completeOrder(orderId) {
      if (confirm('Are you sure you want to mark this order as complete?')) {
        fetch('includes/complete_order.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ order_id: orderId })
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Order marked as complete');
              location.reload();
            } else {
              alert('Failed to mark order as complete');
            }
          })
          .catch(error => console.error('Error:', error));
      }
    }
  </script>


  <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
</body>

</html>