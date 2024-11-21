<?php
// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session to access session variables
}

include './php/conn.php'; // Include the database connection file

try {
    // Check if user_id is set in the session
    if (isset($_SESSION['user_id'])) {
        $current_user_id = $_SESSION['user_id'];

        // Prepare SQL query to fetch PDFs from the wishlist using the pdf_wishlist table
        $sql = "
            SELECT pdf.pdf_id, 
                   pdf.pdf_name, 
                   pdf.pdf_path, 
                   pdf.price,
                   pdf.img1, 
                   pdf.img2, 
                   pdf.img3, 
                   pdf.description, 
                   pdf.upload_date,
                   CASE 
                       WHEN pw.pdf_id IS NOT NULL THEN 'images/icons/icon-heart-02.png' 
                       ELSE 'images/icons/icon-heart-01.png' 
                   END AS wishlist_icon
            FROM pdf_documents pdf
            LEFT JOIN pdf_wishlist pw ON pdf.pdf_id = pw.pdf_id AND pw.user_id = :current_user_id
            WHERE pdf.pdf_id IS NOT NULL
            ORDER BY RAND()"; // Randomly order the PDFs

        // Prepare the statement
        $stmt = $pdo->prepare($sql);
        // Bind the user_id parameter
        $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
        // Execute the statement
        $stmt->execute();
    } else {
        throw new Exception('User ID not set in session.');
    }

    // Initialize an array to hold the PDFs
    $pdfs = [];

    // Loop through each row in the result set and add to the PDFs array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pdfs[] = $row;
    }

    // Echo the JSON encoded PDFs array
    echo json_encode($pdfs);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
