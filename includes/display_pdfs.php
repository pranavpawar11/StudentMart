<?php
// Include the database connection
include("conn.php");
session_start();

try {
    // Fetch PDFs for the logged-in user
    $user_id = $_SESSION['user_id'];
    // Query to fetch PDFs
    $sql = "
        SELECT 
            pdf_id, 
            pdf_name, 
            pdf_path, 
            price,
            rented_by, 
            img1, 
            img2, 
            img3, 
            description, 
            upload_date
        FROM 
            pdf_documents
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PDFs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 1200px;
            margin-top: 20px;
        }

        .pdf-card {
            border: 1px solid #ddd;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .pdf-card:hover {
            transform: translateY(-5px);
        }

        .pdf-img {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .pdf-img img {
            width: auto;
            height: 100%;
            object-fit: cover;
        }

        .pdf-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .btn-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: auto;
        }

        .btn-primary {
            font-size: 14px;
            padding: 8px 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>My PDFs</h2>
        <br>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
            <div class="col mb-4">
                <div class="card pdf-card">
                    <div class="pdf-img">
                        <img src="<?php echo htmlspecialchars($row['img1']); ?>" class="card-img-top" alt="PDF Image">
                    </div>
                    <div class="card-body pdf-body">
                        <h6 class="card-title"><?php echo htmlspecialchars($row['pdf_name']); ?></h6>
                        <strong class="card-text">RS <?php echo htmlspecialchars($row['price']);  ?> </strong>
                        <strong class="card-text"><?php echo htmlspecialchars($row['description']); ?></strong>
                        <strong class="card-text">Rented by: <?php echo htmlspecialchars($row['rented_by']); ?> users</strong>
                        <div class="btn-container">
                            <button class="btn btn-primary view-pdf" 
                                    onclick="openPdfModal('<?php echo htmlspecialchars($row['pdf_path']); ?>')">View PDF</button>
                            <a href="#" class="btn btn-secondary btn-edit mx-2">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Modal for PDF Viewing -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">View PDF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewer" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>

</html>
