<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Pastikan hanya admin master yang bisa menghapus
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    header("location: login.php");
    exit;
}

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id_to_delete = trim($_GET["id"]);

    // Cek apakah admin mencoba menghapus dirinya sendiri
    if ($_SESSION['id'] == $id_to_delete) {
        // Redirect dengan pesan error jika diperlukan
        header("location: speakers_list.php?error=cannot_delete_self");
        exit;
    }

    // Ambil nama admin yang akan dihapus untuk log
    $sql_select = "SELECT name FROM admin WHERE id = ?";
    if ($stmt_select = mysqli_prepare($conn, $sql_select)) {
        mysqli_stmt_bind_param($stmt_select, "i", $id_to_delete);
        mysqli_stmt_execute($stmt_select);
        $result_select = mysqli_stmt_get_result($stmt_select);
        $admin_to_delete = mysqli_fetch_assoc($result_select);
        $admin_name = $admin_to_delete['name'] ?? 'ID ' . $id_to_delete;
        mysqli_stmt_close($stmt_select);
    } else {
        $admin_name = 'ID ' . $id_to_delete;
    }

    // Persiapkan query untuk menghapus data
    $sql_delete = "DELETE FROM admin WHERE id = ?";
    if ($stmt_delete = mysqli_prepare($conn, $sql_delete)) {
        mysqli_stmt_bind_param($stmt_delete, "i", $id_to_delete);
        
        if (mysqli_stmt_execute($stmt_delete)) {
            log_admin_activity($conn, $_SESSION['id'], 'delete', 'Menghapus admin: ' . $admin_name);
            header("location: list-admin.php?success=deleted");
            exit;
        } else {
            // Log error
            log_admin_activity($conn, $_SESSION['id'], 'error', 'Gagal menghapus admin: ' . $admin_name . ' (' . mysqli_stmt_error($stmt_delete) . ')');
            header("location: list-admin.php?error=delete_failed");
            exit;
        }
        mysqli_stmt_close($stmt_delete);
    }
} else {
    header("location: list-admin.php");
    exit;
}

mysqli_close($conn);
?>