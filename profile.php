<?php
// Include the database connection file
include 'php/conn.php';

try {
    session_start();
    // Query to fetch user data
    $sql = "SELECT * FROM user WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $user_id = $_SESSION['user_id']; // Replace with the actual user ID
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Fetch user data as an associative array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user["photo"] == null) {
        // $user["photo"] = "images\avatar-01.jpg";
        $user["photo"] = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
    }

    // Display user data in the profile section
    // echo '<h2>Profile</h2>';
    // echo '<p>First Name: ' . $user['fname'] . '</p>';
    // echo '<p>Last Name: ' . $user['lname'] . '</p>';
    // echo '<p>Email: ' . $user['email'] . '</p>';
    // echo '<p>Phone: ' . $user['phone'] . '</p>';
    // echo '<p>Password: ' . $user['password'] . '</p>';
    // echo '<img src="' . $user['photo'] . '" alt="Profile Photo">';
} catch (PDOException $e) {
    // Handle exceptions
    die("Error: " . $e->getMessage());
}
?>



<!--Website: wwww.codingdung.com-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>
    <div id="alert-container" class="alert-container"></div>
    <div class="container light-style flex-grow-1 container-p-y" style="margin-top: 20px;">
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <button onclick="history.back()" type="button" class="btn btn-primary" style="width: auto;">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </button>
                    <h4 class="font-weight-bold py-3 mb-4 text-center">Account Settings</h4>
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change Password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Info</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-notifications">Notifications</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="account-general">

                            <form id="general-form" method="post" action="php/update_user.php"
                                enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="media align-items-center">
                                        <img id="preview-image" src="<?php echo $user['photo']; ?>" alt="User Photo"
                                            class="d-block ui-w-80">
                                        <div class="media-body ml-4">
                                            <label class="btn btn-outline-primary">
                                                Upload New Photo
                                                <input type="file" id="upload-photo" name="photo"
                                                    onchange="previewImage(event)" class="account-settings-fileinput">
                                            </label>
                                            <button type="button" class="btn btn-default md-btn-flat ml-2"
                                                onclick="resetPhoto()">Reset</button>
                                            <div class="text-muted small mt-1">Allowed formats: JPG, GIF, PNG. Max size:
                                                800KB</div>
                                        </div>
                                    </div>
                                    <hr class="border-light">
                                    <div class="form-group">
                                        <label class="form-label">User ID</label>
                                        <input type="text" name="user_id" class="form-control mb-2"
                                            value="<?php echo htmlspecialchars($user['user_id']); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" id="first-name" class="form-control"
                                            value="<?php echo htmlspecialchars($user['fname']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" id="last-name" class="form-control"
                                            value="<?php echo htmlspecialchars($user['lname']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="<?php echo htmlspecialchars($user['email']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" id="phone" class="form-control"
                                            value="<?php echo htmlspecialchars($user['phone']); ?>">
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="button" class="btn btn-primary"
                                            onclick="saveGeneralChanges()">Save General Changes</button>
                                    </div>
                                </div>
                            </form>



                        </div>

                        <!-- Change Password Tab -->
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <img id="preview-image" src="<?php echo $user['photo']; ?>" alt="User Photo"
                                        class="d-block ui-w-80">
                                    <!-- <div class="media-body ml-4">
                                        <label class="btn btn-outline-primary">
                                            Upload New Photo
                                            <input type="file" id="upload-photo" class="account-settings-fileinput"
                                                onchange="previewImage(event)">
                                        </label>
                                        <button type="button" class="btn btn-default md-btn-flat ml-2"
                                            onclick="resetPhoto()">Reset</button>
                                        <div class="text-muted small mt-1">Allowed formats: JPG, GIF, PNG. Max size:
                                            800KB</div>
                                    </div> -->
                                </div>
                                <hr class="border-light">
                                <div class="form-group">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" id="curr_pass" placeholder="Enter Current Password"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" id="new_pass" class="form-control"
                                        placeholder="Enter new Password">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" id="confirm_new_pass" class="form-control"
                                        placeholder="Re-Enter New Password">
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary" onclick="savePasswordChanges()">Save
                                        Password Changes</button>
                                </div>
                            </div>
                        </div>

                        <!-- Info Tab -->
                        <div class="tab-pane fade" id="account-info">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <img id="preview-image" src="<?php echo $user['photo']; ?>" alt="User Photo"
                                        class="d-block ui-w-80">
                                    <!-- <div class="media-body ml-4">
                                        <h5 class="mb-0">Username:
                                            <?php echo htmlspecialchars($user['fname'] . " " . $user['lname']); ?>
                                        </h5>
                                    </div> -->
                                </div>
                                <hr class="border-light">
                                <div class="form-group">
                                    <label class="form-label">Country</label>
                                    <select class="custom-select">
                                        <option selected>India</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control"
                                        value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control"
                                        value="<?php echo htmlspecialchars($user['email']); ?>" readonly> 
                                </div>
                                <!-- <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary" onclick="saveInfoChanges()">Save Info
                                        Changes</button>
                                </div> -->
                            </div>
                        </div>

                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="account-notifications">
                            <div class="card-body">
                                <div class="media align-items-center">
                                <img id="preview-image" src="<?php echo $user['photo']; ?>" alt="User Photo"
                                class="d-block ui-w-80">
                                    <!-- <div class="media-body ml-4">
                                        <h5 class="mb-0">Username:
                                            <?php echo htmlspecialchars($user['fname'] . " " . $user['lname']); ?>
                                        </h5>
                                    </div> -->
                                </div>
                                <hr class="border-light">
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone requests for a product</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone confirms an order</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input">
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when an order is confirmed</span>
                                    </label>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary"
                                        onclick="saveNotificationsChanges()">Save Notifications Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

    </script>


    <!-- add image -->
    <script>
        function previewImage(event) {
            var input = event.target;
            if (input.files && input.files[0]) {
                var imageFile = input.files[0];
                var imageUrl = URL.createObjectURL(imageFile);
                document.getElementById('preview-image').src = imageUrl;
            }
        }

        function saveGeneralChanges() {
            var formData = new FormData(document.getElementById('general-form'));

            fetch('php/update_user.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    showAlert('User information updated successfully.', 'success');
                })
                .catch(error => {
                    showAlert('Error updating user information.', 'danger');
                    console.error('Error:', error);
                });
        }




        // Function to display Bootstrap alert
        function showAlert(message, type) {
            var alertContainer = document.getElementById('alert-container');
            if (alertContainer) {
                var alertDiv = document.createElement('div');
                alertDiv.classList.add('alert', 'alert-' + type, 'alert-dismissible', 'fade', 'show');
                alertDiv.innerHTML = `
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                ${message}
            `;
                alertContainer.appendChild(alertDiv);

                // Automatically close the alert after 5 seconds
                setTimeout(function () {
                    alertDiv.classList.remove('show');
                    setTimeout(function () {
                        alertDiv.remove();
                    }, 300); // remove after fading out
                }, 5000); // 5 seconds
            }
        }

        function savePasswordChanges() {
            var userId = '<?php echo $user['user_id']; ?>';
            var curr_pass = document.getElementById('curr_pass').value;
            var new_pass = document.getElementById('new_pass').value;
            var confirm_new_pass = document.getElementById('confirm_new_pass').value;

            var formData = new FormData();
            formData.append('user_id', userId);
            formData.append('current_password', curr_pass);
            formData.append('new_password', new_pass);

            if (curr_pass == "") {
                showAlert('Enter Old Password', 'danger');
            }
            if (curr_pass == new_pass) {
                showAlert('Old Password and New are Same.', 'danger');
            }
            if (new_pass == confirm_new_pass && new_pass != "") {
                fetch('php/change_password.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(data => {
                        // Success message
                        showAlert('Password changed successfully.', 'success');
                    })
                    .catch(error => {
                        // Error message
                        showAlert('Error in updating Password.', 'danger');
                        console.error('Error:', error);
                    });
            } else {
                showAlert('New Password dose not Match.', 'danger');
            }
        }
    </script>



</body>

</html>