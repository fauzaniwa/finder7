<?php
// Pastikan tidak ada output lain sebelum header
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

require_once 'config.php';
header('Content-Type: application/json');

$response = ['error' => 'Terjadi kesalahan tidak terduga.'];

try {
    if (!$conn) {
        throw new Exception("Koneksi database gagal.");
    }
    
    // Ambil semua data speakers
    $sql = "SELECT id_speaker, nama_speaker, instansi, foto_speaker FROM speakers ORDER BY urutan ASC, created_at DESC";
    
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        throw new Exception("Error saat mengambil data speakers: " . mysqli_error($conn));
    }
    
    $speakers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $speakers[] = $row;
    }
    
    $response = ['speakers' => $speakers];

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    $response = ['error' => $e->getMessage()];
} finally {
    if (isset($conn) && is_object($conn)) {
        mysqli_close($conn);
    }
}

echo json_encode($response);
exit;
?>