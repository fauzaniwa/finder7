<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Pastikan hanya admin master yang bisa menghapus
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    header("location: login.php");
    exit;
}

// Pastikan ID speaker tersedia di URL
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id_speaker = trim($_GET["id"]);

    // Dapatkan path foto sebelum menghapus record
    $sql_select = "SELECT foto_speaker FROM speakers WHERE id_speaker = ?";
    if ($stmt_select = mysqli_prepare($conn, $sql_select)) {
        mysqli_stmt_bind_param($stmt_select, "i", $id_speaker);
        if (mysqli_stmt_execute($stmt_select)) {
            mysqli_stmt_store_result($stmt_select);
            if (mysqli_stmt_num_rows($stmt_select) == 1) {
                mysqli_stmt_bind_result($stmt_select, $foto_speaker);
                mysqli_stmt_fetch($stmt_select);
                
                // Hapus foto dari server jika ada dan path valid
                // Periksa apakah path dimulai dengan ../img/speakers/
                if ($foto_speaker && strpos($foto_speaker, '../img/speakers/') === 0 && file_exists($foto_speaker)) {
                    unlink($foto_speaker);
                }
            }
        }
        mysqli_stmt_close($stmt_select);
    }

    // Persiapkan query untuk menghapus data
    $sql_delete = "DELETE FROM speakers WHERE id_speaker = ?";
    if ($stmt_delete = mysqli_prepare($conn, $sql_delete)) {
        mysqli_stmt_bind_param($stmt_delete, "i", $id_speaker);
        
        if (mysqli_stmt_execute($stmt_delete)) {
            header("location: speakers_list.php");
            exit;
        } else {
            echo "Error: Terjadi kesalahan saat menghapus data.";
        }
        mysqli_stmt_close($stmt_delete);
    }
} else {
    // Redirect jika ID tidak valid
    header("location: speakers_list.php");
    exit;
}

mysqli_close($conn);
?>