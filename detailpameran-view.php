<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
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
  <title>Finder - Pameran</title>
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

<body>
  <?php
  require '_navbar.php';
  ?>
  <section id="" class="w-full h-full pt-32 bg-[#0D0D0D] gap-6">
    <!-- Headline -->
    <section class="flex flex-col text-center text-white max-w-[947px] mx-auto py-16 p-4">
      <h2 class="w-full text-4xl max-md:max-w-full font-work">Pameran Finder</h2>
      <p class="mt-3 w-full text-base font-light max-md:max-w-full font-work">
        Halaman ini menampilkan berbagai karya yang luar biasa dari para kontributor yang telah berpartisipasi dalam
        acara FINDER 6. Setiap individu yang terlibat telah memberikan sumbangsih yang berarti dalam berbagai bentuk,
        mulai dari karya seni Hingga Fotografi
      </p>
    </section>

    <!-- Form Pencarian -->
    <form class="w-full flex flex-col justify-center md:flex-row text-center p-4 gap-4 mb-4 md:gap-6" method="GET"
      action="">
      <!-- <button
        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg"
        type="button" onclick="window.location.href='?search=&angkatan=';">Tampilkan Semua</button> -->

      <input type="text"
        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg"
        name="search" placeholder="Cari karya..." value="<?php echo htmlspecialchars($search); ?>">

      <select
        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-black rounded-full md:text-lg"
        name="angkatan">
        <option class="text-black" value="">Semua Angkatan</option>
        <option class="text-black" value="2021" <?php echo $angkatan == '2021' ? 'selected' : ''; ?>>Angkatan 2021
        </option>
        <option class="text-black" value="2022" <?php echo $angkatan == '2022' ? 'selected' : ''; ?>>Angkatan 2022
        </option>
        <option class="text-black" value="2023" <?php echo $angkatan == '2023' ? 'selected' : ''; ?>>Angkatan 2023
        </option>
      </select>

      <button
        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg"
        type="submit">Cari</button>
    </form>
    <?php
    // Sambungkan ke database
    require_once "admin-one/dist/koneksi.php";
    $koneksi = mysqli_connect($host, $username, $password, $database);

    // Periksa koneksi
    if (mysqli_connect_errno()) {
      die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Ambil nilai pencarian dan filter angkatan jika ada
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : '';

    // Buat kondisi filter berdasarkan angkatan
    $angkatanCondition = '';
    if ($angkatan == '2021') {
      $angkatanCondition = " AND NIM LIKE '21%'";
    } elseif ($angkatan == '2022') {
      $angkatanCondition = " AND NIM LIKE '22%'";
    } elseif ($angkatan == '2023') {
      $angkatanCondition = " AND NIM LIKE '23%'";
    }

    // Query untuk mengambil data karya, dengan pencarian dan filter angkatan jika ada
    $query = "SELECT id_karya, judul_karya, nama_karya, instagram, deskripsi, pict_karya, optional_karya, likes, comments 
          FROM karya
          WHERE (judul_karya LIKE ? OR nama_karya LIKE ? OR instagram LIKE ?)" . $angkatanCondition . "
          ORDER BY likes DESC";
    $stmt = mysqli_prepare($koneksi, $query);
    $searchParam = '%' . $search . '%';
    mysqli_stmt_bind_param($stmt, 'sss', $searchParam, $searchParam, $searchParam);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Periksa jika query berhasil dijalankan
    if (!$result) {
      die('Query error: ' . mysqli_error($koneksi));
    }
    ?>
    <!-- Detail Acara -->
    <section class="flex-wrap content-center pb-20 gap-6">
      <div class="grid gap-5 px-8 md:px-12 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        <?php
        // Pastikan koneksi ke database sudah dilakukan dan statement sudah dipersiapkan
        $query_check_like = "SELECT id_like FROM likes WHERE id_karya = ? AND user_id = ?";
        $stmt_check_like = mysqli_prepare($koneksi, $query_check_like);

        if ($stmt_check_like === false) {
          die("Error preparing the statement: " . mysqli_error($koneksi));
        }

        while ($row = mysqli_fetch_assoc($result)) {
          $id_karya = $row['id_karya'];
          $judul_karya = $row['judul_karya'];
          $nama_karya = $row['nama_karya'];
          $deskripsi = $row['deskripsi'];
          $instagram = $row['instagram'];
          $pict_karya = $row['pict_karya'];
          $optional_karya = $row['optional_karya'];
          $likes = $row['likes'];
          $comments = $row['comments'];

          // Memotong deskripsi maksimal 50 karakter
          $deskripsi_short = strlen($deskripsi) > 50 ? substr($deskripsi, 0, 50) . '...' : $deskripsi;

          // Menyiapkan dan mengeksekusi query untuk cek status like
          mysqli_stmt_bind_param($stmt_check_like, "ii", $id_karya, $_SESSION['user_id']);
          mysqli_stmt_execute($stmt_check_like);
          mysqli_stmt_store_result($stmt_check_like);
          $already_liked = mysqli_stmt_num_rows($stmt_check_like) > 0;

          // Tentukan kelas CSS untuk tombol like berdasarkan status like
          $likeButtonClass = $already_liked ? 'text-red-500' : 'text-white';
          ?>
          <article class="flex flex-col max-md:w-full">
            <div class="flex flex-col grow text-center text-white max-md:mt-8">
              <?php
              $imagePath = !empty($pict_karya) ? "img/karya/{$pict_karya}" : "img/karya/default.jpg";
              ?>
              <a style="background-image: url(<?php echo $imagePath; ?>); background-size: cover; background-position: center;"
                class="shrink-0 rounded-xl aspect-square bg-neutral-900 h-[340px]"
                href="detailkarya.php?id=<?php echo $id_karya; ?>"></a>
              <figcaption class="flex flex-col px-16 py-3 mt-4 rounded-xl bg-neutral-900 max-md:px-5">
                <a class="self-center text-2xl font-work"
                  href="detailkarya.php?id=<?php echo $id_karya; ?>"><?php echo $judul_karya; ?></a>
                <p class="mt-1 text-sm font-work">by : <?php echo $nama_karya; ?></p>

                <!-- Deskripsi -->
                <div class="description text-left my-2">
                  <span class="short-description"><?php echo $deskripsi_short; ?></span>
                  <?php if (strlen($deskripsi) > 50): ?>
                    <span class="full-description" style="display: none;"><?php echo $deskripsi; ?></span>
                    <br><button class="see-more-button text-red-500 font-semibold" onclick="showFullDescription(this)">See
                      more</button>
                  <?php endif; ?>
                </div>
                <hr class="mb-2 mt-2">
                <div class="flex justify-between items-center mt-auto">
                  <!-- Tombol Like -->
                  <div class="flex items-center">
                    <button class="like-button <?php echo $likeButtonClass ?> hover:text-red-500 mr-2"
                      onclick="likeArtwork(<?php echo $id_karya; ?>)">
                      <i class="fas fa-heart"></i>
                    </button>
                    <span class="text-white" id="like-count-<?php echo $id_karya; ?>"><?php echo $likes; ?></span>
                    <!-- Total Like -->
                  </div>
                  <!-- Tombol Komentar -->
                  <a href="detailkarya.php?id=<?php echo $id_karya . '#komentar1'; ?>" class="flex items-center">
                    <button class="comment-button text-white hover:text-green-500 mr-2">
                      <i class="fas fa-comment"></i>
                    </button>
                    <span class="text-white"><?php echo $comments; ?></span> <!-- Total Komentar -->
                  </a>
                  <!-- Tombol Share -->
                  <a href="detailkarya.php?id=<?php echo $id_karya . '#share'; ?>"
                    class="share-button text-white hover:text-blue-500">
                    <i class="fas fa-share"></i>
                  </a>
                </div>
              </figcaption>
            </div>
          </article>
          <?php
        }
        // Tutup statement setelah selesai
        mysqli_stmt_close($stmt_check_like);
        ?>
      </div>
    </section>


    <script>
      function showFullDescription(button) {
        let article = button.closest('article');
        let shortDescSpan = article.querySelector('.short-description');
        let fullDescSpan = article.querySelector('.full-description');
        if (shortDescSpan && fullDescSpan) {
          shortDescSpan.style.display = 'none';
          fullDescSpan.style.display = 'inline';
          button.innerText = 'See less';
          button.classList.remove('text-red-500');
          button.classList.remove('font-semibold');
          button.classList.add('text-blue-500');
          button.classList.add('font-normal');
          button.onclick = function () { showLessDescription(this); };
        }
      }

      function showLessDescription(button) {
        let article = button.closest('article');
        let shortDescSpan = article.querySelector('.short-description');
        let fullDescSpan = article.querySelector('.full-description');
        if (shortDescSpan && fullDescSpan) {
          shortDescSpan.style.display = 'inline';
          fullDescSpan.style.display = 'none';
          button.innerText = 'See more';
          button.classList.remove('text-blue-500');
          button.classList.remove('font-normal');
          button.classList.add('text-red-500');
          button.classList.add('font-semibold');
          button.onclick = function () { showFullDescription(this); };
        }
      }

      function likeArtwork(idKarya) {
        // Kirim permintaan Ajax ke server
        fetch('like_artwork_process.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ idKarya: idKarya }),
        })
          .then(response => response.json())
          .then(data => {
            console.log('Respons dari server:', data); // Tambahkan ini untuk melihat respons dari server di konsol

            if (data.success) {
              // Update jumlah like di UI
              const likeCountElement = document.getElementById(`like-count-${idKarya}`);
              likeCountElement.textContent = data.likes;

              // Ubah warna tombol like berdasarkan respons dari server
              const likeButton = document.querySelector(`.like-button[onclick="likeArtwork(${idKarya})"]`);
              if (data.alreadyLiked) {
                likeButton.classList.add('text-red-500');
                likeButton.classList.remove('text-white');
              } else {
                likeButton.classList.remove('text-red-500');
                likeButton.classList.add('text-white');
              }
            } else {
              alert('Gagal menambahkan like. Harap login terlebih dahulu.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
          });
      }
    </script>

    <?php
    // Tutup koneksi
    mysqli_close($koneksi);
    ?>



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
    <script src="system.js"></script>
    <!-- Tambahkan link Font Awesome di head -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

    <?php
    require '_footer.php';
    ?>
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