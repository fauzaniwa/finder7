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
    // Tentukan field yang wajib diisi untuk pendaftaran cosplay
    $required_fields = ['Nama_Lengkap', 'Nama_Karakter', 'Email', 'Media_Sosial', 'persetujuan'];
    
    foreach ($required_fields as $field) {
        // Perbaiki validasi untuk nama karakter
        if ($field === 'Nama_Karakter' && (!isset($_POST[$field]) || empty(trim($_POST[$field])))) {
            die("Error: Field 'Nama Karakter' wajib diisi.");
        }
        if ($field !== 'Nama_Karakter' && (!isset($_POST[$field]) || empty(trim($_POST[$field])))) {
            die("Error: Semua field wajib diisi.");
        }
    }
    
    // Ambil dan bersihkan data dari POST
    $nama_lengkap = bersihkanInput($_POST['Nama_Lengkap']);
    $nama_karakter = bersihkanInput($_POST['Nama_Karakter']);
    $email = bersihkanInput($_POST['Email']);
    $media_sosial = bersihkanInput($_POST['Media_Sosial']);
    $persetujuan = isset($_POST['persetujuan']) ? 1 : 0;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Format email tidak valid.");
    }

    // Simpan data ke tabel pendaftaran_cosplay
    $sql = "INSERT INTO pendaftaran_cosplay (nama_lengkap, nama_karakter, email, media_sosial, persetujuan) 
             VALUES (?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    
    if ($stmt === false) {
        die("Error saat mempersiapkan statement: " . $koneksi->error);
    }
    
    // Sesuaikan bind_param dengan kolom baru (tipe data: ssss, i untuk persetujuan)
    $stmt->bind_param("ssssi", $nama_lengkap, $nama_karakter, $email, $media_sosial, $persetujuan);

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

            // BARIS UNTUK ATTACHMENT DIHAPUS

            $mail->isHTML(true);
            // Ubah subjek dan isi email untuk pendaftaran cosplay
            $mail->Subject = 'Konfirmasi Pendaftaran Lomba Cosplay Finder';
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
                            <h3>Konfirmasi Pendaftaran Lomba Cosplay Finder</h3>
                        </div>
                        <div class='content'>
                            <p>Yth. Saudara/i <b>{$nama_lengkap}</b>,</p>
                            <p>Kami informasikan bahwa pendaftaran Anda pada Lomba Cosplay Finder telah kami terima. Terima kasih atas partisipasi dan antusiasme Anda.</p>
                            <div class='details'>
                                <p><b>Rincian Pendaftaran:</b></p>
                                <ul>
                                    <li><b>Nama Lengkap:</b> {$nama_lengkap}</li>
                                    <li><b>Nama Karakter:</b> {$nama_karakter}</li>
                                    <li><b>Email:</b> {$email}</li>
                                </ul>
                            </div>
                            <p>Hormat kami,</p>
                            <p><b>Panitia Lomba Cosplay Finder</b></p>
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

        header("Location: submitcosplay.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$koneksi->close();
?>