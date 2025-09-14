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

// Periksa apakah QR code sudah ada dalam tabel absen
$checkAbsenQuery = "SELECT id_absen FROM absen WHERE kode_absen = ?";
$checkAbsenStmt = mysqli_prepare($conn, $checkAbsenQuery);

if ($checkAbsenStmt) {
    mysqli_stmt_bind_param($checkAbsenStmt, "s", $qrCodeValue);
    mysqli_stmt_execute($checkAbsenStmt);
    mysqli_stmt_store_result($checkAbsenStmt);

    // Jika QR code sudah ada, berikan respons 'exists'
    if (mysqli_stmt_num_rows($checkAbsenStmt) > 0) {
        $response = ['status' => 'exists', 'message' => 'QR Code sudah terdaftar.'];
    } else {
        // QR code belum terdaftar, lakukan pengecekan ke tabel tiket
        $checkTiketQuery = "SELECT id_tiket, nama_lengkap FROM tiket WHERE tiket_code = ?";
        $checkTiketStmt = mysqli_prepare($conn, $checkTiketQuery);
        
        if ($checkTiketStmt) {
            mysqli_stmt_bind_param($checkTiketStmt, "s", $qrCodeValue);
            mysqli_stmt_execute($checkTiketStmt);
            mysqli_stmt_store_result($checkTiketStmt);

            // Jika tiket_code ditemukan dalam tabel tiket
            if (mysqli_stmt_num_rows($checkTiketStmt) > 0) {
                // Ambil nama lengkap dari hasil query
                mysqli_stmt_bind_result($checkTiketStmt, $id_tiket, $nama_lengkap);
                mysqli_stmt_fetch($checkTiketStmt);

                // Lakukan penyimpanan ke dalam tabel absen
                $insertQuery = "INSERT INTO absen (kode_absen, created_absen) VALUES (?, NOW())";
                $insertStmt = mysqli_prepare($conn, $insertQuery);

                if ($insertStmt) {
                    mysqli_stmt_bind_param($insertStmt, "s", $qrCodeValue);
                    mysqli_stmt_execute($insertStmt);
                    
                    // Berikan respons 'success' beserta nama lengkap
                    $response = [
                        'status' => 'success', 
                        'message' => 'Absensi berhasil!',
                        'nama_lengkap' => $nama_lengkap
                    ];
                } else {
                    $response = ['status' => 'error', 'message' => 'Gagal menyimpan QR Code ke tabel absen.'];
                }

                mysqli_stmt_close($insertStmt);
            } else {
                // Jika tiket_code tidak ditemukan dalam tabel tiket
                $response = ['status' => 'error', 'message' => 'Kode Tiket tidak ditemukan.'];
            }

            mysqli_stmt_close($checkTiketStmt);
        } else {
            $response = ['status' => 'error', 'message' => 'Gagal memeriksa Kode Tiket.'];
        }
    }

    mysqli_stmt_close($checkAbsenStmt);
} else {
    $response = ['status' => 'error', 'message' => 'Gagal memeriksa QR Code di tabel absen.'];
}

// Tutup koneksi
mysqli_close($conn);

// Keluarkan respons JSON
echo json_encode($response);
?>