<?php
session_start();
require_once 'admin/config.php';
header('Content-Type: application/json');

// Pastikan slug karya ada di URL
if (!isset($_GET['karya']) || empty($_GET['karya'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Slug karya tidak valid.']);
    exit;
}

$slug = $_GET['karya'];
$id_user = $_SESSION['user_id'] ?? null;
$artwork = null;

try {
    // Query untuk mengambil data karya berdasarkan slug
    $sql = "SELECT
                k.id_karya, k.judul_karya, k.nama_karya, k.deskripsi, k.pict_karya,
                (SELECT COUNT(*) FROM likes_new WHERE id_karya = k.id_karya) AS likes_count,
                (SELECT COUNT(*) FROM likes_new WHERE id_karya = k.id_karya AND id_user = ?) AS user_liked
            FROM karya k
            WHERE k.slug = ?";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        throw new Exception(mysqli_error($conn));
    }
    
    // Bind parameter untuk mencegah SQL Injection
    mysqli_stmt_bind_param($stmt, "is", $id_user, $slug);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $artwork = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$artwork) {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Karya tidak ditemukan.']);
        exit;
    }

    echo json_encode($artwork);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
    exit;
}
?>