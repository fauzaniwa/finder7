<?php
session_start(); // Mulai session jika belum dimulai

// Pastikan request POST disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addbroadcast'])) {
    // Sambungkan ke database
    require_once "koneksi.php";
    $koneksi = mysqli_connect($host, $username, $password, $database);

    // Periksa koneksi
    if (mysqli_connect_errno()) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Tangkap data dari form
    $penerima = $_POST['penerima'];
    $title = $_POST['title'];
    $pesan = $_POST['pesan'];
    $status = $_POST['status'];

    // Ambil email penerima
    $emails = [];
    if ($penerima === "individu" && !empty($_POST['penerima_individual'])) {
        $emails[] = $_POST['penerima_individual'];
    } elseif ($penerima === "array" && !empty($_POST['penerima_array'])) {
        $emails = array_map('trim', explode(',', $_POST['penerima_array']));
    } elseif ($penerima === "semua") {
        $result = mysqli_query($koneksi, "SELECT email FROM user");
        while ($row = mysqli_fetch_assoc($result)) {
            $emails[] = $row['email'];
        }
    }

    // Tentukan nama file untuk gambar
    $foto_broadcast = null;

    // Proses file upload jika ada file gambar yang diunggah
    if (!empty($_FILES['foto_broadcast']['name'])) {
        $file_tmp = $_FILES['foto_broadcast']['tmp_name'];
        $file_ext = pathinfo($_FILES['foto_broadcast']['name'], PATHINFO_EXTENSION);
        $foto_broadcast = "broadcast_" . time() . "." . $file_ext; // Nama file baru dengan timestamp
        move_uploaded_file($file_tmp, "../../img/broadcast/" . $foto_broadcast);
    }

    // Simpan pesan ke database jika status adalah draft
    if ($status === "Draft") {
        // Query untuk menyimpan pesan ke tabel broadcast
        $query = "INSERT INTO broadcast (title, message, status, foto_broadcast, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $title, $pesan, $status, $foto_broadcast);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<script>alert('Pesan disimpan sebagai draft.'); window.location.href='broadcast.php';</script>";
        exit;
    }

    // Kirim email jika status adalah kirim
    foreach ($emails as $email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Tentukan URL gambar
            $backgroundImageUrl = "https://finderdkvupi.com/img/broadcast/" . $foto_broadcast;

            // Template email
            $template = "
            <!DOCTYPE html>
            <html lang='en'>
              <head>
                <meta charset='UTF-8' />
                <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                <link rel='stylesheet' href='https://cdn.tailwindcss.com' />
                <link href='https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap' rel='stylesheet' />
                <style>
                  .button {
                    font-family: 'Work Sans';
                    border: 1px solid white;
                    padding: 10px 20px;
                    color: white;
                    background: transparent;
                    border-radius: 50px;
                    cursor: pointer;
                    text-decoration: none;
                    display: inline-block;
                    text-align: center;
                  }
                  .button:hover {
                    background: rgba(255, 255, 255, 0.25);
                  }
                </style>
              </head>
              <body>
                <section class='w-full h-full py-16 gap-5'>
                  <div style='background-image: url({$backgroundImageUrl})' class='bg-cover bg-center flex justify-center items-center h-[350px] w-[90%] max-w-[1000px] bg-neutral-900 mx-auto'></div>
                  <h2 class='text-2xl md:text-3xl font-semibold text-center font-work mx-auto'>{$title}</h2>
                  <p class='w-[90%] max-w-[1000px] mx-auto text-justify text-lg md:text-xl font-light font-work'>
                    {$pesan}
                    <br /><br />
                    <span class='font-medium'>Thanks, Finder 6 Pusaka</span>
                  </p>
                </section>
                <footer class='container p-6'>
                  <h1 class='text-justify text-lg md:text-xl font-semibold font-work'>Finder 6 Pusaka Copyright 2024</h1>
                </footer>
              </body>
            </html>";

            // Kirim email
            $to = $email;
            $subject = $title;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers = "From: information@finderdkvupi.com\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            mail($to, $subject, $template, $headers);
        }
    }

    echo "<script>alert('Pesan berhasil dikirim ke email penerima.'); window.location.href='broadcasts.php';</script>";

    // Tutup koneksi
    mysqli_close($koneksi);
}
?>
