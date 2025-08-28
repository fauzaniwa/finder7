<?php
session_start();

// Sambungkan ke database (sesuaikan dengan lokasi file koneksi)
require_once "admin-one/dist/koneksi.php";

// Periksa koneksi
if (mysqli_connect_errno()) {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Koneksi database gagal: ' . mysqli_connect_error()));
    exit;
}

// Lanjutkan dengan proses memberikan like
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data dari permintaan Ajax
    $data = json_decode(file_get_contents("php://input"), true);
    $id_karya = mysqli_real_escape_string($koneksi, $data['idKarya']);
    $id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Ambil user_id dari session

    // Periksa apakah user sudah login
    if (!$id_user) {
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'message' => 'Anda harus login terlebih dahulu.'));
        exit;
    }

    // Query untuk mengecek apakah user sudah memberikan like sebelumnya
    $query_check_like = "SELECT id_like FROM likes WHERE id_karya = ? AND user_id = ?";
    $stmt_check_like = mysqli_prepare($koneksi, $query_check_like);
    mysqli_stmt_bind_param($stmt_check_like, "ii", $id_karya, $id_user);
    mysqli_stmt_execute($stmt_check_like);
    mysqli_stmt_store_result($stmt_check_like);

    // Jika sudah memberikan like sebelumnya, hapus like
    if (mysqli_stmt_num_rows($stmt_check_like) > 0) {
        // Hapus like dari tabel likes
        $query_delete_like = "DELETE FROM likes WHERE id_karya = ? AND user_id = ?";
        $stmt_delete_like = mysqli_prepare($koneksi, $query_delete_like);
        mysqli_stmt_bind_param($stmt_delete_like, "ii", $id_karya, $id_user);
        mysqli_stmt_execute($stmt_delete_like);

        // Kurangi jumlah like di tabel karya
        $query_subtract_like = "UPDATE karya SET likes = likes - 1 WHERE id_karya = ?";
        $stmt_subtract_like = mysqli_prepare($koneksi, $query_subtract_like);
        mysqli_stmt_bind_param($stmt_subtract_like, "i", $id_karya);
        mysqli_stmt_execute($stmt_subtract_like);

        // Ambil jumlah like terbaru
        $query_get_likes = "SELECT likes FROM karya WHERE id_karya = ?";
        $stmt_get_likes = mysqli_prepare($koneksi, $query_get_likes);
        mysqli_stmt_bind_param($stmt_get_likes, "i", $id_karya);
        mysqli_stmt_execute($stmt_get_likes);
        mysqli_stmt_bind_result($stmt_get_likes, $new_likes);
        mysqli_stmt_fetch($stmt_get_likes);

        // Mengembalikan respon JSON ke client
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'likes' => $new_likes, 'alreadyLiked' => false));
    } else {
        // Tambahkan like ke tabel likes
        $query_add_like = "INSERT INTO likes (id_karya, user_id, liked_at) VALUES (?, ?, NOW())";
        $stmt_add_like = mysqli_prepare($koneksi, $query_add_like);
        mysqli_stmt_bind_param($stmt_add_like, "ii", $id_karya, $id_user);
        mysqli_stmt_execute($stmt_add_like);

        // Tambah jumlah like di tabel karya
        $query_increase_like = "UPDATE karya SET likes = likes + 1 WHERE id_karya = ?";
        $stmt_increase_like = mysqli_prepare($koneksi, $query_increase_like);
        mysqli_stmt_bind_param($stmt_increase_like, "i", $id_karya);
        mysqli_stmt_execute($stmt_increase_like);

        // Ambil jumlah like terbaru
        $query_get_likes = "SELECT likes FROM karya WHERE id_karya = ?";
        $stmt_get_likes = mysqli_prepare($koneksi, $query_get_likes);
        mysqli_stmt_bind_param($stmt_get_likes, "i", $id_karya);
        mysqli_stmt_execute($stmt_get_likes);
        mysqli_stmt_bind_result($stmt_get_likes, $new_likes);
        mysqli_stmt_fetch($stmt_get_likes);

        // Mengembalikan respon JSON ke client
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'likes' => $new_likes, 'alreadyLiked' => true));
    }

    // Tutup pernyataan dan koneksi
    mysqli_stmt_close($stmt_check_like);
    mysqli_close($koneksi);
} else {
    // Mengembalikan respons JSON jika method bukan POST
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Metode tidak diizinkan.'));
}
?>
