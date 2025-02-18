<?php


include("php/conn.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header("Location:./index.php");
    exit;
  }
if (isset($_POST['Subregister'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        try {
            // Create a PDO connection
            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            // Set PDO to throw exceptions on error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare a statement to check if the email already exists
            $stmt_check_email = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");

            // Prepare an insert statement
            $stmt_insert_user = $pdo->prepare("INSERT INTO user (fname, lname, email, phone, password, photo) VALUES (:fname, :lname, :email, :phone, :password, :photo)");

            // Sample data
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $photo = ''; // Assuming photo is optional, otherwise handle file upload

            // Bind parameters
            $stmt_check_email->bindParam(':email', $email);
            $stmt_insert_user->bindParam(':fname', $fname);
            $stmt_insert_user->bindParam(':lname', $lname);
            $stmt_insert_user->bindParam(':email', $email);
            $stmt_insert_user->bindParam(':phone', $phone);
            $stmt_insert_user->bindParam(':password', $password);
            $stmt_insert_user->bindParam(':photo', $photo);

            // Execute the check email statement
            $stmt_check_email->execute();
            $email_exists = $stmt_check_email->fetchColumn();

            // If the email doesn't exist, insert the user
            if ($email_exists == 0) {
                $stmt_insert_user->execute();
                echo "User inserted successfully.";

                $stmt = $pdo->prepare("SELECT user_id FROM user WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                // Fetch user_id
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $user_id = $result['user_id'];
                    $_SESSION['user_id'] = $user_id;
                    header("Location: ./index.php?register=success");
                    exit;
                } else {
                    echo "<script>alert('Error Occurred');</script>";
                }

            } else {
                echo "<script>alert('Email exists');</script>";
            }

        } catch (PDOException $e) {
            // Handle connection errors
            die("Connection failed: " . $e->getMessage());
        }
    }
}
?>
