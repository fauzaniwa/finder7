<?php
session_start();

// Koneksi ke database
include 'admin-one/dist/koneksi.php';

// Ambil user_id dari session jika ada
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Query untuk mendapatkan data dari tabel event dengan show_event = 1
$query_event = "SELECT id_event, judul_event, speakers_event, jadwal_event, waktu_event, lokasi_event, tiket_event, event_status FROM event WHERE show_event = 1";

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
    $events_data[] = $row_event;
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

// ====== Grouping data by tanggal untuk tampilan kolom seperti desain ======
function indoMonth($n) {
    $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $n = (int)$n; return $bulan[$n] ?? '';
}

$grouped_by_date = [];
foreach ($events_data as $ev) {
    $dateKey = $ev['jadwal_event']; // format yyyy-mm-dd
    $grouped_by_date[$dateKey][] = $ev;
}
ksort($grouped_by_date); // urutkan berdasarkan tanggal naik

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
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&family=Inter:wght@400;700&display=swap" rel="stylesheet" />

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
                        sans: ['Inter', 'sans-serif'], // Set Inter sebagai font default
                    },
                    animation: {
                        'spin-slow': 'spin 4s linear infinite',
                        'loop-scroll': 'loop-scroll 10s linear infinite',
                    },
                    keyframes: {
                        'loop-scroll': {
                            from: { transform: 'translateX(0)' },
                            to: { transform: 'translateX(-100%)' },
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
        /* Style lain yang mungkin Anda perlukan bisa tetap di sini */
    </style>
</head>

<body class="bg-black text-white">
    <?php require '_navbar.php'; ?>

    <main class="container mx-auto px-6 py-20 pt-32">
        <h1 class="text-4xl md:text-5xl font-bold text-center mb-16">Jadwal Acara</h1>
        
        <?php if ($events_found): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-12 md:gap-x-8">

            <?php 
            $card_count = 0;
            $total_cards = count($grouped_by_date);
            foreach ($grouped_by_date as $tanggal => $events): 
                $card_count++;
                // Menambahkan border kanan hanya jika bukan kartu terakhir di tampilan desktop
                $border_class = ($card_count < $total_cards) ? 'lg:border-r lg:border-neutral-800' : '';
            ?>
            <div class="flex flex-col space-y-8 px-4 <?php echo $border_class; ?>">
    <div class="flex items-center space-x-4">
        <span class="text-6xl md:text-7xl font-bold"><?php echo date('d', strtotime($tanggal)); ?></span>
        <div class="flex flex-col">
            <span class="text-2xl md:text-3xl"><?php echo indoMonth(date('n', strtotime($tanggal))); ?></span>
            <span class="text-2xl md:text-3xl text-neutral-400"><?php echo date('Y', strtotime($tanggal)); ?></span>
        </div>
    </div>

    <div class="flex flex-col space-y-12"> <?php foreach ($events as $event): ?>
        <div>
            <h3 class="text-lg font-bold"><?php echo htmlspecialchars($event['judul_event']); ?></h3>
            
            <p class="text-neutral-400 text-sm mt-2">Pembicara: <?php echo htmlspecialchars($event['speakers_event']); ?></p>
            
            <div class="flex justify-between item-center mt-1 pr-3">
                <span class="text-neutral-400 text-sm">Waktu: <?php echo htmlspecialchars($event['waktu_event']); ?></span>
                <span class="font-semibold">
                    <?php
                    $price = is_numeric($event['tiket_event']) ? (int)$event['tiket_event'] : 0;
                    echo ($price > 0) ? 'Rp ' . number_format($price, 0, ',', '.') : 'Gratis';
                    ?>
                </span>
            </div>

            <div class="flex space-x-4 mt-6">
                <a href="detail-event.php?id=<?php echo $event['id_event']; ?>" class="border border-neutral-600 rounded-xl px-5 py-2 text-sm hover:bg-white hover:text-black transition-colors duration-300">Detail Kegiatan</a>
                
                <?php if (!$user_id || !in_array($event['id_event'], $events_with_tickets)): ?>
                    <?php if ($event['event_status'] == 1): ?>
                        <form method="post" class="inline">
                            <input type="hidden" name="id_event" value="<?php echo $event['id_event']; ?>">
                            <button type="submit" class="border border-neutral-600 rounded-xl px-5 py-2 text-sm hover:bg-white hover:text-black transition-colors duration-300">Daftar</button>
                        </form>
                    <?php elseif ($event['event_status'] == 2): ?>
                        <button class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-sm cursor-not-allowed" disabled>Segera Hadir</button>
                    <?php else: ?>
                        <button class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-sm cursor-not-allowed" disabled>Event Selesai</button>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="border border-emerald-800 bg-emerald-950 text-emerald-400 rounded-xl px-5 py-2 text-sm">Tiket Diambil</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-20">
            <button class="bg-[#26d0a5] text-black font-bold py-3 px-16 rounded-full hover:bg-[#21b38f] transition-colors duration-300 text-lg">
                See More
            </button>
        </div>

        <?php else: ?>
            <p class="text-center text-neutral-400 text-xl">Saat ini belum ada jadwal acara yang tersedia.</p>
        <?php endif; ?>

    </main>

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
        new kursor({ type: 4, removeDefaultCursor: true, color: '#ffffff' });
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="system.js"></script>
</body>
</html>