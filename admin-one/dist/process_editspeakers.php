<?php
session_start(); // Mulai session jika belum dimulai
// Tampilkan semua error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Pastikan request POST disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addspeakers'])) {
    // Sambungkan ke database
    require_once "koneksi.php";
    $koneksi = mysqli_connect($host, $username, $password, $database);

    // Periksa koneksi
    if (mysqli_connect_errno()) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Tangkap data dari form
    $id_speaker = $_POST['id_speaker']; // Pastikan ada input id speaker untuk edit
    $nama_speaker = $_POST['namaspeakers'];
    $instansi = $_POST['instansi'];
    $deskripsi = $_POST['deskripsi'];
    $kontak = $_POST['contact'];
    $urutan = $_POST['urutan'];

    // Periksa apakah urutan sudah ada di database selain dari speaker ini
    $query_check_urutan = "SELECT COUNT(*) FROM speakers WHERE urutan = ? AND id_speaker != ?";
    $stmt_check_urutan = mysqli_prepare($koneksi, $query_check_urutan);
    mysqli_stmt_bind_param($stmt_check_urutan, "ii", $urutan, $id_speaker);
    mysqli_stmt_execute($stmt_check_urutan);
    mysqli_stmt_bind_result($stmt_check_urutan, $count);
    mysqli_stmt_fetch($stmt_check_urutan);
    mysqli_stmt_close($stmt_check_urutan);

    if ($count > 0) {
        // Jika urutan sudah digunakan oleh speaker lain, beri pesan error dan hentikan eksekusi
        echo "<script>alert('Urutan $urutan sudah digunakan. Silakan pilih urutan yang lain.'); window.history.back();</script>";
        exit;
    }

    // Dapatkan informasi speaker saat ini dari database (termasuk foto yang ada)
    $query_speaker = "SELECT foto_speaker FROM speakers WHERE id_speaker = ?";
    $stmt_speaker = mysqli_prepare($koneksi, $query_speaker);
    mysqli_stmt_bind_param($stmt_speaker, "i", $id_speaker);
    mysqli_stmt_execute($stmt_speaker);
    mysqli_stmt_bind_result($stmt_speaker, $foto_speaker_lama);
    mysqli_stmt_fetch($stmt_speaker);
    mysqli_stmt_close($stmt_speaker);

    // Tentukan nama file untuk gambar speaker
    $foto_speaker_baru = $foto_speaker_lama; // Default-nya tetap gambar lama

    // Proses file upload jika ada file gambar yang diunggah
    if (!empty($_FILES['foto_speakers']['name'])) {
        // Hapus file lama jika ada
        if (!empty($foto_speaker_lama) && file_exists("../../img/speakers/" . $foto_speaker_lama)) {
            unlink("../../img/speakers/" . $foto_speaker_lama);
        }

        // Simpan file gambar baru
        $file_tmp = $_FILES['foto_speakers']['tmp_name'];
        $file_ext = pathinfo($_FILES['foto_speakers']['name'], PATHINFO_EXTENSION);
        $foto_speaker_baru = $nama_speaker . "_" . time() . "." . $file_ext; // Nama file baru dengan timestamp
        move_uploaded_file($file_tmp, "../../img/speakers/" . $foto_speaker_baru);
    }

    // Query untuk memperbarui data speaker
    $query = "UPDATE speakers SET nama_speaker = ?, instansi = ?, deskripsi = ?, kontak = ?, foto_speaker = ?, urutan = ?, updated_at = NOW() WHERE id_speaker = ?";
    $stmt = mysqli_prepare($koneksi, $query);

    // Periksa jika pernyataan berhasil disiapkan
    if ($stmt === false) {
        die('Query prepare error: ' . mysqli_error($koneksi));
    }

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt, "ssssssi", $nama_speaker, $instansi, $deskripsi, $kontak, $foto_speaker_baru, $urutan, $id_speaker);

    // Eksekusi pernyataan
    if (mysqli_stmt_execute($stmt)) {
        // Catat informasi edit speaker jika session admin sudah ada
        if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
            $admin_id = $_SESSION['admin_id'];
            $admin_name = $_SESSION['admin_name'];
            $report = "Admin $admin_name mengedit speaker: $nama_speaker.";
            $filter = "Edit Speaker";
            $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
            $stmt_log = mysqli_prepare($koneksi, $sql_log);
            if ($stmt_log === false) {
                die('Query prepare error: ' . mysqli_error($koneksi));
            }
            mysqli_stmt_bind_param($stmt_log, "iss", $admin_id, $report, $filter);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        echo "<script>alert('Data Speaker Berhasil Diedit.'); window.location.href='speakers.php';</script>";
    } else {
        echo "<script>alert('Data Speaker Gagal Diedit.'); window.location.href='speakers.php';</script>";
    }

    // Tutup pernyataan dan koneksi
    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>
