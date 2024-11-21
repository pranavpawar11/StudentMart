<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require("conn.php"); // Include the database connection file

// Function to upload files and return the relative path
function uploadFile($file, $type)
{
    // Adjust target directory to be relative to the web root
    $targetDir = ($type == 'pdf') ? "../pdfs/documents/" : "../pdfs/images/";
    $originalFileName = basename($file["name"]);
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $uniqueFileName = uniqid() . '.' . $fileExtension; // Unique filename
    $targetFile = $targetDir . $uniqueFileName; // Path to save the file
    $sourceFile = $file["tmp_name"];
    $maxSize = ($type == 'pdf') ? 10 * 1024 * 1024 : 2 * 1024 * 1024; // 10MB for PDF, 2MB for images
    $allowedTypes = ($type == 'pdf') ? ['application/pdf'] : ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];

    // Check if the directory exists or create it
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the directory recursively
    }

    // Check if the file size is within the limit
    if ($file["size"] > $maxSize) {
        return false; // Return false if file size exceeds limit
    }

    // Check if the file type is allowed
    if (!in_array($file["type"], $allowedTypes)) {
        return false; // Return false if file type is not allowed
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($sourceFile, $targetFile)) {
        return $targetFile; // Return the full path if successful
    } else {
        return false; // Return false if move fails
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare SQL statement
    $sql = "INSERT INTO pdf_documents (pdf_name, pdf_path, price, img1, img2, img3, description, category, upload_date) 
            VALUES (:pdf_name, :pdf_path, :price, :img1, :img2, :img3, :description, :category, CURDATE())";

    // Prepare PDO statement
    $stmt = $pdo->prepare($sql);

    // Upload PDF and images
    $pdfPath = uploadFile($_FILES["pdf"], 'pdf');
    $img1 = uploadFile($_FILES["image1"], 'image');
    $img2 = uploadFile($_FILES["image2"], 'image');
    $img3 = uploadFile($_FILES["image3"], 'image');

    if ($pdfPath && $img1 && $img2 && $img3) {
        // Files uploaded successfully, remove '../' from the paths for the database storage
        $pdfPath = str_replace("../", "", $pdfPath);
        $img1 = str_replace("../", "", $img1);
        $img2 = str_replace("../", "", $img2);
        $img3 = str_replace("../", "", $img3);

        // Files paths are now in the format pdfs/documents/filename.pdf, pdfs/images/filename.jpg
        $stmt->bindParam(':pdf_name', $_POST['pdf_name']);
        $stmt->bindParam(':pdf_path', $pdfPath);
        $stmt->bindParam(':price', $_POST['price']);
        $stmt->bindParam(':img1', $img1);
        $stmt->bindParam(':img2', $img2);
        $stmt->bindParam(':img3', $img3);
        $stmt->bindParam(':description', $_POST['description']);
        $stmt->bindParam(':category', $_POST['category']); // Bind the category parameter

        // Execute the statement
        try {
            $stmt->execute();
            echo "<script>alert('PDF added successfully!');</script>";
            echo "<script>window.location.href = '../dashboard.php';</script>"; // Redirect to dashboard after successful insertion
            exit; // Ensure script execution stops after redirection
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Error handling if file upload failed
        echo "<script>alert('Failed to upload PDF or images. Please ensure each file is valid and within the size limit.');</script>";
        echo "<script>window.history.back();</script>"; // Go back to the previous page
        exit; // Ensure script execution stops after handling the error
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h2 class="text-center mb-0">Add PDF</h2>
                </div>
                <div class="card-body">
                    <form action="includes/add_pdf.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="pdf_name">PDF Name:</label>
                            <input type="text" class="form-control" id="pdf_name" name="pdf_name" placeholder="Enter PDF Name" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Enter Price" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Add detailed information about the PDF..." rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="Education">Education</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Technology">Technology</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pdf">Upload PDF (Max 10MB):</label>
                            <input type="file" class="form-control-file" id="pdf" name="pdf" accept="application/pdf" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="image1">Upload Image 1 (Max 2MB):</label>
                                <input type="file" class="form-control-file" id="image1" name="image1" accept="image/*">
                            </div>
                            <div class="form-group col">
                                <label for="image2">Upload Image 2 (Max 2MB):</label>
                                <input type="file" class="form-control-file" id="image2" name="image2" accept="image/*">
                            </div>
                            <div class="form-group col">
                                <label for="image3">Upload Image 3 (Max 2MB):</label>
                                <input type="file" class="form-control-file" id="image3" name="image3" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-dark">Add PDF</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
