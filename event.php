<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

/* =========================
   KONEKSI DATABASE
   ========================= */
require_once __DIR__ . '/admin-one/dist/koneksi.php';
if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    die('Koneksi DB belum terinisialisasi. Pastikan koneksi.php mengatur variabel $koneksi.');
}
if (!mysqli_ping($koneksi)) {
    die('Gagal terhubung ke database: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, 'utf8mb4');

/* =========================
   AMBIL USER ID & EMAIL (JIKA LOGIN)
   ========================= */
$user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
$user_email = isset($_SESSION['user_data']['email']) ? $_SESSION['user_data']['email'] : null;

/* =========================
   AMBIL DATA EVENT + SISA KUOTA + SPEAKERS
   ========================= */
$query_event = "
    SELECT
        e.id_event,
        e.judul_event,
        e.jadwal_event,
        e.waktu_event,
        e.kuota,
        e.lokasi_event,
        e.tiket_event,
        e.event_status,
        e.thumbnail_event,
        e.statusbayar,
        e.slug,  -- Kolom slug ditambahkan di sini
        (e.kuota - COALESCE(t.cnt, 0)) AS sisa_kuota
    FROM event e
    LEFT JOIN (
        SELECT id_event, COUNT(*) AS cnt
        FROM tiket
        GROUP BY id_event
    ) t ON t.id_event = e.id_event
    WHERE e.show_event = 1
    ORDER BY e.urutan_show ASC
";

$stmt_event = mysqli_prepare($koneksi, $query_event);
if (!$stmt_event) {
    die('Prepare statement event failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_execute($stmt_event);
$result_event = mysqli_stmt_get_result($stmt_event);

$events_data = [];
while ($row_event = mysqli_fetch_assoc($result_event)) {
    $id_event = $row_event['id_event'];

    // Ambil speakers untuk event ini
    $query_speakers = "SELECT s.nama_speaker, s.instansi
                         FROM event_speakers es
                         JOIN speakers s ON es.id_speaker = s.id_speaker
                         WHERE es.id_event = ?";
    $stmt_speakers = mysqli_prepare($koneksi, $query_speakers);
    mysqli_stmt_bind_param($stmt_speakers, "i", $id_event);
    mysqli_stmt_execute($stmt_speakers);
    $result_speakers = mysqli_stmt_get_result($stmt_speakers);

    $speakers_data = [];
    while ($row_speaker = mysqli_fetch_assoc($result_speakers)) {
        $speakers_data[] = $row_speaker;
    }
    mysqli_stmt_close($stmt_speakers);

    $row_event['sisa_kuota'] = max(0, (int) ($row_event['sisa_kuota'] ?? 0));
    $row_event['speakers'] = $speakers_data;
    $events_data[] = $row_event;
}
mysqli_stmt_close($stmt_event);

/* =========================
   CEK TIKET USER (JIKA LOGIN)
   ========================= */
$events_with_tickets = [];
if ($user_id || $user_email) {
    // Gunakan OR untuk mencocokkan id_user atau email
    $query_check_tiket = "SELECT id_event, is_verified FROM tiket WHERE id_user = ? OR email = ?";
    $stmt_check_tiket = mysqli_prepare($koneksi, $query_check_tiket);

    // Asumsi: jika $user_id null, kita gunakan 0 atau null. Dan jika $user_email null, kita gunakan string kosong.
    $id_param = $user_id ?? 0;
    $email_param = $user_email ?? '';

    if ($stmt_check_tiket) {
        mysqli_stmt_bind_param($stmt_check_tiket, "is", $id_param, $email_param);
        mysqli_stmt_execute($stmt_check_tiket);
        $result_check_tiket = mysqli_stmt_get_result($stmt_check_tiket);

        while ($row_check_tiket = mysqli_fetch_assoc($result_check_tiket)) {
            $events_with_tickets[$row_check_tiket['id_event']] = intval($row_check_tiket['is_verified']);
        }
        mysqli_stmt_close($stmt_check_tiket);
    }
}


/* =========================
   FUNGSI KODE TIKET
   ========================= */
function generateTicketCode($id_event, $user_id)
{
    $random_part = substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', 6)), 0, 6);
    $random_partt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 2)), 0, 2);
    return $random_partt . $id_event . $user_id . $random_part;
}

/* =========================
   HANDLE CLAIM TIKET (POST)
   ========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_event'])) {
    if (!$user_id) {
        echo '<script>alert("Harap Login terlebih dahulu!");</script>';
    } else {
        $id_event = (int) $_POST['id_event'];

        // Menggunakan array yang diperbarui
        if (!array_key_exists($id_event, $events_with_tickets)) {
            $tiket_code = generateTicketCode($id_event, $user_id);

            $query_insert_tiket = "INSERT INTO tiket (id_user, id_event, tiket_code, email, created_tiket) VALUES (?, ?, ?, ?, NOW())";
            $stmt_insert_tiket = mysqli_prepare($koneksi, $query_insert_tiket);
            if (!$stmt_insert_tiket) {
                die('Prepare statement insert tiket failed: ' . mysqli_error($koneksi));
            }
            // Tambahkan email ke query insert
            mysqli_stmt_bind_param($stmt_insert_tiket, "iiss", $user_id, $id_event, $tiket_code, $user_email);

            if (mysqli_stmt_execute($stmt_insert_tiket)) {
                echo "<script>
                        alert('Ticket berhasil di-claim. Cek profile untuk mengambil tiket.');
                        document.location='account.php';
                      </script>";
                exit;
            } else {
                echo '<script>alert("Gagal mengambil tiket. Silakan coba lagi.");</script>';
            }
            mysqli_stmt_close($stmt_insert_tiket);
        }
    }
}

/* =========================
   GROUPING BY TANGGAL
   ========================= */
function indoMonth($n)
{
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $n = (int) $n;
    return $bulan[$n] ?? '';
}

$grouped_by_date = [];
foreach ($events_data as $ev) {
    $dateKey = $ev['jadwal_event'] ?? '';
    if ($dateKey !== '') {
        $grouped_by_date[$dateKey][] = $ev;
    }
}
ksort($grouped_by_date);
$events_found = !empty($grouped_by_date);
?>


<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&family=Inter:wght@400;700&display=swap"
        rel="stylesheet" />

    <title>Finder - Jadwal Acara</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />

    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    work: ['Work Sans'],
                    sans: ['Inter', 'sans-serif'],
                },
                animation: {
                    'spin-slow': 'spin 4s linear infinite',
                    'loop-scroll': 'loop-scroll 10s linear infinite',
                },
                keyframes: {
                    'loop-scroll': {
                        from: {
                            transform: 'translateX(0)'
                        },
                        to: {
                            transform: 'translateX(-100%)'
                        },
                    },
                },
            },
        },
    };
    </script>
    <style type="text/tailwindcss">
        .navbar-scrolled { box-shadow: 2px 2px 30px #000000; }
        .ext-scrolled { color: black; }
        .navbar { transition: all 0.5s; }
    </style>
</head>

<body class="bg-black text-gray-100">
    <?php require '_navbar.php'; ?>

    <main class="container mx-auto px-6 py-20 pt-32">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-12 text-gray-100 text-center mb-16">Jadwal Acara</h1>

        <?php if ($events_found): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-y-12 md:gap-x-8">

            <?php
                $card_count = 0;
                $total_cards = count($grouped_by_date);
                foreach ($grouped_by_date as $tanggal => $events):
                    $card_count++;
                    $border_class = ($card_count < $total_cards) ? 'lg:border-r lg:border-neutral-800' : '';
                    ?>
            <div class="flex flex-col space-y-8 px-4 <?php echo $border_class; ?>">
                <div class="flex items-center space-x-4">
                    <span class="text-6xl md:text-7xl font-bold"><?php echo date('d', strtotime($tanggal)); ?></span>
                    <div class="flex flex-col">
                        <span
                            class="text-2xl md:text-3xl"><?php echo indoMonth(date('n', strtotime($tanggal))); ?></span>
                        <span
                            class="text-2xl md:text-3xl text-neutral-400"><?php echo date('Y', strtotime($tanggal)); ?></span>
                    </div>
                </div>

                <div class="flex flex-col space-y-12">
                    <div class="flex flex-col lg:flex-row items-start gap-0 lg:gap-6">
                        <?php foreach ($events as $event): ?>
                        <div class="w-full lg:w-1/3 flex-shrink-0">
                            <?php if (!empty($event['thumbnail_event'])): ?>
                            <img src="./img/thumbnail/<?php echo htmlspecialchars($event['thumbnail_event']); ?>"
                                alt="<?php echo htmlspecialchars($event['judul_event']); ?>"
                                class="w-full aspect-square object-cover rounded-xl">
                            <?php else: ?>
                            <div class="w-full aspect-square bg-neutral-800 rounded-xl"></div>
                            <?php endif; ?>
                        </div>
                        <br>
                        <div class="w-full">
                            <h3 class="text-lg font-bold"><?php echo htmlspecialchars($event['judul_event']); ?></h3>
                            <?php if (!empty($event['speakers'])): ?>
                            <p class="text-sm text-neutral-400 mt-1">
                                Speakers:
                                <?php
                                                $speaker_names = array_map(function ($speaker) {
                                                    $instansi = !empty($speaker['instansi']) ? " ({$speaker['instansi']})" : "";
                                                    return htmlspecialchars($speaker['nama_speaker'] . $instansi);
                                                }, $event['speakers']);
                                                echo implode(', ', $speaker_names);
                                                ?>
                            </p>
                            <?php endif; ?>

                            <div class="flex justify-between items-center mt-1 pr-3">
                                <span class="text-neutral-400 text-sm">Waktu:
                                    <?php echo htmlspecialchars($event['waktu_event']); ?></span>
                                <span class="font-semibold text-sm">
                                    <?php
                                                $status_bayar = htmlspecialchars($event['statusbayar']);
                                                echo ($status_bayar == 'yes') ? 'Berbayar' : 'Gratis';
                                                ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center mt-1 pr-3">
                                <span class="text-neutral-400 text-sm">Lokasi:
                                    <?php echo htmlspecialchars($event['lokasi_event']); ?></span>
                                <span class="font-semibold text-sm">Kuota:
                                    <?php echo htmlspecialchars($event['sisa_kuota']); ?></span>
                            </div>

                            <div class="flex justify-between lg:justify-start space-x-4 mt-6">
                                <?php
                                            $slug_event = htmlspecialchars($event['slug'] ?? 'default-slug');
                                            $user_has_ticket = false;
                                            $is_verified_ticket = false;

                                            // Cek apakah user sudah mendaftar dan dapatkan status verifikasinya
                                            if (isset($events_with_tickets[$event['id_event']])) {
                                                $user_has_ticket = true;
                                                $is_verified_ticket = ($events_with_tickets[$event['id_event']] == 1);
                                            }
                                            ?>
                                <a href="detailevent.php?slug=<?php echo $slug_event; ?>"
                                    class="border border-neutral-600 rounded-xl px-5 py-2 text-sm hover:bg-white hover:text-black transition-colors duration-300">Detail
                                    Kegiatan</a>

                                <?php if ($user_has_ticket): ?>
                                <?php if ($is_verified_ticket): ?>
                                <span
                                    class="border border-emerald-800 bg-emerald-950 text-emerald-400 rounded-xl px-5 py-2 text-sm">Tiket
                                    Diambil</span>
                                <?php else: ?>
                                <span
                                    class="border border-yellow-800 bg-yellow-950 text-yellow-400 rounded-xl px-5 py-2 text-sm">Menunggu
                                    Verifikasi</span>
                                <?php endif; ?>
                                <?php else: ?>
                                <?php
                                                $sisa_kuota = isset($event['sisa_kuota']) ? $event['sisa_kuota'] : 0;
                                                ?>
                                <?php if ($event['event_status'] == 0): ?>
                                <?php if ($sisa_kuota > 0): ?>
                                <a href="detailevent.php?slug=<?php echo $slug_event; ?>"
                                    class="border border-neutral-700 rounded-xl px-5 py-2 hover:border-emerald-800 hover:bg-emerald-950 hover:text-emerald-400 transition-colors duration-300">Daftar</a>
                                <?php else: ?>
                                <button
                                    class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-sm cursor-not-allowed"
                                    disabled>Kuota Penuh</button>
                                <?php endif; ?>
                                <?php elseif ($event['event_status'] == 1): ?>
                                <button
                                    class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-sm cursor-not-allowed"
                                    disabled>Telah Berakhir</button>
                                <?php elseif ($event['event_status'] == 2): ?>
                                <button
                                    class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-sm cursor-not-allowed"
                                    disabled>Kuota Penuh</button>
                                <?php elseif ($event['event_status'] == 4): ?>
                                <button
                                    class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-sm cursor-not-allowed"
                                    disabled>Segera Hadir</button>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>


        <?php else: ?>
        <p class="text-center text-neutral-400 text-xl">Saat ini belum ada jadwal acara yang tersedia.</p>
        <?php endif; ?>
    </main>
    <?php
    // ==================================================
// BAGIAN DATA (HANYA EDIT BAGIAN INI)
// ==================================================
// 'image_url' bisa diisi dengan path ke gambar, atau biarkan kosong ('') untuk menampilkan placeholder abu-abu.
    
    $performances = [];
    $query_performances = "SELECT nama_penampil, tanggal_tampil, jam_tampil, lokasi_tampil, path_image_penampil FROM performance WHERE status_view = 1 ORDER BY tanggal_tampil, jam_tampil ASC";
    $result_performances = mysqli_query($koneksi, $query_performances);

    if ($result_performances && mysqli_num_rows($result_performances) > 0) {
        while ($row = mysqli_fetch_assoc($result_performances)) {
            $performances[] = [
                'band_name' => $row['nama_penampil'],
                'image_url' => $row['path_image_penampil'],
                'date' => $row['tanggal_tampil'],
                'time' => $row['jam_tampil'],
                'location' => $row['lokasi_tampil']
            ];
        }
    }
    ?>

    <div>
        <section
            class="relative bg-black text-white w-full min-h-screen overflow-hidden py-20 px-4 sm:px-8 md:px-16 lg:px-24">

            <div class="container mx-auto relative z-10">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-12 text-gray-100 text-start mb-16">
                    Performance</h1>

                <div class="space-y-12">
                    <?php if (empty($performances)): ?>
                    <p class="text-center text-gray-500 text-lg">Tidak ada jadwal performance saat ini.</p>
                    <?php else: ?>
                    <?php foreach ($performances as $performance): ?>
                    <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">

                        <?php if (!empty($performance['image_url'])): ?>
                        <img src="./img/performance/<?php echo htmlspecialchars($performance['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($performance['band_name']); ?>"
                            class="w-full md:w-1/3 lg:w-1/4 h-48 object-cover rounded-2xl flex-shrink-0">
                        <?php else: ?>
                        <div class="w-full md:w-1/3 lg:w-1/4 h-48 bg-neutral-800 rounded-2xl flex-shrink-0"></div>
                        <?php endif; ?>

                        <div>
                            <h2 class="text-lg md:text-2xl font-bold mb-4">
                                <?php echo htmlspecialchars($performance['band_name']); ?>
                            </h2>
                            <div class="space-y-3 text-gray-300 text-md">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span><?php echo htmlspecialchars($performance['date']); ?> |
                                        <?php echo htmlspecialchars($performance['time']); ?></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    <span><?php echo htmlspecialchars($performance['location']); ?></span>
                                    <?php
                                            // Buat URL Google Calendar
                                            $event_date_time_start = new DateTime("{$performance['date']} {$performance['time']}");
                                            // Asumsi durasi event 2 jam
                                            $event_date_time_end = (clone $event_date_time_start)->modify('+2 hours');

                                            $google_calendar_url = 'https://www.google.com/calendar/render?action=TEMPLATE&text=' . urlencode('Performance oleh ' . $performance['band_name']) . '&dates=' . $event_date_time_start->format('Ymd\THis') . '/' . $event_date_time_end->format('Ymd\THis') . '&details=' . urlencode('Jangan lewatkan performance menarik dari ' . $performance['band_name'] . '!') . '&location=' . urlencode($performance['location']);
                                            ?>
                                    <a href="<?php echo $google_calendar_url; ?>" target="_blank"
                                        class="border border-neutral-600 rounded-xl px-5 py-2 text-sm hover:bg-white hover:text-black transition-colors duration-300 ml-4">
                                        Add to Calendar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>


    <?php require '_footer.php'; ?>

    <script>
    const navEL = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 56) {
            navEL.classList.add('navbar-scrolled');
        } else if (window.scrollY < 56) {
            navEL.classList.remove('navbar-scrolled');
        }
    });
    </script>
    <script src="https://unpkg.com/kursor"></script>
    <script>
    new kursor({
        type: 4,
        removeDefaultCursor: true,
        color: '#ffffff'
    });
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="system.js"></script>
</body>

</html>