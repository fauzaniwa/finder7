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
    <title>Finder - Lomba Ilustrasi</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <!-- Script Navbar Menu -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Script Cursor -->
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <!-- Script Cursor -->
    <link rel="stylesheet" href="style.css" />
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
    <img src="./img/hero/1.png" alt="Ilustrasi karakter dekoratif pojok kiri atas"
      class="absolute top-0 left-0 w-[clamp(20rem,25vw,32rem)] translate-x-[-35%] translate-y-[30%] sm:translate-x-[-25%] sm:translate-y-[16.66%] animate-pulse" />

    <!-- Top Right -->
    <img src="./img/hero/3.png" alt="Ilustrasi karakter dekoratif pojok kanan atas"
      class="absolute top-0 right-0 w-[clamp(20rem,25vw,32rem)] translate-x-[35%] translate-y-[30%] sm:translate-x-[35%] sm:translate-y-[10%] animate-pulse" />

    <!-- Bottom Left -->
    <img src="./img/hero/2.png" alt="Ilustrasi karakter dekoratif pojok kiri bawah"
      class="absolute bottom-0 left-0 w-[clamp(20rem,25vw,40rem)] translate-x-[-35%] sm:translate-x-[-30%] sm:translate-y-[20%] animate-pulse" />

    <!-- Bottom Right -->
    <img src="./img/hero/4.png" alt="Ilustrasi karakter dekoratif pojok kanan bawah"
      class="absolute bottom-0 right-0 w-[clamp(20rem,30vw,40rem)] translate-x-[35%] sm:translate-x-[25%] sm:translate-y-[25%] animate-pulse" />

        <div class="justify-center flex flex-col items-center p-10 max-w-xl mx-auto py-72 ">


            <!-- <div class="countdown flex justify-center gap-2 items-center mb-4">
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
            </div> -->

            
            <br><br>
            <h3 class="text-white text-base md:text-2xl italic mb-5 text-center"> Terimakasih atas partisipasi anda</h3>
            <h1 class="text-white text-3xl md:text-5xl font-semibold text-center"> D-Day Pengumuman Lomba!</h1>
            <br>
            <a href="pengumuman_lomba.php">
                <button
                    class="submit-btn  bg-emerald-600 hover:bg-emerald-800 transition-all duration-300 ease-in-out px-10 py-3 md:px-20 md:py-5 rounded-2xl md:rounded-3xl mt-6 text-base md:text-xl text-white  ">Lihat Pemenang</button>
            
        </div>
    </section>

    <!-- Deskripsi Section -->
    <section id="deskripsi" class="deskripsi relative py-[32rem] mx-auto overflow-hidden w-full">
        Kanan Atas
        <img src="./img/Submission/Supergraphic2.png" alt=""
            class="hidden sm:block absolute top-0 right-0 translate-x-1/2 translate-y-1/4 w-1/3 z-10">

        Kiri Bawah
        <img src="./img/Submission/Supergraphic1.png" alt=""
            class="hidden sm:block absolute bottom-0 left-0 -translate-x-1/2 -translate-y-1/4 w-1/3 z-10">
        <div class="flex flex-col justify-center items-center text-center text-white  mx-auto max-w-5xl px-12">
            <h1 class="text-3xl md:text-5xl font-bold w-2/3 md:w-auto ">Sekilas Tentang FINDER 7</h1>
            <br>
            <p class="max-w-2xl mx-auto italic text-sm md:text-md">“Di tengah kemajuan kecerdasan buatan, satu hal yang
                tersisa dari
                manusia adalah cinta dan perasaan dalam pikirannya.” </p>
            <br>
            <br>
            <p class="text-sm md:text-md text-justify">Karena untuk memahami manusia, kita harus kembali pada cara pikir
                manusia itu sendiri. Tanpa
                disadari, keputusan dan pilihan individu membentuk hampir seluruh aspek kehidupan kita. Perkembangan AI
                membuat manusia semakin bergantung pada ciptaannya sendiri. Namun, adaptasi bukan soal bersaing,
                melainkan memanfaatkan keunikan manusia: kesadaran, emosi, kreativitas, dan pencarian makna. Pameran ini
                menjadi ruang bagi audiens untuk memahami cara pikir mereka, sekaligus belajar berkontribusi demi
                kehidupan yang lebih harmonis. Mindspace: Fungsi Psikologis pada Otak Manusia adalah pameran Desain
                Komunikasi Visual UPI yang mewadahi ekspresi untuk berkaca dan mencipta. Kami mengajak seluruh
                masyarakat umum Indonesia untuk turut berkontribusi dan hal tersebut melalui perlombaan Desain Karakter
                dan Poster Infografis. Kami menantang para peserta untuk dapat membuat visual dari sesuatu yang
                seringkali dirasakan dalam diri manusia.</p>
        </div>
    </section>
    <br><br><br><br><br><br><br><br>
    <!-- Timeline Section -->
    <div class="flex justify-center items-center">
        <!-- <div
        class=" absolute flex pointer-events-none w-full h-20 bg-gradient-to-r from-transparent via-emerald-400 to-transparent -z-10">
    </div> -->
        <section class="timeline flex flex-col justify-center w-full mx-auto ">
            <h1 class="text-3xl md:text-6xl font-bold text-white text-center mb-16 md:mb-32">Timeline</h1>
            <img src="./img/Submission/Timeline.png" alt="" class="hidden sm:block w-10/12 justify-center mx-auto">
            <img src="./img/Submission/Timeline2.png" alt="" class="block sm:hidden w-full">
        </section>
    </div>
    <br><br><br><br><br><br><br><br>

    <!-- Prize Section -->
    <section class="prize flex flex-col justify-center">
        <h1 class="text-3xl md:text-6xl font-bold text-white text-center mb-16 md:mb-32">Prizes</h1>
        <div class="flex-col flex md:flex-row bg-neutral-800 w-full text-white">
            <div class="flex flex-col w-full md:w-1/4 p-10">
                <div class="flex flex-col">
                    <h1 class=" text-center font-bold text-2xl md:text-3xl pb-10">Juara 1</h1>
                    <img src="./img/Submission/juara1.png" alt="" class="w-72 h-auto mx-auto">
                </div>
                <div class="flex flex-col">
                    <h1 class="text-center italic font-semibold text-2xl pb-4"> Rp. 1.750.000,-</h1>
                    <p class="text-center text-lg "> <b>Karya akan dipamerkan pada pameran</b> FInder 7 serta
                        mendapatkan <b>e-sertifikat</b> resmi dari DKV UPI.</p>
                </div>
            </div>
            <div class="flex flex-row md:flex-col w-full md:w-1/4 p-10 items-center gap-4 md:gap-0">
                <div class="flex flex-col w-1/2 md:w-full">
                    <h1 class="hidden md:block text-center font-bold text-3xl pb-10">Juara 2</h1>
                    <img src="./img/Submission/juara2.png" alt="" class="w-72 h-auto mx-auto">
                </div>
                <div class="flex flex-col w-1/2 md:w-full ">
                    <h1 class="block md:hidden text-left font-bold text-2xl pb-3">Juara 2</h1>
                    <h1 class="text-left md:text-center italic font-semibold text-xl md:text-2xl pb-4"> Rp. 1.250.000,-
                    </h1>
                    <p class="text-left md:text-center text-base md:text-lg "> <b>Karya akan dipamerkan pada pameran</b>
                        FInder 7 serta mendapatkan <b>e-sertifikat</b> resmi dari DKV UPI.</p>
                </div>
            </div>

            <div class="flex flex-row md:flex-col w-full md:w-1/4 p-10 items-center gap-4 md:gap-0">
                <div class="flex flex-col w-1/2 md:w-full">
                    <h1 class="hidden md:block text-center font-bold text-3xl pb-10">Juara 3</h1>
                    <img src="./img/Submission/juara3.png" alt="" class="w-72 h-auto mx-auto">
                </div>
                <div class="flex flex-col w-1/2 md:w-full ">
                    <h1 class="block md:hidden text-left font-bold text-2xl pb-3">Juara 3</h1>
                    <h1 class="text-left md:text-center italic font-semibold text-xl md:text-2xl pb-4"> Rp. 750.000,-
                    </h1>
                    <p class="text-left md:text-center text-base md:text-lg "> <b>Karya akan dipamerkan pada pameran</b>
                        FInder 7 serta mendapatkan <b>e-sertifikat</b> resmi dari DKV UPI.</p>
                </div>
            </div>

            <div class="flex flex-row md:flex-col w-full md:w-1/4 p-10 items-center gap-4 md:gap-0">
                <div class="flex flex-col w-1/2 md:w-full">
                    <h1 class="hidden md:block text-center font-bold text-3xl pb-10">Juara Favorit</h1>
                    <img src="./img/Submission/harapan1.png" alt="" class="w-72 h-auto mx-auto">
                </div>
                <div class="flex flex-col w-1/2 md:w-full ">
                    <h1 class="block md:hidden text-left font-bold text-2xl pb-3">Juara Favorit</h1>
                    <h1 class="hidden text-left md:text-center italic font-semibold text-xl md:text-2xl pb-4"> Rp.
                        1.250.000,-</h1>
                    <p class="text-left md:text-center text-base md:text-lg "> <b>Karya akan dipamerkan pada pameran</b>
                        FInder 7 serta mendapatkan <b>e-sertifikat</b> resmi dari DKV UPI.</p>
                </div>
            </div>

        </div>
    </section>
    <br><br><br><br><br><br><br><br>

    <!-- Ketentuan Umum Section -->
    <h1 class="text-center text-white text-3xl md:text-5xl font-bold"> Ketentuan Umum</h1>
    <br><br>
    <!-- Header Navigasi -->
    <div
        class="flex flex-col md:flex-row mx-auto text-center justify-center gap-5 text-base md:text-lg font-semibold text-neutral-600 pb-10">
        <a href="#syarat" class="nav-tab text-emerald-400 underline">Syarat dan Ketentuan</a>
        <a href="#teknis" class="nav-tab">Teknis Pelaksanaan</a>
    </div>
    <div class="relative">
        <div
            class="pointer-events-none absolute right-0 top-0 h-full w-1/4 bg-gradient-to-l from-neutral-950 to-transparent z-10">
        </div>
        <section
            class="umum flex overflow-x-auto snap-x snap-mandatory gap-5 md:gap-20 px-7 md:px-24 scrollbar-hide scroll-smooth ">
            <div id="syarat"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Syarat dan Ketentuan</h1>


                <ol
                    class="list-decimal list-outside pl-4 space-y-1 md:space-y-4 text-sm md:text-xl text-left md:text-left">
                    <li>Lomba bersifat individu. Karya hasil kolaborasi atau kelompok dianggap tidak memenuhi syarat.
                    </li>
                    <li>Peserta hanya boleh mengikuti satu mata lomba.</li>
                    <li>Terbuka untuk umum dengan rentang usia 16-25 tahun.</li>
                    <li>Setiap peserta hanya boleh mengirimkan 1 (satu) karya.</li>
                    <li>Karya tidak diperkenankan menggunakan AI (Artificial Intelligence).</li>
                    <li>Karya yang dikirimkan harus orisinil, tidak terikat oleh hak cipta apapun, belum pernah
                        dipublikasikan di media apapun, dilombakan, atau menjadi pemenang di perlombaan lain.</li>
                    <li>Peserta wajib mengikuti tema yang telah ditentukan.</li>
                    <li>Keputusan juri bersifat mutlak dan tidak dapat diganggu gugat. Bila didapati kecurangan, pihak
                        panitia dan juri berhak untuk mengubah keputusan.</li>
                    <li>Kompetisi ini tidak dipungut biaya apapun.</li>
                </ol>
                <br><br><br>
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Hak Cipta</h1>

                <ol
                    class="list-decimal list-outside pl-4 space-y-1 md:space-y-4 text-sm md:text-xl text-left md:text-leftl">
                    <li>Hak cipta karya tetap milik peserta.</li>
                    <li>Namun, panitia berhak menggunakan karya untuk keperluan publikasi acara dengan tetap
                        mencantumkan kredit pada pembuat.</li>
                </ol>
            </div>

            <div id="teknis"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Teknis Pelaksanaan</h1>

                <ol
                    class="list-decimal list-outside pl-4 space-y-1 md:space-y-4 text-sm md:text-xl text-left md:text-left">
                    <li>Peserta wajib mengunduh, mengisi, menandatangani dan mengunggah kembali Surat Pernyataan
                        Orisinalitas Karya.
                    </li>
                    <li>Surat Pernyataan Orisinalitas Karya dapat diunduh <a
                            href="https://drive.google.com/drive/folders/11tiUX9hAg-7iLA88x7rJ5iyBlavmRGJV?usp=sharing"
                            target="_blank" class="underline text-emerald-400">di sini.</a></li>
                    <li>Peserta wajib mengisi formulir pengiriman karya yang tertera di sini, mulai tanggal 29 Mei - 25
                        Juni 2025 pukul 23.59 WIB.</li>
                    <li>Karya diunggah dengan format penamaan [Mata Lomba] - [Nama peserta].</li>
                    <li>Peserta wajib mengirim bukti proses pembuatan karya dalam format .MP4 dengan durasi 30 detik
                        sampai 1 menit.</li>
                    <li>Peserta wajib menyertakan tautan (link) di Google Drive berisi karya, surat pernyataan, dan
                        video proses pembuatan dalam satu folder dengan format penamaan [Mata Lomba] - [Nama peserta].
                    </li>
                    <li>Peserta wajib mengunggah karya di Instagram Feeds (akun tidak dikunci) dengan hastag
                        #Finder7LombaDesainKarakter atau #Finder7LombaPosterIlustrasi #Finder7Mindspace serta tag akun
                        @finder_dkv.</li>
                    <li>Pemenang akan diumumkan pada tanggal 10 Juli 2025 melalui akun Instagram resmi FINDER 7
                        (@finder_dkv) dan dihubungi langsung melalui nomor telepon atau e-mail.</li>
                    <li>3 (tiga) karya terbaik dan 1 (satu) karya favorit akan mendapatkan hadiah berupa;
                        <ol class="list-[lower-alpha] list-outside pl-6 space-y-4 pt-4">
                            <li>Juara 1 : Rp1.750.000 + e-sertifikat + karya dipamerkan</li>
                            <li>Juara 2 : Rp1.250.000 + e-sertifikat + karya dipamerkan</li>
                            <li>Juara 3 : Rp750.000 + e-sertifikat + karya dipamerkan</li>
                            <li>Juara Favorit : e-sertifikat + karya dipamerkan</li>
                        </ol>
                    </li>
                </ol>
                <br><br><br>
            </div>
        </section>
    </div>
    <br><br><br><br><br><br><br>

    <!-- Ketentuan Khusus Section -->
    <h1 class="text-center text-white text-5xl font-bold"> Ketentuan Khusus</h1>
    <br><br>

    <!-- Header Navigasi -->

    <div
        class="flex flex-col md:flex-row mx-auto text-center justify-center gap-5 text-base md:text-lg font-semibold text-neutral-600 pb-10">
        <a href="#desainkarakter" class="nav2-tab text-emerald-400 underline">Character Design</a>
        <a href="#poster" class="nav2-tab">Poster Illustration</a>
    </div>
    <div class="relative">
        <div
            class="pointer-events-none absolute right-0 top-0 h-full w-1/4 bg-gradient-to-l from-neutral-950 to-transparent z-10">
        </div>
        <section
            class="khusus flex overflow-x-auto snap-x snap-mandatory gap-5 md:gap-20 px-7 md:px-24 scrollbar-hide scroll-smooth ">
            <div id="desainkarakter"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Tema</h1>
                <h2 class="text-base md:text-xl text-left md:text-left font-semibold pb-3 italic">EMOTIONAL CHARACTER
                </h2>
                <p class="text-sm md:text-xl text-left md:text-left"> Peserta ditantang untuk menciptakan sosok
                    karakter orisinil yang merepresentasikan suatu emosi atau rasa yang ada dalam diri manusia.</p>
                <br><br>
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Ketentuan</h1>
                <ol
                    class="list-decimal list-outside pl-4 space-y-1 md:space-y-4 text-sm md:text-xl text-left md:text-left">
                    <li>Karya berupa desain karakter orisinal.
                    </li>
                    <li>Media desain digital.</li>
                    <li>Karya disajikan dalam bentuk character sheet yang memuat karakter tampak depan, tampak samping,
                        tampak belakang, dan variasi ekspresi sebanyak 3 jenis.</li>
                    <li>Template character sheet berukuran A3 landscape dapat diunduh <a
                            href="https://drive.google.com/drive/folders/11tiUX9hAg-7iLA88x7rJ5iyBlavmRGJV?usp=sharing"
                            target="_blank" class="underline text-emerald-400">di sini.</a></li>
                    <li>Wajib menyertakan nama karakter dan deskripsi singkat (maksimal 150 kata).</li>
                    <li>Karya dapat dibuat menggunakan software gambar seperti Clip Studio Paint, Adobe Illustrator,
                        Medibang, Ibis Paint, dan sejenisnya.</li>
                    <li>Format file .JPEG atau .PNG, resolusi minimal 300 DPI, dan format pewarnaan RGB.</li>
                    <li>Pemenang karya terpilih akan dihubungi panitia untuk mengirimkan karya dalam format file .PDF
                        dengan format pewarnaan CMYK untuk keperluan cetak.</li>
                    <li>Karya tidak mengandung unsur SARA, kekerasan, pornografi, ujaran kebencian, atau konten sensitif
                        lainnya.</li>
                </ol>
                <br><br>
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Kriteria Penilaian</h1>

                <ol
                    class="list-decimal list-outside pl-4 space-y-1 md:space-y-4 text-sm md:text-xl text-left md:text-leftl">
                    <li>Orisinalitas dan kreativitas (30%)</li>
                    <li>Kesesuaian dengan tema (25%)</li>
                    <li>Kekuatan naratif atau latar belakang karakter (20%)</li>
                    <li>Teknik dan kualitas visual (25%)</li>
                </ol>

                <div class="flex gap-4 justify-center items-center mx-auto pt-8">

                    <a href="https://chat.whatsapp.com/EwcdEbO5GQIKENgzD6HK9C" target="_blank">
                        <button
                        class="submit-btn bg-emerald-600 hover:bg-emerald-800 transition-all duration-300 ease-in-out px-10 py-3 md:px-20 md:py-5 rounded-2xl md:rounded-3xl mt-6 text-base md:text-xl text-white">Join
                        Grup Whatsapp</button>
                    </a>
                    <a href="https://drive.google.com/drive/folders/11tiUX9hAg-7iLA88x7rJ5iyBlavmRGJV?usp=sharing"
                            target="_blank">
                        <button
                        class="submit-btn flex justify-center mx-auto bg-neutral-600 hover:bg-neutral-700 transition-all duration-300 ease-in-out px-10 py-3 md:px-20 md:py-5 rounded-2xl md:rounded-3xl mt-6 text-base md:text-xl text-white">Dowload Berkas</button>
                    </a>
                    </div>
                </div>

            <div id="poster"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Tema</h1>
                <h2 class="text-base md:text-xl text-left md:text-left font-semibold pb-3 italic">DEEP STATE OF MIND
                </h2>
                <p class="text-sm md:text-xl text-left md:text-left"> Peserta ditantang untuk menciptakan sebuah desain
                    poster ilustrasi yang menggambarkan tentang pikiran terdalam yang ada dalam diri manusia</p>
                <br><br>
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Ketentuan</h1>
                <ol
                    class="list-decimal list-outside pl-4 space-y-1 md:space-y-4 text-sm md:text-xl text-left md:text-left">
                    <li>Karya berupa poster ilustrasi orisinal.
                    </li>
                    <li>Media desain digital.</li>
                    <li>Wajib menyertakan deskripsi singkat (maksimal 150 kata).</li>
                    <li>Karya dibuat dalam ukuran A3 portrait.</li>
                    <li>Karya dapat dibuat menggunakan software gambar seperti Clip Studio Paint, Adobe Illustrator,
                        Medibang, Ibis Paint, dan sejenisnya.</li>
                    <li>Karya tidak boleh menggunakan stock image, aset elemen lain (kecuali brush, pattern, dan effect
                        bawaan software yang digunakan), dan template selain ilustrasi orisinal peserta.</li>
                    <li>Format file .JPEG atau .PNG, resolusi minimal 300 DPI, dan format pewarnaan RGB.</li>
                    <li>Pemenang karya terpilih akan dihubungi panitia untuk mengirimkan karya dalam format file .PDF
                        dengan format pewarnaan CMYK untuk keperluan cetak.</li>
                    <li>Karya tidak mengandung unsur SARA, kekerasan, pornografi, ujaran kebencian, atau konten sensitif
                        lainnya.</li>
                </ol>
                <br><br>
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Kriteria Penilaian</h1>

                <ol
                    class="list-decimal list-outside pl-4 space-y-1 md:space-y-4 text-sm md:text-xl text-left md:text-leftl">
                    <li>Orisinalitas dan kreativitas (30%)</li>
                    <li>Kesesuaian dengan tema (30%)</li>
                    <li>Kekuatan visual dan teknis desain (25%)</li>
                    <li>Makna dan interpretasi karya (25%)</li>
                </ol>

                <div class="flex gap-4 justify-center items-center mx-auto pt-8">
                    <a href="https://chat.whatsapp.com/CwR2w1GobuJFpEM1SdDN8m" target="_blank">
                        <button
                        class="submit-btn flex justify-center mx-auto bg-emerald-600 hover:bg-emerald-800 transition-all duration-300 ease-in-out px-10 py-3 md:px-20 md:py-5 rounded-2xl md:rounded-3xl mt-6 text-base md:text-xl text-white">Join
                        Grup Whatsapp</button>
                    </a>
                    <a href="https://drive.google.com/drive/folders/11tiUX9hAg-7iLA88x7rJ5iyBlavmRGJV?usp=sharing"
                            target="_blank">
                        <button
                        class="submit-btn flex justify-center mx-auto bg-neutral-600 hover:bg-neutral-700 transition-all duration-300 ease-in-out px-10 py-3 md:px-20 md:py-5 rounded-2xl md:rounded-3xl mt-6 text-base md:text-xl text-white">Dowload Berkas</button>
                    </a>
                </div>
            </div>
        </section>
    </div>
    <br><br><br><br><br><br><br>

    <!-- Juri Section -->
    <section class="judges">
        <h1 class="text-5xl text-center text-white font-bold">Judges</h1>
        <div class="flex mt-10 justify-center">
            <div class="text-white py-1 px-10 bg-neutral-800 rounded-full ">Character Design</div>
        </div>


        <div class="mt-12 flex gap-8 justify-center flex-wrap px-4">
            <div class="flex flex-col  max-w-64">

                <div class="w-auto h-auto bg-neutral-900 rounded-3xl overflow-hidden shadow-lg relative">
                    <img src="./img/Submission/Juri1desainkarakter.png" alt=""
                        class="w-full h-80 object-cover cursor-pointer popup-image"
                        data-img="./img/Submission/Juri1desainkarakter.png">
                </div>
                <h2 class="text-lg font-semibold text-white text-center pt-5    ">Romy Hernadi</h2>
                <div class="max-w-full">
                    <p class=" text-center text-sm text-gray-400">Komikus, Illustrator</p>
                </div>
            </div>
            <div class="flex flex-col  max-w-64">

                <div class="w-auto h-auto bg-neutral-900 rounded-3xl overflow-hidden shadow-lg relative">
                    <img src="./img/Submission/Juri2desainkarakter.png" alt=""
                        class="w-full h-80 object-cover cursor-pointer popup-image"
                        data-img="./img/Submission/Juri2desainkarakter.png">
                </div>
                <h2 class="text-lg font-semibold text-white text-center pt-5    ">Diah Mayang Sari, M.Ds.</h2>
                <div class="max-w-full">
                    <p class=" text-center text-sm text-gray-400">Dosen DKV UPI</p>
                </div>
            </div>
            <div class="flex flex-col  max-w-64">

                <div class="w-auto h-auto bg-neutral-900 rounded-3xl overflow-hidden shadow-lg relative">
                    <img src="./img/Submission/Juri3desainkarakter.png" alt=""
                        class="w-full h-80 object-cover cursor-pointer popup-image"
                        data-img="./img/Submission/Juri3desainkarakter.png">
                </div>
                <h2 class="text-lg font-semibold text-white text-center pt-5    ">Dewi Iriani, M.Ds.</h2>
                <div class="max-w-full">
                    <p class=" text-center text-sm text-gray-400">Dosen DKV UPI</p>
                </div>
            </div>
        </div>
        <br><br><br><br><br><br><br>
        <div class="flex mt-10 justify-center">
            <div class="text-white py-1 px-10 bg-neutral-800 rounded-full ">Poster Illustration</div>
        </div>
        <div class="mt-12 flex gap-8 justify-center flex-wrap px-4">
            <div class="flex flex-col max-w-64">

                <div class="w-auto h-auto bg-neutral-900 rounded-3xl overflow-hidden shadow-lg relative">
                    <img src="./img/Submission/Juri1ilustrasi.png" alt=""
                        class="w-full h-80 object-cover cursor-pointer popup-image"
                        data-img="./img/Submission/Juri1ilustrasi.png">
                </div>
                <h2 class="text-lg font-semibold text-white text-center pt-5">Aji Juasal Mahendra</h2>
                <div class="max-w-full">
                    <p class=" text-center text-sm text-gray-400">Visual Artist, Desainer Grafis</p>
                </div>
            </div>
            <div class="flex flex-col  max-w-64">

                <div class="w-auto h-auto bg-neutral-900 rounded-3xl overflow-hidden shadow-lg relative">
                    <img src="./img/Submission/Juri2ilustrasi.png" alt=""
                        class="w-full h-80 object-cover cursor-pointer popup-image"
                        data-img="./img/Submission/Juri2ilustrasi.png">
                </div>
                <h2 class="text-lg font-semibold text-white text-center pt-5    ">Palupi Argani, S. Ds, M.Ds.</h2>
                <div class="max-w-full">
                    <p class=" text-center text-sm text-gray-400">Dosen DKV UPI</p>
                </div>
            </div>
            <div class="flex flex-col max-w-64">

                <div class="w-auto h-auto bg-neutral-900 rounded-3xl overflow-hidden shadow-lg relative">
                    <img src="./img/Submission/Juri3ilustrasi.png" alt=""
                        class="w-full h-80 object-cover cursor-pointer popup-image"
                        data-img="./img/Submission/Juri3ilustrasi.png">
                </div>
                <h2 class="text-lg font-semibold text-white text-center pt-5    ">Dwita Alfiani, M.Ds.</h2>
                <div class="max-w-full">
                    <p class=" text-center text-sm text-gray-400">Dosen DKV UPI</p>
                </div>
            </div>
        </div>
    </section>

    <br><br><br><br><br><br>
    <div class="flex justify-center">
        <a href="submitkarya.php">
            <button
                class="submit-btn bg-emerald-600 hover:bg-emerald-800 transition-all duration-300 ease-in-out px-10 py-3 md:px-20 md:py-5 rounded-2xl md:rounded-3xl mt-6 text-base md:text-xl text-white">Submit
                karya </button>
        </a>
    </div>
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

    // Target: 25 Juni 2025 pukul 23:59 WIB → 16:59 UTC
    const targetDate = new Date(Date.UTC(2025, 5, 25, 16, 59, 0));
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