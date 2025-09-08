<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Pastikan hanya admin 'master' yang bisa mengakses
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    header("location: login.php");
    exit;
}

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id_karya = trim($_GET["id"]);

    // Dapatkan nama file media yang akan dihapus dari database
    $sql_get_file = "SELECT pict_karya FROM karya WHERE id_karya = ?";
    if ($stmt_get_file = mysqli_prepare($conn, $sql_get_file)) {
        mysqli_stmt_bind_param($stmt_get_file, "i", $id_karya);
        if (mysqli_stmt_execute($stmt_get_file)) {
            $result_get_file = mysqli_stmt_get_result($stmt_get_file);
            if (mysqli_num_rows($result_get_file) == 1) {
                $row = mysqli_fetch_assoc($result_get_file);
                $pict_karya = $row['pict_karya'];
                
                // Hapus file media dari server
                $file_path = '../img/karya/' . $pict_karya;
                if (!empty($pict_karya) && file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }
        mysqli_stmt_close($stmt_get_file);
    }

    // Hapus data karya dari database
    $sql_delete = "DELETE FROM karya WHERE id_karya = ?";
    if ($stmt_delete = mysqli_prepare($conn, $sql_delete)) {
        mysqli_stmt_bind_param($stmt_delete, "i", $id_karya);
        if (mysqli_stmt_execute($stmt_delete)) {
            log_admin_activity($conn, $_SESSION['id'], 'delete', 'Menghapus karya dengan ID ' . $id_karya);
            header("location: karya_list.php");
            exit();
        } else {
            echo "Ups! Terjadi kesalahan. Silakan coba lagi nanti.";
        }
        mysqli_stmt_close($stmt_delete);
    } else {
        echo "Gagal menyiapkan statement. " . mysqli_error($conn);
    }
} else {
    header("location: karya_list.php");
    exit();
}
mysqli_close($conn);
?>