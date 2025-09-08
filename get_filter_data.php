<?php
require_once 'admin/config.php';
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Failed to fetch data.'
];

try {
    $categories = [];
    $categorySql = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
    $result = mysqli_query($conn, $categorySql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
    } else {
        throw new Exception(mysqli_error($conn));
    }

    $jenis = [];
    $jenisSql = "SELECT j.id_jenis, j.jenis, k.nama_kategori FROM jenis_karya j JOIN kategori k ON j.id_kategori = k.id_kategori ORDER BY j.jenis ASC";
    $result = mysqli_query($conn, $jenisSql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $jenis[] = $row;
        }
    } else {
        throw new Exception(mysqli_error($conn));
    }

    $response['success'] = true;
    $response['message'] = 'Filters fetched successfully.';
    $response['data'] = [
        'categories' => $categories,
        'jenis' => $jenis
    ];
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

mysqli_close($conn);
echo json_encode($response);