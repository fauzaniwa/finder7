<?php
session_start();

// Koneksi ke database
include 'admin-one/dist/koneksi.php';

// Ambil user_id dari session jika ada
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Query untuk mendapatkan data dari tabel event dengan show_event = 1 dan diurutkan berdasarkan urutan_show
$query_event = "SELECT id_event, judul_event, speakers_event, jadwal_event, waktu_event, kuota, lokasi_event, tiket_event, event_status 
FROM event 
WHERE show_event = 1 
ORDER BY urutan_show ASC";


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



<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
  <style>
    .filter-button.active {
      background-color: #FFFFFF;
      color: #000000;
    }
  </style>
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
      .scroller {
        max-width: 600px;
      }

      .scroller__inner {
        padding-block: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 3rem;
      }

      .scroller[data-animated='true'] {
        overflow: hidden;
        -webkit-mask: linear-gradient(90deg, transparent, white 20%, white 80%, transparent);
        mask: linear-gradient(90deg, transparent, white 20%, white 80%, transparent);
      }

      .scroller[data-animated='true'] .scroller__inner {
        width: max-content;
        flex-wrap: nowrap;
        animation: scroll var(--_animation-duration, 40s) var(--_animation-direction, forwards) linear infinite;
      }

      .scroller[data-direction='right'] {
        --_animation-direction: reverse;
      }

      .scroller[data-direction='left'] {
        --_animation-direction: forwards;
      }

      .scroller[data-speed='fast'] {
        --_animation-duration: 20s;
      }

      .scroller[data-speed='slow'] {
        --_animation-duration: 60s;
      }

      @keyframes scroll {
        to {
          transform: translate(calc(-50% - 0.5rem));
        }
      }

      /* for testing purposed to ensure the animation lined up correctly */
      .test {
        background: red !important;
      }
    </style>
  <style>
    .button-container {
      display: flex;
      gap: 10px;
      margin: 20px;
    }

    .hidden {
      display: none;
    }

    .button {
      font-family: 'Work Sans';
      border: 1px solid white;
      padding: 10px 20px;
      color: white;
      background: transparent;
      border-radius: 50px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .button:hover {
      background: rgba(255, 255, 255, 0.25);
    }
  </style>

  <style>
        @layer components {
            .hover-radial-bg {
                position: relative;
                overflow: hidden;
            }

            .hover-radial-bg::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image: radial-gradient(circle at center, transparent 40%, var(--hover-color) 100%);
                opacity: 0;
                transition: opacity 300ms ease;
                /* z-index: -1; akan menempatkan efek DI BELAKANG konten.
                   Ini adalah pendekatan yang bagus dan lebih simpel.
                */
                z-index: -1;
            }

            .hover-radial-bg:hover::before {
                opacity: 1;
            }
        }
    </style>

  <title>Finder 7 - Homepage</title>
  <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />

  <link rel="stylesheet" href="style.css" />

  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>

