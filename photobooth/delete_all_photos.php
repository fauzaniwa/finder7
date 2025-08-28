<?php
header('Content-Type: application/json');
require 'koneksi.php';

$folder = 'imgphotobooth/';

// Hapus file dari folder
$files = glob($folder . '*'); // Ambil semua file
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

// Hapus semua data dari database
$query = "DELETE FROM photobooth";
if ($koneksi->query($query)) {
    echo json_encode(['status' => 'success', 'message' => 'Semua data berhasil dihapus.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data dari database.']);
}
?>
