<?php
session_start();

// Pengecekan sesi login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Sertakan file koneksi database
require_once '../admin-one/dist/koneksi.php';

// Ambil semua data dari tabel pendaftaran_wacom
$sql = "SELECT * FROM pendaftaran_wacom";
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    // Tentukan header untuk membuat file CSV
    $filename = "data_pendaftaran_wacom_" . date('Ymd') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Buat file pointer untuk output
    $output = fopen('php://output', 'w');
    
    // Tulis header kolom ke file CSV
    $columns = array('ID', 'Kategori Karya', 'Nama Lengkap', 'Nomor Telepon', 'Email', 'Instansi', 'Judul Karya', 'Media Sosial', 'Deskripsi Karya', 'Link Karya', 'Bukti Pembayaran', 'Persetujuan', 'Tanggal Pendaftaran');
    fputcsv($output, $columns);

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