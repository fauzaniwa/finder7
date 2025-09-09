<?php
// Koneksi ke database
require_once "admin-one/dist/koneksi.php";
$koneksi = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (mysqli_connect_errno()) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil id_speaker dari URL
$id_speaker = isset($_GET['id_speaker']) ? intval($_GET['id_speaker']) : 0;

// Pastikan id_speaker valid
if ($id_speaker === 0) {
  die("ID Speaker tidak valid.");
}

// Query untuk mengambil data speaker berdasarkan id_speaker
$query = "SELECT id_speaker, nama_speaker, instansi, deskripsi, kontak, foto_speaker, created_at, urutan FROM speakers WHERE id_speaker = ?";
$stmt = mysqli_prepare($koneksi, $query);

if ($stmt) {
  // Bind parameter id_speaker
  mysqli_stmt_bind_param($stmt, "i", $id_speaker);

  // Eksekusi query
  mysqli_stmt_execute($stmt);

  // Ambil hasilnya
  $result = mysqli_stmt_get_result($stmt);

  // Periksa apakah ada data
  if (mysqli_num_rows($result) > 0) {
    // Ambil data speaker
    $speaker = mysqli_fetch_assoc($result);
    $nama = htmlspecialchars($speaker['nama_speaker']);
    $instansi = htmlspecialchars($speaker['instansi']);
    $deskripsi = htmlspecialchars($speaker['deskripsi']);
    $kontak = htmlspecialchars($speaker['kontak']);
    $foto = htmlspecialchars($speaker['foto_speaker']);
    $fotoPath = !empty($foto) ? 'img/speakers/' . $foto : 'img/narsum/segerahadir.png'; // Tentukan foto default jika tidak ada

  } else {
    die("Speaker tidak ditemukan.");
  }

  // Tutup statement
  mysqli_stmt_close($stmt);
} else {
  die("Gagal menyiapkan query.");
}

// Tutup koneksi
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
  <!-- Title Web & Icon -->
  <title>Finder - Details Speakers</title>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const today = new Date();
      const targetDate = new Date('2024-07-24T00:00:00+07:00'); // 24 Juli 2024 WIB

      if (today < targetDate) {
        alert('Halaman ini hanya dapat diakses setelah 24 Juli 2024.');
        window.location.href = 'homepage.php'; // Redirect ke homepage.php
      }
    });
  </script>
  <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
  <!-- Script Navbar Menu -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <!-- Script Cursor -->
  <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
  <!-- Script Cursor -->
  <link rel="stylesheet" href="style.css" />
</head>

