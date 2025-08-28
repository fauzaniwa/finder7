<?php
// Pastikan Anda memiliki session_start() di awal script jika belum ada
session_start();

// Ambil informasi admin dari session
$id = $_SESSION['admin_id'];
$name = $_SESSION['admin_name'];

// Ambil ID QnA dari parameter URL
$qna_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Koneksi ke database (sesuaikan dengan konfigurasi Anda)
require_once "koneksi.php";
$koneksi = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Query untuk menghapus data QnA
$query = "DELETE FROM qna WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);

// Bind parameter dan eksekusi statement untuk menghapus QnA
mysqli_stmt_bind_param($stmt, "i", $qna_id);
$success = mysqli_stmt_execute($stmt);

// Periksa apakah penghapusan berhasil
if ($success) {
    // Report informasi log
    $report = "Admin $name menghapus QnA dengan ID $qna_id.";
    $filter = "Delete QnA";

    // Query untuk log informasi
    $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
    $stmt_log = mysqli_prepare($koneksi, $sql_log);
    mysqli_stmt_bind_param($stmt_log, "iss", $id, $report, $filter);
    mysqli_stmt_execute($stmt_log);
    mysqli_stmt_close($stmt_log);

    echo "<script>alert('Data QnA Berhasil Dihapus.'); window.location.href='dataqna.php';</script>";
    // Redirect atau tampilkan pesan sukses lainnya
} else {
    echo "<script>alert('Gagal Menghapus Data..'); window.location.href='dataqna.php';</script>";
}

// Tutup statement dan koneksi
mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>
