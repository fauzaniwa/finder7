<?php
// Sertakan file PHP yang berisi semua logika
include 'homepage_data.php';
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

    <section data-section-bg="dark"
        class="relative min-h-screen flex flex-col lg:flex-row items-center justify-center overflow-hidden px-4 mx-auto">
        <div class="order-last lg:order-first relative z-10 max-w-md text-center text-white space-y-4">
            <h2 class="text-4xl md:text-5xl font-bold text-center md:text-left leading-tight max-w-xs sm:max-w-md">
                Welcome <br />Finder 7 Mindspace
            </h2>
            <p class="sm:text-xl md:text-2xl text-gray-300 text-center md:text-left">
                think the unthinkable
            </p>

            <div class="flex flex-col gap-4 items-center md:items-start">
                <a href="https://www.instagram.com/finder_dkv/"
                    class="w-48 sm:w-64 h-12 sm:h-14 flex items-center justify-center bg-[#008C62] text-white text-lg sm:text-xl font-medium rounded-2xl sm:rounded-2xl shadow-md hover:scale-105 transition-transform duration-300">
                    Instagram
                </a>
                <a href="#finderdesc"
                    class="w-48 sm:w-64 h-12 sm:h-14 flex items-center justify-center bg-neutral-700 text-gray-300 text-lg sm:text-xl font-medium rounded-2xl sm:rounded-2xl shadow-md hover:scale-105 transition-transform duration-300">
                    See More
                </a>
            </div>

        </div>
        <div class="order-first lg:order-last mb-8 md:mb-0 flex justify-center">
            <img src="./img/hero/cover.gif" alt="Mindspace Characters" class="sm:max-w-md lg:max-w-xl animate-pulse" />
        </div>
    </section>

    <section id="finderdesc" class="relative pt-16 pb-16 overflow-hidden">
        <img src="./img/supergrafis/SG L.png" alt="Ilustrasi karakter dekoratif pojok kiri bawah" class=" absolute
            top-0 sm:top-auto sm:bottom-0  /* ① posisi */
            left-0
            w-[clamp(20rem,25vw,40rem)]
            translate-x-[-60%] sm:translate-x-[-30%]
            translate-y-[30%] sm:translate-y-[-50%] 
              z-0 sm:opacity-100 opacity-35" />

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
                <div class="relative z-10 flex flex-col items-center justify-center h-full">
                    <h2 class="w-full md:w-96 text-center text-white text-2xl sm:text-3xl md:text-4xl 
                        font-semibold font-['Work_Sans'] leading-[56px] md:leading-[64px]
                        mb-8 md:mb-12">
                        Finder
                    </h2>

                    <div
                        class="inline-flex flex-col md:flex-row justify-center items-center gap-10 md:gap-20 mb-8 md:mb-12">
                        <img class="w-60 md:w-72 h-auto md:h-24 object-contain"
                            src="img/Finder 7 Logo Title Tagline Full White.png" alt="Logo Finder 7 Mindspace" />
                        <img class="w-44 md:w-52 h-auto md:h-24 object-contain" src="img/DKVUPI WHITE 1.png"
                            alt="Logo DKV UPI" />
                    </div>

                    <p class="w-full max-w-[824px] text-center text-gray-100 text-base md:text-lg
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
    <section id="about" class="bg-black text-white py-16 px-6">
        <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-12 justify-center">
            <div class="flex-shrink-0 flex flex-col items-center md:items-start">
                <img src="img/Finder 7 Logo Title White.png" alt="Finder 7 Logo" class="w-32 sm:w-40 md:w-48" />
            </div>

            <div class="max-w-xl">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">
                    Finder 7
                </h2>
                <p class="text-base md:text-lg text-gray-100 leading-relaxed">
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
    <section class="py-20 px-4">
        <div class="max-w-7xl lg:max-w-screen-xl 2xl:max-w-screen-2xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl text-gray-100 font-bold mb-2">Get to Know About Them!</h2>
                <p class="text-base md:text-lg italic text-gray-100">Maskot Finder 7 yang lucu-lucu!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="rounded-3xl p-8 border border-white/10 hover:border-yellow-400 duration-300 hover-radial-bg"
                    style="--hover-color: #FEE139">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
                        <div class="flex-shrink-0">
                            <img src="img/hero/intuiting2.gif" alt="Intuitive Mascot"
                                class="w-52 h-auto filter drop-shadow-[0_0_15px_rgba(234,179,8,0.6)] animate-pulse">
                        </div>
                        <div class="text-center sm:text-left">
                            <h2 class="text-2xl md:text-3xl font-bold mb-3 text-yellow-300">Intuitive</h2>
                            <p class="text-gray-100 text-base md:text-lg leading-relaxed">
                                Intuitive ditandai oleh kemampuan untuk mendeteksi pola dan makna tersembunyi, yang
                                didorong oleh rasa ingin tahu yang tinggi. Individu dengan kepribadian intuitif
                                cenderung lebih kreatif, mengandalkan imajinasi, dan sering kali menghasilkan ide-ide
                                baru yang inovatif.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl p-8 border border-white/10 hover:border-green-400 duration-300 hover-radial-bg"
                    style="--hover-color: #4ade80">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
                        <div class="flex-shrink-0">
                            <img src="img/hero/sensing1.gif" alt="Sensing Mascot"
                                class="w-52 h-auto filter drop-shadow-[0_0_15px_rgba(74,222,128,0.6)] animate-pulse">
                        </div>
                        <div class="text-center sm:text-left">
                            <h2 class="text-2xl md:text-3xl font-bold mb-3 text-green-300">Sensing</h2>
                            <p class="text-gray-100 text-base md:text-lg leading-relaxed">
                                Sensing merupakan kepribadian yang sangat mengandalkan panca indra untuk mengambil suatu
                                informasi atau keputusan dalam berbagai aspek, sensing juga memiliki memori yang cukup
                                kuat. Seseorang dengan kepribadian sensing cenderung detail, realistis dan logis, ia
                                harus melihat atau merasakan sendiri suatu hal atau fenomena.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl p-8 border border-white/10 hover:border-blue-400 duration-300 hover-radial-bg"
                    style="--hover-color: #60a5fa">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
                        <div class="text-center sm:text-left">
                            <h2 class="text-2xl md:text-3xl font-bold mb-3 text-blue-300">Thinking</h2>
                            <p class="text-gray-100 text-base md:text-lg leading-relaxed">
                                Thinking adalah salah satu dari dua cara utama orang membuat suatu keputusan
                                (pasangannya feeling). Thinking cenderung menggunakan logika, analisis objektif dan
                                rasional saat menentukan pilihan atau menilai sesuatu. Untuk Thinking semua hal itu
                                kayak puzzle, haruslah ada bukti, dipikirin baik-baik, terus disusun baru sampai ketemu
                                jawaban yang paling masuk akal.
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <img src="img/hero/thinking1.gif" alt="Thinking Mascot"
                                class="w-52 h-auto filter drop-shadow-[0_0_15px_rgba(96,155,250,0.6)] animate-pulse">
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl p-8 border border-white/10 hover:border-pink-400 duration-300 hover-radial-bg"
                    style="--hover-color: #f472b6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
                        <div class="text-center sm:text-left">
                            <h2 class="text-2xl md:text-3xl font-bold mb-3 text-pink-400">Feeling</h2>
                            <p class="text-gray-100 text-base md:text-lg leading-relaxed">
                                Seorang feeling sering kali mengambil keputusan berdasarkan emosi, empati, dan keyakinan
                                pribadi. saat mengambil keputusan, feeling akan sangat mempertimbangkan bagaimana
                                perasaan orang lain terhadap hasil dari keputusan tersebut. Sehingga, tipe feeling akan
                                sangat mendengarkan dan berusaha memahami bagaimana perasaan orang lain.
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <img src="img/hero/feeling1.gif" alt="Feeling Mascot"
                                class="w-52 h-auto filter drop-shadow-[0_0_15px_rgba(236,72,153,0.6)] animate-pulse">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="jadwal" class="bg-neutral-950 text-gray-100 min-h-screen">
        <main class="container mx-auto px-6 py-20 pt-32">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-16">
                Jadwal Acara
            </h1>

            <?php
            // Mengelompokkan semua event berdasarkan tanggal dari $events_data
            $grouped_by_date = [];
            foreach ($events_data as $event) {
                $tanggal = $event['jadwal_event']; // Menggunakan 'jadwal_event' sebagai kunci
                $grouped_by_date[$tanggal][] = $event;
            }

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
                        <span
                            class="text-6xl md:text-7xl font-bold"><?php echo date('d', strtotime($tanggal)); ?></span>
                        <div class="flex flex-col">
                            <span class="text-2xl md:text-3xl"><?php echo date('F', strtotime($tanggal)); ?></span>
                            <span
                                class="text-2xl md:text-3xl text-neutral-400"><?php echo date('Y', strtotime($tanggal)); ?></span>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-12">
                        <?php
                                // Ambil hanya 3 event pertama untuk tanggal ini
                                $limited_events = array_slice($events, 0, 3);
                                // Lakukan perulangan pada array event yang sudah dibatasi
                                foreach ($limited_events as $event):
                                    ?>
                        <div>
                            <h3 class="text-lg font-bold"><?php echo htmlspecialchars($event['judul_event']); ?></h3>
                            <?php if (!empty($event['speakers'])): ?>
                            <p class="text-base text-neutral-400 mt-1">
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
                                <span class="text-neutral-400 text-base">Waktu:
                                    <?php echo htmlspecialchars($event['waktu_event']); ?></span>
                                <span class="font-semibold text-base">Kuota:
                                    <?php echo htmlspecialchars($event['kuota']); ?></span>
                            </div>
                            <div class="flex space-x-4 mt-6">
                                <?php
                                            $slug_event = htmlspecialchars($event['slug'] ?? 'default-slug');

                                            // --- LOGIKA PERBAIKAN DI SINI ---
                                            $user_has_ticket = false;
                                            $is_verified_ticket = false;

                                            // Cek apakah user sudah mendaftar dan dapatkan status verifikasinya
                                            if (isset($events_with_tickets[$event['id_event']])) {
                                                $user_has_ticket = true;
                                                $is_verified_ticket = ($events_with_tickets[$event['id_event']] == 1);
                                            }
                                            ?>
                                <a href="detailevent.php?slug=<?php echo $slug_event; ?>"
                                    class="border border-neutral-600 rounded-xl px-5 py-2 text-base hover:bg-white hover:text-black transition-colors duration-300">Detail
                                    Kegiatan</a>

                                <?php if ($user_has_ticket): ?>
                                <?php if ($is_verified_ticket): ?>
                                <span
                                    class="border border-emerald-800 bg-emerald-950 text-emerald-400 rounded-xl px-5 py-2 text-base">Kamu
                                    Memiliki Tiket</span>
                                <?php else: ?>
                                <span
                                    class="border border-yellow-800 bg-yellow-950 text-yellow-400 rounded-xl px-5 py-2 text-base">Menunggu
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
                                    class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-base cursor-not-allowed"
                                    disabled>Kuota Penuh</button>
                                <?php endif; ?>
                                <?php elseif ($event['event_status'] == 1): ?>
                                <button
                                    class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-base cursor-not-allowed"
                                    disabled>Telah Berakhir</button>
                                <?php elseif ($event['event_status'] == 2): ?>
                                <button
                                    class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-base cursor-not-allowed"
                                    disabled>Kuota Penuh</button>
                                <?php elseif ($event['event_status'] == 4): ?>
                                <button
                                    class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-base cursor-not-allowed"
                                    disabled>Segera Hadir</button>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-20">
                <a href="event.php"
                    class="inline-block rounded-2xl font-semibold text-center tracking-wide transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black whitespace-nowrap px-9 py-3 md:px-16 text-base md:text-lg bg-emerald-400 text-emerald-950 hover:bg-emerald-500 focus:ring-emerald-400">
                    Lihat Semua
                </a>
            </div>

            <?php else: ?>
            <p class="text-center text-neutral-400 text-xl">Saat ini belum ada jadwal acara yang tersedia.</p>
            <?php endif; ?>

        </main>
    </section>

    <section id="lomba">
        <div class="max-w-7xl mx-auto px-6 py-16 space-y-8">
            <div class="text-center space-y-2 mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 text-gray-100">Lomba</h2>
                <p class="max-w-3xl mx-auto text-base md:text-lg text-gray-200">
                    panggung kreatif bagi kamu yang ingin menguji dan memamerkan kemampuan desain visual. Tahun ini kami
                    membuka
                    dua kategori: Poster Ilustrasi, di mana kamu dapat mengekspresikan ide atau pesan sosial melalui
                    karya
                    ilustratif, serta Character Design, untuk merancang karakter orisinal yang kuat dan berkarakter.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-[60px] justify-items-center">
                <div class="flex flex-col items-center gap-6">
                    <div
                        class=" relative w-[300px] h-[400px] /* default mobile */ sm:w-[450px] sm:h-[600px] /* tablet ke atas */ md:w-[600px] md:h-[800px] /* desktop ke atas */ max-w-full rounded-2xl overflow-hidden shadow-lg">
                        <img src="img/BANNER LOMBA POSTER 1 (OPSI 2).png" alt="Poster Ilustrasi"
                            class="absolute inset-0 w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-tr from-pink-600/60 to-transparent"></div>
                        <h3 class="relative z-10 mt-6 ml-6 text-white text-2xl font-medium">
                            Poster Ilustrasi
                        </h3>
                    </div>
                    <a href="pengumuman_lomba.php"
                        class="inline-block rounded-2xl font-semibold text-center tracking-wide transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black whitespace-nowrap px-9 py-3 md:px-16 text-base md:text-lg bg-emerald-400 text-emerald-950 hover:bg-emerald-500 focus:ring-emerald-400">
                        Lihat Pemenang
                    </a>
                </div>

                <div class="flex flex-col items-center gap-6">
                    <div
                        class="relative w-[300px] h-[400px] sm:w-[450px] sm:h-[600px] md:w-[600px] md:h-[800px] max-w-full rounded-2xl overflow-hidden shadow-lg">
                        <img src="img/BANNER CHARACTER 2.png" alt="Character Design"
                            class="absolute inset-0 w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-bl from-cyan-500/60 to-transparent"></div>
                        <h3 class="relative z-10 mt-6 ml-6 text-white text-2xl font-medium">
                            Character Design
                        </h3>
                    </div>
                    <a href="pengumuman_lomba.php"
                        class="inline-block rounded-2xl font-semibold text-center tracking-wide transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black whitespace-nowrap px-9 py-3 md:px-16 text-base md:text-lg bg-emerald-400 text-emerald-950 hover:bg-emerald-500 focus:ring-emerald-400">
                        Lihat Pemenang
                    </a>
                </div>
            </div>
        </div>
    </section>


    <!-- Guest Star -->
    <section id="gueststar" class="container flex flex-col max-w-full items-center bg-[#0D0D0D] gap-6 py-14">
        <div class="flex flex-col items-center gap-2 px-4">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 text-gray-100 text-center">Profil Kolaborator
            </h1>
            <p class="text-gray-100 text-center mb-12 max-w-3xl mx-auto">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nisi arcu, lobortis quis ligula vel,
                accumsan congue diam. Nullam porta enim ut tristique fermentum. Sed vestibulum sit amet arcu eu sodales.
                Duis sed facilisis quam, id rhoncus nisi.
            </p>
        </div>

        <div id="guest-grid"
            class="grid container grid-cols-2 md:grid-cols-2 lg:grid-cols-3 justify-items-center gap-2">

            <?php
            $koneksi = mysqli_connect($host, $username, $password, $database);

            if (mysqli_connect_errno()) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

            $query = "SELECT id_speaker, nama_speaker, instansi, foto_speaker, urutan FROM speakers ORDER BY urutan ASC";
            $result = mysqli_query($koneksi, $query);

            if ($result) {
                $total_speakers = mysqli_num_rows($result);
                $counter = 0;

                if ($total_speakers > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id_speaker = intval($row['id_speaker']);
                        $nama = htmlspecialchars($row['nama_speaker']);
                        $instansi = htmlspecialchars($row['instansi']);
                        $foto = htmlspecialchars($row['foto_speaker']);

                        $fotoPath = !empty($foto) ? 'img/speakers/' . $foto : 'img/narsum/segerahadir.png';

                        // Tambahkan class 'hidden' jika item ke-7 atau lebih
                        $hidden_class = ($counter >= 6) ? 'hidden' : '';

                        // Menambahkan class 'guest-item' untuk target JavaScript
                        echo '<div class="guest-item ' . $hidden_class . ' w-[340px] flex flex-col items-center scale-[54%] -m-12 md:scale-100 md:m-0 transition-all duration-300">';
                        echo '<a href="detailspeakers.php?id_speaker=' . $id_speaker . '">';
                        echo '<img class="w-[280px]" src="' . $fotoPath . '" alt="' . $nama . '" />';
                        echo '<h1 class="font-work text-2xl text-white font-semibold">' . $nama . '</h1>';
                        echo '</a>';
                        echo '<h2 class="font-work font-light text-lg text-white">' . $instansi . '</h2>';
                        echo '</div>';

                        $counter++;
                    }
                } else {
                    echo '<p class="font-work text-white text-lg col-span-full text-center">Belum ada data speaker tersedia.</p>';
                }
            }

            mysqli_close($koneksi);
            ?>

        </div>

        <?php if (isset($total_speakers) && $total_speakers > 6): ?>
        <div id="show-more-container" class="text-center mt-10">
            <button id="show-more-btn"
                class="inline-block rounded-2xl font-semibold text-center tracking-wide transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black whitespace-nowrap px-9 py-3 md:px-16 text-base md:text-lg bg-emerald-400 text-emerald-950 hover:bg-emerald-500 focus:ring-emerald-400">
                Tampilkan Semua
            </button>
        </div>
        <?php endif; ?>

    </section>

    <script>
    const showMoreBtn = document.getElementById('show-more-btn');
    const showMoreContainer = document.getElementById('show-more-container');
    const guestGrid = document.getElementById('guest-grid');

    if (showMoreBtn && guestGrid) {
        showMoreBtn.addEventListener('click', () => {
            // Cari semua item yang masih tersembunyi
            const hiddenItems = guestGrid.querySelectorAll('.guest-item.hidden');

            hiddenItems.forEach(item => {
                // Hapus class 'hidden' untuk menampilkannya
                item.classList.remove('hidden');
            });

            // Sembunyikan tombol setelah diklik
            if (showMoreContainer) {
                showMoreContainer.style.display = 'none';
                s
            }
        });
    }
    </script>

    <!-- FAQ -->
    <section class="bg-black text-gray-100 py-16 sm:py-20">
        <div class="container mx-auto px-4 max-w-7xl lg:max-w-screen-xl 2xl:max-w-screen-2xl">

            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-12 text-gray-100 text-center font-work">
                FAQ (Frequently Asked Questions)
            </h2>

            <div id="faq-container" class="mx-auto space-y-3">

                <?php
                // Koneksi ke database
                // Pastikan variabel koneksi ($host, $username, dll.) sudah didefinisikan sebelum blok ini.
                $koneksi = mysqli_connect($host, $username, $password, $database);

                // Periksa koneksi
                if (mysqli_connect_errno()) {
                    die("Koneksi database gagal: " . mysqli_connect_error());
                }

                // Query untuk mengambil data QnA
                $query = "SELECT topik, jawaban FROM qna WHERE status = 'active' ORDER BY created_at DESC";
                $result = mysqli_query($koneksi, $query);

                // Periksa apakah ada data
                if (mysqli_num_rows($result) > 0) {
                    // Loop melalui hasil query dan tampilkan data
                    while ($row = mysqli_fetch_assoc($result)) {
                        $topik = htmlspecialchars($row['topik']);
                        $jawaban = htmlspecialchars($row['jawaban']);

                        // Mencetak HTML dengan style yang diinginkan
                        echo '<details class="group rounded-lg bg-transparent open:bg-[#1c1c1c] transition-colors duration-300">';
                        echo '<summary class="flex cursor-pointer list-none items-center justify-between p-4 sm:p-5 font-medium">';

                        // Menampilkan Topik/Pertanyaan
                        echo '<span class="font-work text-base md:text-lg">' . $topik . '</span>';

                        // Ikon Chevron dengan 2 state (buka/tutup)
                        echo '<span class="relative h-5 w-5 shrink-0">';
                        echo '<img src="img/icon/chevron-down.svg" alt="Buka" class="group-open:hidden h-full w-full">';
                        echo '<img src="img/icon/chevron-up.svg" alt="Tutup" class="hidden group-open:block h-full w-full">';
                        echo '</span>';

                        echo '</summary>';

                        // Menampilkan Jawaban
                        echo '<div class="px-4 sm:px-5 pb-5 text-gray-400">';
                        echo '<p class="font-work font-light">' . nl2br($jawaban) . '</p>';
                        echo '</div>';

                        echo '</details>';
                    }
                } else {
                    // Jika tidak ada data
                    echo '<p class="font-work text-gray-400 text-center">Belum ada FAQ tersedia.</p>';
                }

                // Tutup koneksi
                mysqli_close($koneksi);
                ?>

            </div>
        </div>
    </section>

    <script>
    const faqContainer = document.getElementById('faq-container');
    if (faqContainer) {
        const detailsElements = faqContainer.querySelectorAll('details');

        detailsElements.forEach(details => {
            details.addEventListener('toggle', (event) => {
                // Jika item ini dibuka, tutup semua yang lain
                if (details.open) {
                    detailsElements.forEach(otherDetails => {
                        if (otherDetails !== details) {
                            otherDetails.removeAttribute('open');
                        }
                    });
                }
            });
        });
    }
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

    <!-- Map -->
    <section class="py-16 px-6">
        <div class="max-w-5xl mx-auto text-center space-y-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 text-gray-100">Lokasi Finder 7</h2>

            <div class="w-full max-w-full md:max-w-4xl mx-auto bg-gray-300 rounded-2xl overflow-hidden">
                <iframe class="w-full h-64 sm:h-96"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.2348973267035!2d107.59106691057444!3d-6.862428093107444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6b943c2c5ff%3A0xee36226510a79e76!2sUniversitas%20Pendidikan%20Indonesia!5e0!3m2!1sid!2sid!4v1710655960109!5m2!1sid!2sid"
                    style="border: 0" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

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