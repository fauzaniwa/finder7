<?php session_start();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
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
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }


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
    <title>Finder - Lomba Cover Buku</title>
    <link rel="icon" href="../img/FinderLogo.svg" type="image/x-icon" />
    <!-- Script Navbar Menu -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Script Cursor -->
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <!-- Script Cursor -->
    <link rel="stylesheet" href="../style.css" />
</head>

<body class="bg-black">
    <?php
    require '_navbar.php'
        ?>
    <div
        class="w-2/3 h-3/4 blur-3xl absolute -z-10 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
    </div>

    <!-- Hero Section -->
    <section class="hero relative min-h-screen px-4  flex items-center justify-center w-full mx-auto overflow-hidden ">
        <!-- Top Left -->
        <img src="./img/Submission/supergraphic5.png" alt="Ilustrasi karakter dekoratif pojok kiri atas"
            class="absolute hidden lg:flex top- left- right-2/3 w-[clamp(8rem,9vw,10rem)] translate-x-[-35%] translate-y-[30%] sm:translate-x-[-10%] sm:translate-y-[-16.66%] animate-pulse" />

        <!-- Top Right -->
        <img src="./img/Submission/supergraphic6.png" alt="Ilustrasi karakter dekoratif pojok kanan atas"
            class="absolute hidden lg:flex top-2/4 left- right-1/3 w-[clamp(12rem,16vw,20rem)] translate-x-[35%] translate-y-[30%] sm:translate-x-[75%] sm:translate-y-[-10%] animate-pulse" />

        <!-- Top Left -->
        <img src="./img/Submission/supergraphic3.png" alt="Ilustrasi karakter dekoratif pojok kiri atas"
            class="absolute hidden sm:flex bottom-32 left-0 w-[clamp(10rem,15vw,16rem)] translate-x-[-35%] translate-y-[70%] sm:translate-x-[-25%] sm:translate-y-[79.99%%] animate-pulse" />

        <!-- Top Right -->
        <img src="./img/Submission/supergraphic4.png" alt="Ilustrasi karakter dekoratif pojok kanan atas"
            class="absolute hidden sm:flex top-0 right-0 w-[clamp(10rem,15vw,16rem)] translate-x-[0%] translate-y-[70%] sm:translate-x-[0%] sm:translate-y-[19.99%] animate-pulse" />

        

        <div class="justify-center flex flex-col items-center p-10 max-w-xl mx-auto py-72 ">

            <h1 class="text-white text-3xl md:text-5xl font-semibold text-center"> FINDER X WACOM X NEON EXPERIENCE</h1>
            <br><br>
            <div class="countdown flex justify-center gap-2 items-center mb-4">
                <div class="flex flex-col items-center justify-center">
                    <div class="flex flex-row gap-2">
                        <div
                            class="digit bg-white text-black text-3xl md:text-5xl p-3 md:p-5 items-center justify-center rounded-md font-mono">
                            0</div>
                        <div
                            class="digit bg-white text-black text-3xl md:text-5xl p-3 md:p-5 items-center justify-center rounded-md font-mono">
                            0</div>
                    </div>
                    <div class="text-white justify-center items-center italic mt-2 text-sm md:text-base">Days</div>
                </div>
                <div class="separator text-white text-3xl md:text-5xl font-mono mb-6">:</div>
                <div class="flex flex-col items-center justify-center">
                    <div class="flex flex-row gap-2">
                        <div
                            class="digit bg-white text-black text-3xl md:text-5xl p-3 md:p-5 items-center justify-center rounded-md font-mono">
                            0</div>
                        <div
                            class="digit bg-white text-black text-3xl md:text-5xl p-3 md:p-5 items-center justify-center rounded-md font-mono">
                            0</div>
                    </div>
                    <div class="text-white justify-center items-center italic mt-2 text-sm md:text-base">Hours</div>
                </div>
                <div class="separator text-white text-3xl md:text-5xl font-mono mb-6">:</div>
                <div class="flex flex-col items-center justify-center">
                    <div class="flex flex-row gap-2">
                        <div
                            class="digit bg-white text-black text-3xl md:text-5xl p-3 md:p-5 items-center justify-center rounded-md font-mono">
                            0</div>
                        <div
                            class="digit bg-white text-black text-3xl md:text-5xl p-3 md:p-5 items-center justify-center rounded-md font-mono">
                            0</div>
                    </div>
                    <div class="text-white justify-center items-center italic mt-2 text-sm md:text-base">Minutes</div>
                </div>
            </div>


            <br><br>
            <h1 class="text-white text-2xl md:text-4xl font-semibold text-center"> Ayo Submit Karyamu!</h1>
            <br>
            <h3 class="text-white text-base md:text-2xl italic mb-5 text-center"> Jangan lupa baca syarat dan
                ketentuannya dulu ya!</h3>
            <a href="#ketentuan">
                <button
                    class="submit-btn  bg-emerald-600 hover:bg-emerald-800 transition-all duration-300 ease-in-out px-10 py-3 md:px-20 md:py-5 rounded-2xl md:rounded-3xl mt-6 text-base md:text-xl text-white  ">Lihat
                    Ketentuan</button>
            </a>
        </div>
    </section>

