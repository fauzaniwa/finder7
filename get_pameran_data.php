<?php
session_start();
require_once 'admin/config.php';
header('Content-Type: application/json');

$response = [
    'success' => false,
    'data' => [],
    'hasMore' => false,
    'message' => 'Failed to fetch data.'
];

try {
    // Tentukan ID user yang sedang login, jika ada
    $id_user = $_SESSION['user_id'] ?? null;

    // Ambil parameter limit dan offset dari request
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 6;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    
    // Pastikan limit dan offset tidak negatif
    if ($limit < 1) $limit = 6;
    if ($offset < 0) $offset = 0;

    $sql = "SELECT
                k.id_karya, k.judul_karya, k.nama_karya, k.deskripsi, k.pict_karya,
                k.optional_karya, k.slug, j.jenis AS nama_jenis, kat.nama_kategori,
                (SELECT COUNT(*) FROM likes_new WHERE id_karya = k.id_karya) AS likes_count,
                (SELECT COUNT(*) FROM likes_new WHERE id_karya = k.id_karya AND id_user = ?) AS user_liked
            FROM karya k
            JOIN jenis_karya j ON k.id_jenis = j.id_jenis
            JOIN kategori kat ON j.id_kategori = kat.id_kategori";

    $params = [];
    $types = '';
    $where_clauses = [];

    // Filter berdasarkan kategori
    $filter_category = $_GET['kategori'] ?? '';
    if (!empty($filter_category)) {
        $where_clauses[] = "kat.nama_kategori = ?";
        $params[] = $filter_category;
        $types .= 's';
    }

    // Filter berdasarkan jenis karya
    $filter_jenis = $_GET['jenis'] ?? '';
    if (!empty($filter_jenis)) {
        $where_clauses[] = "j.jenis = ?";
        $params[] = $filter_jenis;
        $types .= 's';
    }

    // Filter berdasarkan pencarian
    $search_query = $_GET['search'] ?? '';
    if (!empty($search_query)) {
        $where_clauses[] = "(k.judul_karya LIKE ? OR k.nama_karya LIKE ? OR k.deskripsi LIKE ?)";
        $search_param = '%' . $search_query . '%';
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= 'sss';
    }

    // Gabungkan prepared statement parameter
    // Parameter pertama untuk user_liked harus selalu ada
    array_unshift($params, $id_user);
    $types = 'i' . $types;

    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    $sql .= " ORDER BY k.created_at DESC LIMIT ? OFFSET ?";
    
    // Tambahkan parameter limit dan offset ke dalam array params
    $params[] = $limit + 1; // Ambil satu data ekstra untuk cek apakah ada data lagi
    $params[] = $offset;
    $types .= 'ii';

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $karya_list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $karya_list[] = $row;
        }

        // Cek apakah ada data lebih dari limit
        if (count($karya_list) > $limit) {
            array_pop($karya_list); // Hapus data ekstra
            $response['hasMore'] = true;
        }

        $response['success'] = true;
        $response['message'] = 'Data fetched successfully.';
        $response['data'] = $karya_list;
        mysqli_stmt_close($stmt);
    } else {
        throw new Exception(mysqli_error($conn));
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

mysqli_close($conn);
echo json_encode($response);