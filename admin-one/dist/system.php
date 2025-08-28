<?php
session_start();
include 'koneksi.php'; // Menghubungkan ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    // RESET PASSWORD
    if ($action == 'resetpassword') {
        // Mengambil input dari form
        $current_password = $_POST['password_current'];
        $new_password = $_POST['password'];
        $password_confirmation = $_POST['password_confirmation'];

        // Validasi password
        if ($new_password !== $password_confirmation) {
            echo "<script>alert('Password dan konfirmasi password tidak sesuai.'); window.location.href='settingadmin.php';</script>";
            exit;
        }

        // Ambil ID admin dari sesi
        $admin_id = $_SESSION['admin_id'];

        // SQL untuk mengambil password saat ini dari database
        $sql_select = "SELECT password FROM admin WHERE id = ?";
        $stmt_select = $koneksi->prepare($sql_select);
        $stmt_select->bind_param("i", $admin_id);
        $stmt_select->execute();
        $stmt_select->store_result();

        // Bind hasil query
        $stmt_select->bind_result($hashed_password);
        $stmt_select->fetch();

        // Periksa apakah password saat ini cocok
        if (password_verify($current_password, $hashed_password)) {
            // Hash password baru
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password di database
            $sql_update = "UPDATE admin SET password = ? WHERE id = ?";
            $stmt_update = $koneksi->prepare($sql_update);
            $stmt_update->bind_param("si", $hashed_new_password, $admin_id);

            if ($stmt_update->execute()) {
                // Catat informasi reset password
                $current_admin_id = $_SESSION['admin_id'];
                $current_admin_name = $_SESSION['admin_name'];
                $report = "Admin $current_admin_name (ID: $current_admin_id) melakukan reset password.";
                $filter = "ResetPassword";
                $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
                $stmt_log = $koneksi->prepare($sql_log);
                $stmt_log->bind_param("iss", $current_admin_id, $report, $filter);
                $stmt_log->execute();
                $stmt_log->close();

                echo "<script>alert('Password berhasil direset.'); window.location.href='settingadmin.php';</script>";
            } else {
                echo "<script>alert('Error: " . $koneksi->error . "'); window.location.href='settingadmin.php';</script>";
            }

            $stmt_update->close();
        } else {
            echo "<script>alert('Password saat ini salah.'); window.location.href='settingadmin.php';</script>";
        }

        $stmt_select->close();
    }

    // ADD ADMIN
    if ($action == 'addadmin') {
        // Mengambil input dari form
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirmation = $_POST['password_confirmation'];

        // Validasi password
        if ($password !== $password_confirmation) {
            echo "<script>alert('Password dan konfirmasi password tidak sesuai.'); window.location.href='addadmin.php';</script>";
            exit;
        }

        // Hashing password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL untuk memasukkan data
        $sql = "INSERT INTO admin (name, email, password) VALUES (?, ?, ?)";

        // Mempersiapkan statement
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        // Menjalankan statement
        if ($stmt->execute()) {
            // Mendapatkan ID admin yang baru ditambahkan
            $new_admin_id = $stmt->insert_id;

            // Catat informasi siapa yang menambahkan admin
            $current_admin_id = $_SESSION['admin_id'];
            $current_admin_name = $_SESSION['admin_name'];
            $report = "Admin $current_admin_name (ID: $current_admin_id) menambahkan admin baru: $name (ID: $new_admin_id).";
            $filter = "AddAdmin";
            $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
            $stmt_log = $koneksi->prepare($sql_log);
            $stmt_log->bind_param("iss", $current_admin_id, $report, $filter);
            $stmt_log->execute();
            $stmt_log->close();

            echo "<script>alert('Admin baru berhasil ditambahkan'); window.location.href='addadmin.php';</script>";
        } else {
            echo "<script>alert('Error: " . $koneksi->error . "'); window.location.href='addadmin.php';</script>";
        }

        // Menutup statement dan koneksi
        $stmt->close();
    } elseif ($action == 'login') {
        // Mengambil input dari form login
        $email = $_POST['login'];
        $password = $_POST['password'];

        // SQL untuk mengambil data admin berdasarkan email
        $sql = "SELECT id, name, password FROM admin WHERE email = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            if (password_verify($password, $hashed_password)) {
                // Password sesuai, set sesi
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_name'] = $name;
                $_SESSION['admin_email'] = $email;

                // Catat informasi login
                $report = "Admin $name berhasil login.";
                $filter = "Aktifitas Login";
                $sql_log = "INSERT INTO informasi (admin_id, report, filter) VALUES (?, ?, ?)";
                $stmt_log = $koneksi->prepare($sql_log);
                $stmt_log->bind_param("iss", $id, $report, $filter);
                $stmt_log->execute();
                $stmt_log->close();

                echo "<script>alert('Login berhasil.'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Password salah.'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('Email tidak ditemukan.'); window.location.href='login.php';</script>";
        }

        $stmt->close();
    } 
    // Tambahkan logika lain untuk operasi berbeda di sini
    // if ($action == 'another_action') {
    //     // logika untuk another_action
    // }
}



$koneksi->close();
?>
