<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

// Periksa izin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["role"], ['master', 'seminar', 'workshop'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Anda tidak memiliki izin untuk mengakses data ini.']);
    exit;
}

// Konfigurasi paginasi
$limit = 50;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Ambil parameter filter, pencarian, dan pengurutan
$search_query = isset($_GET['search']) ? '%' . trim($_GET['search']) . '%' : null;
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : null;
$sort_by = isset($_GET['sort']) ? trim($_GET['sort']) : 'terbaru'; // Default 'terbaru'
$role = $_SESSION['role'];

// Tentukan urutan pengurutan
$order_by = 't.created_tiket DESC';
if ($sort_by === 'terlama') {
    $order_by = 't.created_tiket ASC';
}

// Bangun query SQL dasar
$sql_select = "SELECT
    t.id_tiket,
    t.tiket_code,
    t.payment_status,
    t.is_verified,
    t.nama_lengkap,
    t.email,
    t.no_whatsapp,
    t.id_user,
    t.id_event,
    t.created_tiket,
    u.nama AS nama_user,
    u.email AS user_email,
    u.no_hp AS user_no_hp,
    e.judul_event AS judul_event,
    e.statusbayar AS event_statusbayar,
    pp.path_file
FROM
    `tiket` AS t
JOIN
    `user` AS u ON t.id_user = u.id_user
JOIN
    `event` AS e ON t.id_event = e.id_event
LEFT JOIN 
    `path_pembayaran` AS pp ON t.id_tiket = pp.id_tiket";

// Bangun query untuk total data
$sql_count = "SELECT COUNT(*)
FROM `tiket` AS t
JOIN `user` AS u ON t.id_user = u.id_user
JOIN `event` AS e ON t.id_event = e.id_event";

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
    $where_clauses[] = "(u.nama LIKE ? OR u.email LIKE ? OR u.no_hp LIKE ? OR e.judul_event LIKE ?)";
    $params_where[] = $search_query;
    $params_where[] = $search_query;
    $params_where[] = $search_query;
    $params_where[] = $search_query;
    $types_where .= 'ssss';
}

// Gabungkan semua klausa WHERE
if (!empty($where_clauses)) {
    $where_statement = " WHERE " . implode(" AND ", $where_clauses);
    $sql_select .= $where_statement;
    $sql_count .= $where_statement;
}

$registrants = [];
$total_records = 0;

// Eksekusi query untuk menghitung total data
if ($stmt = mysqli_prepare($conn, $sql_count)) {
    if (!empty($params_where)) {
        mysqli_stmt_bind_param($stmt, $types_where, ...$params_where);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($result);
    $total_records = $row[0];
    mysqli_stmt_close($stmt);
}

// Eksekusi query untuk mengambil data
$sql_select .= " ORDER BY " . $order_by . " LIMIT ? OFFSET ?";
$params_select = array_merge($params_where, [$limit, $offset]);
$types_select = $types_where . 'ii';

if ($stmt = mysqli_prepare($conn, $sql_select)) {
    mysqli_stmt_bind_param($stmt, $types_select, ...$params_select);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $raw_registrants = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $raw_registrants[] = $row;
    }
    mysqli_stmt_close($stmt);
    
    // Proses pengelompokan data
    $groups = [];
    foreach ($raw_registrants as $r) {
        $key = $r['id_user'] . '-' . $r['id_event'] . '-' . $r['created_tiket'];
        if (!isset($groups[$key])) {
            $groups[$key] = [];
        }
        $groups[$key][] = $r;
    }
    
    foreach ($groups as $group) {
        // Jika grup memiliki lebih dari 1 anggota
        if (count($group) > 1) {
            // Ambil data anggota pertama sebagai representasi grup
            $main_registrant = $group[0];
            $main_registrant['is_grouped'] = true;
            $main_registrant['group_members'] = $group; // Tambahkan array anggota ke data utama
            $registrants[] = $main_registrant;
        } else {
            // Jika hanya satu anggota, tambahkan seperti biasa
            $registrants[] = $group[0];
        }
    }

} else {
    http_response_code(500);
    echo json_encode(['error' => 'Terjadi kesalahan saat mengambil data pendaftar: ' . mysqli_error($conn)]);
    exit;
}

mysqli_close($conn);

echo json_encode([
    'data' => $registrants,
    'total_records' => $total_records,
    'total_pages' => ceil($total_records / $limit),
    'current_page' => $page
]);
?>