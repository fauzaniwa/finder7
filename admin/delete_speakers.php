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
    $upload_dir = '../img/speakers/';

    // Dapatkan nama foto sebelum menghapus record
    $sql_select = "SELECT nama_speaker, foto_speaker FROM speakers WHERE id_speaker = ?";
    if ($stmt_select = mysqli_prepare($conn, $sql_select)) {
        mysqli_stmt_bind_param($stmt_select, "i", $id_speaker);
        if (mysqli_stmt_execute($stmt_select)) {
            $result_select = mysqli_stmt_get_result($stmt_select);
            $speaker_to_delete = mysqli_fetch_assoc($result_select);
            
            if ($speaker_to_delete) {
                $nama_speaker_to_delete = $speaker_to_delete['nama_speaker'];
                $foto_speaker_to_delete = $speaker_to_delete['foto_speaker'];
                
                // **Perbaikan:** Hapus foto dari server jika ada dan path valid
                // Kita perlu membuat ulang path lengkap untuk memeriksa keberadaan file
                if (!empty($foto_speaker_to_delete) && file_exists($upload_dir . $foto_speaker_to_delete)) {
                    unlink($upload_dir . $foto_speaker_to_delete);
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
            // **TAMBAHAN:** Panggil fungsi log aktivitas setelah penghapusan berhasil
            if (isset($_SESSION['id'])) {
                log_admin_activity($conn, $_SESSION['id'], 'delete', 'Menghapus speaker: ' . ($nama_speaker_to_delete ?? 'ID ' . $id_speaker));
            }
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