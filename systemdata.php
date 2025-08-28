<?php
// Koneksi ke database
include 'admin-one/dist/koneksi.php';

function generateKodeAccount() {
    // Generate kode_account sesuai format yang diinginkan
    return 'FD' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT) .
           chr(rand(65, 90)) .
           rand(1, 9) .
           chr(rand(65, 90)) .
           chr(rand(65, 90));
}

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $no_hp = $_POST['no_hp'];
    $instansi = $_POST['instansi'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Periksa apakah email sudah ada di database
    $queryCheckEmail = "SELECT email FROM user WHERE email = ?";
    $stmtCheckEmail = mysqli_prepare($koneksi, $queryCheckEmail);
    mysqli_stmt_bind_param($stmtCheckEmail, "s", $email);
    mysqli_stmt_execute($stmtCheckEmail);
    mysqli_stmt_store_result($stmtCheckEmail);

    if (mysqli_stmt_num_rows($stmtCheckEmail) > 0) {
        // Email sudah ada
        echo "<script>
                alert('Registrasi gagal! Email sudah digunakan.');
                document.location='register.php';
              </script>";
    } else {
        // Email belum ada, lakukan insert
        $kode_account = generateKodeAccount(); // Generate kode_account

        // Persiapkan query dengan placeholder
        $query = "INSERT INTO user (nama, tgl_lahir, no_hp, instansi, email, password, kode_account) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // Persiapkan statement
        $stmt = mysqli_prepare($koneksi, $query);

        // Bind parameter ke statement
        mysqli_stmt_bind_param($stmt, "sssssss", $nama, $tgl_lahir, $no_hp, $instansi, $email, $password, $kode_account);

        // Eksekusi statement
        $tambahUser = mysqli_stmt_execute($stmt);

        // Tutup statement
        mysqli_stmt_close($stmt);

        if ($tambahUser) {
            echo "<script>
                    alert('Registrasi berhasil!');
                    document.location='login.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Registrasi gagal!');
                    document.location='register.php';
                  </script>";
        }
    }

    // Tutup statement cek email
    mysqli_stmt_close($stmtCheckEmail);
}

//LOGIN USER
// Mulai session
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Persiapkan query untuk mengambil data user berdasarkan email
    $query = "SELECT id_user, nama, email, password FROM user WHERE email = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id, $nama, $db_email, $db_password);
    mysqli_stmt_fetch($stmt);

    // Periksa apakah email ditemukan
    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Periksa apakah password cocok
        if (password_verify($password, $db_password)) {
            // Login berhasil
            // Simpan informasi pengguna ke session
            $_SESSION['user_id'] = $id;
            $_SESSION['user_nama'] = $nama;
            $_SESSION['user_email'] = $db_email;

            echo "<script>
                    alert('Login berhasil!');
                    document.location='account.php';
                  </script>";
        } else {
            // Password tidak cocok
            echo "<script>
                    alert('Login gagal! Password salah.');
                    document.location='login.php';
                  </script>";
        }
    } else {
        // Email tidak ditemukan
        echo "<script>
                alert('Login gagal! Email belum terdaftar.');
                document.location='login.php';
              </script>";
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
}
?>
