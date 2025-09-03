<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

// Periksa izin. Hanya master dan seminar/workshop admin yang bisa melakukan ini.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["role"], ['master', 'seminar', 'workshop'])) {
    echo json_encode(['success' => false, 'error' => 'Anda tidak memiliki izin untuk melakukan aksi ini.']);
    exit;
}

// Hanya izinkan permintaan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Metode permintaan tidak valid.']);
    exit;
}

// Ambil data dari JSON
$data = json_decode(file_get_contents('php://input'), true);
$id_tiket = isset($data['id']) ? intval($data['id']) : 0;
$new_status = isset($data['status']) ? intval($data['status']) : 0;

if ($id_tiket <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID tiket tidak valid.']);
    exit;
}

// Periksa apakah admin memiliki izin untuk event ini
$is_authorized = false;
if ($_SESSION['role'] === 'master') {
    $is_authorized = true;
} else {
    $sql_check_event = "SELECT e.kategori FROM tiket AS t JOIN event AS e ON t.id_event = e.id_event WHERE t.id_tiket = ?";
    if ($stmt_check = mysqli_prepare($conn, $sql_check_event)) {
        mysqli_stmt_bind_param($stmt_check, "i", $id_tiket);
        mysqli_stmt_execute($stmt_check);
        $result = mysqli_stmt_get_result($stmt_check);
        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['kategori'] === $_SESSION['role']) {
                $is_authorized = true;
            }
        }
        mysqli_stmt_close($stmt_check);
    }
}

if (!$is_authorized) {
    echo json_encode(['success' => false, 'error' => 'Anda tidak memiliki izin untuk memperbarui status verifikasi event ini.']);
    exit;
}

// Tentukan status pembayaran baru berdasarkan status verifikasi
$new_payment_status = ($new_status == 1) ? 'paid' : 'unpaid';

// Lakukan pembaruan
$sql_update = "UPDATE `tiket` SET `is_verified` = ?, `payment_status` = ? WHERE `id_tiket` = ?";
if ($stmt = mysqli_prepare($conn, $sql_update)) {
    mysqli_stmt_bind_param($stmt, "isi", $new_status, $new_payment_status, $id_tiket);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
        // Log aktivitas
        $log_status = $new_status ? 'Verified' : 'Unverified';
        log_admin_activity($conn, $_SESSION['id'], 'update', 'Memperbarui status verifikasi tiket ID: ' . $id_tiket . ' menjadi ' . $log_status . ' dan status pembayaran menjadi ' . $new_payment_status . '.');
    } else {
        echo json_encode(['success' => false, 'error' => 'Gagal memperbarui data: ' . mysqli_stmt_error($stmt)]);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'error' => 'Terjadi kesalahan pada database: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>