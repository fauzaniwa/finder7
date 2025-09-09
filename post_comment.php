<?php
session_start();
require_once 'admin/config.php';
header('Content-Type: application/json');

// Membaca input JSON mentah dari permintaan
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Anda harus login untuk berkomentar.']);
    exit;
}

// Memeriksa apakah data yang diperlukan ada
if (!isset($data['idKarya']) || empty($data['idKarya']) || !isset($data['commentText']) || empty(trim($data['commentText']))) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'ID Karya dan komentar tidak boleh kosong.']);
    exit;
}

$id_user = $_SESSION['user_id'];
$id_karya = $data['idKarya'];
$comment_text = trim($data['commentText']);
$parent_id = $data['parentId'] ?? null; // Null jika itu adalah komentar utama

try {
    // Siapkan dan jalankan query untuk menyimpan komentar
    $sql = "INSERT INTO comments (id_karya, id_user, parent_id, comment_text) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        throw new Exception(mysqli_error($conn));
    }

    if ($parent_id !== null) {
        mysqli_stmt_bind_param($stmt, "iiis", $id_karya, $id_user, $parent_id, $comment_text);
    } else {
        // Untuk komentar utama, parent_id bisa diatur ke NULL
        $null = NULL;
        mysqli_stmt_bind_param($stmt, "iiis", $id_karya, $id_user, $null, $comment_text);
    }

    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['success' => true, 'message' => 'Komentar berhasil ditambahkan.']);
    } else {
        throw new Exception('Gagal menyimpan komentar.');
    }

    mysqli_stmt_close($stmt);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    exit;
}
?>