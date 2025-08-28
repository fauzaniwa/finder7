<?php
session_start();
// Aktifkan pelaporan kesalahan
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
include 'admin-one/dist/koneksi.php';

// Ambil user_id dari session jika ada
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Ambil id_event yang ingin ditampilkan, misalnya dari parameter URL
$id_event_target = isset($_GET['id_event']) ? intval($_GET['id_event']) : 0;

// Query untuk mendapatkan data dari tabel event dengan show_event = 1
$query_event = "SELECT id_event, judul_event, speakers_event, jadwal_event, waktu_event, lokasi_event, tiket_event, event_status, kuota FROM event WHERE show_event = 1";

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
    // Ambil ID event untuk menghitung peserta
    $id_event = $row_event['id_event'];

    // Query untuk menghitung jumlah pengguna yang mendaftar untuk event ini
    $query_count_users = "SELECT COUNT(*) as total FROM tiket WHERE id_event = ?";
    $stmt_count_users = mysqli_prepare($koneksi, $query_count_users);
    mysqli_stmt_bind_param($stmt_count_users, "i", $id_event);
    mysqli_stmt_execute($stmt_count_users);
    $result_count_users = mysqli_stmt_get_result($stmt_count_users);
    $row_count_users = mysqli_fetch_assoc($result_count_users);

    // Total kuota dan total pengguna yang telah mendaftar
    $total_kuota = isset($row_event['kuota']) ? intval($row_event['kuota']) : 0; // Pastikan menjadi integer
    $total_users = intval($row_count_users['total']); // Pastikan total_users adalah integer

    // Hitung sisa kuota
    $sisa_kuota = $total_kuota - $total_users;

    // Tambahkan sisa kuota ke data event
    $row_event['sisa_kuota'] = $sisa_kuota;

    // Simpan data event ke dalam array
    $events_data[$id_event] = $row_event; // Simpan berdasarkan id_event

    // Tutup statement count users
    mysqli_stmt_close($stmt_count_users);
}

// Tutup statement event
mysqli_stmt_close($stmt_event);

// Sekarang $events_data berisi data event lengkap dengan sisa kuota


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


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- CDN Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
    <!--  Font -->

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        work: ['Work Sans'],
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
    <!-- ----------- -->

    <style type="text/tailwindcss">

        .navbar-scrolled {
        box-shadow: 2px 2px 30px #000000;
      }
      .ext-scrolled {
        color: black;
      }
      .navbar {
        transition: all 0.5s;
      }
    </style>
    <!-- Title Web & Icon -->
    <title>Detail Seminar</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <!-- Script Navbar Menu -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Script Cursor -->
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <!-- Script Cursor -->
</head>