<!-- Lomba-lomba -->
<?php
// Data Lomba (berperan sebagai database sementara)
$competitions = [
    [
        'id' => 1,
        'title' => 'Finder 7 x Wacom: Ilustrasi Buku',
        'status' => ['Open'],
        'deadline' => 'Diperpanjang - 9 September 2025',
        'prize' => 'Satu unit wacom, sertifikat, dan masih banyak lagi!',
        'message' => 'Baca Ketentuannya dan Daftar Sekarang!',
        'links' => [
            'ketentuan' => './wacom/#ketentuan',
            'daftar'    => './wacom/submitkaryawacom.php'
        ]
    ],
    [
        'id' => 2,
        'title' => 'Lomba Poster Ilustrasi',
        'status' => ['Close'],
        'deadline' => '9 Juli 2025',
        'prize' => 'Uang tunai, sertifikat, dan masih banyak lagi!',
        'message' => 'Maaf ya lomba ini sudah berakhir :(',
        'links' => [
            'pemenang' => 'pengumuman_lomba.php'
        ]
    ],
    [
        'id' => 3,
        'title' => 'Lomba Desain Karakter',
        'status' => ['Close'],
        'deadline' => '9 Juli 2025',
        'prize' => 'Uang tunai, sertifikat, dan masih banyak lagi!',
        'message' => 'Maaf ya lomba ini sudah berakhir :(',
        'links' => [
            'pemenang' => 'pengumuman_lomba.php'
        ]
    ],
];
?>

<main class="flex items-center justify-center p-4">
  <section class="bg-[#121212] p-8 rounded-3xl w-full max-w-7xl mx-auto shadow-lg">
    <h1 class="text-4xl font-bold text-white text-center my-8">Lomba Finder 7</h1>

    <div class="space-y-8">
      <?php foreach ($competitions as $lomba): ?>
      <div class="p-6 rounded-lg">
        
        <!-- Judul + Status -->
        <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center gap-y-2 sm:gap-x-2">
          <h2 class="text-2xl md:text-3xl font-bold text-white"><?= htmlspecialchars($lomba['title']) ?></h2>
          <div class="flex items-center gap-2 flex-shrink-0">
            <?php foreach ($lomba['status'] as $status): ?>
              <?php if ($status == 'New'): ?>
                <span class="text-white text-md font-semibold px-9 py-2 rounded-full">New</span>
              <?php elseif ($status == 'Open'): ?>
                <span class="bg-white text-gray-800 text-md font-semibold px-9 py-2 rounded-full">Open</span>
              <?php elseif ($status == 'Close'): ?>
                <span class="bg-[#313131] text-gray-300 text-md font-semibold px-9 py-2 rounded-full">Closed</span>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>

        <hr class="my-4 border-t border-gray-500">

        <!-- Isi -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
          <div class="text-base md:text-lg space-y-1">
            <p class="text-gray-300"><strong>Deadline :</strong> <?= htmlspecialchars($lomba['deadline']) ?></p>
            <p class="text-gray-300"><strong>Prize:</strong> <?= htmlspecialchars($lomba['prize']) ?></p>
            <p class="<?= in_array('Close', $lomba['status']) ? 'text-gray-400 italic' : 'text-gray-200' ?> mt-1">
              <?= htmlspecialchars($lomba['message']) ?>
            </p>
          </div>

          <!-- Tombol -->
          <div class="flex flex-col gap-3 w-full md:w-auto flex-shrink-0">
            <?php if (in_array('Open', $lomba['status'])): ?>
              <?php if (isset($lomba['links']['ketentuan'])): ?>
                <a href="<?= $lomba['links']['ketentuan'] ?>" class="bg-white text-gray-900 font-semibold py-3 px-6 rounded-2xl text-center transition hover:bg-gray-200">
                  Lihat Ketentuan
                </a>
              <?php endif; ?>

              <?php if (isset($lomba['links']['daftar'])): ?>
                <a href="<?= $lomba['links']['daftar'] ?>" class="bg-[#10B981] text-white font-semibold py-3 px-6 rounded-2xl text-center transition hover:bg-[#059669]">
                  Daftar
                </a>
              <?php endif; ?>
            
            <?php elseif (in_array('Close', $lomba['status']) && isset($lomba['links']['pemenang'])): ?>
              <a href="<?= $lomba['links']['pemenang'] ?>" class="bg-[#10B981] text-white font-semibold py-3 px-6 rounded-2xl text-center transition hover:bg-[#059669]">
                Lihat Pemenang
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>

    <br><br><br><br><br><br>

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

    <!-- Modal Gambar -->
    <div id="imageModal" class="fixed inset-0 z-50 bg-black bg-opacity-80 hidden items-center justify-center">
        <div class="relative">
            <button id="closeModal"
                class="absolute -top-0 -right-0 bg-white text-black rounded-xl px-4 p-2 hover:bg-gray-200 z-10">✕</button>
            <img id="modalImage" src="" class="max-w-[90vw] max-h-[90vh] rounded-xl shadow-2xl" />
        </div>
    </div>

    <script>
        // Ambil elemen-elemen yang dibutuhkan
        const modal = document.getElementById("imageModal");
        const modalImage = document.getElementById("modalImage");
        const closeModal = document.getElementById("closeModal");

        // Event saat klik gambar
        document.querySelectorAll(".popup-image").forEach(img => {
            img.addEventListener("click", () => {
                const src = img.getAttribute("data-img");
                modalImage.src = src;
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });
        });

        // Event tombol close
        closeModal.addEventListener("click", () => {
            modal.classList.add("hidden");
        });

        // Klik di luar gambar untuk menutup
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");
            }
        });
    </script>

