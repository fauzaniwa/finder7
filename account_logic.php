<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Periksa apakah session user ada dan tidak kosong
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'admin-one/dist/koneksi.php';
// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Persiapkan query untuk mengambil data user berdasarkan user_id
$query_user = "SELECT nama, tgl_lahir, no_hp, instansi, email, kode_account FROM user WHERE id_user = ?";

// Persiapkan statement untuk data user
$stmt_user = mysqli_prepare($koneksi, $query_user);
if (!$stmt_user) {
    // Handle error jika prepare statement gagal
    die('Prepare statement user failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);

// Ambil hasil query data user
$result_user = mysqli_stmt_get_result($stmt_user);

// Periksa apakah data user ditemukan
if ($row_user = mysqli_fetch_assoc($result_user)) {
    // Simpan data user ke dalam session atau langsung gunakan
    $_SESSION['user_data'] = $row_user;
} else {
    // Handle case jika user_id tidak ditemukan
    header("Location: login.php");
    exit();
}
mysqli_stmt_close($stmt_user);

// Persiapan query untuk mengambil data tiket dan event dari user
$query_registrants = "SELECT
    t.tiket_code,
    t.nama_lengkap,
    t.is_verified,
    e.judul_event AS judul_event,
    e.jadwal_event AS tanggal,
    e.waktu_event AS waktu,
    e.slug AS slug
FROM
    tiket t
JOIN
    event e ON t.id_event = e.id_event
WHERE
    t.id_user = ?
ORDER BY
    t.created_tiket DESC";

$stmt_registrants = mysqli_prepare($koneksi, $query_registrants);
if (!$stmt_registrants) {
    die('Prepare statement registrants failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_registrants, "i", $user_id);
mysqli_stmt_execute($stmt_registrants);
$result_registrants = mysqli_stmt_get_result($stmt_registrants);
$registrants = mysqli_fetch_all($result_registrants, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_registrants);

mysqli_close($koneksi);