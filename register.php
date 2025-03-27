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
            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt_check_email = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
            $stmt_insert_user = $pdo->prepare("INSERT INTO user (fname, lname, email, phone, password, role) VALUES (:fname, :lname, :email, :phone, :password, 'user')");

            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $stmt_check_email->bindParam(':email', $email);
            $stmt_insert_user->bindParam(':fname', $fname);
            $stmt_insert_user->bindParam(':lname', $lname);
            $stmt_insert_user->bindParam(':email', $email);
            $stmt_insert_user->bindParam(':phone', $phone);
            $stmt_insert_user->bindParam(':password', $password);

            $stmt_check_email->execute();
            $email_exists = $stmt_check_email->fetchColumn();

            if ($email_exists == 0) {
                $stmt_insert_user->execute();
                
                $stmt = $pdo->prepare("SELECT user_id FROM user WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $_SESSION['user_id'] = $result['user_id'];
                    $_SESSION['user_role'] = 'user';
                    header("Location: ./index.php?register=success");
                    exit;
                } else {
                    echo "<script>alert('Error Occurred');</script>";
                }
            } else {
                echo "<script>alert('Email already exists');</script>";
            }
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
?>