</body>
<script src="https://unpkg.com/kursor"></script>
<script>
    new kursor({
        type: 4,
        removeDefaultCursor: true,
        color: '#ffffff',
    });
</script>

<script>
    // Hapus localStorage lama (kalau pernah pakai sebelumnya)
    localStorage.removeItem('countdownEnd');

// Target: 9 September 2025 pukul 23:59 WIB → 16:59 UTC
const targetDate = new Date(Date.UTC(2025, 8, 9, 16, 59, 0));

    const endTime = targetDate.getTime();

    const digits = document.querySelectorAll('.digit');

    function updateCountdown() {
        const now = Date.now();
        let remaining = Math.floor((endTime - now) / 1000);

        if (remaining < 0) remaining = 0;

        const days = Math.floor(remaining / (24 * 3600));
        const hours = Math.floor((remaining % (24 * 3600)) / 3600);
        const minutes = Math.floor((remaining % 3600) / 60);

        const timeStr =
            String(days).padStart(2, '0') +
            String(hours).padStart(2, '0') +
            String(minutes).padStart(2, '0');

        digits.forEach((digitEl, index) => {
            digitEl.textContent = timeStr[index];
        });

        if (remaining <= 0) {
            clearInterval(timer);
            console.log("Waktu habis!");
        }
    }

    const timer = setInterval(updateCountdown, 1000);
    updateCountdown();




    // Fungsi reusable untuk setup observer dan tab scroll
    function setupSectionNavigation({ sectionClass, navClass }) {
        const section = document.querySelector(`.${sectionClass}`);
        const navTabs = document.querySelectorAll(`.${navClass}`);

        if (!section) return;

        // Observer untuk menyorot tab aktif saat scroll
        const observerOptions = {
            root: section,
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Hapus class aktif dari semua tab
                    navTabs.forEach(tab => {
                        tab.classList.remove('text-emerald-400', 'underline');
                        tab.classList.add('text-neutral-600');
                    });

                    // Tambahkan class aktif ke tab yang sesuai
                    const id = entry.target.getAttribute('id');
                    const activeTab = document.querySelector(`.${navClass}[href="#${id}"]`);
                    if (activeTab) {
                        activeTab.classList.add('text-emerald-400', 'underline');
                        activeTab.classList.remove('text-neutral-600');
                    }
                }
            });
        }, observerOptions);

        // Observe semua child div di dalam section
        section.querySelectorAll('div[id]').forEach(el => observer.observe(el));

        // Klik navigasi => scroll halus
        navTabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                if (target) {
                    section.scrollTo({
                        left: target.offsetLeft - section.offsetLeft,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Setup untuk masing-masing bagian
    setupSectionNavigation({
        sectionClass: 'umum',
        navClass: 'nav-tab'
    });

    setupSectionNavigation({
        sectionClass: 'khusus',
        navClass: 'nav2-tab'
    });
</script>

</html>