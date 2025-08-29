<?php
// Pastikan tidak ada output lain sebelum header
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Sertakan file koneksi database
require_once 'config.php';

// Pastikan skrip mengembalikan JSON
header('Content-Type: application/json');

$response = ['error' => 'Terjadi kesalahan tidak terduga.'];

try {
    // Ambil id_user dari parameter GET
    $id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;

    if ($id_user === null || !is_numeric($id_user)) {
        throw new Exception("ID pengguna tidak valid.");
    }

    // Periksa apakah koneksi berhasil
    if (!$conn) {
        throw new Exception("Koneksi database gagal.");
    }

    // Query untuk mengambil data tiket, event, dan status kehadiran
    // Menambahkan t.created_tiket untuk format tanggal
    $sql = "SELECT e.judul_event, e.harga, t.tiket_code AS kode_event, t.created_tiket, 
            CASE WHEN a.kode_absen IS NOT NULL THEN 'Hadir' ELSE 'Tidak Hadir' END AS status_kehadiran
            FROM tiket t
            LEFT JOIN event e ON t.id_event = e.id_event
            LEFT JOIN absen a ON t.tiket_code = a.kode_absen
            WHERE t.id_user = ?";
    
    // Siapkan dan eksekusi statement
    if (!$stmt = mysqli_prepare($conn, $sql)) {
        throw new Exception("Error saat menyiapkan query: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error saat mengeksekusi query: " . mysqli_error($conn));
    }

    $result = mysqli_stmt_get_result($stmt);
    
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Logika untuk memformat nilai tiket menjadi Rupiah
        $row['nilai_tiket'] = "Rp " . number_format($row['harga'], 0, ',', '.');
        unset($row['harga']); // Hapus kolom 'harga' yang tidak lagi diperlukan
        
        // Logika untuk memformat created_tiket ke zona waktu Indonesia
        $datetime = new DateTime($row['created_tiket'], new DateTimeZone('UTC'));
        $datetime->setTimezone(new DateTimeZone('Asia/Jakarta'));
        $row['jadwal_pengambilan_tiket'] = $datetime->format('d M Y H:i:s');
        unset($row['created_tiket']); // Hapus created_tiket mentah
        
        $events[] = $row;
    }
    
    mysqli_stmt_close($stmt);

    // Siapkan respons yang sukses
    if (empty($events)) {
        $response = ['message' => 'User tidak mendaftar pada event apapun.'];
    } else {
        $response = ['events' => $events];
    }

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    $response = ['error' => $e->getMessage()];
} finally {
    // Pastikan koneksi ditutup
    if (isset($conn) && is_object($conn)) {
        mysqli_close($conn);
    }
}

echo json_encode($response);
exit; // Pastikan tidak ada output lain setelah ini
?>