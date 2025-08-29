<?php

// Pastikan file ini berada di lokasi yang bisa diakses oleh skrip lain,
// dan pastikan file 'config.php' sudah di-include sebelum memanggil fungsi ini.

/**
 * Mencatat aktivitas admin ke dalam tabel admin_logs.
 *
 * @param mysqli $conn Objek koneksi database.
 * @param int $admin_id ID admin yang melakukan aktivitas.
 * @param string $action_type Tipe aktivitas (e.g., 'register', 'login', 'update', 'delete').
 * @param string $description Deskripsi detail dari aktivitas.
 * @return bool True jika log berhasil dicatat, false jika gagal.
 */
function log_admin_activity($conn, $admin_id, $action_type, $description) {
    $sql = "INSERT INTO admin_logs (admin_id, action_type, description) VALUES (?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "iss", $param_admin_id, $param_action_type, $param_description);
        
        $param_admin_id = $admin_id;
        $param_action_type = $action_type;
        $param_description = $description;
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    }
    return false;
}