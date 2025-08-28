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
        <img src="../img/Submission/supergraphic5.png" alt="Ilustrasi karakter dekoratif pojok kiri atas"
            class="absolute hidden lg:flex top- left- right-2/3 w-[clamp(8rem,9vw,10rem)] translate-x-[-35%] translate-y-[30%] sm:translate-x-[-10%] sm:translate-y-[-16.66%] animate-pulse" />

        <!-- Top Right -->
        <img src="../img/Submission/supergraphic6.png" alt="Ilustrasi karakter dekoratif pojok kanan atas"
            class="absolute hidden lg:flex top-2/4 left- right-1/3 w-[clamp(12rem,16vw,20rem)] translate-x-[35%] translate-y-[30%] sm:translate-x-[75%] sm:translate-y-[-10%] animate-pulse" />

        <!-- Top Left -->
        <img src="../img/Submission/supergraphic3.png" alt="Ilustrasi karakter dekoratif pojok kiri atas"
            class="absolute hidden sm:flex bottom-32 left-0 w-[clamp(10rem,15vw,16rem)] translate-x-[-35%] translate-y-[70%] sm:translate-x-[-25%] sm:translate-y-[79.99%%] animate-pulse" />

        <!-- Top Right -->
        <img src="../img/Submission/supergraphic4.png" alt="Ilustrasi karakter dekoratif pojok kanan atas"
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

    <!-- Deskripsi Section -->
    <section id="deskripsi" class="deskripsi relative py-[32rem] mx-auto overflow-hidden w-full">
        Kanan Atas
        <img src="../img/Submission/Supergraphic2.png" alt=""
            class="hidden sm:block absolute top-0 right-0 translate-x-1/2 translate-y-1/4 w-1/3 z-10">

        Kiri Bawah
        <img src="../img/Submission/Supergraphic1.png" alt=""
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
            <img src="../img/Submission/Timelinewacom.png" alt="" class="hidden sm:block w-10/12 justify-center mx-auto">
            <img src="../img/Submission/Timelinewacom.png" alt="" class="block sm:hidden w-full">
        </section>
    </div>
    <br><br><br><br><br><br><br><br>

    <!-- Prize Section -->
    <section class="prize flex flex-col justify-center">
        <h1 class="text-3xl md:text-6xl font-bold text-white text-center mb-16 md:mb-32">Prizes</h1>
        <div class="flex-col flex md:flex-row w-full h-auto justify-center text-white">
            <div class="flex flex-col w-full md:w-1/4 p-10">
                <div class="flex flex-col">
                    <h1 class=" text-center font-bold text-2xl md:text-3xl pb-10">Juara 1</h1>
                    <img src="../img/Submission/juara1wacom.png" alt="" class="w-72 h-auto mx-auto">
                </div>
                <div class="flex flex-col">
                    <h1 class=" mx-auto text-center italic font-semibold text-2xl pt-2 pb-4 max-w-56"> 1x Unit CTL-672 by
                        Wacom</h1>
                    <p class="mx-auto max-w-56 text-center font-light text-xl "> sertifikat cetak + merchandise Finder 7</p>
                </div>
            </div>

            <div class="hidden sm:flex border-l border-white mx-4"></div>

            <div class="flex flex-row md:flex-col w-full md:w-1/4 p-10 items-center gap-4 md:gap-0">
                <div class="flex flex-col w-1/2 md:w-full">
                    <h1 class="hidden md:block text-center font-bold text-3xl pb-10">Juara 2</h1>
                    <img src="../img/Submission/juara2wacom.png" alt="" class="w-72 h-auto mx-auto">
                     <h1 class="block md:hidden text-center font-bold text-2xl pb-3">Juara 2</h1>
                </div>
                
                <div class="flex flex-col">
                    <h1 class=" mx-auto text-center italic font-semibold text-2xl pt-2 pb-4 max-w-56"> 1x Unit CTL-472 by
                        Wacom</h1>
                    <p class="mx-auto max-w-56 text-center font-light text-xl "> sertifikat cetak + merchandise Finder 7</p>
                </div>
            </div>

            <div class="hidden sm:flex border-l border-white mx-4"></div>

            <div class="flex flex-row md:flex-col w-full md:w-1/4 p-10 items-center gap-4 md:gap-0">
                <div class="flex flex-col w-1/2 md:w-full">
                    <h1 class="hidden md:block text-center font-bold text-3xl pb-10">Juara 3</h1>
                    <img src="../img/Submission/juara3wacom.png" alt="" class="w-72 h-auto mx-auto">
                    <h1 class="block md:hidden text-center font-bold text-2xl pb-3">Juara 3</h1>
                </div>
                <div class="flex flex-col">
                    <h1 class=" mx-auto text-center italic font-semibold text-2xl pt-2 pb-4 max-w-56">Voucher Belanja
                        Rp500.000</h1>
                    <p class="mx-auto max-w-56 text-center font-light text-xl "> sertifikat cetak + merchandise Finder 7</p>
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
        <a href="" class="text-emerald-400 underline">Syarat dan Ketentuan</a>
    </div>
    <div id="ketentuan" class="relative">
        <section class="flex px-7 md:px-24 justify-center">
            <div
                class="w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Syarat dan Ketentuan</h1>


                <ol
                    class="list-decimal list-outside pl-4 space-y-1 md:space-y-4 text-sm md:text-xl text-left md:text-left">
                    <li>Lomba bersifat individu. Karya hasil kolaborasi atau kelompok dianggap tidak memenuhi syarat.
                    </li>
                    <li>Peserta merupakan siswa/i aktif SMA/SMK/MA atau sederajat di Jawa Barat.</li>
                    <li>Setiap peserta hanya boleh mengirimkan 1 (satu) karya.</li>
                    <li>Karya tidak diperkenankan menggunakan AI (Artificial Intelligence).</li>
                    <li>Karya yang dikirimkan harus orisinil, tidak terikat oleh hak cipta apapun, belum pernah
                        dipublikasikan di media apapun, dilombakan, atau menjadi pemenang di perlombaan lain.</li>
                    <li>Peserta wajib mengikuti tema yang telah ditentukan.</li>
                    <li>Keputusan juri bersifat mutlak dan tidak dapat diganggu gugat. Bila didapati kecurangan, pihak
                        panitia dan juri berhak untuk mengubah keputusan.</li>
                    <li>Peserta membayar uang pendaftaran sebesar Rp15.000.</li>
                    <li>Peserta wajib mengikuti akun Instagram @finder_dkv @wacomindonesia @neonexp_</li>
                    <li>Seluruh peserta yang telah mendaftar akan mendapatkan voucher digital potongan harga untuk pembelian di Finder Store.</li>
                    <li>Peserta yang lolos babak penyisihan bersedia untuk melaksanakan perlombaan babak final (20
                        besar) secara luring di Gedung Baru FPSD UPI Bandung.</li>
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
        </section>
    </div>
    <br><br><br><br><br><br><br>


    <!-- Ketentuan Umum Section -->
    <h1 class="text-center text-white text-3xl md:text-5xl font-bold"> Teknis Pelaksanaan</h1>
    <br><br>
    <!-- Header Navigasi -->
    <div
        class="flex flex-col md:flex-row mx-auto text-center justify-center gap-5 text-base md:text-lg font-semibold text-neutral-600 pb-10">
        <a href="#syarat" class="nav-tab text-emerald-400 underline">Babak Penyisihan</a>
        <a href="#teknis" class="nav-tab">Babak Final</a>
    </div>
    <div class="relative">
        <div
            class="pointer-events-none absolute right-0 top-0 h-full w-1/4 bg-gradient-to-l from-neutral-950 to-transparent z-10">
        </div>
        <section
            class="umum flex overflow-x-auto snap-x snap-mandatory gap-5 md:gap-20 px-7 md:px-24 scrollbar-hide scroll-smooth ">
            <div id="syarat"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Babak Penyisihan</h1>
                <h1 class="font-normal text-center text-lg md:text-2xl mb-6 md:mb-10">Dilaksanakan Secara ONLINE</h1>
                <br><br><br><br>

                <ol
                    class="list-decimal list-outside pl-4 space-y-3 md:space-y-6 text-sm md:text-xl text-left md:text-justify">
                    <li>Babak penyisihan dilaksanakan secara online.
                    </li>
                    <li>Peserta mendaftarkan diri dengan mengirimkan karya ilustrasi cover buku sesuai dengan ketentuan
                        khusus yang tertera di bawah.</li>
                    <li>Peserta wajib mengunduh, mengisi, menandatangani dan mengunggah kembali Surat Pernyataan
                        Orisinalitas Karya, Surat Identitas Pembimbing, dan Surat Pernyataan Kesediaan Mengikuti Final
                        Luring.</li>
                    <li>Template surat-surat dapat diunduh <a class="text-emerald-600" href="https://drive.google.com/drive/folders/1vW9CxmWhxidXFfb4ueJEKQKubZHEFMv4?usp=sharing" target="_blank">di sini.</a></li>
                    <li>Peserta wajib mengisi formulir pengiriman karya yang tertera di bawah, mulai tanggal 7 Agustus -
                        24 Agustus 2025 pukul 23.59 WIB.</li>
                    <li>Karya diunggah dengan format penamaan [Judul Karya] - [Nama peserta] - [Asal Sekolah]</li>
                    <li>Peserta wajib menyertakan tautan (link) di Google Drive dengan akses terbuka yang berisi karya,
                        Surat Pernyataan Orisinalitas Karya, Surat Identitas Pembimbing, dan Surat Pernyataan Kesediaan
                        Mengikuti Final Luring dalam satu folder dengan format penamaan [Nama peserta] - [Asal Sekolah].
                    </li>
                    <li>Peserta wajib mengikuti akun Instagram @finder_dkv @wacomindonesia @datascript.creativetablet @neonexp_</li>
                    <li>Peserta wajib mengunggah karya di Instagram Feeds (akun tidak dikunci) dengan hastag #Finder7xWacomxNeonExperience #LombaIlustrasiCoverBuku #Finder7Mindspace serta tag akun @finder_dkv @wacomindonesia @datascript.creativetablet @neonexp_.</li>
                    <li>Hasil seleksi babak penyisihan akan diumumkan pada tanggal 1 September 2025 melalui akun
                        Instagram resmi FINDER 7 (@finder_dkv) dan dihubungi langsung melalui nomor telepon atau e-mail.
                    </li>
                    <li>Seluruh peserta akan mendapatkan e-sertifikat partisipasi.</li>
                </ol>
            </div>

            <div id="teknis"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Teknis Pelaksanaan</h1>
                <h1 class="font-normal text-center text-lg md:text-2xl mb-6 md:mb-10">Dilaksanakan Secara OFFLINE</h1>
                <br><br><br><br>

                <ol
                    class="list-decimal list-outside pl-4 space-y-3 md:space-y-6 text-sm md:text-xl text-left md:text-justify">
                    <li>Babak penyisihan dilaksanakan secara offline.
                    </li>
                    <li>Seluruh finalis wajib hadir di lokasi final, yaitu Ruang Kelas 5 Seni Rupa Lt.6 Gedung Baru FPSD
                        UPI Bandung pada tanggal 17 September 2025 pada pukul 10.00 WIB. Timeline pelaksanaan babak
                        final akan diinformasikan lebih lanjut di forum technical meeting.</li>
                    <li>Seluruh finalis wajib membawa identitas diri berupa KTP atau Kartu Pelajar.</li>
                    <li>Seluruh finalis ditantang untuk membuat ilustrasi cover buku menggunakan Unit CTL-472 by Wacom
                        sebagai device yang akan disediakan oleh penyelenggara.</li>
                    <li>Seluruh finalis diwajibkan untuk membawa perangkat pribadi dan menginstall driver yang dapat
                        menunjang penggunaan Unit CTL-472 by Wacom saat pelaksanaan babak final.</li>
                    <li>Seluruh finalis dibebaskan dalam penggunaan software.</li>
                    <li>Seluruh finalis wajib mengikuti sesi tutorial penggunaan Unit CTL-472 by Wacom dengan durasi 1
                        jam sebelum sesi lomba dimulai.
                    </li>
                    <li>Ketentuan khusus yang memuat identitas buku akan diberikan secara mendadak (on the spot) sebelum
                        sesi lomba dimulai.
                    </li>
                    <li>Durasi pengerjaan babak final adalah 3 jam.</li>
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
        <a href="#desainkarakter" class="nav2-tab text-emerald-400 underline">Babak Penyisihan</a>
        <a href="#poster" class="nav2-tab">Babak Final</a>
    </div>
    <div class="relative">
        <div
            class="pointer-events-none absolute right-0 top-0 h-full w-1/4 bg-gradient-to-l from-neutral-950 to-transparent z-10">
        </div>
        <section
            class="khusus flex overflow-x-auto snap-x snap-mandatory gap-5 md:gap-20 px-7 md:px-24 scrollbar-hide scroll-smooth ">
            <div id="desainkarakter"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Babak Penyisihan</h1>
                <h2 class="font-normal text-center text-lg md:text-2xl mb-6 md:mb-10">Dilaksanakan Secara ONLINE</h2>
                <hr class="w-10/12 mx-auto">
                <br><br><br>
                <div class="grid grid-cols-[150px_auto] md:grid-cols-[200px_auto] lg:grid-cols-[300px_auto] gap-y-12 text-white">
                    <div class="font-bold text-base md:text-xl ">Judul Buku</div>
                    <div class="text-sm text-justify">: Di Bulan Aku Menunggu</div>


                    <div class="font-bold text-base md:text-xl ">Penulis</div>
                    <div class="text-sm text-justify">: Finder 7: Mindspace</div>


                    <div class="font-bold text-base md:text-xl ">Genre/Kategori</div>
                    <div class="text-sm text-justify">: Fiksi, folklore, romantis, drama.</div>


                    <div class="font-bold text-base md:text-xl ">Target Audiens</div>
                    <div class="text-sm text-justify">: Semua umur, pecinta cerita rakyat.</div>


                    <div class="font-bold text-base md:text-xl ">Sinopsis buku</div>
                    <div class="text-sm text-justify">: Mengadaptasi dari cerita rakyat berjudul Nyai Anteh Penunggu Bulan. Dalam Nyai Anteh Penunggu
                        Bulan, diceritakan bahwa suatu malam, saat bulan bersinar terang, Nyai Anteh duduk di beranda
                        rumahnya dan menatap bulan dengan penuh kerinduan kepada sang Kekasih yang merupakan pangeran
                        kerajaan. Kisah cinta mereka kandas lantaran Raja tidak mengizinkan Pangeran untuk menikahi
                        rakyat jelata. Ia berdoa kepada para dewa agar diberikan tempat di mana ia bisa hidup dalam
                        ketenangan dan jauh dari rasa sakit hatinya. Tiba-tiba, sebuah cahaya terang menyelimutinya, dan
                        dalam sekejap, ia terangkat ke langit menuju bulan. Sejak saat itu, Nyai Anteh tinggal di bulan
                        bersama kucing kesayangannya, yang selalu menemaninya dalam kesunyian.</div>


                    <div class="font-bold text-base md:text-xl ">Nuansa yang diharapkan</div>
                    <div class="text-sm text-justify">: Dramatis, lembut, menyentuh, sedikit dark.</div>


                    <div class="font-bold text-base md:text-xl ">Referensi Visual</div>
                    <div class="text-sm text-justify">: https://pin.it/1OzikV3vA</div>


                    <div class="font-bold text-base md:text-xl ">Elemen Wajib</div>
                    <div class="text-sm text-justify">
                        <li>Judul buku</li>
                        <li>Pencatutan nama Finder 7: Mindspace sebagai penulis</li>
                        <li>Logo Finder 7: Mindspace, Wacom Indonesia dan Neon Experience</li>
                        <li>Blurb/sinopsis (untuk back cover)</li>
                    </div>


                    <div class="font-bold text-base md:text-xl ">Warna yang diharapkan</div>
                    <div class="text-sm text-justify">: Kombinasi hangat-dingin, dengan sentuhan romansa yang menyayat hati.</div>


                    <div class="font-bold text-base md:text-xl ">Gaya Tipografi</div>
                    <div class="text-sm text-justify">: Handwritten/cursive/dekoratif.</div>


                    <div class="font-bold text-base md:text-xl ">Format & Ukuran</div>
                    <div class="text-sm text-justify">: 13 x 19 cm, spine tebal 1,5 cm, margin safe area kanan-kiri-atas-bawah 1 cm</div>


                    <div class="font-bold text-base md:text-xl ">Output yang diharapkan</div>
                    <div class="text-sm text-justify">: PDF (CMYK 300dpi)</div>


                </div>

            </div>

            <div id="poster"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Babak Final</h1>
                <h2 class="font-normal text-center text-lg md:text-2xl mb-6 md:mb-10">Dilaksanakan Secara OFFLINE <br> Ketentuan khusus babak final akan diberitahukan di hari pelaksanaan</h2>
                <hr class="w-10/12 mx-auto">
                <br><br><br>
                <!--<div class="grid grid-cols-[150px_auto] md:grid-cols-[200px_auto] lg:grid-cols-[300px_auto] gap-y-12 text-white">-->
                <!--    <div class="font-bold text-base md:text-xl">Judul Buku</div>-->
                <!--    <div>: Pojok Ruang</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Penulis</div>-->
                <!--    <div class="text-sm text-justify">: Finder 7: Mindspace</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Genre/Kategori</div>-->
                <!--    <div class="text-sm text-justify">: Fiksi, drama, psikologi</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Target Audiens</div>-->
                <!--    <div class="text-sm text-justify">: Semua umur, menjangkau orang-orang yang membutuhkan pemahaman tentang alam pikir mereka dan-->
                <!--        memperkenalkan lebih dalam alam pikir dengan wujud-wujud karakter yang ada di Finder 7.</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Sinopsis buku</div>-->
                <!--    <div class="text-sm text-justify">: Mengulik sisi pikiran manusia yang rumit dengan storytelling bak novel. Di buku ini,-->
                <!--        menceritakan satu persatu karakter maskot Finder 7: Mindspace dan bagaimana mereka setiap-->
                <!--        harinya mengambil peran dalam alam pikir manusia. Hingga suatu saat, si Pemilik Ruang mengalami-->
                <!--        konflik batin yang menyebabkan alam pikirnya tercerai-berai, tidak lagi satu kesatuan.</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Nuansa yang diharapkan</div>-->
                <!--    <div class="text-sm text-justify">: Dramatis, lembut, menyentuh, sedikit dark.</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Referensi Visual</div>-->
                <!--    <div class="text-sm text-justify">: https://pin.it/1OzikV3vA</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Elemen Wajib</div>-->
                <!--    <div class="text-sm text-justify">-->
                <!--        <li>Judul buku</li>-->
                <!--        <li>Pencatutan nama Finder 7: Mindspace sebagai penulis</li>-->
                <!--        <li>Logo Finder 7: Mindspace dan Wacom Indonesia</li>-->
                <!--        <li>Blurb/sinopsis (untuk back cover)</li>-->
                <!--        <li>Barcode (untuk back cover, menyusul)</li>-->
                <!--    </div>-->


                <!--    <div class="font-bold text-base md:text-xl">Warna yang diharapkan</div>-->
                <!--    <div class="text-sm text-justify">: Menyesuaikan GSM Finder 7: Mindspace. Download di sini.</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Gaya Tipografi</div>-->
                <!--    <div class="text-sm text-justify">: Handwritten/cursive/dekoratif.</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Format & Ukuran</div>-->
                <!--    <div class="text-sm text-justify">: 13 x 19 cm, spine tebal 1,5 cm, margin safe area kanan-kiri-atas-bawah 1 cm</div>-->


                <!--    <div class="font-bold text-base md:text-xl">Output yang diharapkan</div>-->
                <!--    <div class="text-sm text-justify">: PDF (CMYK 300dpi)</div>-->


                <!--</div>-->
            </div>
        </section>
    </div>
    <br><br><br><br><br><br><br>

    <!-- Juri Section -->
    <!--<section class="judges">-->
    <!--    <h1 class="text-5xl text-center text-white font-bold">Judges</h1>-->
    <!--    <div class="flex mt-10 justify-center">-->
    <!--        <div class="text-white py-1 px-10 bg-neutral-800 rounded-full ">Cover Buku</div>-->
    <!--    </div>-->


    <!--    <div class="mt-12 flex gap-8 justify-center flex-wrap px-4">-->
    <!--        <div class="flex flex-col  max-w-64">-->

    <!--            <div class="w-auto h-auto bg-neutral-900 rounded-3xl overflow-hidden shadow-lg relative">-->
    <!--                <img src="../img/Submission/Juri1desainkarakter.png" alt=""-->
    <!--                    class="w-full h-80 object-cover cursor-pointer popup-image"-->
    <!--                    data-img="../img/Submission/Juri1desainkarakter.png">-->
    <!--            </div>-->
    <!--            <h2 class="text-lg font-semibold text-white text-center pt-5    ">Romy Hernadi</h2>-->
    <!--            <div class="max-w-full">-->
    <!--                <p class=" text-center text-sm text-gray-400">Komikus, Illustrator</p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="flex flex-col  max-w-64">-->

    <!--            <div class="w-auto h-auto bg-neutral-900 rounded-3xl overflow-hidden shadow-lg relative">-->
    <!--                <img src="../img/Submission/Juri2desainkarakter.png" alt=""-->
    <!--                    class="w-full h-80 object-cover cursor-pointer popup-image"-->
    <!--                    data-img="../img/Submission/Juri2desainkarakter.png">-->
    <!--            </div>-->
    <!--            <h2 class="text-lg font-semibold text-white text-center pt-5    ">Diah Mayang Sari, M.Ds.</h2>-->
    <!--            <div class="max-w-full">-->
    <!--                <p class=" text-center text-sm text-gray-400">Dosen DKV UPI</p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="flex flex-col  max-w-64">-->

    <!--            <div class="w-auto h-auto bg-neutral-900 rounded-3xl overflow-hidden shadow-lg relative">-->
    <!--                <img src="../img/Submission/Juri3desainkarakter.png" alt=""-->
    <!--                    class="w-full h-80 object-cover cursor-pointer popup-image"-->
    <!--                    data-img="../img/Submission/Juri3desainkarakter.png">-->
    <!--            </div>-->
    <!--            <h2 class="text-lg font-semibold text-white text-center pt-5    ">Dewi Iriani, M.Ds.</h2>-->
    <!--            <div class="max-w-full">-->
    <!--                <p class=" text-center text-sm text-gray-400">Dosen DKV UPI</p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <br><br><br>-->
    <!--</section>-->

    <br><br><br><br><br><br>
    <div class="flex justify-center">
        <a href="./submitkaryawacom.php">
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