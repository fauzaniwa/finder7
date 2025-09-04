<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'admin-one/dist/koneksi.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
$slug_target = isset($_GET['slug']) ? htmlspecialchars($_GET['slug']) : '';

// Validasi apakah slug ada
if (empty($slug_target)) {
    die("Slug event tidak ditemukan.");
}

// Ambil id_event berdasarkan slug
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
if ($user_id) {
    $query_check_tiket = "SELECT id_event FROM tiket WHERE id_user = ?";
    $stmt_check_tiket = mysqli_prepare($koneksi, $query_check_tiket);
    if ($stmt_check_tiket) {
        mysqli_stmt_bind_param($stmt_check_tiket, "i", $user_id);
        mysqli_stmt_execute($stmt_check_tiket);
        $result_check_tiket = mysqli_stmt_get_result($stmt_check_tiket);
        while ($row_check_tiket = mysqli_fetch_assoc($result_check_tiket)) {
            $events_with_tickets[] = intval($row_check_tiket['id_event']);
        }
        mysqli_stmt_close($stmt_check_tiket);
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_event']) && isset($_POST['form_type'])) {
    if (!$user_id) {
        echo '<script>alert("Harap Login terlebih dahulu!"); window.location.href="login.php";</script>';
        exit;
    }

    $id_event = intval($_POST['id_event']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $form_type = $_POST['form_type'];
    $bukti_path = null;

    if (in_array($id_event, $events_with_tickets)) {
        echo '<script>alert("Anda sudah memiliki tiket untuk event ini.");</script>';
    } else {
        $upload_dir = 'uploads/bukti/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_input_name = '';
        if ($form_type === 'free' && !empty($_FILES['kartu_pelajar']['name'])) {
            $file_input_name = 'kartu_pelajar';
        } elseif ($form_type === 'paid' && !empty($_FILES['bukti_pembayaran']['name'])) {
            $file_input_name = 'bukti_pembayaran';
        }

        $upload_error = false;
        if ($file_input_name) {
            $file = $_FILES[$file_input_name];
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
                        echo '<script>alert("Gagal memindahkan file yang diupload.");</script>';
                    }
                } else {
                    $upload_error = true;
                    echo '<script>alert("File tidak valid. Pastikan formatnya JPG/PNG dan ukuran kurang dari 2MB.");</script>';
                }
            } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
                $upload_error = true;
                echo '<script>alert("Terjadi kesalahan saat mengupload file.");</script>';
            }
        }

        $proceed_to_insert = true;
        if ($form_type === 'paid' && $bukti_path === null) {
            echo '<script>alert("Upload bukti pembayaran wajib untuk event berbayar.");</script>';
            $proceed_to_insert = false;
        }

        if (!$upload_error && $proceed_to_insert) {
            $tiket_code = generateTicketCode($id_event, $user_id);
            $query_insert_tiket = "INSERT INTO tiket (id_user, id_event, tiket_code, nama_lengkap, email, bukti_path, created_tiket) VALUES (?, ?, ?, ?, ?, ?, NOW())";

            $stmt_insert_tiket = mysqli_prepare($koneksi, $query_insert_tiket);
            mysqli_stmt_bind_param($stmt_insert_tiket, "iissss", $user_id, $id_event, $tiket_code, $nama_lengkap, $email, $bukti_path);

            if (mysqli_stmt_execute($stmt_insert_tiket)) {
                echo "<script>
                    const registrationModal = document.getElementById('registrationModal');
                    const thankYouModal = document.getElementById('thankYouModal');
                    if (registrationModal) registrationModal.classList.add('hidden');
                    if (thankYouModal) thankYouModal.classList.remove('hidden');
                </script>";
            } else {
                echo '<script>alert("Gagal mendaftar. Silakan coba lagi.");</script>';
            }
            mysqli_stmt_close($stmt_insert_tiket);
        }
    }
}


