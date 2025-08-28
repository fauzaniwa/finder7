<?php
require_once 'admin-one/dist/koneksi.php';

// Cek dulu apakah fungsi sudah ada
if (!function_exists('bersihkanInput')) {
    function bersihkanInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}

// Ambil data dari POST
$nama = bersihkanInput($_POST['Nama_Lengkap']);
$nomor_telp = bersihkanInput($_POST['Nomor_Telepon']);
$email = bersihkanInput($_POST['Email']);
$instansi = bersihkanInput($_POST['Instansi']);
$media_sosial = bersihkanInput($_POST['Media_Sosial']);
$kategori_karya = bersihkanInput($_POST['kategori_karya']);
$judul_karya = bersihkanInput($_POST['Judul_Karya']);
$deskripsi_karya = bersihkanInput($_POST['Deskripsi_Karya']);
$link_karya = bersihkanInput($_POST['Link_Karya']);

// Simpan ke database
$sql = "INSERT INTO Lomba 
(nama, nomor_telp, email, instansi, media_sosial, kategori_karya, judul_karya, deskripsi_karya, link_karya)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($sql);

if (!$stmt) {
    die("Prepare statement gagal: " . $koneksi->error);
}

$stmt->bind_param("sssssssss", 
    $nama, 
    $nomor_telp, 
    $email, 
    $instansi, 
    $media_sosial, 
    $kategori_karya, 
    $judul_karya, 
    $deskripsi_karya, 
    $link_karya
);

// Jika berhasil simpan, kirim email
if ($stmt->execute()) {

    // Kirim email ke peserta
    $to = $email;
    $subject = "Konfirmasi Pengiriman Karya";
    $message = "
    <html>
    <head>
        <title>Konfirmasi Pengiriman Karya</title>
    </head>
    <body style='font-family: Arial, sans-serif;'>
        <h2>Halo, {$nama}!</h2>
        <p>Terima kasih telah mengirimkan karya kamu.</p>
        <p>Karya berjudul <strong>{$judul_karya}</strong> telah berhasil diikutsertakan dalam kompetisi kategori <strong>{$kategori_karya}</strong>.</p>
        <p>Panitia akan melakukan proses kurasi dan seleksi sesuai dengan ketentuan yang berlaku.</p>
        <br>
        <p>Salam hangat,</p>
        <p><strong>Panitia Finder7Mindspace</strong></p>
    </body>
    </html>
    ";

    // Header untuk email HTML
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Tambahkan From (ganti dengan email valid jika perlu)
    $headers .= 'From: noreply@finder7mindspace.com' . "\r\n";

    // Kirim email
    mail($to, $subject, $message, $headers);

    // Redirect ke halaman submit dengan modal sukses
    header("Location: submitkarya.php?success=1");
    exit;
} else {
    echo "Terjadi kesalahan saat menyimpan data: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>
