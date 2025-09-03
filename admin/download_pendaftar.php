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

// Bangun query SQL dasar
$sql = "SELECT
    t.id_tiket,
    t.tiket_code,
    t.payment_status,
    t.is_verified,
    u.nama AS nama_user,
    u.email,
    u.no_hp,
    e.judul_event AS judul_event,
    e.statusbayar AS event_statusbayar
FROM
    `tiket` AS t
JOIN
    `user` AS u ON t.id_user = u.id_user
JOIN
    `event` AS e ON t.id_event = e.id_event";

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
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

// Tambahkan ORDER BY
$sql .= " ORDER BY " . $order_by;

$registrants = [];
if ($stmt = mysqli_prepare($conn, $sql)) {
    if (!empty($params_where)) {
        mysqli_stmt_bind_param($stmt, $types_where, ...$params_where);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $registrants[] = $row;
    }
    mysqli_stmt_close($stmt);
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
    $row = [
        $i++,
        $registrant['nama_user'],
        $registrant['email'],
        $registrant['no_hp'],
        $registrant['judul_event'],
        $registrant['tiket_code'],
        $registrant['payment_status'] === 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar',
        $registrant['is_verified'] == 1 ? 'Sudah Diverifikasi' : 'Belum Diverifikasi'
    ];
    fputcsv($output, $row);
}

fclose($output);
exit;
?>