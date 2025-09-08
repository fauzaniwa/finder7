<?php
require_once 'config.php';
header('Content-Type: application/json');

$response = ['error' => true, 'message' => ''];

if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $sql = "SELECT 
                k.id_karya, 
                k.judul_karya, 
                k.nama_karya, 
                k.instagram, 
                k.deskripsi, 
                k.NIM, 
                k.pict_karya, 
                k.optional_karya, 
                k.likes,
                k.comments,
                DATE_FORMAT(k.created_at, '%d %M %Y') as created_at,
                jk.jenis,
                kt.nama_kategori
            FROM karya k
            LEFT JOIN jenis_karya jk ON k.id_jenis = jk.id_jenis
            LEFT JOIN kategori kt ON k.id_kategori = kt.id_kategori
            WHERE k.id_karya = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = trim($_GET['id']);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $karya = mysqli_fetch_assoc($result);

                $pict_path = '../img/karya/' . $karya['pict_karya'];
                $karya['pict_url'] = (file_exists($pict_path) && !empty($karya['pict_karya'])) ? $pict_path : '../img/noimage.png';

                // Tentukan tipe file berdasarkan ekstensi
                $file_extension = pathinfo($karya['pict_karya'], PATHINFO_EXTENSION);
                $video_extensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm'];
                $karya['file_type'] = in_array(strtolower($file_extension), $video_extensions) ? 'video' : 'image';

                $response['error'] = false;
                $response['karya'] = $karya;
            } else {
                $response['message'] = "Karya tidak ditemukan.";
            }
        } else {
            $response['message'] = "Terjadi kesalahan saat mengambil detail.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = "Gagal menyiapkan statement.";
    }
} else {
    $response['message'] = "ID karya tidak valid.";
}

mysqli_close($conn);
echo json_encode($response);
?>