<body class="bg-neutral-950 font-['Work_Sans']">
  <?php
  require '_navbar.php';
  ?>
  <div
    class="w-2/3 h-3/4 blur-3xl absolute z-0 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
  </div>

  <!-- Hero Section -->
  <section data-section-bg="dark" class="relative min-h-screen flex flex-col lg:flex-row items-center justify-center overflow-hidden px-4 mx-auto">
    <!-- Central Content -->
    <div class="order-last lg:order-first relative z-10 max-w-md text-center text-white space-y-4">
      <h2 class="text-4xl md:text-5xl font-bold text-center md:text-left leading-tight max-w-xs sm:max-w-md">
        Welcome <br />Finder 7 Mindspace
      </h2>
      <p class="sm:text-xl md:text-2xl text-gray-300 text-center md:text-left">
        think the unthinkable
      </p>
      <div class="flex flex-col gap-4 md:items-left">
        <a href="https://www.instagram.com/finder_dkv/"
          class="w-48 sm:w-64 h-12 sm:h-16 flex items-center justify-center bg-[#008C62] text-white text-lg sm:text-xl font-medium rounded-xl sm:rounded-[20px] shadow-md hover:scale-105 transition-transform duration-300">
          Instagram
        </a>
        <a href="#finderdesc"
          class="scroll-button w-48 sm:w-64 h-12 sm:h-16 flex items-center justify-center bg-neutral-500 text-black text-lg sm:text-xl font-medium rounded-xl sm:rounded-[20px] shadow-md hover:scale-105 transition-transform duration-300">
          See More
        </a>
      </div>
    </div>
      <!-- Right Image -->
      <div class="order-first lg:order-last mb-8 md:mb-0 flex justify-center">
        <img src="./img/hero/char.png" alt="Mindspace Characters" class="sm:max-w-md lg:max-w-full animate-pulse" />
      </div>
  </section>

  <section id="finderdesc" class="relative pt-16 pb-16 overflow-hidden">
    <!-- Dekorasi kiri -->
    <img src="./img/supergrafis/SG L.png" alt="Ilustrasi karakter dekoratif pojok kiri bawah" class=" absolute
         top-0 sm:top-auto sm:bottom-0   /* ① posisi */
         left-0
         w-[clamp(20rem,25vw,40rem)]
         translate-x-[-60%] sm:translate-x-[-30%]
         translate-y-[30%] sm:translate-y-[-50%] 
           z-0 sm:opacity-100 opacity-35" />

    <!-- Dekorasi kanan-->
    <img src="./img/supergrafis/SG R.png" alt="Ilustrasi karakter dekoratif pojok kanan bawah" class="absolute
         bottom-0   
         right-0
         w-[clamp(20rem,25vw,40rem)]
         translate-x-[60%] sm:translate-x-[30%]    
         translate-y-[-70%] sm:translate-y-[-50%]   
         z-0 sm:opacity-100 opacity-35" />

    <div class="container mx-auto px-6">
      <div class="w-full max-w-[1264px] mx-auto
             h-auto md:h-[516px]
             relative rounded-3xl p-8 md:p-12">
        <!-- Konten utama Finder -->
        <div class="relative z-10 flex flex-col items-center justify-center h-full">
          <h2 class="w-full md:w-96 text-center text-white text-4xl md:text-5xl
                 font-semibold font-['Work_Sans'] leading-[56px] md:leading-[64px]
                 mb-8 md:mb-12">
            Finder
          </h2>

          <div class="inline-flex flex-col md:flex-row justify-center items-center gap-10 md:gap-20 mb-8 md:mb-12">
            <img class="w-60 md:w-72 h-auto md:h-24 object-contain" src="img/Finder 7 Logo Title Tagline Full White.png"
              alt="Logo Finder 7 Mindspace" />
            <img class="w-44 md:w-52 h-auto md:h-24 object-contain" src="img/DKVUPI WHITE 1.png" alt="Logo DKV UPI" />
          </div>

          <p class="w-full max-w-[824px] text-center text-white text-base md:text-lg
                 font-normal font-['Work_Sans'] leading-7">
            Finder adalah sebuah annual event tahunan yang diadakan oleh prodi
            DKV UPI sebagai bentuk eksistensi diri terhadap dunia. Dalam event
            Finder terdapat beberapa rangkaian acara yang memiliki beberapa
            tujuan seperti memberikan wawasan mengenai desain serta hal-hal
            umum lainnya. Ada pula seperti perlombaan untuk menjadi wadah
            kreatifitas. Finder tiap munculnya selalu membawa tema untuk
            dijadikan sebagai dasar pembawaan.
          </p>
        </div>
      </div>

      <!-- Video YouTube -->
      <div class="mt-12 max-w-4xl mx-auto">
        <div class="relative w-full pb-[56.25%] overflow-hidden rounded-xl shadow-lg">
          <iframe class="absolute top-0 left-0 w-full h-full"
            src="https://www.youtube.com/embed/AEZUoTgOOII?si=tN2UDfsSrdtqzPar" title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </div>
      </div>
    </div>
  </section>
  <div
    class="w-[1046px] h-0 outline outline-1 outline-offset-[-0.25px] outline-zinc-600 justify-center items-center mx-auto mb-10">
  </div>
  <!-- Finder Deskripsi -->
  <section id="about" class="bg-black text-white py-16 px-6">
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-12 justify-center">
      <!-- Logo & Keterangan Kiri -->
      <div class="flex-shrink-0 flex flex-col items-center md:items-start">
        <img src="img/Finder 7 Logo Title White.png" alt="Finder 7 Logo" class="w-32 sm:w-40 md:w-48" />
      </div>

      <!-- Judul & Deskripsi Kanan -->
      <div class="max-w-xl">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">
          Finder 7
        </h2>
        <p class="text-base md:text-lg text-gray-300 leading-relaxed">
          Finder 7 Mindspace adalah ruang memahami diri yang dimulai dari
          insight, di mana pikiran, emosi, dan cara berpikir membentuk
          persepsi kita. Dari kesadaran ini, lahir inisiatif untuk merespons
          dengan bijak, mencerminkan jati diri. Seiring waktu, pemahaman dan
          tindakan yang selaras menciptakan harmoni, membawa keseimbangan
          antara diri sendiri dan dunia sekitar.
        </p>
      </div>
    </div>
  </section>
