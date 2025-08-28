<?php
header('Content-Type: application/json');
require 'koneksi.php';

// Ambil semua data foto dari tabel photobooth
$query = "SELECT filename FROM photobooth ORDER BY id DESC";
$result = $koneksi->query($query);

$photos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $photos[] = [
            'filename' => $row['filename']
        ];
    }
}

echo json_encode($photos);
?>