<body class="bg-[#000000]">
    <?php
    require '_navbar.php';
    ?>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Koneksi ke database
    
    // Ambil id_event dari URL
    $id_event = isset($_GET['id_event']) ? intval($_GET['id_event']) : 0;

    // Query untuk mendapatkan detail event
    $query_event = "SELECT * FROM event WHERE id_event = ?";
    $stmt_event = mysqli_prepare($koneksi, $query_event);
    mysqli_stmt_bind_param($stmt_event, "i", $id_event);
    mysqli_stmt_execute($stmt_event);
    $result_event = mysqli_stmt_get_result($stmt_event);
    $row_event = mysqli_fetch_assoc($result_event);

    // Jika tidak ada event dengan id tersebut
    if (!$row_event) {
        die("Event tidak ditemukan.");
    }

    // Ambil informasi pengguna (jika ada)
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
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
            } else {
                echo '<script>alert("Anda sudah memiliki tiket untuk event ini.");</script>';
            }
        }
    }
    // Menampilkan detail event
    ?>
    <section class="w-full min-h-screen pt-32 pb-32 bg-[#0D0D0D] font-work">
  <!-- Container -->
  <div class="w-[90%] mx-auto flex flex-col lg:flex-row gap-10 lg:items-start bg-[#131313] p-6 rounded-xl">
    
    <!-- Gambar: atas di mobile, KIRI di desktop -->
    <div class="order-first lg:order-first w-full lg:w-1/2 flex justify-center">
      <div class="relative bg-[#D9D9D9] rounded-2xl w-full max-w-[650px] md:aspect-[3/4] aspect-square overflow-hidden">
        <img src="./img/event/<?php echo htmlspecialchars($row_event['thumbnail_event']); ?>"
             class="object-cover w-full h-full rounded-2xl" alt="Poster/Event Thumbnail">
        <!-- Dekorasi PNG kiri atas -->
        <img src="./img/dekorasi/atas.png"
             class="absolute -top-4 -left-4 z-10 opacity-35 pointer-events-none select-none" alt="" aria-hidden="true">
        <!-- Dekorasi PNG kanan bawah -->
        <img src="./img/dekorasi/bawah.png"
             class="absolute -bottom-4 -right-4 z-10 opacity-35 pointer-events-none select-none" alt="" aria-hidden="true">
      </div>
    </div>

    <!-- Konten Kanan -->
    <div class="order-last lg:order-last w-full lg:w-1/2 space-y-5">
      <!-- Breadcrumb -->
      <p class="text-sm text-white/70">Homepage / Jadwal / <span class="text-yellow-400 italic">Detail Acara</span></p>

      <!-- Judul -->
      <h1 class="text-white text-3xl md:text-4xl font-semibold leading-snug">
        <?php echo htmlspecialchars($row_event['judul_event']); ?>
      </h1>

      <!-- Pembicara -->
      <p class="text-white italic text-lg">
        <?php echo htmlspecialchars($row_event['speakers_event']); ?>
      </p>

      <!-- Aksi (ikon) -->
      <div class="flex items-center gap-5 text-white/90">
        <!-- like -->
        <button type="button" aria-label="Suka" class="p-2 rounded-lg hover:bg-white/10 transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
               d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/></svg>
        </button>
        <!-- comment -->
        <button type="button" aria-label="Komentar" class="p-2 rounded-lg hover:bg-white/10 transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
               d="M8 10h8M8 14h5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round"
               d="M9 19l-5 2 1.5-4"/></svg>
        </button>
        <!-- share -->
        <button type="button" aria-label="Bagikan" class="p-2 rounded-lg hover:bg-white/10 transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
               d="M15 8a3 3 0 100-6 3 3 0 000 6zM19 22v-5a4 4 0 00-4-4H9a4 4 0 00-4 4v5"/><path stroke-linecap="round" stroke-linejoin="round"
               d="M15 8l-6 4"/></svg>
        </button>
      </div>

      <!-- Info Waktu & Lokasi -->
      <div class="space-y-2 text-white">
        <div class="flex items-center gap-3">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
               d="M8 7V3m8 4V3m-9 8h10m-11 8h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/></svg>
          <span><?php echo htmlspecialchars($row_event['waktu_event']); ?> | 09:00–12:00</span>
        </div>
        <div class="flex items-center gap-3">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
               d="M17.657 16.657L13.414 12.414a4 4 0 1 0-1.414 1.414l4.243 4.243a1 1 0 0 0 1.414-1.414z"/>
               <path stroke-linecap="round" stroke-linejoin="round"
               d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/></svg>
          <span><?php echo htmlspecialchars($row_event['lokasi_event']); ?></span>
        </div>
        <div>
          <?php
            if (array_key_exists($id_event_target, $events_data)) {
              echo "<span>" . htmlspecialchars($events_data[$id_event_target]['sisa_kuota']) . " Tiket Tersedia</span>";
            } else {
              echo "<span>Event tidak ditemukan.</span>";
            }
          ?>
        </div>
      </div>

      <!-- Deskripsi (sesuai mockup, teks kiri & lebar nyaman) -->
      <div class="max-w-prose">
        <h2 class="text-white text-2xl md:text-3xl font-semibold mb-3">Deskripsi Acara</h2>
        <p class="text-white/90 text-base md:text-lg leading-relaxed">
          <?php echo htmlspecialchars($row_event['deskripsi_event']); ?>
        </p>
      </div>

      <!-- CTA -->
      <div class="w-full pt-2">
        <?php if (!$user_id || !in_array($row_event['id_event'], $events_with_tickets)): ?>
          <?php $sisa_kuota = array_key_exists($id_event_target, $events_data) ? $events_data[$id_event_target]['sisa_kuota'] : 0; ?>
          <?php if ($row_event['event_status'] == 1 && $sisa_kuota > 0): ?>
            <form method="post" action="">
              <input type="hidden" name="id_event" value="<?php echo htmlspecialchars($row_event['id_event']); ?>">
              <button type="submit"
                class="bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-full text-lg transition-all">
                Dapatkan Tiket
              </button>
            </form>
          <?php elseif ($row_event['event_status'] == 2): ?>
            <button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">
              Pendaftaran Belum Dibuka
            </button>
          <?php elseif ($row_event['event_status'] != 1): ?>
            <button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">
              Event Sudah Berakhir
            </button>
          <?php else: ?>
            <button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">
              Tiket Telah Habis
            </button>
          <?php endif; ?>
        <?php else: ?>
          <p class="text-[#00E091] font-semibold px-0 py-3 text-lg">
            Kamu sudah memiliki tiket.
          </p>
        <?php endif; ?>

        <!-- Label kecil seperti di mockup -->
        <p class="text-white/60 text-sm mt-2">Sosmed Artist</p>
      </div>
    </div>
  </div>
  <?php
  // Tutup koneksi
  mysqli_stmt_close($stmt_event);
  mysqli_close($koneksi);
  ?>
