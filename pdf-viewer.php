<?php
session_start();
require_once('php/conn.php');

// Security Check
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized Access");
}

$pdf_path = filter_var($_GET['path'], FILTER_SANITIZE_STRING);
if (empty($pdf_path) || !file_exists($pdf_path)) {
    die("Invalid PDF");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>PDF Viewer</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        #viewerContainer {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            background: #404040;
        }

        #pdfViewer {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>

<body>
    <div id="viewerContainer">
        <iframe id="pdfViewer"
            src="pdfjs/web/viewer.html?file=<?= urlencode('http://localhost/StudentMart/secure-pdf-stream.php?path=' . $pdf_path) ?>"
            sandbox="allow-same-origin allow-scripts allow-forms">
        </iframe>
    </div>

    <script>
        // Disable right click
        document.addEventListener('contextmenu', e => e.preventDefault());

        // Disable keyboard shortcuts
        document.addEventListener('keydown', e => {
            if (e.key === 'PrintScreen' ||
                (e.ctrlKey && (e.key === 'p' || e.key === 's' || e.key === 'c')) ||
                (e.altKey && e.key === 'PrintScreen')) {
                e.preventDefault();
                return false;
            }
        });

        // Disable copy
        document.addEventListener('copy', e => e.preventDefault());

        // Disable selection
        document.addEventListener('selectstart', e => e.preventDefault());
    </script>
</body>

</html>