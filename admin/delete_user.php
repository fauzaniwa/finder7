<?php
// Sertakan file koneksi database dan fungsi logging
require_once 'config.php';
require_once 'functions.php';

// Periksa apakah admin sudah login dan memiliki ID
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["id"])) {
    header("location: login.php");
    exit;
}

// Periksa apakah ID pengguna yang akan dihapus dikirimkan
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $user_id = trim($_GET['id']);
    
    // Siapkan pernyataan SQL untuk menghapus data
    $sql = "DELETE FROM user WHERE id_user = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $user_id;
        
        if (mysqli_stmt_execute($stmt)) {
            // Log aktivitas admin
            log_admin_activity($conn, $_SESSION["id"], 'delete_user', 'Menghapus pengguna dengan ID: ' . $user_id);
            
            // Berhasil, redirect kembali ke halaman users
            header("location: users.php?status=deleted");
            exit();
        } else {
            // Gagal, tampilkan pesan error
            error_log("Error deleting user: " . mysqli_error($conn));
            echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
        }
    }
    mysqli_stmt_close($stmt);
} else {
    // Jika tidak ada ID yang dikirimkan, redirect ke halaman users
    header("location: users.php");
    exit();
}
?>