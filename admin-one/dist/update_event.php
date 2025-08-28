<?php
// Mulai sesi dan pastikan pengguna sudah login (opsional)
session_start();

// Koneksi ke database
require_once "koneksi.php";
$koneksi = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil data dari POST
$id_event = isset($_POST['id_event']) ? intval($_POST['id_event']) : 0;
$event_status = isset($_POST['event_status']) ? intval($_POST['event_status']) : null;
$show_event = isset($_POST['show_event']) ? intval($_POST['show_event']) : null;
$urutan_show = isset($_POST['urutan_show']) ? intval($_POST['urutan_show']) : null; // Ambil nilai urutan_show dari form

// Pastikan id_event valid
if ($id_event === 0 || $event_status === null || $show_event === null || $urutan_show === null) {
    die("Data tidak valid.");
}

// Validasi nilai event_status
if (!in_array($event_status, [0, 1, 2])) {
    die("Status event tidak valid.");
}

// Query untuk mengupdate event status, show_event, dan urutan_show
$query_update = "UPDATE event SET event_status = ?, show_event = ?, urutan_show = ? WHERE id_event = ?";
$stmt_update = mysqli_prepare($koneksi, $query_update);

if ($stmt_update) {
    // Bind parameter
    mysqli_stmt_bind_param($stmt_update, "iiii", $event_status, $show_event, $urutan_show, $id_event);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt_update)) {
        // Redirect kembali ke halaman detail event dengan pesan sukses
        header("Location: details_event.php?id_event=$id_event&message=update_success");
    } else {
        // Redirect dengan pesan error jika gagal
        header("Location: details_event.php?id_event=$id_event&message=update_failed");
    }

    // Tutup statement
    mysqli_stmt_close($stmt_update);
} else {
    die("Gagal menyiapkan query.");
}

// Tutup koneksi
mysqli_close($koneksi);
?>
