<?php
session_start(); // Mulai session jika belum dimulai

// Pastikan ID speaker yang akan dihapus diberikan melalui parameter GET
if (isset($_GET['id'])) {
    // Sambungkan ke database
    require_once "koneksi.php";
    $koneksi = mysqli_connect($host, $username, $password, $database);

    // Periksa koneksi
    if (mysqli_connect_errno()) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Ambil ID speaker dari parameter GET
    $id_speaker = $_GET['id'];

    // Inisialisasi variabel untuk nama speaker dan foto speaker
    $nama_speaker = '';
    $foto_speaker = '';

    // Query untuk mengambil nama speaker dan foto berdasarkan ID speaker
    $query_select = "SELECT nama_speaker, foto_speaker FROM speakers WHERE id_speaker = ?";
    $stmt_select = mysqli_prepare($koneksi, $query_select);

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt_select, "i", $id_speaker);

    // Eksekusi pernyataan
    mysqli_stmt_execute($stmt_select);

    // Ambil hasil query
    mysqli_stmt_bind_result($stmt_select, $nama_speaker, $foto_speaker);
    mysqli_stmt_fetch($stmt_select);

    // Tutup pernyataan
    mysqli_stmt_close($stmt_select);

    // Query untuk menghapus data speaker dari tabel speakers berdasarkan ID
    $query_delete_speaker = "DELETE FROM speakers WHERE id_speaker = ?";
    $stmt_delete_speaker = mysqli_prepare($koneksi, $query_delete_speaker);

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt_delete_speaker, "i", $id_speaker);

    // Eksekusi pernyataan
    if (mysqli_stmt_execute($stmt_delete_speaker)) {
        // Hapus file gambar jika ada
        if ($foto_speaker) {
            $file_path = "../../img/speakers/" . $foto_speaker;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Catat informasi penghapusan speaker jika session admin sudah ada
        if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
            $admin_id = $_SESSION['admin_id'];
            $admin_name = $_SESSION['admin_name'];
            $report = "Admin $admin_name menghapus speaker dengan nama: $nama_speaker.";
            $filter = "Penghapusan Speaker";
            $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
            $stmt_log = mysqli_prepare($koneksi, $sql_log);
            if ($stmt_log === false) {
                die('Query prepare error: ' . mysqli_error($koneksi));
            }
            mysqli_stmt_bind_param($stmt_log, "iss", $admin_id, $report, $filter);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        echo "<script>alert('Data Speaker Berhasil Dihapus.'); window.location.href='speakers.php';</script>";
    } else {
        echo "<script>alert('Data Speaker Gagal Dihapus.'); window.location.href='speakers.php';</script>";
    }

    // Tutup pernyataan dan koneksi
    mysqli_stmt_close($stmt_delete_speaker);
    mysqli_close($koneksi);
} else {
    echo "ID speaker tidak diberikan.";
}
?>
