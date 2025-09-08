<?php
require_once 'config.php';
header('Content-Type: application/json');

$response = [
    'error' => false,
    'karya' => []
];

// Inisialisasi query dasar
$sql = "SELECT 
            k.id_karya, 
            k.judul_karya, 
            k.nama_karya, 
            k.pict_karya, 
            k.created_at,
            k.likes,
            k.comments,
            jk.jenis,
            kt.nama_kategori
        FROM karya k
        LEFT JOIN jenis_karya jk ON k.id_jenis = jk.id_jenis
        LEFT JOIN kategori kt ON k.id_kategori = kt.id_kategori";

// Inisialisasi array untuk parameter prepared statement
$params = [];
$types = "";

// Periksa apakah ada parameter pencarian (q)
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $search_query = "%" . trim($_GET['q']) . "%";
    $sql .= " WHERE k.judul_karya LIKE ? OR k.nama_karya LIKE ?";
    $params[] = $search_query;
    $params[] = $search_query;
    $types = "ss";
}

$sql .= " ORDER BY k.created_at DESC";

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Bind parameter jika ada
    if ($types) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Periksa apakah kolom 'pict_karya' ada
                if (isset($row['pict_karya'])) {
                    $pict_path = '../img/karya/' . $row['pict_karya'];
                    $row['pict_url'] = (file_exists($pict_path) && !empty($row['pict_karya'])) ? $pict_path : '../img/noimage.png';
                    
                    // Tentukan tipe file berdasarkan ekstensi
                    $file_extension = pathinfo($row['pict_karya'], PATHINFO_EXTENSION);
                    $video_extensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm'];
                    $row['file_type'] = in_array(strtolower($file_extension), $video_extensions) ? 'video' : 'image';
                } else {
                    $row['pict_url'] = '../img/noimage.png';
                    $row['file_type'] = 'image';
                }
                
                // Pastikan kolom 'jenis' dan 'nama_kategori' ada
                $row['jenis'] = $row['jenis'] ?? 'Tidak Diketahui';
                $row['nama_kategori'] = $row['nama_kategori'] ?? 'Tidak Diketahui';
                
                $response['karya'][] = $row;
            }
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Gagal mengambil data karya: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    $response['error'] = true;
    $response['message'] = "Gagal menyiapkan statement: " . mysqli_error($conn);
}

mysqli_close($conn);
echo json_encode($response);
?>