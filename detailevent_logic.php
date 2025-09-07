<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() sudah dipanggil di file utama (detailevent.php)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'admin-one/dist/koneksi.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
$user_email = isset($_SESSION['user_data']['email']) ? $_SESSION['user_data']['email'] : null;

$slug_target = isset($_GET['slug']) ? htmlspecialchars($_GET['slug']) : '';

if (empty($slug_target)) {
    die("Slug event tidak ditemukan.");
}

$id_event_target = 0;
$query_get_id = "SELECT id_event FROM event WHERE slug = ?";
$stmt_get_id = mysqli_prepare($koneksi, $query_get_id);

if ($stmt_get_id) {
    mysqli_stmt_bind_param($stmt_get_id, "s", $slug_target);
    mysqli_stmt_execute($stmt_get_id);
    mysqli_stmt_bind_result($stmt_get_id, $id_event_target_result);
    mysqli_stmt_fetch($stmt_get_id);
    mysqli_stmt_close($stmt_get_id);
}

if ($id_event_target_result) {
    $id_event_target = $id_event_target_result;
} else {
    die("Event tidak ditemukan.");
}

function generateTicketCode($id_event, $user_id)
{
    $random_part = substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', 6)), 0, 6);
    $random_partt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 2)), 0, 2);
    $tiket_code = $random_partt . $id_event . $user_id . $random_part;
    return $tiket_code;
}

$events_with_tickets = [];

