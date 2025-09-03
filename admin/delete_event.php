<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Periksa apakah pengguna sudah login dan memiliki peran 'master' untuk menghapus event
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    $_SESSION['error_message'] = "Anda tidak memiliki izin untuk menghapus event.";
    header("location: event.php");
    exit;
}

// Periksa apakah ID event valid ada di URL
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    // Ambil ID dari URL dan sanitasi
    $id_to_delete = intval(trim($_GET['id']));

    // Mulai transaksi untuk memastikan semua operasi berhasil atau tidak sama sekali
    mysqli_begin_transaction($conn);
    $delete_success = true;

    // Langkah 1: Hapus data speakers terkait dari tabel `event_speakers`
    $sql_delete_speakers = "DELETE FROM `event_speakers` WHERE `id_event` = ?";
    if ($stmt_delete_speakers = mysqli_prepare($conn, $sql_delete_speakers)) {
        mysqli_stmt_bind_param($stmt_delete_speakers, "i", $id_to_delete);
        if (!mysqli_stmt_execute($stmt_delete_speakers)) {
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus speakers: " . mysqli_stmt_error($stmt_delete_speakers);
            $delete_success = false;
        }
        mysqli_stmt_close($stmt_delete_speakers);
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat mempersiapkan statement delete speakers: " . mysqli_error($conn);
        $delete_success = false;
    }

    // Langkah 2: Jika berhasil, lanjutkan untuk menghapus event dari tabel `event`
    if ($delete_success) {
        // Dapatkan judul event untuk log
        $sql_get_title = "SELECT `judul_event` FROM `event` WHERE `id_event` = ?";
        $title = "Unknown Event";
        if ($stmt_get_title = mysqli_prepare($conn, $sql_get_title)) {
            mysqli_stmt_bind_param($stmt_get_title, "i", $id_to_delete);
            mysqli_stmt_execute($stmt_get_title);
            $result = mysqli_stmt_get_result($stmt_get_title);
            if ($row = mysqli_fetch_assoc($result)) {
                $title = $row['judul_event'];
            }
            mysqli_stmt_close($stmt_get_title);
        }

        // Hapus event
        $sql_delete_event = "DELETE FROM `event` WHERE `id_event` = ?";
        if ($stmt_delete_event = mysqli_prepare($conn, $sql_delete_event)) {
            mysqli_stmt_bind_param($stmt_delete_event, "i", $id_to_delete);
            if (!mysqli_stmt_execute($stmt_delete_event)) {
                $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus event: " . mysqli_stmt_error($stmt_delete_event);
                $delete_success = false;
            }
            mysqli_stmt_close($stmt_delete_event);
        } else {
            $_SESSION['error_message'] = "Terjadi kesalahan saat mempersiapkan statement delete event: " . mysqli_error($conn);
            $delete_success = false;
        }
    }

    // Komit atau rollback transaksi
    if ($delete_success) {
        mysqli_commit($conn);
        $_SESSION['success_message'] = "Event '" . htmlspecialchars($title) . "' berhasil dihapus!";
        log_admin_activity($conn, $_SESSION['id'], 'delete', 'Menghapus event: ' . $title . ' (ID: ' . $id_to_delete . ')');
    } else {
        mysqli_rollback($conn);
    }

    mysqli_close($conn);

    // Alihkan kembali ke halaman daftar event
    header("location: event.php");
    exit;

} else {
    // Jika ID tidak ada, berikan pesan error dan alihkan
    $_SESSION['error_message'] = "ID event tidak valid.";
    header("location: event.php");
    exit;
}
?>