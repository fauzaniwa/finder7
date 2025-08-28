<?php
require_once "koneksi.php"; // Pastikan file koneksi sudah ada

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil id_user dan id_event dari form
    $id_user = intval($_POST['id_user']);
    $id_event = intval($_POST['id_event']);

    // Pastikan id_user dan id_event valid
    if ($id_user > 0 && $id_event > 0) {
        // Query untuk menghapus data dari tabel tiket
        $query = "DELETE FROM tiket WHERE id_user = ? AND id_event = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ii", $id_user, $id_event);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            // Jika berhasil menghapus, redirect kembali ke halaman dataevent.php
            echo "<script>alert('Data Tiket Berhasil Dihapus.'); window.location.href='details_event.php?id_event=$id_event';</script>";
        } else {
            echo "<script>alert('Gagal Menghapus Data Tiket.'); window.location.href='details_event.php?id_event=$id_event';</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Data tidak valid.'); window.location.href='details_event.php?id_event=$id_event';</script>";
    }
}

mysqli_close($koneksi);
?>