// Query untuk mengambil detail event berdasarkan slug
$query_event_detail = "
    SELECT 
        e.id_event,
        e.judul_event,
        e.jadwal_event,
        e.waktu_event,
        e.kuota,
        e.lokasi_event,
        e.tiket_event,
        e.event_status,
        e.statusbayar,
        e.thumbnail_event,
        e.deskripsi_event
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

// Query untuk mengambil speakers
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

// Query untuk menghitung total user
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        work: ['Work Sans'],
                    },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        .navbar-scrolled { box-shadow: 2px 2px 30px #0000001a; }
        .navbar { transition: all 0.5s; }
    </style>
    <title>Detail Seminar</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
</head>

<body class="bg-[#0D0D0D]">
    <?php require '_navbar.php'; ?>

    <section class="w-full min-h-screen pt-32 pb-32 bg-[#0D0D0D] font-work">
        <div class="w-[90%] mx-auto flex flex-col lg:flex-row gap-10 lg:items-center bg-[#131313] p-6 rounded-xl">
            <div class="order-first lg:order-last w-full lg:w-1/2 flex justify-center">
                <div class="relative w-full max-w-[650px] aspect-square overflow-hidden">
                    <img src="./img/event/<?php echo htmlspecialchars($row_event['thumbnail_event']); ?>" class="object-cover w-full h-full rounded-2xl">
                    <img src="./img/dekorasi/atas.png" class="absolute -top-4 -left-4 z-10 opacity-35" alt="Dekorasi Kiri">
                    <img src="./img/dekorasi/bawah.png" class="absolute -bottom-4 -right-4 z-10 opacity-35" alt="Dekorasi Kanan">
                </div>
            </div>

            <div class="order-last lg:order-first w-full lg:w-1/2 space-y-5">
                <p class="text-sm text-white/70">Homepage / Jadwal / <span class="text-yellow-400 italic">Detail Acara</span></p>

                <?php if ($row_event['statusbayar'] === 'no'): ?>
                    <span class="inline-block border border-green-400 text-green-400 text-sm font-semibold px-4 py-1 rounded-full">FREE ENTRY</span>
                <?php else: ?>
                    <div class="flex items-center gap-2 bg-gray-800/50 text-white font-semibold px-4 py-2 rounded-full w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8.433 7.418c.158-.103.346-.196.567-.267v1.698a2.5 2.5 0 00-1.134 0v-1.43zM9.5 0a9.5 9.5 0 100 19 9.5 9.5 0 000-19zM.5 9.5a9 9 0 1118 0 9 9 0 01-18 0z" />
                            <path d="M9 7.083A2.502 2.502 0 007.5 9.5v.001c0 .895.46 1.708 1.134 2.166a2.5 2.5 0 002.732 0C12.04 11.21 12.5 10.396 12.5 9.501V9.5A2.5 2.5 0 009 7.083z" />
                        </svg>
                        <span>Rp <?php echo number_format($row_event['tiket_event'], 0, ',', '.'); ?>,-</span>
                    </div>
                <?php endif; ?>

                <h1 class="text-white text-3xl md:text-4xl font-semibold leading-snug">
                    <?php echo htmlspecialchars($row_event['judul_event']); ?>
                </h1>

                <p class="text-white italic text-lg">
                    <?php
                    $speaker_names = array_map(function ($speaker) {
                        return htmlspecialchars($speaker['nama_speaker']);
                    }, $speakers_data);
                    echo implode(', ', $speaker_names);
                    ?>
                </p>

                <div class="space-y-2 text-white">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10m-11 8h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                        </svg>
                        <span><?php echo htmlspecialchars(date('d F Y', strtotime($row_event['jadwal_event']))); ?> | <?php echo htmlspecialchars($row_event['waktu_event']) ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 12.414a4 4 0 1 0-1.414 1.414l4.243 4.243a1 1 0 0 0 1.414-1.414z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                        </svg>
                        <span><?php echo htmlspecialchars($row_event['lokasi_event']); ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21v-1.5a6 6 0 00-6-6M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span><?php echo htmlspecialchars($sisa_kuota); ?> Kuota Tersisa</span>
                    </div>
                </div>

                <?php if ($row_event['statusbayar'] === 'no'): ?>
                    <p class="text-yellow-400 text-sm italic">*untuk pelajar SMA/SMK diwajibkan mengupload KTP/Kartu Pelajar</p>
                <?php endif; ?>

                <div>
                    <h2 class="text-white text-2xl md:text-3xl font-semibold text-left mb-4">Deskripsi Acara</h2>
                    <p class="text-white text-lg text-justify font-light">
                        <?php echo nl2br(htmlspecialchars($row_event['deskripsi_event'])); ?>
                    </p>
                </div>

                <div class="w-full flex py-4">
                    <?php if (!$user_id): ?>
                        <button id="openNotLoginModalBtn" type="button" class="bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-full text-lg transition-all">Daftar</button>
                    <?php elseif (in_array($row_event['id_event'], $events_with_tickets)): ?>
                        <p class="text-[#00E091] font-semibold text-lg">
                            ✅ Kamu sudah memiliki tiket.
                        </p>
                    <?php else: ?>
                        <?php if ($row_event['event_status'] == 0 && $sisa_kuota > 0): ?>
                            <button id="openRegistrationModalBtn" type="button" class="bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-full text-lg transition-all">
                                Daftar
                            </button>
                        <?php elseif ($row_event['event_status'] == 2 || $sisa_kuota <= 0): ?>
                            <button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">Kuota Penuh</button>
                        <?php elseif ($row_event['event_status'] == 1): ?>
                            <button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">Telah Berakhir</button>
                        <?php elseif ($row_event['event_status'] == 4): ?>
                            <button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">Segera Hadir</button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div id="registrationModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4 hidden">
            <?php if ($row_event['statusbayar'] === 'no'): // Modal FREE ?>
                <div class="bg-white rounded-2xl p-8 max-w-lg w-full relative">
                    <button type="button" id="closeRegistrationModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
                    <p class="text-sm text-gray-500 mb-1">Kamu akan mendaftar di seminar/workshop:</p>
                    <h2 class="text-2xl font-bold mb-6"><?php echo htmlspecialchars($row_event['judul_event']); ?></h2>
                    <form id="freeRegistrationForm" method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="id_event" value="<?php echo htmlspecialchars($row_event['id_event']); ?>">
                        <input type="hidden" name="form_type" value="free">
                        <div class="mb-4">
                            <label for="nama_lengkap_free" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap_free" name="nama_lengkap"
                                class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                required>
                        </div>
                        <div class="mb-6">
                            <label for="email_free" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email_free" name="email"
                                class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                required>
                        </div>
                        <div class="mb-6">
                            <label for="kartu_pelajar" class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 text-gray-500 rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:bg-gray-200">
                                <span id="file-name-free">*opsional KTP/Kartu pelajar untuk pelajar SMA/SMK</span>
                                <span class="bg-[#00E091] text-black font-semibold px-4 py-1 rounded-md">Upload File</span>
                            </label>
                            <input type="file" id="kartu_pelajar" name="kartu_pelajar" class="hidden" accept=".jpg, .jpeg, .png">
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG (MAX. 2MB).</p>
                        </div>
                        <button type="submit" class="w-full bg-[#00E091] hover:bg-[#00c77e] text-black font-bold py-3 rounded-lg text-lg">Kirim</button>
                    </form>
                </div>
            <?php else: // Modal PAID ?>
                <div class="bg-white rounded-2xl p-8 max-w-3xl w-full relative">
                    <button type="button" id="closeRegistrationModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
                    <p class="text-sm text-gray-500 mb-1">Kamu akan mendaftar di seminar/workshop:</p>
                    <h2 class="text-2xl font-bold mb-6"><?php echo htmlspecialchars($row_event['judul_event']); ?></h2>
                    <form id="paidRegistrationForm" method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="id_event" value="<?php echo htmlspecialchars($row_event['id_event']); ?>">
                        <input type="hidden" name="form_type" value="paid">
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="w-full md:w-1/2 space-y-4">
                                <div>
                                    <label for="nama_lengkap_paid" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*wajib diisi</span></label>
                                    <input type="text" id="nama_lengkap_paid" name="nama_lengkap" class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                                </div>
                                <div>
                                    <label for="email_paid" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*wajib diisi</span></label>
                                    <input type="email" id="email_paid" name="email" class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fee <span class="text-red-500">*wajib upload</span></label>
                                    <label for="bukti_pembayaran" class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 text-gray-500 rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:bg-gray-200">
                                        <span id="file-name-paid">qris-bukti-payment</span>
                                        <span class="bg-[#00E091] text-black font-semibold px-4 py-1 rounded-md">Upload File</span>
                                    </label>
                                    <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="hidden" accept=".jpg, .jpeg, .png" required>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG (MAX. 2MB).</p>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="font-bold text-lg">QRIS PAY</h3>
                                    <span class="bg-gray-800 text-white px-4 py-2 rounded-full font-semibold">Rp. <?php echo number_format($row_event['tiket_event'], 0, ',', '.'); ?>,-</span>
                                </div>
                                <div class="bg-gray-200 w-full aspect-square rounded-lg flex items-center justify-center">
                                    <img src="./img/qris_placeholder.png" alt="QRIS Code" class="object-contain max-w-full max-h-full p-4">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-6 bg-[#00E091] hover:bg-[#00c77e] text-black font-bold py-3 rounded-lg text-lg">Kirim</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <div id="notLoginModal" class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center relative">
                <button type="button" id="closeNotLoginModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
                <h2 class="text-2xl font-bold mb-4">Oops! Kamu belum punya akun nih.</h2>
                <p class="text-gray-700 mb-6">Ayo daftarkan akunmu untuk mendaftar di seminar/workshop ini!</p>
                <a href="register.php" class="inline-block bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-full text-lg transition-all">Buat Akun</a>
            </div>
        </div>

        <div id="confirmCloseModal" class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center relative">
                <button type="button" id="cancelCloseModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
                <h2 class="text-2xl font-bold mb-4">Kesempatan ini mungkin nggak datang dua kali!</h2>
                <p class="text-gray-700 mb-6">Kamu yakin ingin menutup pop up pendaftaran ini?</p>
                <button type="button" id="confirmCloseModalBtn" class="inline-block bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-2xl text-lg transition-all">Tutup</button>
            </div>
        </div>

        <div id="thankYouModal" class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center relative">
                <h2 class="text-2xl font-bold mb-4">Terima Kasih!</h2>
                <p class="text-gray-700 mb-6">Tunggu 1x24 jam dan cek berkala halaman account page pada bagian Tiket. Hubungi CP admin dibawah jika dalam batas waktu tersebut tiket belum didapatkan atau terdapat bug sistem. CP : 085155471153</p>
                <a href="account.php" class="inline-block bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-2xl text-lg transition-all">Tutup</a>
            </div>
        </div>
    </section>

    <?php
    mysqli_close($koneksi);
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openNotLoginModalBtn = document.getElementById('openNotLoginModalBtn');
            const notLoginModal = document.getElementById('notLoginModal');
            const closeNotLoginModalBtn = document.getElementById('closeNotLoginModalBtn');

            const openRegistrationModalBtn = document.getElementById('openRegistrationModalBtn');
            const registrationModal = document.getElementById('registrationModal');
            const closeRegistrationModalBtn = document.getElementById('closeRegistrationModalBtn');
            const confirmCloseModal = document.getElementById('confirmCloseModal');
            const confirmCloseModalBtn = document.getElementById('confirmCloseModalBtn');
            const cancelCloseModalBtn = document.getElementById('cancelCloseModalBtn');
            const thankYouModal = document.getElementById('thankYouModal');

            const freeRegistrationForm = document.getElementById('freeRegistrationForm');
            const paidRegistrationForm = document.getElementById('paidRegistrationForm');
            const fileInputFree = document.getElementById('kartu_pelajar');
            const fileNameSpanFree = document.getElementById('file-name-free');
            const fileInputPaid = document.getElementById('bukti_pembayaran');
            const fileNameSpanPaid = document.getElementById('file-name-paid');

            // --- Modal Belum Login ---
            if (openNotLoginModalBtn) {
                openNotLoginModalBtn.addEventListener('click', () => {
                    notLoginModal.classList.remove('hidden');
                });
            }
            if (closeNotLoginModalBtn) {
                closeNotLoginModalBtn.addEventListener('click', () => {
                    notLoginModal.classList.add('hidden');
                });
            }
            if (notLoginModal) {
                notLoginModal.addEventListener('click', (event) => {
                    if (event.target === notLoginModal) {
                        notLoginModal.classList.add('hidden');
                    }
                });
            }

            // --- Modal Pendaftaran (Free/Paid) ---
            if (openRegistrationModalBtn) {
                openRegistrationModalBtn.addEventListener('click', () => {
                    registrationModal.classList.remove('hidden');
                });
            }

            // --- Konfirmasi Tutup Modal Pendaftaran ---
            if (closeRegistrationModalBtn) {
                closeRegistrationModalBtn.addEventListener('click', () => {
                    confirmCloseModal.classList.remove('hidden');
                });
            }
            if (confirmCloseModalBtn) {
                confirmCloseModalBtn.addEventListener('click', () => {
                    confirmCloseModal.classList.add('hidden');
                    registrationModal.classList.add('hidden');
                });
            }
            if (cancelCloseModalBtn) {
                cancelCloseModalBtn.addEventListener('click', () => {
                    confirmCloseModal.classList.add('hidden');
                });
            }
            if (confirmCloseModal) {
                confirmCloseModal.addEventListener('click', (event) => {
                    if (event.target === confirmCloseModal) {
                        confirmCloseModal.classList.add('hidden');
                    }
                });
            }

            // --- Thank You Modal (Triggered by form submission, handled in PHP script) ---
            if (thankYouModal) {
                const thankYouCloseBtn = thankYouModal.querySelector('a'); // Get the 'Tutup' button/link
                if (thankYouCloseBtn) {
                    thankYouCloseBtn.addEventListener('click', (event) => {
                        event.preventDefault();
                        thankYouModal.classList.add('hidden');
                        window.location.href = 'account.php';
                    });
                }
                thankYouModal.addEventListener('click', (event) => {
                    if (event.target === thankYouModal) {
                        thankYouModal.classList.add('hidden');
                        window.location.href = 'account.php';
                    }
                });
            }

            // --- File Name Display Logic ---
            if (fileInputFree && fileNameSpanFree) {
                fileInputFree.addEventListener('change', () => {
                    fileNameSpanFree.textContent = fileInputFree.files.length > 0 ? fileInputFree.files[0].name : '*opsional KTP/Kartu pelajar untuk pelajar SMA/SMK';
                });
            }
            if (fileInputPaid && fileNameSpanPaid) {
                fileInputPaid.addEventListener('change', () => {
                    fileNameSpanPaid.textContent = fileInputPaid.files.length > 0 ? fileInputPaid.files[0].name : 'qris-bukti-payment';
                });
            }
        });

        // Script Navbar
        const navEL = document.querySelector('.navbar');
        if (navEL) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 56) {
                    navEL.classList.add('navbar-scrolled');
                } else {
                    navEL.classList.remove('navbar-scrolled');
                }
            });
        }
    </script>
</body>
</html>