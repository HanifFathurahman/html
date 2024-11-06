<?php
date_default_timezone_set('Asia/Jakarta');

// Menampilkan error untuk debugging (matikan di produksi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fungsi untuk menampilkan file yang sudah ter-upload
function listUploadedFiles($directory) {
    if (is_dir($directory)) {
        $files = array_diff(scandir($directory), array('.', '..')); // Abaikan . dan ..
        if (count($files) > 0) {
            echo "<h2>Files yang telah diupload:</h2><ul class='file-list'>";
            foreach ($files as $file) {
                $filePath = $directory . '/' . $file;
                $fileTime = date("Y-m-d H:i:s", filemtime($filePath)); // Waktu upload dari file
                echo "<li>
                        <div class='file-info'>
                            <a href='$filePath' target='_blank' class='file-name'>$file</a>
                            <span class='file-details'>(" . formatSize(filesize($filePath)) . ") - Diunggah pada $fileTime</span>
                        </div>
                        <a href='download.php?file=$file' class='download-btn'>Download</a>
                      </li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Tidak ada file yang diunggah.</p>";
        }
    } else {
        echo "<p>Folder $directory tidak ditemukan.</p>";
    }
}

// Fungsi untuk memformat ukuran file menjadi lebih terbaca
function formatSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $factor = floor((strlen($bytes) - 1) / 3);
    return number_format($bytes / pow(1024, $factor), 2) . ' ' . $units[$factor];
}

// Tampilkan daftar file yang sudah diupload
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File dan Download</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #e9f7ef;
        }
        h2 {
            color: #2e7d32;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
            padding: 15px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: calc(100% - 22px);
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .file-list {
            list-style-type: none;
            padding: 0;
        }
        .file-list li {
            background: #fff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }
        .file-list li:hover {
            transform: scale(1.02);
        }
        .file-info {
            flex-grow: 1;
        }
        .file-name {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .file-details {
            color: #555;
            font-size: 0.9em;
        }
        .download-btn {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .download-btn:hover {
            background-color: #0056b3;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .progress {
            display: none;
            margin-top: 10px;
        }
        .progress-bar {
            width: 100%;
            background: #f3f3f3;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }
        .progress-fill {
            height: 100%;
            background: #5cb85c;
            width: 0;
            transition: width 0.5s;
        }
    </style>
</head>
<body>

    <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="uploadedFile" required>
        <input type="submit" name="uploadBtn" value="Upload File">
    </form>
    
    <div class="progress" id="progress">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <span id="progressText"></span>
    </div>

    <!-- Bagian menampilkan file yang telah diupload -->
    <?php listUploadedFiles('uploads/'); ?>

    <script>
        document.getElementById('uploadForm').onsubmit = function(e) {
            e.preventDefault(); // Mencegah form default submit
            const formData = new FormData(this);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', 'upload.php', true);

            // Update progress bar
            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    const percentComplete = (event.loaded / event.total) * 100;
                    document.getElementById('progress').style.display = 'block';
                    document.getElementById('progressFill').style.width = percentComplete + '%';
                    document.getElementById('progressText').textContent = Math.round(percentComplete) + '%';
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Refresh the page after upload
                    location.reload();
                } else {
                    alert('Error occurred during the upload.');
                }
            };

            xhr.send(formData);
        };
    </script>

</body>
</html>
