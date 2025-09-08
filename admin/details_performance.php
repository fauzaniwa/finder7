<?php
require_once 'config.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM performance WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $id;
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $performance_data = mysqli_fetch_assoc($result);
            if ($performance_data) {
                echo json_encode(['performance' => $performance_data]);
            } else {
                echo json_encode(['error' => 'Data penampil tidak ditemukan.']);
            }
        } else {
            echo json_encode(['error' => 'Terjadi kesalahan saat mengambil data.']);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo json_encode(['error' => 'ID penampil tidak diberikan.']);
}
mysqli_close($conn);
?>