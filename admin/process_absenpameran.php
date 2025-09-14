<?php
header('Content-Type: application/json');

// Ambil nilai QR code dari body POST request
$input = file_get_contents('php://input');
$data = json_decode($input);

if (!$data || !isset($data->qrCode)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Data QR Code tidak diterima.']);
    exit;
}

$qrCodeValue = $data->qrCode;

// Lakukan koneksi ke database menggunakan config.php
require_once "config.php";

$response = [];

// Periksa apakah kode_absen sudah ada dalam tabel absenpameran
$checkAbsenPameranQuery = "SELECT id_absenpameran FROM absenpameran WHERE kode_absen = ?";
$checkAbsenPameranStmt = mysqli_prepare($conn, $checkAbsenPameranQuery);

if ($checkAbsenPameranStmt) {
    mysqli_stmt_bind_param($checkAbsenPameranStmt, "s", $qrCodeValue);
    mysqli_stmt_execute($checkAbsenPameranStmt);
    mysqli_stmt_store_result($checkAbsenPameranStmt);

    // Jika QR code sudah ada, berikan respons 'exists'
    if (mysqli_stmt_num_rows($checkAbsenPameranStmt) > 0) {
        $response = ['status' => 'exists', 'message' => 'QR Code sudah terdaftar.'];
    } else {
        // QR code belum terdaftar, lakukan pengecekan ke tabel user
        $checkUserQuery = "SELECT id_user, nama FROM user WHERE kode_account = ?";
        $checkUserStmt = mysqli_prepare($conn, $checkUserQuery);
        
        if ($checkUserStmt) {
            mysqli_stmt_bind_param($checkUserStmt, "s", $qrCodeValue);
            mysqli_stmt_execute($checkUserStmt);
            mysqli_stmt_store_result($checkUserStmt);

            // Jika kode_account ditemukan dalam tabel user
            if (mysqli_stmt_num_rows($checkUserStmt) > 0) {
                // Ambil nama dari hasil query
                mysqli_stmt_bind_result($checkUserStmt, $id_user, $nama);
                mysqli_stmt_fetch($checkUserStmt);

                // Lakukan penyimpanan ke dalam tabel absenpameran
                $insertQuery = "INSERT INTO absenpameran (kode_absen, created_absen) VALUES (?, NOW())";
                $insertStmt = mysqli_prepare($conn, $insertQuery);

                if ($insertStmt) {
                    mysqli_stmt_bind_param($insertStmt, "s", $qrCodeValue);
                    mysqli_stmt_execute($insertStmt);
                    
                    // Berikan respons 'success' beserta nama pengguna
                    $response = [
                        'status' => 'success', 
                        'message' => 'Absensi berhasil!',
                        'nama_lengkap' => $nama
                    ];
                } else {
                    $response = ['status' => 'error', 'message' => 'Gagal menyimpan QR Code ke tabel absen pameran.'];
                }

                mysqli_stmt_close($insertStmt);
            } else {
                // Jika kode_account tidak ditemukan dalam tabel user
                $response = ['status' => 'error', 'message' => 'Kode Akun tidak ditemukan.'];
            }

            mysqli_stmt_close($checkUserStmt);
        } else {
            $response = ['status' => 'error', 'message' => 'Gagal memeriksa Kode Akun.'];
        }
    }

    mysqli_stmt_close($checkAbsenPameranStmt);
} else {
    $response = ['status' => 'error', 'message' => 'Gagal memeriksa QR Code di tabel absen pameran.'];
}

// Tutup koneksi
mysqli_close($conn);

// Keluarkan respons JSON
echo json_encode($response);
?>