<!-- Pengenalan Karakter -->
 <section class=" text-white py-20 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold mb-2">Get to Know About Them!</h2>
            <p class="text-xl text-gray-400">Maskot Finder 7 yang lucu-lucu!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class=" rounded-3xl p-8 border border-white/10 hover:border-yellow-400 duration-300 hover-radial-bg" style="--hover-color: #FEE139">
                <div class="flex flex-col sm:flex-row items-center gap-8">
                    <div class="flex-shrink-0">
                        <img src="./img/hero/5.png" alt="Intuitive Mascot" class="w-32 h-auto filter drop-shadow-[0_0_15px_rgba(234,179,8,0.6)] animate-pulse">
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold mb-3 text-yellow-300">Intuitive</h2>
                        <p class="text-gray-300 text-base leading-relaxed">
                            Intuitive ditandai oleh kemampuan untuk mendeteksi pola dan makna tersembunyi, yang didorong oleh rasa ingin tahu yang tinggi. Individu dengan kepribadian intuitif cenderung lebih kreatif, mengandalkan imajinasi, dan sering kali menghasilkan ide-ide baru yang inovatif.
                        </p>
                    </div>
                </div>
            </div>
            <div class=" rounded-3xl p-8 border border-white/10 hover:border-green-400 duration-300 hover-radial-bg" style="--hover-color: #4ade80">
                <div class="flex flex-col sm:flex-row items-center gap-8">
                    <div class="flex-shrink-0">
                        <img src="./img/hero/6.png" alt="Sensing Mascot" class="w-32 h-auto filter drop-shadow-[0_0_15px_rgba(74,222,128,0.6)] animate-pulse">
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold mb-3 text-green-300">Sensing</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Sensing merupakan kepribadian yang sangat mengandalkan panca indra untuk mengambil suatu informasi atau keputusan dalam berbagai aspek, sensing juga memiliki memori yang cukup kuat. Seseorang dengan kepribadian sensing cenderung detail, realistis dan logis, ia harus melihat atau merasakan sendiri suatu hal atau fenomena.
                        </p>
                    </div>
                </div>
            </div>

            <div class=" rounded-3xl p-8 border border-white/10 hover:border-blue-400 duration-300 hover-radial-bg" style="--hover-color: #60a5fa">
                <div class="flex flex-col sm:flex-row items-center gap-8">
                  <div>
                        <h2 class="text-3xl font-bold mb-3 text-blue-300">Thinking</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Thinking adalah salah satu dari dua cara utama orang membuat suatu keputusan (pasangannya feeling). Thinking cenderung menggunakan logika, analisis objektif dan rasional saat menentukan pilihan atau menilai sesuatu. Untuk Thinking semua hal itu kayak puzzle, haruslah ada bukti, dipikirin baik-baik, terus disusun baru sampai ketemu jawaban yang paling masuk akal.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <img src="./img/hero/80.png" alt="Thinking Mascot" class="w-32 h-auto filter drop-shadow-[0_0_15px_rgba(96,155,250,0.6)] animate-pulse">
                    </div>
                    
                </div>
            </div>

            <div class="rounded-3xl p-8 border border-white/10 hover:border-pink-400 duration-300 hover-radial-bg" style="--hover-color: #f472b6">
                <div class="flex flex-col sm:flex-row items-center gap-8">
                  <div>
                        <h2 class="text-3xl font-bold mb-3 text-pink-400">Feeling</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Seorang feeling sering kali mengambil keputusan berdasarkan emosi, empati, dan keyakinan pribadi. saat mengambil keputusan, feeling akan sangat mempertimbangkan bagaimana perasaan orang lain terhadap hasil dari keputusan tersebut. Sehingga, tipe feeling akan sangat mendengarkan dan berusaha memahami bagaimana perasaan orang lain.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <img src="./img/hero/70.png" alt="Feeling Mascot" class="w-36 h-auto filter drop-shadow-[0_0_15px_rgba(236,72,153,0.6)] animate-pulse">
                    </div>  
                </div>
            </div>
        </div>
    </div>
