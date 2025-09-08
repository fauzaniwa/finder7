<?php
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

$sql = "SELECT id, nama_penampil, tanggal_tampil, jam_tampil, lokasi_tampil, status_view FROM performance ORDER BY tanggal_tampil, jam_tampil ASC";
$result = mysqli_query($conn, $sql);

$performance = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $performance[] = $row;
    }
}

echo json_encode(['performance' => $performance]);
mysqli_close($conn);
?>