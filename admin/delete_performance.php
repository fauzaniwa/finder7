<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    header("location: login.php");
    exit;
}

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    // Dapatkan path gambar sebelum menghapus data
    $sql_select = "SELECT path_image_penampil, path_image_logo_penampil FROM performance WHERE id = ?";
    if ($stmt_select = mysqli_prepare($conn, $sql_select)) {
        mysqli_stmt_bind_param($stmt_select, "i", $param_id);
        $param_id = $id;
        if (mysqli_stmt_execute($stmt_select)) {
            $result_select = mysqli_stmt_get_result($stmt_select);
            $row = mysqli_fetch_assoc($result_select);
            $path_image_penampil = $row['path_image_penampil'];
            $path_image_logo_penampil = $row['path_image_logo_penampil'];
        }
        mysqli_stmt_close($stmt_select);
    }

    // Hapus data dari database
    $sql_delete = "DELETE FROM performance WHERE id = ?";
    if ($stmt_delete = mysqli_prepare($conn, $sql_delete)) {
        mysqli_stmt_bind_param($stmt_delete, "i", $param_id);
        $param_id = $id;

        if (mysqli_stmt_execute($stmt_delete)) {
            // Hapus file gambar jika ada
            $upload_dir = '../img/performance/';
            if ($path_image_penampil && file_exists($upload_dir . $path_image_penampil)) {
                unlink($upload_dir . $path_image_penampil);
            }
            if ($path_image_logo_penampil && file_exists($upload_dir . $path_image_logo_penampil)) {
                unlink($upload_dir . $path_image_logo_penampil);
            }

            if (isset($_SESSION['id'])) {
                log_admin_activity($conn, $_SESSION['id'], 'delete', 'Menghapus penampil dengan ID: ' . $id);
            }
            header("location: performance_list.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt_delete);
    }
} else {
    header("location: performance_list.php");
    exit();
}
mysqli_close($conn);
?>