</section>

  <br /><br />

  <!-- Liat lainnya -->
   

 <!-- Jadwal -->
  <section id="jadwal" class="bg-neutral-950 text-white min-h-screen">
    <main class="container mx-auto px-6 py-20 pt-32">
        <h1 class="text-4xl md:text-5xl font-bold text-center mb-16">
            Jadwal Acara
        </h1>

        <?php
        // Mengelompokkan semua event berdasarkan tanggal dari $events_data
        $grouped_by_date = [];
        foreach ($events_data as $event) {
            $tanggal = $event['jadwal_event']; // Menggunakan 'jadwal_event' sebagai kunci
            $grouped_by_date[$tanggal][] = $event;
        }

        // ---- [MODIFIKASI 1] ----
        // Ambil hanya 3 tanggal pertama dari array yang sudah dikelompokkan
        $limited_grouped_by_date = array_slice($grouped_by_date, 0, 3, true);
        
        // Cek apakah ada event yang ditemukan setelah pengelompokan dan pembatasan
        $events_found = !empty($limited_grouped_by_date);
        ?>

        <?php if ($events_found): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-12 md:gap-x-8">

                <?php
                // Logika untuk menambahkan border di antara kolom
                $card_count = 0;
                // Gunakan array yang sudah dibatasi untuk menghitung total kartu
                $total_cards = count($limited_grouped_by_date); 
                // Lakukan perulangan pada array tanggal yang sudah dibatasi
                foreach ($limited_grouped_by_date as $tanggal => $events):
                    $card_count++;
                    // Menambahkan border kanan hanya jika bukan kartu terakhir di tampilan desktop
                    $border_class = ($card_count < $total_cards && $total_cards > 1) ? 'lg:border-r lg:border-neutral-800' : '';
                ?>
                <div class="flex flex-col space-y-8 px-4 <?php echo $border_class; ?>">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl md:text-7xl font-bold"><?php echo date('d', strtotime($tanggal)); ?></span>
                        <div class="flex flex-col">
                            <span class="text-2xl md:text-3xl"><?php echo date('F', strtotime($tanggal)); ?></span>
                            <span class="text-2xl md:text-3xl text-neutral-400"><?php echo date('Y', strtotime($tanggal)); ?></span>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-12">
                        <?php 
                        // ---- [MODIFIKASI 2] ----
                        // Ambil hanya 3 event pertama untuk tanggal ini
                        $limited_events = array_slice($events, 0, 3);
                        // Lakukan perulangan pada array event yang sudah dibatasi
                        foreach ($limited_events as $event): 
                        ?>
                            <div>
                                <h3 class="text-lg font-bold"><?php echo htmlspecialchars($event['judul_event']); ?></h3>
                                <p class="text-neutral-400 text-sm mt-2">Pembicara: <?php echo htmlspecialchars($event['speakers_event']); ?></p>

                                <div class="flex justify-between items-center mt-1 pr-3">
                                    <span class="text-neutral-400 text-sm">Waktu: <?php echo htmlspecialchars($event['waktu_event']); ?></span>
                                    <span class="font-semibold text-sm">Kuota: <?php echo htmlspecialchars($event['kuota']); ?></span>
                                </div>

                                <div class="flex space-x-4 mt-6">
                                    <a href="detailevent.php?id_event=<?php echo $event['id_event']; ?>" class="border border-neutral-600 rounded-xl px-5 py-2 text-sm hover:bg-white hover:text-black transition-colors duration-300">Detail</a>

                                    <?php if (!$user_id || !in_array($event['id_event'], $events_with_tickets)): ?>
                                        <?php
                                        $sisa_kuota = isset($event['sisa_kuota']) ? $event['sisa_kuota'] : 0;
                                        ?>
                                        <?php if ($event['event_status'] == 1): ?>
                                            <?php if ($sisa_kuota > 0): ?>
                                                <form method="post" class="inline">
                                                    <input type="hidden" name="id_event" value="<?php echo $event['id_event']; ?>">
                                                    <button type="submit" class="border border-neutral-600 rounded-xl px-5 py-2 text-sm hover:bg-white hover:text-black transition-colors duration-300">Daftar</button>
                                                </form>
                                            <?php else: ?>
                                                <button class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-sm cursor-not-allowed" disabled>Tiket Habis</button>
                                            <?php endif; ?>
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
                <a href="event.php" class="bg-[#26d0a5] text-black font-bold py-3 px-16 rounded-full hover:bg-[#21b38f] transition-colors duration-300 text-lg">
                    Lihat Semua
                </a>
            </div>

        <?php else: ?>
            <p class="text-center text-neutral-400 text-xl">Saat ini belum ada jadwal acara yang tersedia.</p>
        <?php endif; ?>

    </main>
