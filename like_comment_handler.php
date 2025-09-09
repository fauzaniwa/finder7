<?php
session_start();
require_once 'admin/config.php';
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login untuk menyukai komentar.']);
    exit;
}

if (!isset($data['idKomentar']) || empty($data['idKomentar'])) {
    echo json_encode(['success' => false, 'message' => 'ID Komentar tidak valid.']);
    exit;
}

$id_user = $_SESSION['user_id'];
$id_komentar = $data['idKomentar'];
$action = '';

try {
    // Periksa apakah pengguna sudah menyukai komentar ini
    $sql_check = "SELECT id_like FROM likes_comment WHERE id_comment = ? AND id_user = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $id_komentar, $id_user);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    $liked = mysqli_stmt_num_rows($stmt_check) > 0;
    mysqli_stmt_close($stmt_check);

    if ($liked) {
        // Jika sudah suka, hapus sukanya
        $sql_delete = "DELETE FROM likes_comment WHERE id_comment = ? AND id_user = ?";
        $stmt_delete = mysqli_prepare($conn, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "ii", $id_komentar, $id_user);
        mysqli_stmt_execute($stmt_delete);
        $action = 'unliked';
        mysqli_stmt_close($stmt_delete);
    } else {
        // Jika belum suka, tambahkan suka baru
        $sql_insert = "INSERT INTO likes_comment (id_comment, id_user) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "ii", $id_komentar, $id_user);
        mysqli_stmt_execute($stmt_insert);
        $action = 'liked';
        mysqli_stmt_close($stmt_insert);
    }

    // Hitung ulang jumlah suka untuk komentar tersebut
    $sql_count = "SELECT COUNT(*) FROM likes_comment WHERE id_comment = ?";
    $stmt_count = mysqli_prepare($conn, $sql_count);
    mysqli_stmt_bind_param($stmt_count, "i", $id_komentar);
    mysqli_stmt_execute($stmt_count);
    mysqli_stmt_bind_result($stmt_count, $likes_count);
    mysqli_stmt_fetch($stmt_count);
    mysqli_stmt_close($stmt_count);

    echo json_encode([
        'success' => true,
        'action' => $action,
        'likes' => $likes_count,
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>