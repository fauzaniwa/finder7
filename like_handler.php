<?php
session_start();
require_once 'admin/config.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data dari permintaan Ajax (JSON)
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Ambil id_karya dari data POST
    $id_karya = $data['idKarya'] ?? null;
    
    // Periksa apakah user sudah login dengan variabel sesi yang benar
    $id_user = $_SESSION['user_id'] ?? null;

    if (!$id_user) {
        $response['message'] = 'Anda harus login terlebih dahulu.';
        $response['redirect'] = 'login.php'; // Opsional: Tambahkan URL redirect
        echo json_encode($response);
        exit;
    }
    
    if (empty($id_karya)) {
        $response['message'] = 'ID karya tidak valid.';
        echo json_encode($response);
        exit;
    }

    // Gunakan prepared statement untuk keamanan
    try {
        // Query untuk mengecek apakah user sudah memberikan like sebelumnya
        $query_check_like = "SELECT id_like_new FROM likes_new WHERE id_karya = ? AND id_user = ?";
        $stmt_check_like = mysqli_prepare($conn, $query_check_like);
        
        if ($stmt_check_like === false) {
            throw new Exception("Gagal menyiapkan query cek like: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt_check_like, "ii", $id_karya, $id_user);
        mysqli_stmt_execute($stmt_check_like);
        mysqli_stmt_store_result($stmt_check_like);
        $is_liked = mysqli_stmt_num_rows($stmt_check_like) > 0;
        mysqli_stmt_close($stmt_check_like);

        // Lakukan aksi (like atau unlike)
        if ($is_liked) {
            $query_action = "DELETE FROM likes_new WHERE id_karya = ? AND id_user = ?";
            $action = "unliked";
        } else {
            $query_action = "INSERT INTO likes_new (id_karya, id_user, created_at) VALUES (?, ?, NOW())";
            $action = "liked";
        }

        $stmt_action = mysqli_prepare($conn, $query_action);
        if ($stmt_action === false) {
            throw new Exception("Gagal menyiapkan query aksi like: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt_action, "ii", $id_karya, $id_user);
        mysqli_stmt_execute($stmt_action);
        mysqli_stmt_close($stmt_action);
        
        // Ambil jumlah like terbaru
        $query_get_likes = "SELECT COUNT(*) AS total_likes FROM likes_new WHERE id_karya = ?";
        $stmt_get_likes = mysqli_prepare($conn, $query_get_likes);
        
        if ($stmt_get_likes === false) {
            throw new Exception("Gagal menyiapkan query hitung like: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt_get_likes, "i", $id_karya);
        mysqli_stmt_execute($stmt_get_likes);
        $result = mysqli_stmt_get_result($stmt_get_likes);
        $likes_data = mysqli_fetch_assoc($result);
        $new_likes = $likes_data['total_likes'];
        
        mysqli_stmt_close($stmt_get_likes);

        $response['success'] = true;
        $response['message'] = "Aksi berhasil.";
        $response['likes'] = $new_likes;
        $response['action'] = $action;

    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }
    
    mysqli_close($conn);

} else {
    $response['message'] = 'Metode permintaan tidak valid.';
}
echo json_encode($response);
?>