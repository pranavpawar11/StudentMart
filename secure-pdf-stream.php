<?php
session_start();
require_once('php/conn.php');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$pdf_path = filter_var($_GET['path'], FILTER_SANITIZE_STRING);
if (empty($pdf_path) || !file_exists($pdf_path)) {
    http_response_code(404);
    exit('PDF not found');
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="document.pdf"');
header('Cache-Control: no-store, must-revalidate');
header('Pragma: public');
header('X-Content-Type-Options: nosniff');

readfile($pdf_path);