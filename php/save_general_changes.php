<?php
// Example PHP script to handle saving general settings changes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from POST request
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Example PDO connection and update query
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('UPDATE user SET fname = :fname, lname = :lname, email = :email, phone = :phone WHERE user_id = :user_id');
        $stmt->execute(array(
            ':fname' => $fname,
            ':lname' => $lname,
            ':email' => $email,
            ':phone' => $phone,
            ':user_id' => $userId
        ));

        echo 'Changes saved successfully.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
