<?php
// Ambil nilai QR code dari body POST request
$input = file_get_contents('php://input');
$data = json_decode($input);

if ($data && isset($data->qrCode)) {
    $qrCodeValue = $data->qrCode;

    // Lakukan koneksi ke database
    require_once "koneksi.php"; // Sesuaikan dengan konfigurasi koneksi Anda

    // Periksa apakah QR code sudah ada dalam tabel absen
    $checkQuery = "SELECT id_absen FROM absen WHERE kode_absen = ?";
    $checkStmt = mysqli_prepare($koneksi, $checkQuery);
    
    if ($checkStmt) {
        mysqli_stmt_bind_param($checkStmt, "s", $qrCodeValue);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        // Jika QR code sudah ada, tidak perlu memasukkan ke dalam tabel
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $response = ['status' => 'exists', 'message' => 'QR Code sudah terdaftar.'];
        } else {
            // QR code belum terdaftar, lakukan penyimpanan ke dalam tabel absen
            // Periksa apakah tiket_code ada dalam tabel tiket
            $checkTiketCodeQuery = "SELECT id_tiket FROM tiket WHERE tiket_code = ?";
            $checkTiketCodeStmt = mysqli_prepare($koneksi, $checkTiketCodeQuery);
            
            if ($checkTiketCodeStmt) {
                mysqli_stmt_bind_param($checkTiketCodeStmt, "s", $qrCodeValue);
                mysqli_stmt_execute($checkTiketCodeStmt);
                mysqli_stmt_store_result($checkTiketCodeStmt);

                // Jika tiket_code ditemukan dalam tabel tiket, lakukan penyimpanan ke dalam tabel absen
                if (mysqli_stmt_num_rows($checkTiketCodeStmt) > 0) {
                    $insertQuery = "INSERT INTO absen (kode_absen, created_absen) VALUES (?, NOW())";
                    $insertStmt = mysqli_prepare($koneksi, $insertQuery);

                    if ($insertStmt) {
                        mysqli_stmt_bind_param($insertStmt, "s", $qrCodeValue);
                        mysqli_stmt_execute($insertStmt);
                        $response = ['status' => 'success', 'message' => 'QR Code berhasil disimpan.'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'Gagal menyimpan QR Code.'];
                    }

                    // Tutup statement insert
                    mysqli_stmt_close($insertStmt);
                } else {
                    // Jika tiket_code tidak ditemukan dalam tabel tiket
                    $response = ['status' => 'error', 'message' => 'Code Tiket tidak ditemukan.'];
                }

                // Tutup statement check tiket_code
                mysqli_stmt_close($checkTiketCodeStmt);
            } else {
                // Gagal membuat statement check tiket_code
                $response = ['status' => 'error', 'message' => 'Gagal memeriksa Code Tiket.'];
            }
        }

        // Tutup statement check kode_absen
        mysqli_stmt_close($checkStmt);
    } else {
        // Gagal membuat statement check kode_absen
        $response = ['status' => 'error', 'message' => 'Gagal memeriksa QR Code.'];
    }

    // Tutup koneksi
    mysqli_close($koneksi);

    // Keluarkan respons JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Keluar dari skrip setelah mengirim respons
} else {
    // Jika data QR code tidak diterima
    $response = ['status' => 'error', 'message' => 'Data QR Code tidak diterima.'];
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode($response);
    exit; // Keluar dari skrip karena ada error
}
?>
