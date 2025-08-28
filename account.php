<?php
session_start();

// Periksa apakah session user ada dan tidak kosong
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Koneksi ke database
include 'admin-one/dist/koneksi.php';
// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Persiapkan query untuk mengambil data user berdasarkan user_id
$query_user = "SELECT nama, tgl_lahir, no_hp, instansi, email, kode_account FROM user WHERE id_user = ?";

// Persiapkan statement untuk data user
$stmt_user = mysqli_prepare($koneksi, $query_user);
if (!$stmt_user) {
  // Handle error jika prepare statement gagal
  die('Prepare statement user failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);

// Ambil hasil query data user
$result_user = mysqli_stmt_get_result($stmt_user);

// Periksa apakah data user ditemukan
if ($row_user = mysqli_fetch_assoc($result_user)) {
  // Simpan data user ke dalam session atau langsung gunakan
  $_SESSION['user_data'] = [
    'nama' => $row_user['nama'],
    'tgl_lahir' => $row_user['tgl_lahir'],
    'no_hp' => $row_user['no_hp'],
    'instansi' => $row_user['instansi'],
    'email' => $row_user['email'],
    'kode_account' => $row_user['kode_account']  // Pastikan kode_account disimpan
  ];
} else {
  // Jika data user tidak ditemukan, logout dan kembali ke halaman login
  session_destroy();
  header("Location: login.php");
  exit();
}

// Tutup statement data user
mysqli_stmt_close($stmt_user);


// Query untuk mendapatkan data tiket dan event berdasarkan id_user
$query_tiket_event = "
    SELECT tiket.tiket_code, event.judul_event, event.speakers_event, event.jadwal_event, event.waktu_event, event.lokasi_event, event.link_grup
    FROM tiket
    JOIN event ON tiket.id_event = event.id_event
    WHERE tiket.id_user = ?";

// Persiapkan statement untuk data tiket dan event
$stmt_tiket_event = mysqli_prepare($koneksi, $query_tiket_event);
if (!$stmt_tiket_event) {
  // Handle error jika prepare statement gagal
  die('Prepare statement tiket event failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_tiket_event, "i", $user_id);
mysqli_stmt_execute($stmt_tiket_event);

// Ambil hasil query data tiket dan event
$result_tiket_event = mysqli_stmt_get_result($stmt_tiket_event);

// Array untuk menyimpan data tiket dan event
$tiket_data = [];
while ($row_tiket_event = mysqli_fetch_assoc($result_tiket_event)) {
  $tiket_data[] = $row_tiket_event;
}

// Tutup statement data tiket dan event
mysqli_stmt_close($stmt_tiket_event);
mysqli_close($koneksi);

?>


<!DOCTYPE html>
<html lang="en">

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

  <title>Finder 6 - Profile</title>
  <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />

  <link rel="stylesheet" href="style.css" />

  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>

<body class="bg-black items-center">
  <?php require '_navbar.php'; ?>

  <div id="profile" style="" class="flex flex-col items-center gap-4 py-10 max-w-full bg-cover">
    <div class="flex items-start w-[90%] pt-24 ">
      <a href="homepage.php" class="">
        <img src="./img/arrow-left 1.svg" alt="Back" />
      </a>
    </div>
  </div>

  <section class="flex flex-col sm:flex-row w-11/12 mx-auto gap-8">

    <div class="flex flex-col w-full sm:w-2/3 gap-8">

      <!-- Profile Section -->

      <div class="flex gap-10 w-full justify-start items-center bg-neutral-900 py-24 px-16 rounded-3xl">
        <div style="background-image: url(./img/profill.png)" class="w-32 sm:w-64 aspect-square bg-cover rounded-full bg-slate-300 overflow-hidden">
        </div>
        <div class="flex flex-col gap-4">
          <h1 class="text-white text-lg sm:text-3xl font-semibold font-work w-full ">
            <?php echo htmlspecialchars($_SESSION['user_data']['nama']); ?>
          </h1>
          <h2 class="text-white text-base sm:text-2xl font-light font-work w-full ">
            <?php echo htmlspecialchars($_SESSION['user_data']['instansi']); ?>
          </h2>
        </div>
      </div>

      <!-- <section id="" style="background-image: url(./img/bghero.png)" class="flex flex-col items-center py-10 max-w-full bg-cover">
              <a href="homepage.html" target="_blank" class="w-[90%] flex items-start"
              ><div><img src="./img/arrow-left 1.svg" alt="" /></div
              ></a>
              <div class="flex flex-col gap- w-full items-center">
                <div style="background-image: url(./img/profill.png)" class="w-32 h-32 bg-cover rounded-full bg-slate-300"></div>
                <h1 class="text-white text-2xl md:text-3xl font-semibold font-work w-full text-center">Javier Ramadhan</h1>
                <h2 class="text-white text-xl md:text-2xl font-light font-work w-full text-center">Universitas Pendidikan Indonesia</h2>
              </div>
            </section> -->



      <!-- Tiket seminar dan workshop Section -->
      <div id="tiket" class="w-full bg-[#0D0D0D] flex flex-col gap-8 py-32 rounded-3xl" >
        <h1 class="text-white text-2xl md:text-4xl font-bold font-work w-full text-center">Tiket Seminar dan
          Workshop</h1>

        <!-- Loop untuk menampilkan setiap tiket -->
        <?php foreach ($tiket_data as $tiket): ?>
          <div
            class="flex gap-5 md:justify-between items-center px-8 py-6 rounded-xl max-md:flex-wrap max-md:px-5 w-[90%] mx-auto bg-gradient-to-r from-[#121212] to-[#1A1A1A]">
            <div
              class="flex flex-col w-2/3 mx-auto md:mx-0 text-center md:items-start gap-2 my-auto text-3xl font-medium md:text-start text-white">
              <div class="text-white text-2xl md:text-3xl font-semibold font-work">
                <?php echo htmlspecialchars($tiket['judul_event']); ?>
              </div>
              <div class="text-white text-lg md:text-xl font-semibold font-works">By
                <?php echo htmlspecialchars($tiket['speakers_event']); ?>
              </div>
              <div class="text-white text-lg md:text-xl font-semibold font-works">
                Date: <?php echo htmlspecialchars($tiket['jadwal_event']); ?>
              </div>
              <div class="text-white text-lg md:text-xl font-semibold font-works">
                Time: <?php echo htmlspecialchars($tiket['waktu_event']); ?>
              </div>
              <div class="text-white text-lg md:text-xl font-semibold font-works">
                Location: <?php echo htmlspecialchars($tiket['lokasi_event']); ?>
              </div>
              <div class="text-white text-lg md:text-xl font-semibold font-works"> Ticket Code :
                <?php echo htmlspecialchars($tiket['tiket_code']); ?>
              </div>
              <a href="<?php echo htmlspecialchars($tiket['link_grup']); ?>" target="_blank"
                style="font-family: 'Work Sans'"
                class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">
                Group WhatsApp
              </a>
            </div>
            <div
              class="mx-auto md:mx-0 flex flex-col justify-center items-center max-w-[250px] max-h-[250px] rounded-xl">
              <div id="qr-code-<?php echo $tiket['tiket_code']; ?>" class="qr-code-container">
                <!-- QR Code akan di-generate menggunakan JavaScript -->
              </div>
            </div>
          </div>


          <!-- JavaScript untuk menghasilkan QR Code -->
          <script>
            // Ambil nilai tiket_code dari PHP untuk tiket ini
            var tiketCode<?php echo $tiket['tiket_code']; ?> = "<?php echo htmlspecialchars($tiket['tiket_code']); ?>";

            // Buat QR Code menggunakan data URI
            var qrCodeUrl<?php echo $tiket['tiket_code']; ?> = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" + encodeURIComponent(tiketCode<?php echo $tiket['tiket_code']; ?>);

            // Tampilkan QR Code di dalam div yang sesuai
            document.getElementById('qr-code-<?php echo $tiket['tiket_code']; ?>').innerHTML = '<img src="' + qrCodeUrl<?php echo $tiket['tiket_code']; ?> + '" alt="QR Code">';
          </script>
        <?php endforeach; ?>

        <!-- Akhir Loop Tiket -->
      </div>
    </div>

    <!-- Tiket Section -->
    <div id="tiket" class="w-full sm:w-1/3 bg-[#0D0D0D] flex flex-col gap-8 py-32 rounded-3xl">
      <h1 class="text-white text-2xl md:text-4xl font-bold font-work w-full text-center">Tiket Masuk Finder
      </h1>

      <!-- Loop untuk menampilkan setiap tiket -->

      <div class="flex flex-col gap-5 md:justify-center items-center px-8 py-8 rounded-xl max-md:flex-wrap max-md:px-5 w-full mx-auto">
        <div class="mx-auto md:mx-0 flex flex-col justify-center items-center bg-white max-w-[500px] max-h-[500px] rounded-xl">
          <div id="qr-codee-<?php echo $_SESSION['user_data']['kode_account']; ?>" class="qr-code-container">
            <!-- QR Code akan di-generate menggunakan JavaScript -->
          </div>
        </div>
        <hr>
        <hr>
        <div
          class="flex flex-col mx-auto md:mx-0 text-center md:items-center gap-4 my-auto text-3xl font-medium md:text-center text-white w-5/6">
          <div class="text-white text-2xl md:text-3xl font-bold font-work">
            Akses Masuk
          </div>
          <div class="text-white text-lg md:text-xl font-semibold font-works italic">
            Ticket Code: <?php echo htmlspecialchars($_SESSION['user_data']['kode_account']); ?>
          </div>
          <div class="text-white text-base md:text-lg font-light font-works">
            Tunjukkan tiket ini kepada panitia disaat kamu memasuki area Finder 6 Pusaka
          </div>
        </div>
      </div>


      <script>
        // Ambil nilai kode_account dari PHP untuk tiket ini
        var kodeAccount = "<?php echo htmlspecialchars($_SESSION['user_data']['kode_account']); ?>";

        // Buat QR Code menggunakan data URI
        var qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" + encodeURIComponent(kodeAccount);

        // Tampilkan QR Code di dalam div yang sesuai
        document.getElementById('qr-codee-<?php echo $_SESSION['user_data']['kode_account']; ?>').innerHTML = '<img src="' + qrCodeUrl + '" alt="QR Code">';
      </script>

      <!-- JavaScript untuk menghasilkan QR Code -->
      <script>
        // Ambil nilai tiket_code dari PHP untuk tiket ini
        var tiketCode<?php echo $tiket['tiket_code']; ?> = "<?php echo htmlspecialchars($tiket['tiket_code']); ?>";

        // Buat QR Code menggunakan data URI
        var qrCodeUrl<?php echo $tiket['tiket_code']; ?> = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" + encodeURIComponent(tiketCode<?php echo $tiket['tiket_code']; ?>);

        // Tampilkan QR Code di dalam div yang sesuai
        document.getElementById('qr-code-<?php echo $tiket['tiket_code']; ?>').innerHTML = '<img src="' + qrCodeUrl<?php echo $tiket['tiket_code']; ?> + '" alt="QR Code">';
      </script>


      <!-- Akhir Loop Tiket -->
    </div>
  </section>


  <?php require '_footer.php'; ?>
</body>
<!-- JavaScript -->
<script src="https://unpkg.com/kursor"></script>
<script>
  new kursor({
    type: 4,
    removeDefaultCursor: true,
    color: '#ffffff',
  });
</script>
<script src="system.js"></script>
<!-- Script Toggle -->
<script>
  const navLinks = document.querySelector('.nav-links');
  function onToggleMenu(e) {
    e.name = e.name === 'menu' ? 'close' : 'menu';
    navLinks.classList.toggle('-bottom-52');
  }
</script>
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

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

</html>