<?php
// Tampilkan semua error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sertakan file koneksi.php untuk koneksi database
require_once '../admin-one/dist/koneksi.php';

// Sertakan file PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Fungsi untuk membersihkan input data
if (!function_exists('bersihkanInput')) {
    function bersihkanInput($data) {
        // Cek apakah data adalah null sebelum dilewatkan ke trim()
        return htmlspecialchars(strip_tags(trim($data ?? '')));
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan bersihkan data dari POST
    $required_fields = ['kategori_karya', 'Nama_Lengkap', 'Nomor_Telepon', 'Email', 'Judul_Karya', 'Media_Sosial', 'Deskripsi_Karya', 'Link_Karya', 'persetujuan'];
    
    // Perbaikan: Ubah perulangan validasi agar memeriksa key yang benar
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            die("Error: Semua field wajib diisi.");
        }
    }
    
    $kategori_karya = bersihkanInput($_POST['kategori_karya']);
    $nama_lengkap = bersihkanInput($_POST['Nama_Lengkap']);
    $nomor_telepon = bersihkanInput($_POST['Nomor_Telepon']);
    $email = bersihkanInput($_POST['Email']);
    $instansi = isset($_POST['Instansi']) ? bersihkanInput($_POST['Instansi']) : '';
    $judul_karya = bersihkanInput($_POST['Judul_Karya']);
    $media_sosial = bersihkanInput($_POST['Media_Sosial']);
    $deskripsi_karya = bersihkanInput($_POST['Deskripsi_Karya']);
    $link_karya = bersihkanInput($_POST['Link_Karya']);
    $persetujuan = isset($_POST['persetujuan']) ? 1 : 0;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Format email tidak valid.");
    }

    $bukti_pembayaran = 'Tidak Ada';

    // Simpan data ke database
    $sql = "INSERT INTO pendaftaran_wacom (kategori_karya, nama_lengkap, nomor_telepon, email, instansi, judul_karya, media_sosial, deskripsi_karya, link_karya, bukti_pembayaran, persetujuan) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    
    if ($stmt === false) {
        die("Error saat mempersiapkan statement: " . $koneksi->error);
    }
    
    $stmt->bind_param("ssssssssssi", $kategori_karya, $nama_lengkap, $nomor_telepon, $email, $instansi, $judul_karya, $media_sosial, $deskripsi_karya, $link_karya, $bukti_pembayaran, $persetujuan);

    if ($stmt->execute()) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@finderdkvupi.id';
            $mail->Password = 'Finderdkvupi1234aja*';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('info@finderdkvupi.id', 'Panitia Acara Finder');
            $mail->addAddress($email, $nama_lengkap);

            $attachment_path = 'voucher_finder.png';
            $mail->addAttachment($attachment_path);

            $mail->isHTML(true);
            $mail->Subject = 'Konfirmasi Pendaftaran Lomba Finder 7 x Wacom x Neon Experience';
            $mail->Body = "
                <html>
                <head>
                    <title>Konfirmasi Pendaftaran</title>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                        .header { background-color: #f4f4f4; padding: 10px; text-align: center; }
                        .content { margin-top: 20px; }
                        .details { background-color: #f9f9f9; padding: 15px; border-left: 3px solid #007BFF; margin-top: 15px; }
                        .footer { margin-top: 30px; text-align: center; font-size: 0.9em; color: #777; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h3>Konfirmasi Pendaftaran Lomba Finder 7 x Wacom x Neon Experience</h3>
                        </div>
                        <div class='content'>
                            <p>Yth. Saudara/i <b>{$nama_lengkap}</b>,</p>
                            <p>Kami informasikan bahwa pendaftaran Anda pada Lomba Finder 7 x Wacom x Neon Experience telah kami terima. Terima kasih atas partisipasi dan antusiasme Anda.</p>
                            <div class='details'>
                                <p><b>Rincian Pendaftaran:</b></p>
                                <ul>
                                    <li><b>Nama Lengkap:</b> {$nama_lengkap}</li>
                                    <li><b>Judul Karya:</b> {$judul_karya}</li>
                                </ul>
                            </div>
                            <p>Sebagai bentuk apresiasi atas partisipasi Anda, kami melampirkan <b> Voucher Diskon Finder Store</b>. Voucher ini dapat Anda gunakan untuk berbelanja produk-produk kami selama acara berlangsung.</p>
                            <p>Informasi lebih lanjut mengenai jadwal dan ketentuan lomba akan kami sampaikan melalui email terpisah. Mohon untuk memantau kotak masuk (inbox) email Anda secara berkala.</p>
                            <p>Hormat kami,</p>
                            <p><b>Panitia Lomba Wacom x Finder</b></p>
                        </div>
                        <div class='footer'>
                            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
                        </div>
                    </div>
                </body>
                </html>
            ";
            $mail->send();
        } catch (Exception $e) {
            // Email konfirmasi gagal dikirim.
            // Anda dapat menambahkan log di sini jika diperlukan.
        }

        header("Location: submitkaryawacom.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$koneksi->close();
?>