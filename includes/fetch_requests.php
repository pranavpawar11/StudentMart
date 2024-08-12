<?php
function fetchRequests() {
    // Database connection (replace with your actual database connection details)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "your_database_name";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch requests
    $sql = "SELECT id, title, description, image_url, price, seller, contact, status FROM requests";
    $result = $conn->query($sql);

    $requests = array();
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    } else {
        echo "0 results";
    }

    $conn->close();
    return $requests;
}
?>
