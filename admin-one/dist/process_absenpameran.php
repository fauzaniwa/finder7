<?php
// Ambil nilai QR code dari body POST request
$input = file_get_contents('php://input');
$data = json_decode($input);

if ($data && isset($data->qrCode)) {
    $qrCodeValue = $data->qrCode;

    // Lakukan koneksi ke database
    require_once "koneksi.php"; // Sesuaikan dengan konfigurasi koneksi Anda

    // Periksa apakah QR code sudah ada dalam tabel absen
    $checkQuery = "SELECT id_absenpameran FROM absenpameran WHERE kode_absen = ?";
    $checkStmt = mysqli_prepare($koneksi, $checkQuery);
    
    if ($checkStmt) {
        mysqli_stmt_bind_param($checkStmt, "s", $qrCodeValue);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        // Jika QR code sudah ada, tidak perlu memasukkan ke dalam tabel
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $response = ['status' => 'exists', 'message' => 'QR Code sudah terdaftar.'];
        } else {
            // QR code belum terdaftar, lakukan pengecekan ke tabel user
            // Periksa apakah kode_account ada dalam tabel user
            $checkUserCodeQuery = "SELECT id_user FROM user WHERE kode_account = ?";
            $checkUserCodeStmt = mysqli_prepare($koneksi, $checkUserCodeQuery);
            
            if ($checkUserCodeStmt) {
                mysqli_stmt_bind_param($checkUserCodeStmt, "s", $qrCodeValue);
                mysqli_stmt_execute($checkUserCodeStmt);
                mysqli_stmt_store_result($checkUserCodeStmt);

                // Jika kode_account ditemukan dalam tabel user, lakukan penyimpanan ke dalam tabel absen
                if (mysqli_stmt_num_rows($checkUserCodeStmt) > 0) {
                    $insertQuery = "INSERT INTO absenpameran (kode_absen, created_absen) VALUES (?, NOW())";
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
                    // Jika kode_account tidak ditemukan dalam tabel user
                    $response = ['status' => 'error', 'message' => 'Kode Akun tidak ditemukan.'];
                }

                // Tutup statement check kode_account
                mysqli_stmt_close($checkUserCodeStmt);
            } else {
                // Gagal membuat statement check kode_account
                $response = ['status' => 'error', 'message' => 'Gagal memeriksa Kode Akun.'];
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
