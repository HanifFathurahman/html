<?php
// Mengatur timezone
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk memformat ukuran file menjadi lebih terbaca
function formatSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $factor = floor((strlen($bytes) - 1) / 3);
    return number_format($bytes / pow(1024, $factor), 2) . ' ' . $units[$factor];
}

// Cek apakah ada file yang diminta
if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']);
    $filePath = 'uploads/' . $fileName;

    // Cek apakah file ada
    if (file_exists($filePath)) {
        // Mengatur header untuk download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "<div class='error'>File tidak ditemukan.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #e9f7ef;
        }
        h2 {
            color: #2e7d32;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

    <h2>Download File</h2>
    <p>Silakan pilih file untuk diunduh.</p>

</body>
</html>
