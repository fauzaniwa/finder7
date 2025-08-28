<?php
session_start();
include 'admin-one/dist/koneksi.php'; // Sertakan file koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    // Pastikan aksi adalah "register"
    if ($action == "register") {
        // Ambil data yang dikirim dari form
        $nama = $_POST['nama'];
        $no_hp = $_POST['no_hp'];
        $tgl_lahir = $_POST['tgl_lahir'];
        $instansi = $_POST['instansi'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Query SQL untuk memeriksa apakah email sudah terdaftar
        $check_query = "SELECT * FROM `user` WHERE `email` = '$email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['message'] = "Email sudah digunakan. Silakan gunakan email lain.";
            header("Location: register.php"); // Redirect kembali ke halaman pendaftaran
            exit();
        }

        // Hash password menggunakan fungsi password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query SQL untuk menyimpan data ke tabel 'user' dengan password yang di-hash
        $query = "INSERT INTO `user` (`nama`, `no_hp`, `tgl_lahir`, `instansi`, `email`, `password`) 
                  VALUES ('$nama', '$no_hp', '$tgl_lahir', '$instansi', '$email', '$hashed_password')";

        // Eksekusi query
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Jika berhasil disimpan, atur pesan session atau tampilkan pesan sukses
            $_SESSION['message'] = "Pendaftaran berhasil.";
            header("Location: login.php"); // Redirect ke halaman login atau halaman lainnya
            exit();
        } else {
            // Jika gagal menyimpan, atur pesan session atau tampilkan pesan error
            $_SESSION['message'] = "Gagal melakukan pendaftaran: " . mysqli_error($conn);
            header("Location: register.php"); // Redirect ke halaman pendaftaran kembali
            exit();
        }
    } else {
        // Jika aksi tidak valid atau tidak didefinisikan, redirect ke halaman lain atau tampilkan pesan error
        $_SESSION['message'] = "Aksi tidak valid.";
        header("Location: register.php"); // Redirect ke halaman pendaftaran kembali
        exit();
    }
} else {
    // Jika bukan permintaan POST, redirect ke halaman lain atau tampilkan pesan error
    $_SESSION['message'] = "Permintaan tidak valid.";
    header("Location: register.php"); // Redirect ke halaman pendaftaran kembali
    exit();
}

// Tutup koneksi ke database
mysqli_close($koneksi);
?>
