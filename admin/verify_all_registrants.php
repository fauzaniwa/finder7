<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

// Periksa izin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["role"], ['master', 'seminar', 'workshop'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Anda tidak memiliki izin untuk melakukan tindakan ini.']);
    exit;
}

// Ambil parameter dari request POST
$input = json_decode(file_get_contents("php://input"), true);

$event_id = isset($input['event_id']) ? intval($input['event_id']) : null;
$search_query = isset($input['search']) ? trim($input['search']) : '';
$role = $_SESSION['role'];

// Persiapan parameter untuk klausa WHERE
$params_where = [];
$types_where = '';
$where_clauses = [];

// Tambahkan filter berdasarkan peran jika bukan master
if ($role !== 'master') {
    $where_clauses[] = "e.kategori = ?";
    $params_where[] = $role;
    $types_where .= 's';
}

// Tambahkan filter event_id jika ada
if ($event_id) {
    $where_clauses[] = "e.id_event = ?";
    $params_where[] = $event_id;
    $types_where .= 'i';
}

// Tambahkan filter pencarian jika ada
if ($search_query) {
    $search_param = '%' . $search_query . '%';
    $where_clauses[] = "(u.nama LIKE ? OR u.email LIKE ? OR u.no_hp LIKE ? OR e.judul_event LIKE ?)";
    $params_where[] = $search_param;
    $params_where[] = $search_param;
    $params_where[] = $search_param;
    $params_where[] = $search_param;
    $types_where .= 'ssss';
}

// Bangun query SQL UPDATE
$sql_update = "UPDATE `tiket` AS t
JOIN `user` AS u ON t.id_user = u.id_user
JOIN `event` AS e ON t.id_event = e.id_event
SET
    t.is_verified = 1,
    t.payment_status = 'paid'
";

// Gabungkan semua klausa WHERE
if (!empty($where_clauses)) {
    $where_statement = " WHERE " . implode(" AND ", $where_clauses);
    $sql_update .= $where_statement;
}

// Persiapan dan eksekusi statement
if ($stmt = mysqli_prepare($conn, $sql_update)) {
    if (!empty($params_where)) {
        mysqli_stmt_bind_param($stmt, $types_where, ...$params_where);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        echo json_encode([
            'success' => true,
            'message' => "Berhasil memverifikasi $affected_rows pendaftar.",
            'affected_rows' => $affected_rows
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal mengeksekusi query: ' . mysqli_stmt_error($stmt)]);
    }
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal mempersiapkan query: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>