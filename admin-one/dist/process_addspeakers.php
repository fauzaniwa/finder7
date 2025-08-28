<?php
session_start(); // Mulai session jika belum dimulai

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
    $nama_speaker = $_POST['namaspeakers'];
    $instansi = $_POST['instansi'];
    $deskripsi = $_POST['deskripsi'];
    $kontak = $_POST['contact'];
    $urutan = $_POST['urutan'];

    // Periksa apakah urutan sudah ada di database
    $query_check_urutan = "SELECT COUNT(*) FROM speakers WHERE urutan = ?";
    $stmt_check_urutan = mysqli_prepare($koneksi, $query_check_urutan);
    mysqli_stmt_bind_param($stmt_check_urutan, "i", $urutan);
    mysqli_stmt_execute($stmt_check_urutan);
    mysqli_stmt_bind_result($stmt_check_urutan, $count);
    mysqli_stmt_fetch($stmt_check_urutan);
    mysqli_stmt_close($stmt_check_urutan);

    if ($count > 0) {
        // Jika urutan sudah digunakan, beri pesan error dan hentikan eksekusi
        echo "<script>alert('Urutan $urutan sudah digunakan. Silakan pilih urutan yang lain.'); window.history.back();</script>";
        exit;
    }

    // Tentukan nama file untuk gambar speaker
    $foto_speaker = null;

    // Proses file upload jika ada file gambar yang diunggah
    if (!empty($_FILES['foto_speakers']['name'])) {
        $file_tmp = $_FILES['foto_speakers']['tmp_name'];
        $file_ext = pathinfo($_FILES['foto_speakers']['name'], PATHINFO_EXTENSION);
        $foto_speaker = $nama_speaker . "_" . time() . "." . $file_ext; // Nama file baru dengan timestamp
        move_uploaded_file($file_tmp, "../../img/speakers/" . $foto_speaker);
    }

    // Query untuk memasukkan data ke tabel speakers
    $query = "INSERT INTO speakers (nama_speaker, instansi, deskripsi, kontak, foto_speaker, urutan, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($koneksi, $query);

    // Periksa jika pernyataan berhasil disiapkan
    if ($stmt === false) {
        die('Query prepare error: ' . mysqli_error($koneksi));
    }

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt, "sssssi", $nama_speaker, $instansi, $deskripsi, $kontak, $foto_speaker, $urutan);

    // Eksekusi pernyataan
    if (mysqli_stmt_execute($stmt)) {
        // Catat informasi penambahan speaker jika session admin sudah ada
        if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
            $admin_id = $_SESSION['admin_id'];
            $admin_name = $_SESSION['admin_name'];
            $report = "Admin $admin_name menambahkan speaker baru: $nama_speaker.";
            $filter = "Penambahan Speaker";
            $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
            $stmt_log = mysqli_prepare($koneksi, $sql_log);
            if ($stmt_log === false) {
                die('Query prepare error: ' . mysqli_error($koneksi));
            }
            mysqli_stmt_bind_param($stmt_log, "iss", $admin_id, $report, $filter);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        echo "<script>alert('Data Speaker Berhasil Ditambahkan.'); window.location.href='speakers.php';</script>";
    } else {
        echo "<script>alert('Data Speaker Gagal Ditambahkan.'); window.location.href='speakers.php';</script>";
    }

    // Tutup pernyataan dan koneksi
    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>
