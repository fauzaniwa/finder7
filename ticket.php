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

  <title>Finder 6 - Tiket</title>
  <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />

  <link rel="stylesheet" href="style.css" />

  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>

<body>
  <?php
  require '_navbar.php';
  ?>
  <!-- -------- -->

  <!--ClickBaitKarya -->
  <!-- Jadwal -->
  <section id="jadwal" class="container flex flex-col max-w-full bg-[#0D0D0D] gap-4 py-10 pt-28">
    <!-- H1 -->
    <h1 style="font-family: 'Work Sans'" class="text-white px-6 md:px-16 text-2xl md:text-3xl md:mx-auto">Jadwal Acara
    </h1>

    <!-- Filter Jadwal -->
    <div class="flex flex-row gap-2  mx-auto justify-start max-w-[90%] overflow-x-auto snap-x">
      <div class="w-fit px-1 flex flex-shrink-0">
        <!-- Semua Jadwal -->
        <!-- <a href="?filter=#jadwal"
        class="filter-button py-2 px-4 text-white rounded-full hover:bg-white hover:bg-opacity-25 text-lg">Semua
        Jadwal</a> -->

        <!-- List Jadwal Unik dari Data -->
        <?php
        // Mengumpulkan jadwal_event unik dari data event
        $unique_jadwal_events = array_unique(array_column($events_data, 'jadwal_event'));

        // Menampilkan link filter untuk setiap jadwal_event unik
        foreach ($unique_jadwal_events as $jadwal_event) {
          // Format tanggal dari yyyy-mm-dd menjadi dd F
          $formatted_date = date('d F', strtotime($jadwal_event));

          // Tentukan kelas aktif untuk filter yang sedang dipilih
          $activeClass = isset($_GET['filter']) && $_GET['filter'] === urlencode($jadwal_event) ? 'active' : '';

          // Tampilkan link filter dengan format tanggal yang diinginkan
          echo '<a href="?filter=' . urlencode($jadwal_event) . '#jadwal" class="filter-button py-2 px-3 text-white rounded-full hover:bg-white hover:bg-opacity-25 text-base flex flex-shrink-0 ' . $activeClass . '">' . htmlspecialchars($formatted_date) . '</a>';
        }
        ?>
      </div>
    </div>

    <!-- Event List -->
    <?php
    // Flag untuk menandai apakah ada event yang ditemukan
    $events_found = false;

    foreach ($events_data as $event):
      // Cek apakah event harus ditampilkan berdasarkan filter jadwal
      if (isset($_GET['filter']) && $_GET['filter'] !== '' && $event['jadwal_event'] !== urldecode($_GET['filter'])) {
        continue; // Skip event jika tidak cocok dengan filter
      }

      // Set flag bahwa setidaknya ada satu event yang akan ditampilkan
      $events_found = true;

      // Tampilkan event
      ?>
      <div
        class="flex flex-col lg:flex-row lg:justify-between mx-6 md:mx-16 border-b-[1px] border-b-white py-4 gap-6 lg:items-center">
        <!-- Flex-Kiri -->
        <div class="flex flex-col gap-4 lg:gap-2 w-full">
          <div class="flex flex-wrap lg:flex-row gap-2 md:gap-4 ">

            <h1 style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">
              <?php echo $event['waktu_event']; ?>
            </h1>
            <li style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">Kuota :
              <?php echo $event['kuota']; ?>
            </li>
          </div>
          <div class="flex flex-col gap-2">
            <h1 style="font-family: 'Work Sans'" class="text-white text-2xl lg:text-3xl font-medium">
              <?php echo $event['judul_event']; ?>
            </h1>
            <h1 style="font-family: 'Work Sans'" class="text-white md:text-lg font-light">By
              <?php echo $event['speakers_event']; ?>
            </h1>
          </div>
        </div>

        <!-- Tombol -->
        <div class="flex flex-col w-full md:max-w-[280px] items-start lg:items-center gap-4">
          <a href="detailevent.php?id_event=<?php echo $event['id_event']; ?>">
            <button style="font-family: 'Work Sans'"
              class="w-[275px] h-fit border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">
              Lihat Detail
            </button>
          </a>

          <?php if (!$user_id || !in_array($event['id_event'], $events_with_tickets)): ?>
            <?php
            // Mengambil sisa kuota dari $events_data
            if (array_key_exists($event['id_event'], $events_data)) {
              $sisa_kuota = $events_data[$event['id_event']]['sisa_kuota'];
            } else {
              $sisa_kuota = 0; // Nilai default jika event tidak ditemukan
            }
            ?>

            <?php if ($event['event_status'] == 1): // Pengecekan status event ?>
              <?php if ($sisa_kuota > 0): // Jika kuota masih ada ?>
                <form method="post">
                  <input type="hidden" name="id_event" value="<?php echo $event['id_event']; ?>">
                  <button style="font-family: 'Work Sans'"
                    class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black">
                    Dapatkan Tiket
                  </button>
                </form>
              <?php else: // Jika kuota habis ?>
                <button style="font-family: 'Work Sans'"
                  class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black"
                  disabled>
                  Tiket telah habis
                </button>
              <?php endif; ?>

            <?php elseif ($event['event_status'] == 2): // Status event belum dimulai ?>
              <button style="font-family: 'Work Sans'"
                class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black"
                disabled>Pendaftaran Belum Dibuka
              </button>

            <?php else: // Jika event sudah berakhir ?>
              <button style="font-family: 'Work Sans'"
                class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black"
                disabled>Event Sudah Berakhir
              </button>

            <?php endif; ?>

          <?php else: // Jika user sudah memiliki tiket ?>
            <div>
              <p style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl">Kamu sudah memiliki tiket.</p>
            </div>
          <?php endif; ?>
        </div>

      </div>
    <?php endforeach; ?>

    <!-- Tampilkan pesan jika tidak ada event yang sesuai -->
    <?php if (!$events_found): ?>
      <p class="text-white px-6 md:px-16 text-xl md:text-2xl md:mx-auto">Jadwal Belum Tersedia</p>
    <?php endif; ?>
  </section>

  <div class="flex flex-col max-w-full items-center text-center gap-6 text-white bg-[#0d0d0d] pt-12 pb-20">
    <div class="text-white text-2xl md:text-3xl font-semibold font-work">Finder 6 Map</div>
    <div class="w-5/6 max-w-[1200px] overflow-hidden rounded-xl">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.2348973267035!2d107.59106691057444!3d-6.862428093107444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6b943c2c5ff%3A0xee36226510a79e76!2sUniversitas%20Pendidikan%20Indonesia!5e0!3m2!1sid!2sid!4v1710655960109!5m2!1sid!2sid"
        width="1366" height="400" style="border: 0" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <a href="https://maps.app.goo.gl/w12NySVz2bjC6X527" target="_blank"><button
        class="border-[1px] font-work hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Check
        On Google Map</button></a>
  </div>
  <?php
  require '_footer.php';
  ?>
  <!-- J56w5l -->
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
  <!-- Corosuel Animasi Js -->
  <script>
    const scrollers = document.querySelectorAll('.scroller');

    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      addAnimation();
    }

    function addAnimation() {
      scrollers.forEach((scroller) => {
        scroller.setAttribute('data-animated', true);
        const scrollerInner = scroller.querySelector('.scroller__inner');
        const scrollerContent = Array.from(scrollerInner.children);
        scrollerContent.forEach((item) => {
          const duplicatedItem = item.cloneNode(true);
          duplicatedItem.setAttribute('aria-hidden', true);
          scrollerInner.appendChild(duplicatedItem);
        });
      });
    }
  </script>
</body>
<script>
  // Script untuk mengatur kelas aktif pada tombol filter
  document.addEventListener('DOMContentLoaded', function () {
    // Ambil semua elemen tombol filter
    const filterButtons = document.querySelectorAll('.filter-button');

    // Tambah event listener untuk setiap tombol filter
    filterButtons.forEach(button => {
      button.addEventListener('click', function () {
        // Hapus kelas 'active' dari semua tombol filter
        filterButtons.forEach(btn => btn.classList.remove('active'));

        // Tambah kelas 'active' pada tombol yang diklik
        this.classList.add('active');
      });
    });
  });
</script>
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
<!-- Scroll Animatioin -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="system.js"></script>
<!-- ------- -->

</html>