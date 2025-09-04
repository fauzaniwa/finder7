<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Koneksi ke database
include 'admin-one/dist/koneksi.php';

// Ambil user_id dari session jika ada
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Query untuk mendapatkan data dari tabel event dengan show_event = 1 dan diurutkan berdasarkan urutan_show
$query_event = "SELECT id_event, slug, judul_event, jadwal_event, waktu_event, kuota, lokasi_event, tiket_event, event_status FROM event WHERE show_event = 1 ORDER BY urutan_show ASC";

// Persiapkan statement untuk query event
$stmt_event = mysqli_prepare($koneksi, $query_event);
if (!$stmt_event) {
    die('Prepare statement event failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_execute($stmt_event);

// Ambil hasil query data event
$result_event = mysqli_stmt_get_result($stmt_event);

// Array untuk menyimpan data event
$events_data = [];
while ($row_event = mysqli_fetch_assoc($result_event)) {
    $id_event = $row_event['id_event'];
    $slug_event = $row_event['slug']; 
    // Query untuk menghitung jumlah pengguna yang mendaftar untuk event ini
    $query_count_users = "SELECT COUNT(*) as total FROM tiket WHERE id_event = ?";
    $stmt_count_users = mysqli_prepare($koneksi, $query_count_users);
    mysqli_stmt_bind_param($stmt_count_users, "i", $id_event);
    mysqli_stmt_execute($stmt_count_users);
    $result_count_users = mysqli_stmt_get_result($stmt_count_users);
    $row_count_users = mysqli_fetch_assoc($result_count_users);

    // Total kuota dan total pengguna yang telah mendaftar
    $total_kuota = isset($row_event['kuota']) ? intval($row_event['kuota']) : 0;
    $total_users = intval($row_count_users['total']);

    // Hitung sisa kuota
    $sisa_kuota = $total_kuota - $total_users;

    // Tambahkan sisa kuota ke data event
    $row_event['sisa_kuota'] = $sisa_kuota;

    // Query untuk mendapatkan speakers untuk event ini
    $query_speakers = "SELECT s.nama_speaker, s.instansi
                       FROM event_speakers es
                       JOIN speakers s ON es.id_speaker = s.id_speaker
                       WHERE es.id_event = ?";
    $stmt_speakers = mysqli_prepare($koneksi, $query_speakers);
    mysqli_stmt_bind_param($stmt_speakers, "i", $id_event);
    mysqli_stmt_execute($stmt_speakers);
    $result_speakers = mysqli_stmt_get_result($stmt_speakers);

    // Array untuk menyimpan data speakers
    $speakers_data = [];
    while ($row_speaker = mysqli_fetch_assoc($result_speakers)) {
        $speakers_data[] = $row_speaker;
    }

    // Tambahkan data speakers ke data event
    $row_event['speakers'] = $speakers_data;

    // Simpan data event ke dalam array
    $events_data[$id_event] = $row_event;

    // Tutup statement speakers
    mysqli_stmt_close($stmt_speakers);
}

// Tutup statement event
mysqli_stmt_close($stmt_event);

// Jika user sudah login, cek tiket yang dimiliki
$events_with_tickets = [];
if ($user_id) {
    // Query untuk mengecek apakah event sudah terhubung dengan user di tabel tiket
    $query_check_tiket = "SELECT id_event FROM tiket WHERE id_user = ?";

    // Persiapkan statement untuk query tiket
    $stmt_check_tiket = mysqli_prepare($koneksi, $query_check_tiket);
    if (!$stmt_check_tiket) {
        die('Prepare statement check tiket failed: ' . mysqli_error($koneksi));
    }
    mysqli_stmt_bind_param($stmt_check_tiket, "i", $user_id);
    mysqli_stmt_execute($stmt_check_tiket);

    // Ambil hasil query data tiket
    $result_check_tiket = mysqli_stmt_get_result($stmt_check_tiket);

    // Array untuk menyimpan id_event yang sudah terhubung dengan user
    while ($row_check_tiket = mysqli_fetch_assoc($result_check_tiket)) {
        $events_with_tickets[] = $row_check_tiket['id_event'];
    }

    // Tutup statement check tiket
    mysqli_stmt_close($stmt_check_tiket);
}

// Fungsi untuk generate tiket code
function generateTicketCode($id_event, $user_id)
{
    // Generate 6 digit random alphanumeric
    $random_part = substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', 6)), 0, 6);
    $random_partt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 2)), 0, 2);
    // Combine components into tiket_code
    $tiket_code = $random_partt . $id_event . $user_id . $random_part;
    return $tiket_code;
}

// Handle POST request untuk mendapatkan tiket
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_event'])) {
    // Jika user tidak login, beri alert untuk login
    if (!$user_id) {
        echo '<script>alert("Harap Login terlebih dahulu!");</script>';
    } else {
        // Ambil id_event dari form
        $id_event = $_POST['id_event'];

        // Jika user belum memiliki tiket untuk event tersebut, insert tiket baru
        if (!in_array($id_event, $events_with_tickets)) {
            // Generate tiket_code
            $tiket_code = generateTicketCode($id_event, $user_id);

            // Query untuk insert tiket baru
            $query_insert_tiket = "INSERT INTO tiket (id_user, id_event, tiket_code, created_tiket) VALUES (?, ?, ?, NOW())";

            // Persiapkan statement untuk insert tiket
            $stmt_insert_tiket = mysqli_prepare($koneksi, $query_insert_tiket);
            if (!$stmt_insert_tiket) {
                die('Prepare statement insert tiket failed: ' . mysqli_error($koneksi));
            }

            // Bind parameter ke statement
            mysqli_stmt_bind_param($stmt_insert_tiket, "iis", $user_id, $id_event, $tiket_code);

            // Eksekusi statement
            if (mysqli_stmt_execute($stmt_insert_tiket)) {
                // Jika insert berhasil, beri alert sukses
                echo "<script>
                        alert('Ticket berhasil di claim. Cek profile untuk mengambil tiket.');
                        document.location='account.php';
                      </script>";
            } else {
                // Jika insert gagal, beri alert gagal
                echo '<script>alert("Gagal mengambil tiket. Silakan coba lagi.");</script>';
            }

            // Tutup statement insert tiket
            mysqli_stmt_close($stmt_insert_tiket);
        }
    }
}

// Tutup koneksi MySQL
mysqli_close($koneksi);
?>