<?php
include("php/conn.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['user_id'])) {
  header("Location:./login.php");
  exit;
}
?>
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
  <style>
    /* Role-based visibility */
    .admin-only {
      display:
        <?php echo ($_SESSION['user_role'] === 'admin') ? 'block' : 'none'; ?>
      ;
    }

    .user-only {
      display:
        <?php echo ($_SESSION['user_role'] === 'user') ? 'block' : 'none'; ?>
      ;
    }
  </style>
</head>

<body>
  <button class="menu-toggle d-md-none" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>
  <div class="d-flex">
    <div class="sidebar bg-dark">
      <div class="logo text-white py-4 px-3 d-flex align-items-center justify-content-center">
        <i class="fas fa-shopping-cart mr-2"></i>
        <span>My Dashboard</span>
      </div>
      <nav class="nav flex-column">
        <a href="#" class="nav-link active text-white py-3 px-4" data-target="home" onclick="loadContent('home')">
          <i class="fas fa-home mr-2"></i> Home
        </a>

        <a href="#" class="nav-link text-white py-3 px-4 dropdown-toggle" data-toggle="collapse"
          data-target="#productMenu" aria-expanded="false">
          <i class="fas fa-box mr-2"></i> Products
        </a>
        <div id="productMenu" class="collapse">
          <a href="#" class="nav-link text-white py-3 px-5" data-target="add_product"
            onclick="loadContent('add_product')">
            <i class="fas fa-plus mr-2"></i> Add Book
          </a>
          <a href="#" class="nav-link text-white py-3 px-5" data-target="display_products"
            onclick="loadContent('display_products')">
            <i class="fas fa-list mr-2"></i> Display Books
          </a>
        </div>

        <!-- Admin Only Sections -->
        <div class="admin-only">
          <!-- Products Menu -->


          <!-- PDFs Menu -->
          <a href="#" class="nav-link text-white py-3 px-4 dropdown-toggle" data-toggle="collapse"
            data-target="#pdfMenu" aria-expanded="false">
            <i class="fas fa-file-pdf mr-2"></i> PDFs
          </a>
          <div id="pdfMenu" class="collapse">
            <a href="#" class="nav-link text-white py-3 px-5" data-target="add_pdf" onclick="loadContent('add_pdf')">
              <i class="fas fa-plus mr-2"></i> Add PDF
            </a>
            <a href="#" class="nav-link text-white py-3 px-5" data-target="display_pdfs"
              onclick="loadContent('display_pdfs')">
              <i class="fas fa-list mr-2"></i> Display PDFs
            </a>
          </div>

          <!-- Rent management -->
          <a href="#" class="nav-link text-white py-3 px-4 dropdown-toggle" data-toggle="collapse"
            data-target="#rentalManagementMenu" aria-expanded="false">
            <i class="fas fa-sync-alt mr-2"></i> Rental Management
          </a>
          <div id="rentalManagementMenu" class="collapse">
            <a href="#" class="nav-link text-white py-3 px-5" data-target="admin-rentals-requested"
              onclick="loadContent('admin-rentals-requested')">
              <i class="fas fa-clock mr-2"></i> Requested Rentals
            </a>
            <a href="#" class="nav-link text-white py-3 px-5" data-target="admin-rentals-approved"
              onclick="loadContent('admin-rentals-approved')">
              <i class="fas fa-check-circle mr-2"></i> Approved Rentals
            </a>
            <a href="#" class="nav-link text-white py-3 px-5" data-target="admin-rentals-in-transit"
              onclick="loadContent('admin-rentals-in-transit')">
              <i class="fas fa-truck mr-2"></i> In Transit
            </a>
            <a href="#" class="nav-link text-white py-3 px-5" data-target="admin-rentals-delivered"
              onclick="loadContent('admin-rentals-delivered')">
              <i class="fas fa-box-open mr-2"></i> Delivered
            </a>
            <a href="#" class="nav-link text-white py-3 px-5" data-target="admin-rentals-return-initiated"
              onclick="loadContent('admin-rentals-return-initiated')">
              <i class="fas fa-undo mr-2"></i> Return Initiated
            </a>
            <a href="#" class="nav-link text-white py-3 px-5" data-target="admin-rentals-completed"
              onclick="loadContent('admin-rentals-completed')">
              <i class="fas fa-check-double mr-2"></i> Completed
            </a>
          </div>

          <!-- Deposit Tracking -->
          <a href="#" class="nav-link text-white py-3 px-4" data-target="admin-deposit-tracking"
            onclick="loadContent('admin-deposit-tracking')">
            <i class="fas fa-wallet mr-2"></i> Deposit Tracking
          </a>

        </div>
        <!-- Buyer Requests -->
        <a href="#" class="nav-link text-white py-3 px-4 dropdown-toggle" data-toggle="collapse"
          data-target="#buyerRequestMenu" aria-expanded="false">
          <i class="fas fa-clipboard-list mr-2"></i> Buyer Requests
        </a>
        <div id="buyerRequestMenu" class="collapse">
          <a href="#" class="nav-link text-white py-3 px-5" data-target="user_completed_requests"
            onclick="loadContent('user_completed_requests')">
            <i class="fas fa-check-double mr-2"></i> Completed
          </a>
          <a href="#" class="nav-link text-white py-3 px-5" data-target="user_approved_requests"
            onclick="loadContent('user_approved_requests')">
            <i class="fas fa-check mr-2"></i> Approved
          </a>
          <a href="#" class="nav-link text-white py-3 px-5" data-target="user_pending_requests"
            onclick="loadContent('user_pending_requests')">
            <i class="fas fa-clock mr-2"></i> Pending
          </a>
          <!-- <a href="#" class="nav-link text-white py-3 px-5" data-target="declined_requests"
            onclick="loadContent('user_declined_requests')">
            <i class="fas fa-times mr-2"></i> Declined
          </a> -->
        </div>
        <!-- User Only Sections -->
        <div class="user-only">
          <!-- My Requests -->
          <a href="#" class="nav-link text-white py-3 px-4 dropdown-toggle" data-toggle="collapse"
            data-target="#myRequestMenu" aria-expanded="false">
            <i class="fas fa-clipboard-list mr-2"></i> My Requests
          </a>
          <div id="myRequestMenu" class="collapse">
            <a href="#" class="nav-link text-white py-3 px-5" data-target="my_completed_requests"
              onclick="loadContent('my_completed_requests')">
              <i class="fas fa-check-circle mr-2"></i> Completed
            </a>
            <a href="#" class="nav-link text-white py-3 px-5" data-target="my_approved_requests"
              onclick="loadContent('my_approved_requests')">
              <i class="fas fa-thumbs-up mr-2"></i> Approved
            </a>
            <a href="#" class="nav-link text-white py-3 px-5" data-target="my_pending_requests"
              onclick="loadContent('my_pending_requests')">
              <i class="fas fa-hourglass-half mr-2"></i> Pending
            </a>
            <a href="#" class="nav-link text-white py-3 px-5" data-target="my_declined_requests"
              onclick="loadContent('my_declined_requests')">
              <i class="fas fa-times-circle mr-2"></i> Declined
            </a>
          </div>
        </div>

        <!-- Common Sections -->
        <a href="#" class="nav-link text-white py-3 px-4" data-target="notifications"
          onclick="loadContent('notifications')">
          <i class="fas fa-bell mr-2"></i> Notifications
        </a>
        <a href="#" class="nav-link text-white py-3 px-4" data-target="settings" onclick="loadContent('settings')">
          <i class="fas fa-cog mr-2"></i> Settings
        </a>
      </nav>
    </div>

    <div class="main-content">
      <header class="bg-light px-3 d-flex justify-content-between align-items-center">
        <div class="logo_mobile text-black py-4 px-3 align-items-center justify-content-center">
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

  <!-- PDF Viewer Modal -->
  <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">PDF Viewer</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <iframe id="pdfViewer" style="width:100%; height:80vh;" frameborder="0"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="js/dashboard.js"></script>
</body>

</html>