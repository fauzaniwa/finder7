<?php
session_start();
include 'admin-one/dist/koneksi.php'; // Sertakan file koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirim dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query SQL untuk mendapatkan data user berdasarkan email
    $query = "SELECT * FROM `user` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Jika query berhasil dieksekusi
        if (mysqli_num_rows($result) == 1) {
            // Ambil data user dari hasil query
            $row = mysqli_fetch_assoc($result);
            $hashed_password = $row['password'];

            // Verifikasi password dengan password_hash()
            if (password_verify($password, $hashed_password)) {
                // Jika password cocok, atur session untuk user yang login
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['message'] = "Login berhasil.";
                // Redirect menggunakan JavaScript untuk memastikan alert bisa muncul
                echo '<script>alert("Login berhasil."); window.location.href = "account.php";</script>';
                exit();
            } else {
                $_SESSION['message'] = "Email atau password salah.";
                // Redirect menggunakan JavaScript untuk memastikan alert bisa muncul
                echo '<script>alert("Email atau password salah."); window.location.href = "login.php";</script>';
                exit();
            }
        } else {
            $_SESSION['message'] = "Email atau password salah.";
            // Redirect menggunakan JavaScript untuk memastikan alert bisa muncul
            echo '<script>alert("Email atau password salah."); window.location.href = "login.php";</script>';
            exit();
        }
    } else {
        $_SESSION['message'] = "Gagal melakukan login: " . mysqli_error($conn);
        // Redirect menggunakan JavaScript untuk memastikan alert bisa muncul
        echo '<script>alert("Gagal melakukan login: ' . mysqli_error($conn) . '"); window.location.href = "login.php";</script>';
        exit();
    }
} else {
    $_SESSION['message'] = "Permintaan tidak valid.";
    // Redirect menggunakan JavaScript untuk memastikan alert bisa muncul
    echo '<script>alert("Permintaan tidak valid."); window.location.href = "login.php";</script>';
    exit();
}

// Tutup koneksi ke database
mysqli_close($conn);
?>
