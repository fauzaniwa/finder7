<?php
session_start();
header('Content-Type: application/json');

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Koneksi ke database
include 'admin-one/dist/koneksi.php';

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

$response = [
    'success' => false,
    'data' => [],
    'message' => 'Failed to fetch liked artworks.'
];

try {
    // Query untuk mengambil karya yang disukai oleh user yang sedang login
    $sql = "SELECT
                k.id_karya, k.judul_karya, k.nama_karya, k.deskripsi, k.pict_karya, k.slug,
                (SELECT COUNT(*) FROM likes_new WHERE id_karya = k.id_karya) AS likes_count,
                (SELECT COUNT(*) FROM likes_new WHERE id_karya = k.id_karya AND id_user = ?) AS user_liked
            FROM likes_new AS l
            JOIN karya AS k ON l.id_karya = k.id_karya
            WHERE l.id_user = ?";

    if ($stmt = mysqli_prepare($koneksi, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $liked_karya = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $liked_karya[] = $row;
        }

        $response['success'] = true;
        $response['message'] = 'Liked artworks fetched successfully.';
        $response['data'] = $liked_karya;
        mysqli_stmt_close($stmt);
    } else {
        throw new Exception(mysqli_error($koneksi));
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

mysqli_close($koneksi);
echo json_encode($response);