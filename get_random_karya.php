<?php
session_start();
require_once 'admin/config.php';
header('Content-Type: application/json');

$response = [
    'success' => false,
    'data' => [],
    'message' => 'Failed to fetch data.'
];

try {
    $id_user = $_SESSION['user_id'] ?? null;

    $sql = "SELECT
                k.id_karya, k.judul_karya, k.nama_karya, k.deskripsi, k.pict_karya, k.slug,
                (SELECT COUNT(*) FROM likes_new WHERE id_karya = k.id_karya) AS likes_count,
                (SELECT COUNT(*) FROM likes_new WHERE id_karya = k.id_karya AND id_user = ?) AS user_liked
            FROM karya k
            ORDER BY RAND()
            LIMIT 3";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $karya_list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $karya_list[] = $row;
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