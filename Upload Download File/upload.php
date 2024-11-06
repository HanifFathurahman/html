<?php
date_default_timezone_set('Asia/Jakarta');

// Menampilkan error untuk debugging (matikan di produksi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validasi file upload
if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';

    // Memastikan folder 'uploads' ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Mengambil nama file dan informasi file
    $fileName = basename($_FILES['uploadedFile']['name']);
    $uploadFilePath = $uploadDir . $fileName;
    $fileSize = $_FILES['uploadedFile']['size'];

    // Validasi ukuran file (maksimal 50MB)
    if ($fileSize > 50 * 1024 * 1024) { // 50MB
        echo "<div class='error'>File terlalu besar. Maksimal ukuran file adalah 50MB.</div>";
        exit;
    }

    // Proses upload
    if (move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $uploadFilePath)) {
        echo "<div class='success'>File berhasil diunggah: " . htmlspecialchars($fileName) . "</div>";
    } else {
        echo "<div class='error'>Terjadi kesalahan saat mengunggah file. Silakan coba lagi.</div>";
    }
} else {
    // Menangani error upload
    switch ($_FILES['uploadedFile']['error']) {
        case UPLOAD_ERR_INI_SIZE:
            echo "<div class='error'>File melebihi batas maksimal ukuran yang diizinkan oleh server.</div>";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            echo "<div class='error'>File melebihi batas maksimal yang ditentukan dalam form HTML.</div>";
            break;
        case UPLOAD_ERR_PARTIAL:
            echo "<div class='error'>File hanya terupload sebagian.</div>";
            break;
        case UPLOAD_ERR_NO_FILE:
            echo "<div class='error'>Tidak ada file yang diupload.</div>";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            echo "<div class='error'>Temporary folder hilang.</div>";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            echo "<div class='error'>Gagal menulis file ke disk.</div>";
            break;
        case UPLOAD_ERR_EXTENSION:
            echo "<div class='error'>File ditolak karena ekstensi tidak diizinkan.</div>";
            break;
        default:
            echo "<div class='error'>Terjadi kesalahan tidak terduga saat mengunggah file.</div>";
            break;
    }
}
?>
