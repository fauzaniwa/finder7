<?php
session_start();

// Pengecekan sesi login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Sertakan file koneksi database
require_once '../admin-one/dist/koneksi.php';

// Ambil semua data dari tabel pendaftaran_cosplay
$sql = "SELECT * FROM pendaftaran_cosplay";
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    // Tentukan header untuk membuat file CSV
    $filename = "data_pendaftaran_cosplay_" . date('Ymd') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Buat file pointer untuk output
    $output = fopen('php://output', 'w');
    
    // Ambil nama kolom dari hasil query untuk dijadikan header CSV
    $row = $result->fetch_assoc();
    if ($row) {
        $columns = array_keys($row);
        fputcsv($output, $columns);
        // Kembali ke awal hasil query untuk menulis data
        $result->data_seek(0);
    }

    // Tulis setiap baris data dari database ke file CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    
    fclose($output);
} else {
    echo "Tidak ada data untuk diunduh.";
}

$koneksi->close();
exit;
?>