<?php
include("php/conn.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_SESSION['user_id'])) {
  header("Location:./index.php");
  exit;
} else {
}
if (isset($_POST['submitlogin'])) {

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
      // Create a PDO connection
      $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
      // Set PDO to throw exceptions on error
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Prepare a statement to fetch user details based on email
      $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Check if user with given email exists
      if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
          // Password matches, login successful
          $stmt = $pdo->prepare("SELECT user_id FROM user WHERE email = :email");
          $stmt->bindParam(':email', $email);
          $stmt->execute();

          // Fetch user_id
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($result) {
            $user = $result['user_id'];
            $_SESSION['user_id'] = $user;
            header("location:./index.php?login=success");
            exit;
            // echo "User ID: " . $user_id;
          } else {
            echo "<script>alert('Error Occured');</script>";
          }

        } else {
          // Password does not match

          echo "<script>alert('Incorrect Password');</script>";
          // header("location:./login.php");
          // echo "Invalid email or password.";
        }
      } else {
        echo "<script>alert('User not exits');</script>";
      }
    } catch (PDOException $e) {
      // Handle connection errors
      echo "Connection failed: " . $e->getMessage();
    }


  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/login.css">
  <title>Document</title>
</head>

<body>

  <div id="form">
    <div class="container">
      <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-md-8 col-md-offset-2">
        <div id="userform">
          <ul class="nav nav-tabs nav-justified" role="tablist">
            <li class="active"><a href="#signup" role="tab" data-toggle="tab">Sign up</a></li>
            <li><a href="#login" role="tab" data-toggle="tab">Log in</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active in" id="signup">
              <h2 class="text-uppercase text-center"> Sign Up </h2>

              <form id="signup" method="post" action="./register.php">
                <div class="row">
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label>First Name<span class="req">*</span> </label>
                      <input type="text" name="fname" class="form-control" id="first_name" required
                        data-validation-required-message="Please enter your name." autocomplete="off">
                      <p class="help-block text-danger"></p>
                    </div>
                  </div>

                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label> Last Name<span class="req">*</span> </label>
                      <input type="text" name="lname" class="form-control" id="last_name" required
                        data-validation-required-message="Please enter your name." autocomplete="off">
                      <p class="help-block text-danger"></p>
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label> Your Email<span class="req">*</span> </label>
                  <input type="email" name="email" class="form-control" id="email" required
                    data-validation-required-message="Please enter your email address." autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>

                <div class="form-group">
                  <label> Your Phone<span class="req">*</span> </label>
                  <input type="tel" name="phone" class="form-control" id="phone" required
                    data-validation-required-message="Please enter your phone number." autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>

                <div class="form-group">
                  <label> Password<span class="req">*</span> </label>
                  <input type="password" name="password" class="form-control" id="password" required
                    data-validation-required-message="Please enter your password" autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>

                <div class="mrgn-30-top">
                  <button type="submit" name="Subregister" class="btn btn-larger btn-block" />
                  Sign up
                  </button>
                </div>
              </form>

            </div>
            <div class="tab-pane fade in" id="login">
              <h2 class="text-uppercase text-center"> Log in</h2>


              <form id="login" method="post">
                <div class="form-group">
                  <label> Your Email<span class="req">*</span> </label>
                  <input type="email" name="email" class="form-control" id="email" required
                    data-validation-required-message="Please enter your email address." autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>
                <div class="form-group">
                  <label> Password<span class="req">*</span> </label>
                  <input type="password" name="password" class="form-control" id="password" required
                    data-validation-required-message="Please enter your password" autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>
                <div class="mrgn-30-top">
                  <button type="submit" name="submitlogin" class="btn btn-larger btn-block" />
                  Log in
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container -->
  </div>


  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>


<html lang="en">

<head>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>

<body>
  <div id="form">
    <div class="container">
      <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-md-8 col-md-offset-2">
        <div id="userform">
          <ul class="nav nav-tabs nav-justified" role="tablist">
            <li class="active"><a href="#signup" role="tab" data-toggle="tab">Sign up</a></li>
            <li><a href="#login" role="tab" data-toggle="tab">Log in</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active in" id="signup">
              <h2 class="text-uppercase text-center"> Sign Up for Free</h2>
              <form id="signup" method="post" action="./register.php">
                <div class="row">
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label>First Name<span class="req">*</span> </label>
                      <input type="text" name="fname" class="form-control" id="first_name" required
                        data-validation-required-message="Please enter your name." autocomplete="off">
                      <p class="help-block text-danger"></p>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label> Last Name<span class="req">*</span> </label>
                      <input type="text" name="lname" class="form-control" id="last_name" required
                        data-validation-required-message="Please enter your name." autocomplete="off">
                      <p class="help-block text-danger"></p>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label> Your Email<span class="req">*</span> </label>
                  <input type="email" name="email" class="form-control" id="email" required
                    data-validation-required-message="Please enter your email address." autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>
                <div class="form-group">
                  <label> Your Phone<span class="req">*</span> </label>
                  <input type="tel" name="phone" class="form-control" id="phone" required
                    data-validation-required-message="Please enter your phone number." autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>
                <div class="form-group">
                  <label> Password<span class="req">*</span> </label>
                  <input type="password" name="password" class="form-control" id="password" required
                    data-validation-required-message="Please enter your password" autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>
                <div class="mrgn-30-top">
                  <button type="submit" name="Subregister" class="btn btn-larger btn-block" />
                  Sign up
                  </button>
                </div>
              </form>
            </div>
            <div class="tab-pane fade in" id="login">
              <h2 class="text-uppercase text-center"> Log in</h2>

              <form id="login" method="post">
                <div class="form-group">
                  <label> Your Email<span class="req">*</span> </label>
                  <input type="email" name="email" class="form-control" id="email" required
                    data-validation-required-message="Please enter your email address." autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>
                <div class="form-group">
                  <label> Password<span class="req">*</span> </label>
                  <input type="password" name="password" class="form-control" id="password" required
                    data-validation-required-message="Please enter your password" autocomplete="off">
                  <p class="help-block text-danger"></p>
                </div>
                <div class="mrgn-30-top">
                  <button type="submit" name="submitlogin" class="btn btn-larger btn-block">
                    Log in
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container -->
  </div>
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>




</body>
<script src="js/login.js"></script>
</body>

</html>