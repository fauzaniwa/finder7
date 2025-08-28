<?php
require_once "koneksi.php"; // Memasukkan koneksi ke database

// Ambil data pengguna yang ulang tahun hari ini
$sql = "SELECT `nama`, `email` FROM `user` WHERE DATE(`tgl_lahir`) = CURDATE()";
$result = $koneksi->query($sql);

// Cek apakah ada pengguna yang ulang tahun
if ($result->num_rows > 0) {
    // Kirim email ke setiap pengguna yang ulang tahun
    while ($row = $result->fetch_assoc()) {
        $name = $row['nama'];
        $to = $row['email'];
        $subject = '🎉 Selamat Ulang Tahun dari Finder 6 Pusaka! 🎉';

        // Konten email
        $message = '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Selamat Ulang Tahun!</title>
            <style>
              body {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #333;
              }
              .container {
                background-color: #ffffff;
                border-radius: 10px;
                box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
                padding: 40px;
                text-align: center;
                max-width: 600px;
                margin: auto;
                border: 5px solid #000; /* Garis luar */
              }
              h1 {
                color: #000000;
                margin-bottom: 15px;
                font-size: 2.5em;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
              }
              p {
                color: #555;
                line-height: 1.6;
                font-size: 1.1em;
              }
              .footer {
                margin-top: 25px;
                font-size: 1em;
                color: #777;
                border-top: 1px solid #eee;
                padding-top: 15px;
              }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Selamat Ulang Tahun!</h1>
                <p>Hai ' . htmlspecialchars($name) . '</p>
                <p>Di hari istimewa ini, kami merayakan kehadiranmu!</p>
                <p>Semoga tahun ini dipenuhi dengan petualangan baru, momen berharga, dan semua impian yang menjadi kenyataan.</p>
                <p>Jadilah bintang di langit yang bersinar terang, menginspirasi orang-orang di sekitarmu dengan kehangatan dan kebaikanmu.</p>
                <p>Terima kasih telah menjadi bagian dari keluarga besar Finder 6 Pusaka. Kami sangat menghargai semua momen yang telah kita lalui bersama.</p>
                <p>Semoga setiap hari di tahun yang akan datang dipenuhi dengan kebahagiaan dan kesuksesan!</p>
                <div class="footer">
                    <p><strong>Finder 6 Pusaka Copyright 2024</strong></p>
                </div>
            </div>
        </body>
        </html>
        ';

        // Kirim email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: hello@finderdkvupi.com' . "\r\n"; // Ganti dengan email pengirim

        // Cek apakah email berhasil dikirim atau tidak
        if(mail($to, $subject, $message, $headers)) {
            echo 'Email berhasil dikirim ke ' . htmlspecialchars($name) . '<br>';
        } else {
            echo 'Gagal mengirim email ke ' . htmlspecialchars($name) . '<br>';
        }
    }
} else {
    echo 'Tidak ada pengguna yang ulang tahun hari ini.';
}

// Tutup koneksi
$koneksi->close();
?>