// Perbaikan logika pengecekan tiket:
// Gunakan id_user ATAU email untuk memeriksa tiket
if ($user_id || $user_email) {
    $query_check_tiket = "SELECT id_event, is_verified FROM tiket WHERE (id_user = ? OR email = ?) AND id_event = ?";
    $stmt_check_tiket = mysqli_prepare($koneksi, $query_check_tiket);
    
    // Siapkan parameter, gunakan 0 jika user_id tidak ada, dan string kosong jika email tidak ada
    $id_param = $user_id ?? 0;
    $email_param = $user_email ?? '';
    
    if ($stmt_check_tiket) {
        // PERHATIAN: Perhatikan urutan dan tipe data parameter (i untuk integer, s untuk string)
        mysqli_stmt_bind_param($stmt_check_tiket, "isi", $id_param, $email_param, $id_event_target);
        mysqli_stmt_execute($stmt_check_tiket);
        $result_check_tiket = mysqli_stmt_get_result($stmt_check_tiket);
        
        while ($row_check_tiket = mysqli_fetch_assoc($result_check_tiket)) {
            $events_with_tickets[$row_check_tiket['id_event']] = intval($row_check_tiket['is_verified']);
        }
        mysqli_stmt_close($stmt_check_tiket);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_event']) && isset($_POST['form_type'])) {
    if (!$user_id) {
        echo '<script>alert("Harap Login terlebih dahulu!"); window.location.href="login.php";</script>';
        exit;
    }

    $id_event = intval($_POST['id_event']);
    $form_type = $_POST['form_type'];
    $insert_success = true;

    // Cek apakah user sudah mendaftar event ini
    $sudah_daftar = isset($events_with_tickets[$id_event]);
    $is_verified_status = $sudah_daftar ? $events_with_tickets[$id_event] : 0;

    if ($sudah_daftar) {
        if ($is_verified_status == 0) {
            // Jika sudah daftar tapi belum diverifikasi, tampilkan modal tunggu
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("verificationPendingModal").classList.remove("hidden");
                });
            </script>';
        } else {
            // Jika sudah daftar dan sudah diverifikasi
            echo '<script>alert("Anda sudah memiliki tiket untuk event ini."); window.location.reload();</script>';
        }
        exit;
    } else {
        $nama_lengkap = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : [];
        $email = isset($_POST['email']) ? $_POST['email'] : [];
        $no_whatsapp = isset($_POST['no_whatsapp']) ? $_POST['no_whatsapp'] : [];

        if ($form_type === 'free') {
            // Logika untuk pendaftaran gratis (hanya 1 user)
            $nama_user = trim($nama_lengkap[0]);
            $email_user = trim($email[0]);
            $wa_user = isset($no_whatsapp[0]) ? trim($no_whatsapp[0]) : null;
            $payment_status = 'unpaid';
            $is_verified = 1;
            $tiket_code = generateTicketCode($id_event, $user_id);

            $query_insert_tiket = "INSERT INTO tiket (id_user, id_event, tiket_code, nama_lengkap, email, no_whatsapp, payment_status, is_verified, created_tiket) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt_insert_tiket = mysqli_prepare($koneksi, $query_insert_tiket);
            mysqli_stmt_bind_param($stmt_insert_tiket, "iisssssi", $user_id, $id_event, $tiket_code, $nama_user, $email_user, $wa_user, $payment_status, $is_verified);
            $insert_success = mysqli_stmt_execute($stmt_insert_tiket);
            mysqli_stmt_close($stmt_insert_tiket);

        } elseif ($form_type === 'paid') {
            $bukti_path = null;
            $first_tiket_id = null;
            $upload_error = false;
            $jumlah_user = count($nama_lengkap);

            // Logika diskon
            $harga_per_tiket_query = "SELECT tiket_event FROM event WHERE id_event = ?";
            $stmt_harga = mysqli_prepare($koneksi, $harga_per_tiket_query);
            mysqli_stmt_bind_param($stmt_harga, "i", $id_event);
            mysqli_stmt_execute($stmt_harga);
            mysqli_stmt_bind_result($stmt_harga, $harga_per_tiket);
            mysqli_stmt_fetch($stmt_harga);
            mysqli_stmt_close($stmt_harga);

            $total_harga = 0;
            if ($jumlah_user == 3) {
                if ($harga_per_tiket == 20000) {
                    $total_harga = 50000;
                } elseif ($harga_per_tiket == 35000) {
                    $total_harga = 90000;
                } else {
                    $total_harga = $jumlah_user * $harga_per_tiket;
                }
            } else {
                $total_harga = $jumlah_user * $harga_per_tiket;
            }

            // Proses upload file bukti pembayaran
            $upload_dir = 'uploads/bukti/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            if (!empty($_FILES['bukti_pembayaran']['name'])) {
                $file = $_FILES['bukti_pembayaran'];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $file_size = $file['size'];
                    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowed_ext = ['jpg', 'jpeg', 'png'];
                    if (in_array($file_ext, $allowed_ext) && $file_size <= 2097152) { // Maks 2MB
                        $new_file_name = uniqid('', true) . '.' . $file_ext;
                        $target_path = $upload_dir . $new_file_name;
                        if (move_uploaded_file($file['tmp_name'], $target_path)) {
                            $bukti_path = $target_path;
                        } else {
                            $upload_error = true;
                            echo '<script>alert("Gagal memindahkan file yang diunggah.");</script>';
                        }
                    } else {
                        $upload_error = true;
                        echo '<script>alert("File tidak valid. Pastikan formatnya JPG/PNG dan ukuran kurang dari 2MB.");</script>';
                    }
                } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
                    $upload_error = true;
                    echo '<script>alert("Terjadi kesalahan saat mengunggah file.");</script>';
                }
            } else {
                $upload_error = true;
                echo '<script>alert("Upload bukti pembayaran wajib untuk event berbayar.");</script>';
            }

            if (!$upload_error) {
                // Loop untuk setiap user yang didaftarkan
                foreach ($nama_lengkap as $index => $nama_user) {
                    $email_user = isset($email[$index]) ? trim($email[$index]) : null;
                    $wa_user = isset($no_whatsapp[$index]) ? trim($no_whatsapp[$index]) : null;
                    
                    $payment_status = 'unpaid';
                    $is_verified = 0; // Perlu verifikasi admin
                    
                    $tiket_code = generateTicketCode($id_event, $user_id);
                    
                    $query_insert_tiket = "INSERT INTO tiket (id_user, id_event, tiket_code, nama_lengkap, email, no_whatsapp, payment_status, is_verified, created_tiket) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                    $stmt_insert_tiket = mysqli_prepare($koneksi, $query_insert_tiket);
                    mysqli_stmt_bind_param($stmt_insert_tiket, "iisssssi", $user_id, $id_event, $tiket_code, $nama_user, $email_user, $wa_user, $payment_status, $is_verified);
                    
                    if (!mysqli_stmt_execute($stmt_insert_tiket)) {
                        $insert_success = false;
                        break;
                    }
                    
                    if ($index === 0) {
                        $first_tiket_id = mysqli_insert_id($koneksi);
                    }
                    mysqli_stmt_close($stmt_insert_tiket);
                }

                if ($insert_success && $first_tiket_id && $bukti_path) {
                    $query_insert_bukti = "INSERT INTO path_pembayaran (id_tiket, path_file, total_bayar, created_at) VALUES (?, ?, ?, NOW())";
                    $stmt_insert_bukti = mysqli_prepare($koneksi, $query_insert_bukti);
                    
                    if (!$stmt_insert_bukti) {
                        echo '<script>alert("Gagal mempersiapkan statement path_pembayaran: ' . mysqli_error($koneksi) . '");</script>';
                        $insert_success = false;
                    } else {
                        mysqli_stmt_bind_param($stmt_insert_bukti, "isi", $first_tiket_id, $bukti_path, $total_harga);
                        
                        if (!mysqli_stmt_execute($stmt_insert_bukti)) {
                            echo '<script>alert("Gagal menyimpan bukti pembayaran: ' . mysqli_stmt_error($stmt_insert_bukti) . '");</script>';
                            $insert_success = false;
                        }
                        mysqli_stmt_close($stmt_insert_bukti);
                    }
                }
            }
        }
        
        if ($insert_success) {
            $_SESSION['show_verification_modal'] = true;
            header("Location: detailevent.php?slug=" . $slug_target);
            exit();
        } else {
            $_SESSION['registration_error'] = true;
            header("Location: detailevent.php?slug=" . $slug_target);
            exit();
        }
    }
}

