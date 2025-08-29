<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

require_once 'config.php';
header('Content-Type: application/json');

$response = ['error' => 'Terjadi kesalahan tidak terduga.'];

try {
    if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
        throw new Exception("ID speaker tidak valid.");
    }
    
    $id_speaker = trim($_GET['id']);
    
    if (!$conn) {
        throw new Exception("Koneksi database gagal.");
    }
    
    $sql = "SELECT id_speaker, nama_speaker, instansi, deskripsi, kontak, foto_speaker, urutan, created_at FROM speakers WHERE id_speaker = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_speaker);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $speaker = mysqli_fetch_assoc($result);
        
        if ($speaker) {
            $response = ['speaker' => $speaker];
        } else {
            http_response_code(404);
            $response = ['error' => 'Speaker tidak ditemukan.'];
        }
        
        mysqli_stmt_close($stmt);
    } else {
        throw new Exception("Error saat menyiapkan query: " . mysqli_error($conn));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    $response = ['error' => $e->getMessage()];
} finally {
    if (isset($conn) && is_object($conn)) {
        mysqli_close($conn);
    }
}

echo json_encode($response);
exit;
?>