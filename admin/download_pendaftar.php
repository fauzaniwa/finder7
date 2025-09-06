<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Periksa izin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["role"], ['master', 'seminar', 'workshop'])) {
    header("location: login.php");
    exit;
}

// Ambil parameter filter, pencarian, dan pengurutan
$search_query = isset($_GET['search']) ? '%' . trim($_GET['search']) . '%' : null;
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : null;
$sort_by = isset($_GET['sort']) ? trim($_GET['sort']) : 'terbaru';
$role = $_SESSION['role'];

// Tentukan urutan pengurutan
$order_by = 't.created_tiket DESC';
if ($sort_by === 'terlama') {
    $order_by = 't.created_tiket ASC';
}

// Bangun query SQL utama
$sql = "SELECT
    t.id_tiket,
    t.id_user,
    t.id_event,
    t.created_tiket,
    t.tiket_code,
    t.payment_status,
    t.is_verified,
    t.nama_lengkap AS nama_user,
    t.email,
    t.no_whatsapp AS no_hp,
    e.judul_event AS judul_event
FROM `tiket` AS t
JOIN `event` AS e ON t.id_event = e.id_event";

// Persiapan parameter untuk klausa WHERE
$params = [];
$types = '';
$where_clauses = [];

if ($role !== 'master') {
    $where_clauses[] = "e.kategori = ?";
    $params[] = $role;
    $types .= 's';
}

if ($event_id) {
    $where_clauses[] = "e.id_event = ?";
    $params[] = $event_id;
    $types .= 'i';
}

if ($search_query) {
    $where_clauses[] = "(t.nama_lengkap LIKE ? OR t.email LIKE ? OR t.no_whatsapp LIKE ? OR e.judul_event LIKE ?)";
    $params[] = $search_query;
    $params[] = $search_query;
    $params[] = $search_query;
    $params[] = $search_query;
    $types .= 'ssss';
}

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

$sql .= " ORDER BY " . $order_by;

$registrants = [];
if ($stmt = mysqli_prepare($conn, $sql)) {
    if (!empty($params)) {
        call_user_func_array('mysqli_stmt_bind_param', array_merge([$stmt, $types], $params));
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $registrants[] = $row;
    }
    mysqli_stmt_close($stmt);
}

// Query untuk cek grup
$sql_group = "SELECT `id_user`, `id_event`, `created_tiket` FROM `tiket`";
$grouped_data = [];
if (!empty($where_clauses)) {
    $sql_group .= " WHERE " . implode(" AND ", $where_clauses);
}
$sql_group .= " GROUP BY `id_user`, `id_event`, `created_tiket` HAVING COUNT(*) > 1";

if ($stmt_group = mysqli_prepare($conn, $sql_group)) {
    if (!empty($params)) {
        call_user_func_array('mysqli_stmt_bind_param', array_merge([$stmt_group, $types], $params));
    }
    mysqli_stmt_execute($stmt_group);
    $result_group = mysqli_stmt_get_result($stmt_group);
    while ($row = mysqli_fetch_assoc($result_group)) {
        $key = $row['id_user'] . '|' . $row['id_event'] . '|' . $row['created_tiket'];
        $grouped_data[$key] = true;
    }
    mysqli_stmt_close($stmt_group);
}

mysqli_close($conn);

// Header untuk file CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_pendaftar_' . date('Y-m-d') . '.csv');

// Buka output stream
$output = fopen('php://output', 'w');

// Header CSV
fputcsv($output, array('No', 'Nama User', 'Email', 'No. HP', 'Nama Event', 'Kode Tiket', 'Status Bayar', 'Status Verifikasi'));

// Isi data
$i = 1;
foreach ($registrants as $registrant) {
    $key = $registrant['id_user'] . '|' . $registrant['id_event'] . '|' . $registrant['created_tiket'];
    $is_group = isset($grouped_data[$key]);
    
    $nama_user = $registrant['nama_user'];
    if ($is_group) {
        $nama_user .= ' (Group)';
    }

    $row = [
        $i++,
        $nama_user,
        $registrant['email'],
        $registrant['no_hp'],
        $registrant['judul_event'],
        $registrant['tiket_code'],
        $registrant['payment_status'] == 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar',
        $registrant['is_verified'] == 1 ? 'Sudah Diverifikasi' : 'Belum Diverifikasi'
    ];
    fputcsv($output, $row);
}

fclose($output);
exit;
?>