// ... Kode query event detail dan speakers ...
$query_event_detail = "
    SELECT 
        e.id_event, e.judul_event, e.jadwal_event, e.waktu_event, e.kuota, e.lokasi_event, 
        e.tiket_event, e.event_status, e.statusbayar, e.thumbnail_event, e.deskripsi_event
    FROM event e 
    WHERE e.slug = ? AND e.show_event = 1
";

$stmt_event_detail = mysqli_prepare($koneksi, $query_event_detail);
if (!$stmt_event_detail) {
    die("Prepare statement failed: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_event_detail, "s", $slug_target);
mysqli_stmt_execute($stmt_event_detail);
$result_event_detail = mysqli_stmt_get_result($stmt_event_detail);
$row_event = mysqli_fetch_assoc($result_event_detail);
mysqli_stmt_close($stmt_event_detail);

if (!$row_event) {
    die("Event tidak ditemukan atau tidak aktif.");
}

$query_speakers = "SELECT nama_speaker, instansi FROM event_speakers JOIN speakers ON event_speakers.id_speaker = speakers.id_speaker WHERE id_event = ?";
$stmt_speakers = mysqli_prepare($koneksi, $query_speakers);
mysqli_stmt_bind_param($stmt_speakers, "i", $row_event['id_event']);
mysqli_stmt_execute($stmt_speakers);
$result_speakers = mysqli_stmt_get_result($stmt_speakers);
$speakers_data = [];
while ($row = mysqli_fetch_assoc($result_speakers)) {
    $speakers_data[] = $row;
}
mysqli_stmt_close($stmt_speakers);

$query_count_users = "SELECT COUNT(*) as total FROM tiket WHERE id_event = ?";
$stmt_count_users = mysqli_prepare($koneksi, $query_count_users);
mysqli_stmt_bind_param($stmt_count_users, "i", $row_event['id_event']);
mysqli_stmt_execute($stmt_count_users);
$result_count_users = mysqli_stmt_get_result($stmt_count_users);
$row_count_users = mysqli_fetch_assoc($result_count_users);
$total_kuota = intval($row_event['kuota']);
$total_users = intval($row_count_users['total']);
$sisa_kuota = max(0, $total_kuota - $total_users);
mysqli_stmt_close($stmt_count_users);

// Logika untuk menampilkan modal verifikasi
$show_verification_modal = false;
// Cek apakah tiket untuk event ini ada dan statusnya belum diverifikasi (0)
if (isset($events_with_tickets[$row_event['id_event']]) && $events_with_tickets[$row_event['id_event']] == 0) {
    $show_verification_modal = true;
}

?>