<body class="bg-neutral-950">
  <?php
  require '_navbar.php';
  ?>
  <div
    class="w-2/3 h-3/4 blur-3xl absolute -z-10 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
  </div>


  <section id=""
    class=" text-gray-200 w-10/12 z-10 mx-auto min-h-screen flex flex-col items-center justify-center mt-20 p-4">

    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-10">
      <div class="flex-shrink-0 w-auto h-full rounded-lg flex items-center justify-center overflow-hidden">
        <img src="<?= $fotoPath ?>" alt="<?= $nama ?>"
          class="order-first lg:order-last shrink-0 bg-cover h-full max-h-[350px] max-w-[350px]">
      </div>

      <div class="flex-grow text-center md:text-left">
        <h1 class="text-4xl font-bold text-white mb-2"><?php echo $nama; ?></h1>
        <p class="text-xl text-white mb-4"><?php echo $instansi; ?></p>

        <div class="flex justify-center md:justify-start space-x-4 mb-6">
          <?php if (isset($kontak['twitter'])): ?>
            <a href="<?php echo htmlspecialchars($kontak['twitter']); ?>" target="_blank"
              class="hover:text-white transition-colors duration-200">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path
                  d="M20.21,7.965c-0.658,0.293-1.365,0.49-2.099,0.579c0.758-0.455,1.34-1.173,1.61-2.035c-0.709,0.422-1.496,0.729-2.338,0.895C16.643,6.586,15.688,6,14.619,6c-2.067,0-3.743,1.676-3.743,3.744c0,0.294,0.034,0.58,0.1,0.857c-3.111-0.156-5.86-1.646-7.704-3.903c-0.322,0.554-0.505,1.198-0.505,1.889c0,1.299,0.66,2.44,1.666,3.111c-0.613-0.019-1.19-0.19-1.696-0.468c0,0.016,0,0.031,0,0.046c0,1.815,1.29,3.325,3.001,3.673c-0.314,0.086-0.645,0.132-0.985,0.132c-0.241,0-0.474-0.022-0.701-0.067c0.478,1.492,1.86,2.581,3.5,2.611c-1.282,1.006-2.894,1.604-4.655,1.604c-0.302,0-0.597-0.017-0.887-0.052c1.656,1.062,3.626,1.683,5.748,1.683c6.899,0,10.662-5.714,10.662-10.66c0-0.162-0.003-0.323-0.009-0.482C19.065,9.333,19.704,8.682,20.21,7.965z" />
              </svg>
            </a>
          <?php endif; ?>
          <?php if (isset($kontak['linkedin'])): ?>
            <a href="<?php echo htmlspecialchars($kontak['linkedin']); ?>" target="_blank"
              class="hover:text-white transition-colors duration-200">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path
                  d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm3.36 10.153l-3.36 3.36v-1.153l-2.27 2.27v-1.153h-.177v1.153l-2.27-2.27v1.153l-3.36-3.36v-1.153l3.36-3.36v1.153l2.27-2.27v1.153h.177v-1.153l2.27 2.27v-1.153l3.36 3.36v1.153z"
                  clip-rule="evenodd" />
              </svg>
            </a>
          <?php endif; ?>
          <?php if (isset($kontak['youtube'])): ?>
            <a href="<?php echo htmlspecialchars($kontak['youtube']); ?>" target="_blank"
              class="hover:text-white transition-colors duration-200">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path
                  d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.187 6.331l-2.074 2.074a3 3 0 10-4.243-4.243l2.074 2.074a1 1 0 11-1.414 1.414l-2.074-2.074a5 5 0 117.071 7.071l-2.074-2.074a1 1 0 11-1.414-1.414l2.074 2.074a3 3 0 104.243-4.243z"
                  fill-rule="evenodd" clip-rule="evenodd" />
              </svg>
            </a>
          <?php endif; ?>
        </div>

        <div class="mb-6">
          <p class="text-white"><?php echo $deskripsi; ?>.</p>
        </div>
      </div>
    </div>

    <br><br>

    <!-- <div class="bg-neutral-900 p-6 rounded-lg mt-8 w-full">
      <h3 class="text-lg font-semibold text-white mb-4">(Riwayat Karir)</h3>
      <ul class="list-disc pl-5 space-y-2">
        <li>
          <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </li>
        <li>
          <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </li>
        <li>
          <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </li>
        <li>
          <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </li>
      </ul>

      <p class="text-white mt-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nisi arcu, lobortis
        quis ligula vel, accumsan congue diam.</p>
      <ul class="list-disc pl-5 space-y-2 mt-2">
        <li>
          <p class="text-white">Morbi nisi arcu, lobortis quis ligula vel, accumsan congue diam.</p>
        </li>
        <li>
          <p class="text-white">Morbi nisi arcu, lobortis quis ligula vel, accumsan congue diam.</p>
        </li>
      </ul>
    </div> -->

    <?php
    // Tutup statement dan koneksi
    // mysqli_stmt_close($stmt_event);
    // mysqli_close($koneksi);
    // ?>
  </section>

  <?php
  require '_footer.php';
  ?>

  <!-- Script Toggle
  <script>
    const navLinks = document.querySelector('.nav-links');
    function onToggleMenu(e) {
      e.name = e.name === 'menu' ? 'close' : 'menu';
      navLinks.classList.toggle('-bottom-52');
    }
  </script>

   Script Toggle -->
  <!-- Script Navbar -->
  <!-- <script>
    const navEL = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
      if (window.scrollY > 56) {
        navEL.classList.add('navbar-scrolled');
      } else if (window.scrollY < 56) {
        navEL.classList.remove('navbar-scrolled');
      }
    });
  </script>
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
  <script src="system.js"></script> -->
  <!-- Tambahkan link Font Awesome di head -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->

  <!-- Corosuel Animasi Js -->
  <!-- <script>
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
  </script> -->


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