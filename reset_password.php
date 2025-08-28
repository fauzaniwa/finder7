<?php
// Mulai session untuk penyimpanan sementara
session_start();

// Memasukkan file koneksi
include 'admin-one/dist/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    // Ambil email dari form dan bersihkan input
    $email = bersihkanInput($_POST['email']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Periksa apakah email ada dalam database pengguna (misalnya, di tabel users)
        $check_email_query = "SELECT * FROM users WHERE email = ?";
        $stmt = $koneksi->prepare($check_email_query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika email valid, generate OTP
            $otp_code = mt_rand(100000, 999999); // Menghasilkan 6 digit kode OTP
            $expires_at = date("Y-m-d H:i:s", strtotime('+3 minutes')); // Atur waktu kedaluwarsa OTP (15 menit)

            // Simpan OTP ke tabel resetpassword
            $insert_otp_query = "INSERT INTO resetpassword (email, otp_code, expires_at) VALUES (?, ?, ?)";
            $stmt = $koneksi->prepare($insert_otp_query);
            $stmt->bind_param('sss', $email, $otp_code, $expires_at);

            if ($stmt->execute()) {
                // Jika berhasil disimpan, kirim email ke pengguna
                $subject = "Permintaan Reset Password";
                $message = "Halo,\n\n" .
                    "Kami telah menerima permintaan untuk mereset kata sandi akun Anda.\n\n" .
                    "Berikut adalah kode OTP yang perlu Anda masukkan untuk melanjutkan proses reset password:\n\n" .
                    "Kode OTP Anda: $otp_code\n\n" .
                    "Kode ini akan kedaluwarsa dalam 3 menit.\n\n" .
                    "Jika Anda tidak melakukan permintaan ini, harap abaikan email ini.\n\n" .
                    "Terima kasih,\n" .
                    "Tim Finder DKVI UPI";
                $headers = "From: forgot@finderdkvupi.com\r\n" .
                    "Reply-To: forgot@finderdkvupi.com\r\n" .
                    "Content-Type: text/plain; charset=UTF-8\r\n";

                if (mail($email, $subject, $message, $headers)) {
                    // Simpan email ke session agar dapat digunakan di otp.php
                    $_SESSION['email'] = $email;

                    // Redirect ke halaman otp.php
                    header("Location: verifikasi.php");
                    exit();
                } else {
                    // Jika gagal mengirim email
                    $status_message = "Gagal mengirim email. Silakan coba lagi.";
                }
            } else {
                // Jika gagal menyimpan ke database
                $status_message = "Gagal memproses permintaan reset password.";
            }
        } else {
            // Jika email tidak ditemukan
            $status_message = "Email tidak ditemukan.";
        }
    } else {
        // Jika email tidak valid
        $status_message = "Email tidak valid.";
    }
}
?>