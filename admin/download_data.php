<?php
// Sertakan file koneksi database
require_once 'config.php';

// Fungsi untuk mengambil semua data user dari database
function getAllUsersData() {
    global $conn;

    if (!$conn) {
        error_log("Database connection failed in download_data.php");
        return [];
    }
    
    $users = [];
    $sql = "SELECT id_user, nama, tgl_lahir, no_hp, instansi, email, created, kode_account FROM user ORDER BY created DESC";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        mysqli_free_result($result);
    } else {
        error_log("SQL Error in download_data.php: " . mysqli_error($conn));
    }
    
    return $users;
}

// Ambil semua data pengguna
$users = getAllUsersData();

// Tentukan nama file
$fileName = 'data_pengguna_' . date('Ymd_His') . '.csv';

// Atur header HTTP untuk mengunduh file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $fileName . '"');

// Buka output stream
$output = fopen('php://output', 'w');

// Tulis baris header (nama kolom)
$header = ['ID User', 'Nama', 'Tanggal Lahir', 'No. HP', 'Instansi', 'Email', 'Kode Akun', 'Dibuat'];
fputcsv($output, $header);

// Tulis data pengguna ke file
if (!empty($users)) {
    foreach ($users as $user) {
        fputcsv($output, [
            $user['id_user'],
            $user['nama'],
            $user['tgl_lahir'],
            $user['no_hp'],
            $user['instansi'],
            $user['email'],
            $user['kode_account'],
            $user['created']
        ]);
    }
}

// Tutup output stream
fclose($output);

exit();
?>