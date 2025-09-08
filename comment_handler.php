<?php
session_start();
require_once "admin/config.php";
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id_karya = $data['id_karya'] ?? null;
    $komentar = $data['komentar'] ?? null;
    $id_user = $_SESSION['id'] ?? null;
    
    if (!$id_user) {
        $response['message'] = "Anda harus login untuk berkomentar.";
        echo json_encode($response);
        exit;
    }

    if (empty($id_karya) || empty($komentar)) {
        $response['message'] = "Data tidak lengkap.";
        echo json_encode($response);
        exit;
    }

    // Ambil nama user
    $nama_user = "";
    $sql_user = "SELECT nama FROM user WHERE id_user = ?";
    if ($stmt_user = mysqli_prepare($conn, $sql_user)) {
        mysqli_stmt_bind_param($stmt_user, "i", $id_user);
        mysqli_stmt_execute($stmt_user);
        mysqli_stmt_bind_result($stmt_user, $nama);
        mysqli_stmt_fetch($stmt_user);
        $nama_user = $nama;
        mysqli_stmt_close($stmt_user);
    }

    // Masukkan komentar ke database
    $sql_insert = "INSERT INTO komentar (id_karya, id_user, komentar, created_at) VALUES (?, ?, ?, NOW())";
    if ($stmt_insert = mysqli_prepare($conn, $sql_insert)) {
        mysqli_stmt_bind_param($stmt_insert, "iis", $id_karya, $id_user, $komentar);
        if (mysqli_stmt_execute($stmt_insert)) {
            $response['success'] = true;
            $response['message'] = "Komentar berhasil ditambahkan.";
            $response['komentar'] = htmlspecialchars($komentar);
            $response['nama_user'] = htmlspecialchars($nama_user);
            $response['created_at'] = date('d M Y');
        } else {
            $response['message'] = "Gagal menambahkan komentar: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt_insert);
    } else {
        $response['message'] = "Gagal menyiapkan statement: " . mysqli_error($conn);
    }
    mysqli_close($conn);

} else {
    $response['message'] = "Metode tidak diizinkan.";
}
echo json_encode($response);
?>