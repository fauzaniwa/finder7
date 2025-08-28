<?php
// Memasukkan file koneksi
include 'admin-one/dist/koneksi.php';

// Memeriksa apakah formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil email dari formulir dan membersihkannya
    $email = bersihkanInput($_POST['email']);

    // Menghasilkan kode OTP acak 6 digit
    $otp_code = sprintf("%06d", mt_rand(0, 999999));

    // Menghitung waktu kedaluwarsa (2 menit dari sekarang)
    $expires_at = date("Y-m-d H:i:s", strtotime('+2 minutes'));

    // Mempersiapkan pernyataan SQL untuk memasukkan OTP ke dalam tabel resetpassword
    $sql = "INSERT INTO resetpassword (email, otp_code, expires_at) VALUES ('$email', '$otp_code', '$expires_at')";

    if ($koneksi->query($sql) === TRUE) {
        // Mengirim email kepada pengguna dengan kode OTP
        $subject = "Permintaan Reset Password";
        $message = "Halo,\n\n" .
                   "Kami telah menerima permintaan untuk mereset kata sandi akun Anda. " .
                   "Berikut adalah kode OTP yang perlu Anda masukkan untuk melanjutkan proses reset password:\n\n" .
                   "Kode OTP Anda: <strong>$otp_code</strong>\n\n" .
                   "Kode ini akan kedaluwarsa dalam 2 menit. Jika Anda tidak melakukan permintaan ini, harap abaikan email ini.\n\n" .
                   "Terima kasih,\n" .
                   "Tim Finder DKVI UPI";
        $headers = "From: forgot@finderdkvupi.com\r\n" .
                   "Reply-To: forgot@finderdkvupi.com\r\n" .
                   "Content-Type: text/plain; charset=UTF-8\r\n";

        // Mengirim email
        if (mail($email, $subject, $message, $headers)) {
            echo "<script>
                    alert('OTP telah dikirim ke email Anda. Silakan periksa kotak masuk Anda.');
                    // Menyembunyikan section login dan menampilkan input OTP
                    document.getElementById('login').style.display = 'none';
                    document.getElementById('input-otp').style.display = 'block';
                  </script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengirim email. Silakan coba lagi.');</script>";
        }
    } else {
        echo "<script>alert('Kesalahan: " . $koneksi->error . "');</script>";
    }

    // Menutup koneksi database
    $koneksi->close();
}
?>