<?php 
session_start();

// Ambil informasi admin dari session
$id = $_SESSION['admin_id'];
$name = $_SESSION['admin_name'];

// Ambil data dari form
$judul = $_POST['judul'];
$speakers = $_POST['speakers'];
$jadwal = $_POST['jadwal'];
$waktu = $_POST['waktu'];
$lokasi = $_POST['lokasi'];
$grup = isset($_POST['grup']) ? $_POST['grup'] : null; // Ambil Link Grup, jika ada
$kuota = $_POST['kuota']; // Kuota
$deskripsi = $_POST['deskripsi']; // Deskripsi
$thumbnail_event = $_FILES['thumbnail_event']; // Thumbnail
$urutan_show = $_POST['urutan_show']; // Urutan Show

// Koneksi ke database
require_once "koneksi.php";
$koneksi = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Handle file upload
$target_dir = "../../img/event/";
$timestamp = time();
$target_file = $target_dir . basename($timestamp . "_" . $thumbnail_event["name"]);
$upload_ok = 1;
$image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Cek apakah file adalah gambar
if (isset($_POST["submit"])) {
    $check = getimagesize($thumbnail_event["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File yang diunggah bukan gambar.'); window.location.href='dataevent.php';</script>";
        $upload_ok = 0;
    }
}

// Cek ukuran file
if ($thumbnail_event["size"] > 5000000) { // 5MB limit
    echo "<script>alert('Maaf, ukuran file terlalu besar.'); window.location.href='dataevent.php';</script>";
    $upload_ok = 0;
}

// Izinkan format file tertentu
if (!in_array($image_file_type, ['jpg', 'png', 'jpeg', 'gif'])) {
    echo "<script>alert('Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.'); window.location.href='dataevent.php';</script>";
    $upload_ok = 0;
}

// Cek jika $upload_ok di set menjadi 0 oleh kesalahan
if ($upload_ok == 0) {
    echo "<script>alert('Maaf, file Anda tidak terunggah.'); window.location.href='dataevent.php';</script>";
} else {
    // Upload file
    if (move_uploaded_file($thumbnail_event["tmp_name"], $target_file)) {
        // Query untuk insert data event termasuk urutan_show
        $query = "INSERT INTO event (judul_event, speakers_event, jadwal_event, waktu_event, lokasi_event, thumbnail_event, deskripsi_event, kuota, created_event, link_grup, urutan_show) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);

        // Bind parameter dan eksekusi statement untuk insert event
        mysqli_stmt_bind_param($stmt, "sssssssisi", $judul, $speakers, $jadwal, $waktu, $lokasi, $target_file, $deskripsi, $kuota, $grup, $urutan_show);
        $success = mysqli_stmt_execute($stmt);

        // Periksa apakah insert berhasil
        if ($success) {
            // Report informasi log
            $report = "Admin $name menambahkan Event $judul."; 
            $filter = "Add Event";

            // Query untuk log informasi
            $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
            $stmt_log = mysqli_prepare($koneksi, $sql_log);
            mysqli_stmt_bind_param($stmt_log, "iss", $id, $report, $filter);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);

            echo "<script>alert('Data Event Berhasil Ditambahkan.'); window.location.href='dataevent.php';</script>";
        } else {
            echo "<script>alert('Gagal Menambahkan Data..'); window.location.href='dataevent.php';</script>";
        }
    } else {
        echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file.'); window.location.href='dataevent.php';</script>";
    }
}

// Tutup statement dan koneksi
mysqli_stmt_close($stmt);
mysqli_close($koneksi);

?>