</section>


  
  <!-- Section Event -->
  <section id="pameran" class="bg-[#FDFDF6]">
    <div class="max-w-7xl mx-auto px-6 py-16 space-y-8">
      <!-- Judul & Deskripsi -->
      <div class="text-center space-y-2">
        <h2 class="text-3xl font-semibold text-neutral-900">Lomba</h2>
        <p class="max-w-3xl mx-auto text-base md:text-lg text-gray-600">
          panggung kreatif bagi kamu yang ingin menguji dan memamerkan kemampuan desain visual. Tahun ini kami membuka
          dua kategori: Poster Ilustrasi, di mana kamu dapat mengekspresikan ide atau pesan sosial melalui karya
          ilustratif, serta Character Design, untuk merancang karakter orisinal yang kuat dan berkarakter.
        </p>
      </div>

      <!-- Grid Kartu + Button -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-[60px] justify-items-center">
        <!-- Kartu 1 -->
        <div class="flex flex-col items-center">
          <div
            class="relative w-[300px] h-[400px] /* default mobile */ sm:w-[450px] sm:h-[600px] /* tablet ke atas */ md:w-[600px] md:h-[800px] /* desktop ke atas */ max-w-full rounded-2xl overflow-hidden shadow-lg">
            <img src="img/BANNER LOMBA POSTER 1 (OPSI 2).png" alt="Poster Ilustrasi"
              class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-tr from-pink-600/60 to-transparent"></div>
            <h3 class="relative z-10 mt-6 ml-6 text-white text-2xl font-medium">
              Poster Ilustrasi
            </h3>
          </div>
          <a href="submission.php"
            class="mt-6 inline-block bg-black text-white text-base font-semibold py-3 px-6 rounded-lg shadow hover:opacity-90 transition">
            Lihat Ketentuan
          </a>
        </div>

        <!-- Kartu 2 -->
        <div class="flex flex-col items-center">
          <div
            class="relative w-[300px] h-[400px] sm:w-[450px] sm:h-[600px] md:w-[600px] md:h-[800px] max-w-full rounded-2xl overflow-hidden shadow-lg">
            <img src="img/BANNER CHARACTER 2.png" alt="Character Design"
              class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-bl from-cyan-500/60 to-transparent"></div>
            <h3 class="relative z-10 mt-6 ml-6 text-white text-2xl font-medium">
              Character Design
            </h3>
          </div>
          <a href="submitkarya.php"
            class="mt-6 inline-block bg-emerald-400 text-black text-base font-semibold py-3 px-6 rounded-lg shadow hover:opacity-90 transition">
            Submit Karya
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Map Finder -->
  <section class="bg-[#FDFDF6] py-16 px-6">
    <div class="max-w-5xl mx-auto text-center space-y-8">
      <!-- Judul Section -->
      <h2 class="text-3xl font-semibold text-neutral-900">Lokasi Finder 7</h2>

      <!-- Container Map -->
      <div class="w-full max-w-full md:max-w-4xl mx-auto bg-gray-300 rounded-2xl overflow-hidden">
        <iframe class="w-full h-64 sm:h-96"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.2348973267035!2d107.59106691057444!3d-6.862428093107444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6b943c2c5ff%3A0xee36226510a79e76!2sUniversitas%20Pendidikan%20Indonesia!5e0!3m2!1sid!2sid!4v1710655960109!5m2!1sid!2sid"
          style="border: 0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>

      <!-- Button -->
      <a href="https://maps.app.goo.gl/w12NySVz2bjC6X527" target="_blank"
        class="inline-block bg-gray-300 text-black font-medium text-base py-2 px-6 rounded-full hover:bg-gray-400 transition">
        Show on Google Map
      </a>
    </div>
  </section>
  <div
    class="w-[1046px] h-0 outline outline-1 outline-offset-[-0.25px] outline-zinc-600 justify-center items-center mx-auto">
  </div>
  <br /><br />
  <?php
  require '_footer.php';
  ?>
</body>

</html>