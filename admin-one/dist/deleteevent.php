<?php
// Ambil id_event dari query string
$id_event = isset($_GET['id']) ? $_GET['id'] : die('ID event tidak ditemukan.');

// Lakukan koneksi ke database
require_once "koneksi.php";
$koneksi = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Query untuk mendapatkan informasi event sebelum dihapus
$query_info = "SELECT judul_event FROM event WHERE id_event = ?";
$stmt_info = mysqli_prepare($koneksi, $query_info);
mysqli_stmt_bind_param($stmt_info, "i", $id_event);
mysqli_stmt_execute($stmt_info);
mysqli_stmt_bind_result($stmt_info, $judul_event);
mysqli_stmt_fetch($stmt_info);
mysqli_stmt_close($stmt_info);

// Hapus event
$query_delete = "DELETE FROM event WHERE id_event = ?";
$stmt_delete = mysqli_prepare($koneksi, $query_delete);
mysqli_stmt_bind_param($stmt_delete, "i", $id_event);
mysqli_stmt_execute($stmt_delete);

// Periksa apakah penghapusan berhasil
if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
    // Ambil informasi admin dari session
    session_start();
    $id = $_SESSION['admin_id'];
    $name = $_SESSION['admin_name'];

    // Report informasi log
    $report = "Admin $name menghapus Event $judul_event.";
    $filter = "Delete Event";

    // Query untuk log informasi
    $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
    $stmt_log = mysqli_prepare($koneksi, $sql_log);
    mysqli_stmt_bind_param($stmt_log, "iss", $id, $report, $filter);
    mysqli_stmt_execute($stmt_log);
    mysqli_stmt_close($stmt_log);

    echo "<script>alert('Data event berhasil dihapus.'); window.location.href = 'dataevent.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data event.'); window.location.href = 'dataevent.php';</script>";
}

// Tutup statement dan koneksi
mysqli_stmt_close($stmt_delete);
mysqli_close($koneksi);
?>
