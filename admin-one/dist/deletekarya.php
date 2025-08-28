<?php
session_start(); // Mulai session jika belum dimulai

// Pastikan ID karya yang akan dihapus diberikan melalui parameter GET
if (isset($_GET['id'])) {
    // Sambungkan ke database
    require_once "koneksi.php";
    $koneksi = mysqli_connect($host, $username, $password, $database);

    // Periksa koneksi
    if (mysqli_connect_errno()) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Ambil ID karya dari parameter GET
    $id_karya = $_GET['id'];

    // Inisialisasi variabel untuk judul karya
    $judul_karya = '';

    // Query untuk mengambil judul karya berdasarkan ID karya
    $query_select_judul = "SELECT judul_karya FROM karya WHERE id_karya = ?";
    $stmt_select_judul = mysqli_prepare($koneksi, $query_select_judul);

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt_select_judul, "i", $id_karya);

    // Eksekusi pernyataan
    mysqli_stmt_execute($stmt_select_judul);

    // Ambil hasil query
    mysqli_stmt_bind_result($stmt_select_judul, $judul_karya);
    mysqli_stmt_fetch($stmt_select_judul);

    // Tutup pernyataan
    mysqli_stmt_close($stmt_select_judul);

    // Hapus file gambar karya jika ada
    $file_karya = '';

    // Query untuk mengambil nama file gambar karya berdasarkan ID karya
    $query_select = "SELECT pict_karya FROM karya WHERE id_karya = ?";
    $stmt_select = mysqli_prepare($koneksi, $query_select);

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt_select, "i", $id_karya);

    // Eksekusi pernyataan
    mysqli_stmt_execute($stmt_select);

    // Ambil hasil query
    mysqli_stmt_bind_result($stmt_select, $file_karya);
    mysqli_stmt_fetch($stmt_select);

    // Tutup pernyataan
    mysqli_stmt_close($stmt_select);

    // Hapus file gambar karya jika ada
    if ($file_karya) {
        $file_path = "../../img/karya/" . $file_karya;
        if (file_exists($file_path)) {
            unlink($file_path); // Hapus file dari direktori
        }
    }

    // Query untuk menghapus data dari tabel likes berdasarkan id_karya
    $query_delete_likes = "DELETE FROM likes WHERE id_karya = ?";
    $stmt_delete_likes = mysqli_prepare($koneksi, $query_delete_likes);

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt_delete_likes, "i", $id_karya);

    // Eksekusi pernyataan
    mysqli_stmt_execute($stmt_delete_likes);

    // Tutup pernyataan
    mysqli_stmt_close($stmt_delete_likes);

    // Query untuk menghapus data dari tabel komentar berdasarkan id_karya
    $query_delete_komentar = "DELETE FROM komentar WHERE id_karya = ?";
    $stmt_delete_komentar = mysqli_prepare($koneksi, $query_delete_komentar);

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt_delete_komentar, "i", $id_karya);

    // Eksekusi pernyataan
    mysqli_stmt_execute($stmt_delete_komentar);

    // Tutup pernyataan
    mysqli_stmt_close($stmt_delete_komentar);

    // Query untuk menghapus data karya dari tabel karya berdasarkan ID
    $query_delete_karya = "DELETE FROM karya WHERE id_karya = ?";
    $stmt_delete_karya = mysqli_prepare($koneksi, $query_delete_karya);

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt_delete_karya, "i", $id_karya);

    // Eksekusi pernyataan
    if (mysqli_stmt_execute($stmt_delete_karya)) {
        // Catat informasi penghapusan karya jika session admin sudah ada
        if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
            $admin_id = $_SESSION['admin_id'];
            $admin_name = $_SESSION['admin_name'];
            $report = "Admin $admin_name menghapus karya dengan judul: $judul_karya.";
            $filter = "Penghapusan Karya";
            $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
            $stmt_log = mysqli_prepare($koneksi, $sql_log);
            if ($stmt_log === false) {
                die('Query prepare error: ' . mysqli_error($koneksi));
            }
            mysqli_stmt_bind_param($stmt_log, "iss", $admin_id, $report, $filter);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        echo "<script>alert('Data Karya Berhasil DiHapus.'); window.location.href='datapameran.php';</script>";
    } else {
        echo "<script>alert('Data Karya Gagal DiHapus.'); window.location.href='datapameran.php';</script>";
    }

    // Tutup pernyataan dan koneksi
    mysqli_stmt_close($stmt_delete_karya);
    mysqli_close($koneksi);
} else {
    echo "ID karya tidak diberikan.";
}
?>
