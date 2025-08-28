<?php
// Koneksi ke database
require_once "koneksi.php";
$koneksi = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Periksa apakah request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_event = intval($_POST['id_event']);
    $judul_event = mysqli_real_escape_string($koneksi, $_POST['judul_event']);
    $speakers_event = mysqli_real_escape_string($koneksi, $_POST['speakers_event']);
    $jadwal_event = mysqli_real_escape_string($koneksi, $_POST['jadwal_event']);
    $waktu_event = mysqli_real_escape_string($koneksi, $_POST['waktu_event']);
    $kuota = intval($_POST['kuota']); // Menangkap nilai kuota
    $lokasi_event = mysqli_real_escape_string($koneksi, $_POST['lokasi_event']);
    $deskripsi_event = mysqli_real_escape_string($koneksi, $_POST['deskripsi_event']);
    $link_grup = mysqli_real_escape_string($koneksi, $_POST['link_grup']);
    
    // Asumsi data admin yang sedang login tersedia, misalnya di session
    session_start();
    $admin_id = $_SESSION['admin_id']; // Ganti sesuai metode autentikasi admin
    $admin_name = $_SESSION['admin_name']; // Nama admin yang melakukan perubahan

    // Penanganan file upload untuk thumbnail
    $upload_directory = '../../img/event/';
    $thumbnail_event = '';

    // Query untuk mendapatkan thumbnail lama jika ada
    $query_thumbnail = "SELECT thumbnail_event FROM event WHERE id_event = ?";
    $stmt_thumbnail = mysqli_prepare($koneksi, $query_thumbnail);
    mysqli_stmt_bind_param($stmt_thumbnail, "i", $id_event);
    mysqli_stmt_execute($stmt_thumbnail);
    $result_thumbnail = mysqli_stmt_get_result($stmt_thumbnail);
    $row_thumbnail = mysqli_fetch_assoc($result_thumbnail);
    $old_thumbnail = $row_thumbnail['thumbnail_event'];
    mysqli_stmt_close($stmt_thumbnail);

    // Jika ada file yang diunggah
    if (isset($_FILES['thumbnail_event']) && $_FILES['thumbnail_event']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['thumbnail_event']['tmp_name'];
        $file_name = $_FILES['thumbnail_event']['name'];

        // Pisahkan nama file dan ekstensi
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_base_name = pathinfo($file_name, PATHINFO_FILENAME);

        // Format nama file baru: base name + timestamp + extension
        $new_file_name = $file_base_name . '_' . time() . '.' . $file_extension;

        // Path lengkap untuk penyimpanan
        $file_destination = $upload_directory . $new_file_name;

        // Hapus file lama jika ada
        if ($old_thumbnail && file_exists($upload_directory . $old_thumbnail)) {
            unlink($upload_directory . $old_thumbnail);
        }

        // Pindahkan file ke folder target
        if (move_uploaded_file($file_tmp, $file_destination)) {
            $thumbnail_event = $new_file_name; // Simpan nama file yang diunggah ke database
        } else {
            die("Gagal mengunggah file thumbnail.");
        }
    } else {
        // Jika tidak ada file baru yang diunggah, gunakan thumbnail lama
        $thumbnail_event = $old_thumbnail;
    }

    // Query update untuk memperbarui data event
    $query_update = "UPDATE event 
                     SET judul_event = ?, speakers_event = ?, jadwal_event = ?, waktu_event = ?, kuota = ?, lokasi_event = ?, thumbnail_event = ?, deskripsi_event = ?, link_grup = ? 
                     WHERE id_event = ?";
    $stmt_update = mysqli_prepare($koneksi, $query_update);
    mysqli_stmt_bind_param($stmt_update, "ssssissssi", $judul_event, $speakers_event, $jadwal_event, $waktu_event, $kuota, $lokasi_event, $thumbnail_event, $deskripsi_event, $link_grup, $id_event);

    // Eksekusi query update
    if (mysqli_stmt_execute($stmt_update)) {
        // Jika update berhasil, catat log
        $report = "Admin $admin_name mengedit Event $judul_event."; 
        $filter = "Edit Event";

        // Query untuk log informasi
        $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
        $stmt_log = mysqli_prepare($koneksi, $sql_log);
        mysqli_stmt_bind_param($stmt_log, "iss", $admin_id, $report, $filter);
        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);

        // Redirect atau pesan sukses
        echo "<script>alert('Data Event Berhasil Diperbarui.'); window.location.href='dataevent.php?success=1';</script>";
    } else {
        // Tangani error
        echo "<script>alert('Gagal Memperbarui Data Event.'); window.location.href='dataevent.php';</script>";
    }

    // Tutup statement dan koneksi
    mysqli_stmt_close($stmt_update);
    mysqli_close($koneksi);
}
?>
