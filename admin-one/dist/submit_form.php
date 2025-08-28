<?php
// Sambungkan ke database
require_once "koneksi.php"; // Sesuaikan dengan lokasi file koneksi

// Mulai sesi jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Periksa apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data input dari form
    $categories = $_POST['name'];
    $admin_id = $_SESSION['admin_id'];
    $admin_name = $_SESSION['admin_name'];
    
    // Siapkan pernyataan SQL untuk memasukkan data ke jenis_karya
    $stmt = $koneksi->prepare("INSERT INTO jenis_karya (jenis) VALUES (?)");
    
    // Siapkan pernyataan SQL untuk mencatat informasi ke tabel informasi
    $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
    $stmt_log = $koneksi->prepare($sql_log);
    $filter = "Tambah Kategori";
    
    // Loop melalui setiap input kategori
    foreach ($categories as $category) {
        // Bind parameter dan eksekusi pernyataan untuk jenis_karya
        $stmt->bind_param("s", $category);
        $stmt->execute();
        
        // Buat laporan dan bind parameter untuk informasi
        $report = "Admin $admin_name menambahkan kategori: $category";
        $stmt_log->bind_param("iss", $admin_id, $report, $filter);
        $stmt_log->execute();
    }
    
    // Tutup pernyataan dan koneksi
    $stmt->close();
    $stmt_log->close();
    $koneksi->close();
    
    // Redirect kembali ke halaman asal (misalnya index.php)
    header("Location: addkarya.php"); // Ganti sesuai dengan halaman tujuan
    exit();
}
?>
