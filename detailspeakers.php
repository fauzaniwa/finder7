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

<body class="bg-[#000000]">
    <?php
    require '_navbar.php';
    ?>


    <section id="" class="w-full h-full pt-32 bg-[#0D0D0D] pb-32">
        <div
            class="flex flex-col lg:flex-row lg:justify-between w-[90%] mx-auto bg-[#131313] py-4 gap-6 lg:items-center px-4 rounded-xl">
            <div class="flex flex-col gap-4 lg:gap-2 w-full order-last lg:order-first">

                <div class="flex flex-col gap-4">
                    <h1 style="font-family: 'Work Sans'" class="text-white text-2xl lg:text-3xl font-medium">
                        <?php echo $nama ?>
                    </h1>
                </div>
                <div class="flex flex-wrap lg:flex-row gap-2 md:gap-4">
                    <p style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">
                        <?php echo $instansi ?>
                    </p>
                </div>
            </div>
            <img src="<?= $fotoPath ?>" alt="<?= $nama ?>"
                onerror="this.onerror=null;this.src='img/narsum/segerahadir.png';"
                class="order-first lg:order-last shrink-0 bg-cover h-full max-h-[350px] max-w-[350px]">
        </div>


        <section class="flex gap-5 justify-between w-[90%] lg-w-[60%] mx-auto my-4">
            <h2 class="text-white text-2xl md:text-3xl font-semibold font-work text-center">Profile</h2>
            <div class="flex gap-3 px-5"></div>
        </section>
        <p class="w-[90%] mx-auto text-white text-lg md:text-xl font-light font-work text-justify mb-4">
            <span class="font-reguler"><?= nl2br($deskripsi) ?></span>
        </p>

        <?php
        // Tutup statement dan koneksi
        mysqli_stmt_close($stmt_event);
        mysqli_close($koneksi);
        ?>
    </section>
    <?php
    require '_footer.php';
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