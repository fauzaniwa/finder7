<?php
// Pastikan Anda memiliki session_start() di awal script jika belum ada
session_start();

// Ambil informasi admin dari session
$id = $_SESSION['admin_id'];
$name = $_SESSION['admin_name'];

// Ambil data dari form
$topik = $_POST['topik'];
$jawaban = $_POST['jawaban'];

// Koneksi ke database (sesuaikan dengan konfigurasi Anda)
require_once "koneksi.php";
$koneksi = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Query untuk insert data QnA
$query = "INSERT INTO qna (topik, jawaban, created_at, status) VALUES (?, ?, NOW(), 'active')";
$stmt = mysqli_prepare($koneksi, $query);

// Bind parameter dan eksekusi statement untuk insert QnA
mysqli_stmt_bind_param($stmt, "ss", $topik, $jawaban);
$success = mysqli_stmt_execute($stmt);

// Periksa apakah insert berhasil
if ($success) {
    // Report informasi log
    $report = "Admin $name menambahkan QnA dengan topik $topik.";
    $filter = "Add QnA";

    // Query untuk log informasi
    $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
    $stmt_log = mysqli_prepare($koneksi, $sql_log);
    mysqli_stmt_bind_param($stmt_log, "iss", $id, $report, $filter);
    mysqli_stmt_execute($stmt_log);
    mysqli_stmt_close($stmt_log);

    echo "<script>alert('Data QnA Berhasil Ditambahkan.'); window.location.href='dataqna.php';</script>";
    // Redirect atau tampilkan pesan sukses lainnya
} else {
    echo "<script>alert('Gagal Menambahkan Data..'); window.location.href='dataqna.php';</script>";
}

// Tutup statement dan koneksi
mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>
