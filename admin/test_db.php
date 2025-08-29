<?php
require_once 'config.php';

if ($conn) {
    echo "Koneksi database berhasil!";
} else {
    echo "Koneksi database gagal.";
}

mysqli_close($conn);
?>