</section>

<!-- =======================
  KOMENTAR
======================== -->
<section id="comments" class="w-full bg-[#0D0D0D] font-work mt-10">
  <div class="w-[90%] mx-auto relative">

    <!-- Dekorasi PNG kanan atas -->
    <img src="./img/dekorasi/atas.png"
         class="hidden md:block absolute -top-6 -right-6 z-10 opacity-35 pointer-events-none select-none"
         alt="" aria-hidden="true">

    <div class="bg-[#131313] rounded-xl p-6 md:p-8">
      <!-- Bar input komentar -->
      <div class="flex items-center gap-4">
        <svg class="w-8 h-8 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h7"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <form method="post" action="" class="flex-1">
          <input type="hidden" name="id_event" value="<?php echo htmlspecialchars($row_event['id_event']); ?>">
          <input name="comment" autocomplete="off"
                 class="w-full rounded-full bg-[#1A1A1A] text-white placeholder-white/50 px-6 py-4 border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20"
                 placeholder="Add your comment">
        </form>
      </div>

      <!-- Daftar komentar -->
      <div class="mt-8 space-y-10">

        <!-- KOMENTAR 1 -->
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-full bg-white/90 shrink-0"></div>
          <div class="flex-1">
            <div class="text-white font-semibold">Adit Ramadhan</div>
            <div class="text-xs text-white/60 mt-0.5">10:21 · 21 Juli 2025</div>

            <p class="mt-3 text-white/90 leading-relaxed">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nisi arcu, lobortis quis ligula vel,
              accumsan congue diam. Nullam porta enim ut tristique fermentum. Sed vestibulum sit amet arcu eu sodales.
              Duis sed facilisis quam, id rhoncus nisi. Nam ac efficitur nisi, sed aliquet libero.
            </p>

            <div class="mt-3 flex items-center gap-6 text-white/80">
              <button type="button" aria-label="Suka" class="flex items-center gap-1 hover:text-white transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                </svg>
              </button>
              <button type="button" class="italic hover:text-white transition">Reply</button>
            </div>

            <!-- BALASAN -->
            <div class="mt-6 pl-8 border-l border-white/10">
              <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-white/90 shrink-0"></div>
                <div class="flex-1">
                  <div class="text-white font-semibold">Denis</div>
                  <div class="text-xs text-white/60 mt-0.5">
                    10:27 · 21 Juli 2025
                    <span class="text-white/40">· Replying to Adit</span>
                  </div>

                  <p class="mt-3 text-white/90 leading-relaxed">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nisi arcu, lobortis quis ligula vel
                    accumsan congue diam. Nullam porta enim ut tristique fermentum. Sed vestibulum sit amet arcu eu
                    sodales. Duis sed facilisis quam, id rhoncus nisi. Nam ac efficitur nisi, sed aliquet libero.
                  </p>

                  <div class="mt-3 flex items-center gap-6 text-white/80">
                    <button type="button" aria-label="Suka" class="flex items-center gap-1 hover:text-white transition">
                      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                      </svg>
                    </button>
                    <button type="button" class="italic hover:text-white transition">Reply</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /BALASAN -->

          </div>
        </div>
        <!-- /KOMENTAR 1 -->

        <!-- Jika ingin menampilkan status kosong:
        <p class="text-white/60">Belum ada komentar.</p>
        -->

      </div>
    </div>
  </div>
</section>


    <!-- Script Toggle -->
    <script>
        const navLinks = document.querySelector('.nav-links');
        function onToggleMenu(e) {
            e.name = e.name === 'menu' ? 'close' : 'menu';
            navLinks.classList.toggle('-bottom-52');
        }
    </script>
    <!-- Script Toggle -->
    <!-- Script Navbar -->
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
    <!-- ------------ -->
    <!-- ----------- -->
</body>
<!-- Cursor CDN -->
<script src="https://unpkg.com/kursor"></script>
<script>
    new kursor({
        type: 4,
        removeDefaultCursor: true,
        color: '#ffffff',
    });
</script>
<!-- Cursor CDN -->

</html>