<?php
require_once "koneksi.php";

// Set headers untuk download CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="data_lomba.csv"');

// Open output stream
$output = fopen("php://output", "w");

// Tulis header kolom
fputcsv($output, ['No', 'Nama', 'Nomor Telp', 'Email', 'Instansi', 'Media Sosial', 'Kategori Karya', 'Judul Karya', 'Deskripsi', 'Link', 'Created At']);

// Ambil data
$query = "SELECT * FROM Lomba ORDER BY created_at DESC";
$result = mysqli_query($koneksi, $query);
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {
  fputcsv($output, [
    $no++,
    $row['nama'],
    $row['nomor_telp'],
    $row['email'],
    $row['instansi'],
    $row['media_sosial'],
    $row['kategori_karya'],
    $row['judul_karya'],
    $row['deskripsi_karya'],
    $row['link_karya'],
    $row['created_at']
  ]);
}

fclose($output);
exit;
?>
