<?php
session_start(); // Mulai session jika belum dimulai

// Pastikan request POST disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addkarya'])) {
    // Sambungkan ke database
    require_once "koneksi.php";
    $koneksi = mysqli_connect($host, $username, $password, $database);

    // Periksa koneksi
    if (mysqli_connect_errno()) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Tangkap data dari form
    $judul_karya = $_POST['judul_karya'];
    $nama_karya = $_POST['nama_karya'];
    $deskripsi = $_POST['deskripsi'];
    $id_jenis = $_POST['jenis_karya']; // Ambil ID jenis dari dropdown
    $instagram = $_POST['instagram'];
    $nim = $_POST['nim']; // Ambil NIM dari form

    // Tentukan nama file untuk gambar karya
    $file_karya = null;

    if (!empty($_FILES['file_karya']['name'])) {
        $file_tmp = $_FILES['file_karya']['tmp_name'];
        $file_ext = pathinfo($_FILES['file_karya']['name'], PATHINFO_EXTENSION);
        $file_karya = time() . "_" . uniqid() . "." . $file_ext; // Generate unique filename
        move_uploaded_file($file_tmp, "../../img/karya/" . $file_karya);
    }

    // Gunakan nilai optional_karya jika file tidak diupload
    $optional_karya = !empty($_POST['optional_karya']) ? $_POST['optional_karya'] : null;

    // Query untuk memasukkan data ke tabel karya
    $query = "INSERT INTO karya (judul_karya, nama_karya, deskripsi, id_jenis, instagram, pict_karya, optional_karya, NIM, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($koneksi, $query);

    // Periksa jika pernyataan berhasil disiapkan
    if ($stmt === false) {
        die('Query prepare error: ' . mysqli_error($koneksi));
    }

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt, "sssisssi", $judul_karya, $nama_karya, $deskripsi, $id_jenis, $instagram, $file_karya, $optional_karya, $nim);

    // Eksekusi pernyataan
    if (mysqli_stmt_execute($stmt)) {
        // Catat informasi penambahan karya jika session admin sudah ada
        if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
            $admin_id = $_SESSION['admin_id'];
            $admin_name = $_SESSION['admin_name'];
            $report = "Admin $admin_name menambahkan karya baru: $judul_karya.";
            $filter = "Penambahan Karya";
            $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
            $stmt_log = mysqli_prepare($koneksi, $sql_log);
            if ($stmt_log === false) {
                die('Query prepare error: ' . mysqli_error($koneksi));
            }
            mysqli_stmt_bind_param($stmt_log, "iss", $admin_id, $report, $filter);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        echo "<script>alert('Data Karya Berhasil Ditambahkan.'); window.location.href='datapameran.php';</script>";
    } else {
        echo "<script>alert('Data Karya Gagal Ditambahkan.'); window.location.href='datapameran.php';</script>";
    }

    // Tutup pernyataan dan koneksi
    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>
