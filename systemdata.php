<?php
// Selalu mulai session di awal
session_start();

// Koneksi ke database
include 'admin-one/dist/koneksi.php';

function generateKodeAccount()
{
    return 'FD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT) .
        chr(rand(65, 90)) .
        rand(1, 9) .
        chr(rand(65, 90)) .
        chr(rand(65, 90));
}

// ---- LOGIKA REGISTER ----
if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $no_hp = $_POST['no_hp'];
    $instansi = $_POST['instansi'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $queryCheckEmail = "SELECT email FROM user WHERE email = ?";
    $stmtCheckEmail = mysqli_prepare($koneksi, $queryCheckEmail);
    mysqli_stmt_bind_param($stmtCheckEmail, "s", $email);
    mysqli_stmt_execute($stmtCheckEmail);
    mysqli_stmt_store_result($stmtCheckEmail);

    if (mysqli_stmt_num_rows($stmtCheckEmail) > 0) {
        // Email sudah ada, set pesan error dan kembali ke register
        $_SESSION['notification'] = [
            'status' => 'error',
            'title' => 'Registrasi Gagal!',
            'message' => 'Alamat email yang Anda masukkan sudah terdaftar.',
            'button_text' => 'Coba Lagi',
            'button_url' => 'register.php'
        ];
        header('Location: register.php');
        exit();
    } else {
        // Email belum ada, lakukan insert
        $kode_account = generateKodeAccount();
        $query = "INSERT INTO user (nama, tgl_lahir, no_hp, instansi, email, password, kode_account) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $nama, $tgl_lahir, $no_hp, $instansi, $email, $password, $kode_account);
        $tambahUser = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($tambahUser) {
            // Registrasi berhasil, set pesan sukses dan kembali ke login
            $_SESSION['notification'] = [
                'status' => 'success',
                'title' => 'Registrasi Berhasil!',
                'message' => 'Akun Anda telah berhasil dibuat. Silakan login.',
                'button_text' => 'Login Sekarang',
                'button_url' => 'login.php'
            ];
            header('Location: login.php');
            exit();
        } else {
            // Registrasi gagal (kesalahan server), set pesan error dan kembali ke register
            $_SESSION['notification'] = [
                'status' => 'error',
                'title' => 'Oops! Terjadi Kesalahan',
                'message' => 'Registrasi gagal karena masalah teknis. Silakan coba lagi nanti.',
                'button_text' => 'Kembali',
                'button_url' => 'register.php'
            ];
            header('Location: register.php');
            exit();
        }
    }
    mysqli_stmt_close($stmtCheckEmail);
}

// ---- LOGIKA LOGIN ----
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT id_user, nama, email, password FROM user WHERE email = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id, $nama, $db_email, $db_password);
    mysqli_stmt_fetch($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        if (password_verify($password, $db_password)) {
            // Login berhasil, simpan session dan alihkan ke account.php
            $_SESSION['user_id'] = $id;
            $_SESSION['user_nama'] = $nama;
            $_SESSION['user_email'] = $db_email;

            // Alihkan langsung ke halaman akun
            header('Location: account.php');
            exit();
        } else {
            // Password salah, set pesan error dan kembali ke login
            $_SESSION['notification'] = [
                'status' => 'error',
                'title' => 'Login Gagal!',
                'message' => 'Password yang Anda masukkan salah. Silakan periksa kembali.',
                'button_text' => 'Coba Lagi',
                'button_url' => 'login.php'
            ];
            header('Location: login.php');
            exit();
        }
    } else {
        // Email tidak ditemukan, set pesan error dan kembali ke login
        $_SESSION['notification'] = [
            'status' => 'error',
            'title' => 'Login Gagal!',
            'message' => 'Email yang Anda masukkan belum terdaftar.',
            'button_text' => 'Coba Lagi',
            'button_url' => 'login.php'
        ];
        header('Location: login.php');
        exit();
    }
    mysqli_stmt_close($stmt);
}
?>