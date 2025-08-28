<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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

        /* Styling for the loading overlay */
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 1.5rem;
            z-index: 9999;
        }

        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <title>Finder - Pengumuman Lomba</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body class="bg-black">
    <!-- Loading overlay -->
    <div id="loading-overlay">
        <div class="loader"></div>
        <p>Memuat gambar...</p>
    </div>

    <?php
    require '_navbar.php'
        ?>
    <div
        class="w-2/3 h-3/4 blur-3xl absolute -z-10 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
    </div>

    <br><br><br><br><br>
    <div class="relative">
        <div
            class="pointer-events-none absolute right-0 top-0 h-full w-1/4 bg-gradient-to-l from-black  to-transparent z-10">
        </div>

        <div
            class="hidden md:flex pointer-events-none absolute left-0 top-0 h-full w-1/4 bg-gradient-to-r from-black  to-transparent z-10">
        </div>

        <section
            class="flex overflow-x-auto snap-x snap-mandatory gap-5 md:gap-20 px-7 md:px-24 scrollbar-hide scroll-smooth">
            <div class="snap-center shrink-0 w-full md:w-8/12 pt-20 h-full shadow-lg relative">
                <div class="absolute top-16 md:top-14 -left-3 md:-left-6 z-30">
                    <img src="./img/Lomba/Star 2.png" alt="" class="w-14 md:w-20 h-auto" />
                </div>
                <div class="w-full h-full">

                    <div class="rounded-3xl overflow-hidden relative">
                        <div class="pointer-events-none absolute h-full w-full  bg-black opacity-80">
                        </div>
                        <img src="./img/Lomba/deschar1resize2.jpg"
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/deschar1resize2.jpg">

                        <div class="pointer-events-none">
                            <div class="absolute flex flex-col text-white w-10/12 z-20 top-5 md:top-10 left-10">
                                <h1 class="font-bold text-xl lg:text-5xl mb-1 lg:mb-3 ">Juara 1</h1>
                                <h1 class="font-semibold italic text-lg lg:text-3xl mb-2 ">Desain Karakter</h1>
                            </div>
                            <div class="hidden md:flex absolute flex-col text-white z-20 bottom-10 left-10">
                                <h1 class=" font-semibold text-xl lg:text-3xl mb-2 ">Agni Yudawisesa Langsuyaraga, Api
                                    Perperangan Tinggi yang Tak Pernah Padam.</h1>
                                <h2 class="text-lg lg:text-xl">Khaliza Ramadhania Indianto</h2>
                                <a href="https://www.instagram.com/@_khlzrmdh03">
                                    <h3 class="text-lg lg:text-xl">@_khlzrmdh03</h3>
                                </a>
                                <p class="text-sm lg:text-base text-neutral-400 w-10/12">Melawan ritme seperti bertarung
                                    mempertaruhkan nyawanya, itulah yang dilakukan Agni demi menyelamatkan visi hidupnya
                                    seperti makhluk hidup lainnya. Apakah benar jal...</p>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="flex md:hidden flex-col  text-white ">
                        <div class="flex flex-col ">
                            <h1 class="font-semibold italic text-lg md::text-3xl">Agni Yudawisesa Langsuyaraga, Api
                                Perperangan Tinggi yang Tak Pernah Padam.</h1>
                            <h2 class="text-sm md::text-xl">Khaliza Ramadhania Indianto</h2>
                            <a href="https://www.instagram.com/@_khlzrmdh03">
                                <h3 class="text-lg lg:text-xl">@_khlzrmdh03</h3>
                            </a>

                            <p class="text-xs md::text-base text-neutral-400">Melawan ritme seperti bertarung
                                mempertaruhkan nyawanya, itulah yang dilakukan Agni demi menyelamatkan visi hidupnya
                                seperti makhluk hidup lainnya. Apakah benar jal...</p>
                        </div>

                        <br>

                        <h1 class="p-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-transparent">Swipe</h1>
                    </div>


                </div>

            </div>



            <div class="snap-center shrink-0 w-full md:w-8/12 pt-20 h-full shadow-lg relative">
                <div class="absolute top-16 md:top-14 -left-3 md:-left-6 z-10">
                    <img src="./img/Lomba/Star 2.png" alt="" class="w-14 md:w-20 h-auto" />
                </div>

                <div class="flex w-full h-full ">
                    <div class="w-full sm:w-1/2 relative rounded-3xl overflow-hidden">
                        <div class="pointer-events-none absolute h-full w-full bg-black opacity-80">
                        </div>
                        <img src="./img/Lomba/Juara1resize.jpg"
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/Juara1resize.jpg">

                        <div class="flex sm:hidden pointer-events-none">
                            <div class="absolute flex flex-col text-white w-10/12 top-5 left-10">
                                <h1 class="font-bold text-xl md:text-5xl mb-1 md:mb-3 ">Juara 1</h1>
                                <h1 class="font-semibold italic text-lg md:text-3xl mb-2 ">Poster Ilustrasi</h1>
                            </div>
                            <div class="absolute flex flex-col text-white z-20 bottom-10 left-10">
                                <h1 class=" font-semibold text-base md:text-3xl mb-2 "> Tengah Jelajah</h1>
                                <h2 class="text-sm md:text-xl">Ardy Muhammad Ikhklassul Akbar</h2>
                                <a href="https://www.instagram.com/@meoreo1">
                                    <h3 class="text-xs italic md:text-xl mb-2">@meoreo1</h3>
                                </a>
                                <p class="text-xs md:text-base text-neutral-400 w-10/12">Kondisi autopilot saat
                                    berkendara, terjadi Saat kita melakukan sesuatu yang tidak membutuhkan pemikiran,
                                    keadaan ini akan mengambil alih kendali dan membuat kita melakukan hal tersebut
                                    secara otomatis. Digabung ...</p>
                            </div>
                        </div>
                    </div>

                    <div class="hidden w-1/2 pl-10 py-10 sm:flex flex-col justify-between text-white ">
                        <div>
                            <h1 class="font-bold text-2xl md:text-5xl mb-3 md:mb-5 ">Juara 1</h1>
                            <h1 class="font-semibold italic text-xl md:text-3xl mb-6 md:mb-10 ">Poster Ilustrasi</h1>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <h1 class="font-semibold italic text-xl md:text-3xl">Tengah Jelajah</h1>
                            <h2 class="text-lg md:text-xl">Ardy Muhammad Ikhklassul Akbar</h2>
                            <a href="https://www.instagram.com/@meoreo1">
                                <h3 class="text-lg md:text-xl">@meoreo1</h3>
                            </a>
                            <p class="text-sm md:text-base text-neutral-400">Kondisi autopilot saat berkendara, terjadi
                                Saat kita melakukan sesuatu yang tidak membutuhkan pemikiran, keadaan ini akan mengambil
                                alih kendali dan membuat kita melakukan hal tersebut secara otomatis. Digabung ...</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <br><br><br><br><br><br><br>


    <div
        class="flex flex-col md:flex-row mx-auto text-center justify-center gap-5 text-base md:text-lg font-semibold text-neutral-600 pb-10">
        <a href="#desain" class="nav-tab text-emerald-400 underline">Design Character</a>
        <a href="#poster" class="nav-tab">Poster Illustration</a>
    </div>
    <div class="relative">
        <div
            class="pointer-events-none absolute right-0 top-0 h-full w-1/4 bg-gradient-to-l from-neutral-950 to-transparent z-10">
        </div>
        <section
            class="desain flex overflow-x-auto snap-x snap-mandatory gap-5 md:gap-20 px-7 md:px-24 scrollbar-hide scroll-smooth ">
            <div id="desain"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Design Character</h1>
                <br>


                <div id="juara1" class="pb-32">

                    <div
                        class=" w-3/4 h-full mb-8 mx-auto justify-center rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/deschar1resize2.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/deschar1resize2.jpg">
                    </div>

                    <h1
                        class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-yellow-400 to-transparent">
                        Juara 1</h1>



                    <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Agni Yudawisesa Langsuyaraga
                    </h1>
                    <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Khaliza Ramadhania Indianto
                    </h3>
                    <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"><a
                            href="https://www.instagram.com/@_khlzrmdh03">@_khlzrmdh03</a>
                    </p>
                    <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5">
                        Melawan ritme seperti bertarung mempertaruhkan nyawanya, itulah yang dilakukan Agni demi
                        menyelamatkan visi hidupnya seperti makhluk hidup lainnya. Apakah benar jalan yang dipilihnya
                        sudah tepat? rnrnMenerjang kehidupan dengan melawan paradigma melodinya untuk memenuhi kewajiban
                        sembari terus menari mengeluarkan rasa amarah karena direndahkan tanpa henti oleh kupu-kupu yang
                        lebih cantik dan elegan. Paradoks eksistensial antara alam sadar dan bawah sadar. Haruskah dia
                        terus-menerus memakai topengnya?rnrn“Aku memilih kehancuran ini—ia datang perlahan, menjanjikan
                        kekuatan, dan aku mempercayainya. Sayapku penuh luka, nafasku membawa kematian. Buana Larang
                        menjadi tempat tinggalku. Mungkin mereka benar menyebutku monster, tapi mereka tak pernah
                        melihat bagaimana aku tersenyum dalam lapar demi tawa saudara-sudaraku. Amarah membentukku,
                        tapi cintalah yang menahanku di sini. Aku akan tetap terus bertahan, meski diriku terbakar di
                        fase hidup terakhir.&quot;rnrnTerinspirasi dari Tari Topeng Fase Terakhir dari Cirebon, elemen
                        rarangken Sunda, dan Cinnabar Moth. Sebuah personifikasi rasa amarah dan dendam yang dalam
                        terhadap paradoks kehidupan.
                    </p>
                </div>


                <div id="juara2" class="flex flex-col pb-32 w-full items-end relative">
                    <div class="flex flex-col w-full ">

                        <div
                            class="w-3/4 h-full mb-8 mx-auto justify-center rounded-3xl overflow-hidden shadow-lg relative ">
                            <img src="./img/Lomba/deschar2resize.jpg" alt=""
                                class="w-full h-full object-cover cursor-pointer popup-image"
                                data-img="./img/Lomba/deschar2resize.jpg">
                        </div>

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-blue-500 to-transparent">
                            Juara 2</h1>



                        <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Mentovar </h1>
                        <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Muhammad Iman Ramadhan
                        </h3>
                        <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"> <a
                                href="https://instagram.com/@rmdhn.iman_">@rmdhn.iman_</a></p>
                        <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5"> A wandering celestial who
                            was once human. Mentovar is a being shaped by longing, the deep desire for something once
                            held. After attempting to break the rules of time in pursuit of immortality, he was cursed
                            to drift endlessly through the void beyond time. Now silent and eternal, he collects clocks
                            and watches, hoping one day to bend time once more, not for power, but simply to live a
                            normal life again.rnrnSeorang pengembara langit yang dulunya manusia. Mentovar adalah wujud
                            dari kerinduan — hasrat mendalam akan sesuatu yang pernah dimiliki. Setelah mencoba
                            melanggar hukum waktu demi kehidupan abadi, ia dikutuk untuk terus mengembara di kehampaan
                            yang melampaui waktu. Kini, dalam keheningan dan keabadian, ia mengumpulkan jam dan arloji,
                            berharap suatu hari bisa membelokkan waktu sekali lagi — bukan untuk kekuatan, tapi sekadar
                            untuk bisa hidup sebagai manusia biasa.</p>
                    </div>
                </div>


                <div id="juara3" class="flex flex-col pb-32 w-full items-start">
                    <div class="flex flex-col w-full h-auto gap-14 items-center ">

                        <div
                            class="w-3/4 h-full mb-8 mx-auto justify-center rounded-3xl overflow-hidden shadow-lg relative ">
                            <img src="./img/Lomba/deschar3resize.jpg" alt=""
                                class="w-full h-full object-cover cursor-pointer popup-image"
                                data-img="./img/Lomba/deschar3resize.jpg">
                        </div>

                        <div class="flex flex-col w-full justify-between">
                            <div>
                                <h1
                                    class="font-bold text-start text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-r from-emerald-500 to-transparent">
                                    Juara 3</h1>
                            </div>

                            <div class="flex flex-col ">
                                <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Envy, Invidia</h1>
                                <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Valen Nisa Kirani
                                </h3>
                                <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                    <a href="https://instagram.com/@palinniee">@palinniee</a>
                                </p>
                                    dari rasa dengki yang tak terpadamkan;rnInvidia, nama yang d
                                <p class="font-normal text-justify text-sm md:text-base">Dialah perwujudan paling purbaitulis dalam bisikan
                                    para dewa yang terlupakan,rnlahir dari reruntuhan nurani, dari luka batin yang tak
                                    sempat sembuh,rndan dari tatapan mata yang senantiasa haus akan kepunyaan orang
                                    lain.rnIa bukan sekadar emosi, melainkan makhluk yang tumbuh dalam sunyi,rnmembelit
                                    batin manusia dengan bisik lembut penuh racun.rnSetiap kali seseorang melirik dengan
                                    rasa kurang terhadap milik saudaranya,rndi sanalah ia hidup, sebagai api kecil yang
                                    perlahan membakar akar kasih.rnIa tak berteriak, ia tak mengaum, ia
                                    menyusup.rnSeperti ular di balik semak keinginan,rnseperti bayang di balik sinar
                                    kemenangan orang lain.rnrndan ketika manusia merasa bahwa dunia berlaku tidak
                                    adil,rndi sanalah dia tumbuh, subur, tak terhentikan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="juarafavorit" class="flex flex-col pb-32 w-full items-end">
                    <div class="flex flex-col w-full">

                        <div
                            class=" w-3/4 h-full mb-8 mx-auto justify-center rounded-3xl overflow-hidden shadow-lg relative ">
                            <img src="./img/Lomba/deschar5resize.jpg" alt=""
                                class="w-full h-full object-cover cursor-pointer popup-image"
                                data-img="./img/Lomba/deschar5resize.jpg">
                        </div>

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-pink-500 to-transparent">
                            Juara Favorit</h1>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Skeptikos </h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Tania Andini Susanto
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/@tnds">@tnds</a>
                            </p>
                            <p class="font-normal text-justify text-sm md:text-base">Skeptikos adalah manifestasi dari
                                rasa keraguan/curiga/skeptis yang ada dalam diri manusia. Ia bertugas untuk menilai mana
                                yang baik atau buruk dan benar atau salah dengan banyak menimbang-nimbang dan berpikir
                                ulang. Di era dimana informasi silih berganti dengan sangat cepat dan perkembangan
                                teknologi semakin pesat, pekerjaan Skeptikos menjadi semakin berat. Perlu usaha lebih
                                untuknya memproses data-data yang terus berdatangan, bahkan tas besarnya tampak tidak
                                cukup untuk memuat semua informasi tersebut. Sedangkan manusia seringkali ingin yang
                                instan, sehingga perannya sering dilupakan. Kehadirannya memang terasa seperti
                                menghambatmu untuk melaju. Ia membuatmu berhenti hanya untuk meninjau ulang kebenaran
                                informasi yang ada di hadapanmu. Kulitnya yang pucat, serta pembawaannya yang selalu
                                waspada dan gugup tidak membantumu merasa lebih tenang. Tapi apabila manusia memiliki
                                kemauan kuat untuk menguak kebenaran, Skeptikos akan datang membantu meninjau kembali
                                informasi-informasi yang ia simpan dan akan memberikanmu dorongan dalam menentukan.rn
                            </p>
                        </div>
                        <br><br>
                    </div>
                </div>

                <div id="honourablemention" class="flex flex-col pb-32 w-full items-end">
                    <div class="flex flex-col w-full">

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-pink-500 to-transparent">
                            Honourable Mention</h1>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Bagja</h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Rafi Amarin
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/ramarin_art" target="_blank">@ramarin_art</a>
                            </p>
                        </div>
                        <br><br>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Linxu Shiyan</h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Az Zahra Qyani Anadira
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/zahra_q.20" target="_blank">@zahra_q.20</a>
                            </p>
                        </div>
                        <br><br>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Inny</h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Amanda Fathiyah Din
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/aleso" target="_blank">@aleso</a>
                            </p>
                        </div>
                        <br><br>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">The Anger Yono</h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Felix Alvaro Rahadian
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/felixrahadian_" target="_blank">@felixrahadian_</a>
                            </p>
                        </div>
                        <br><br>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md::mb-10">Mnea</h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Patricia Loretta
                                Hartono
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/iriscencieal" target="_blank">@iriscencieal</a>
                            </p>
                        </div>
                        <br><br>

                    </div>
                </div>
            </div>




            <div id="poster"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Poster Ilustrasi</h1>
                <br>


                <div id="juara1" class="flex flex-col md:flex-row gap-10 pb-32">

                    <div class=" w-full md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/Juara1resize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/Juara1resize.jpg">
                    </div>

                    <div class="w-full md:w-1/2">

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-yellow-400 to-transparent">
                            Juara 1</h1>



                        <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Tengah Jelajah</h1>
                        <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Ardy Muhammad Ikhklassul
                            Akbar</h3>
                        <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"> <a
                                href="https://instagram.com/@meoreo1">@meoreo1</a>
                        </p>
                        <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5">Kondisi autopilot saat
                            berkendara, terjadi Saat kita melakukan sesuatu yang tidak membutuhkan pemikiran, keadaan
                            ini akan mengambil alih kendali dan membuat kita melakukan hal tersebut secara otomatis.
                            Digabung dengan doomscrolling yang menyebabkan kita kesulitan fokus dalam hal penting,
                            sehingga yang teringat hanya hal-hal acak dan pendek yang menurut kita menarik. Ilustrasi
                            ini menggambarkan dua keadaan tersebut dimana tangan menggenggam setir, namun pandangan
                            kosong menatap jauh ke depan, sementara pikiran mengembara tanpa arah. Jalanan seolah
                            menjadi lorong tak berujung, dan meskipun apa yang dipikirkan termasuk hal-hal yang tidak
                            jelas menjadi nyata, kesadaran sulit untuk menangkapnya. Karena itu disebut
                            &quot;Hanyut&quot; yang dalam KBBI memiliki arti kiasan yaitu &quot;terlalu asyik&quot;,
                            dengan tubuh terus bergerak secara otomatis, tanpa kehadiran penuh dari dalam diri.
                        </p>

                    </div>
                </div>
                <div id="juara2" class="flex flex-col md:flex-row gap-10 pb-32">


                    <div class="w-full md:w-1/2">

                        <div
                            class="flex md:hidden w-full md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                            <img src="./img/Lomba/Juara2resize.jpg" alt=""
                                class="w-full h-full object-cover cursor-pointer popup-image"
                                data-img="./img/Lomba/Juara2resize.jpg">
                        </div>

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-blue-500 to-transparent">
                            Juara 2</h1>



                        <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Thread of Mind</h1>
                        <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Muhammad Iqbal Birril Kharishi</h3>
                        <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"> <a
                                href="https://instagram.com/@ibalbirril">@ibalbirril</a>
                        </p>
                        <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5">Thread of MindrnrnDalam
                            heningnya wajah itu, ada riuh yang tak terdengar. Sebuah kepala raksasa muncul dari balik
                            labirin dalam laut, diam dan tenang namun retak-retak di permukaannya menyiratkan sesuatu
                            yang lebih dalam. Ini bukan sekadar patung, tapi wujud dari pikiran manusia: tenang di luar,
                            penuh gema di dalam.rnrnDari retakan kepala itu, benang merah menjulur ke segala arah,
                            menyambungkan berbagai simbol yang mewakili aspek terdalam dari diri:rn-Sebuah buku terbuka
                            menandakan ingatan dan pengetahuan yang tersimpan dalam memori.rn-Topeng-topeng
                            menggambarkan wajah-wajah yang kita pakai untuk dunia kebahagiaan, kesedihan, semua bagian
                            dari sandiwara batin.rn-Sebuah tangan yang menggenggam rantai melambangkan trauma atau luka
                            masa lalu yang masih membelenggu jiwa.rn- mainan-mainan kecil mewakili nostalgia, masa kecil
                            yang tak pernah benar-benar kita tinggalkan.rnrnSemuanya terikat oleh benang yang sama
                            benang pikiran, benang memori, benang diri.rnrnDi bawahnya, labirin menyebar dalam
                            keheningan bawah laut, tempat dua ikan berenang perlahan, seolah menjadi penjaga bawah
                            sadar. Di pusat labirin, seorang manusia kecil berdiri, memandangi cahaya yang muncul dari
                            dalam dirinya sendiri mencari, bertanya, dan mungkin, menemukan.rnrnlabirin
                            merepresentasikan kompleksitas pikiran manusia jalan berliku penuh keraguan, ingatan, dan
                            pencarian makna yang tak selalu mudah. rnrn“Inside your silence, a thousand voices
                            remain.”rnSebuah kalimat yang menjadi pengingat bahwa meski kita tampak diam, dalam benak
                            kita hidup ribuan suara kenangan, pertanyaan, rasa takut, harapan, dan cinta.rnrn“Thread of
                            Mind” bukan sekadar poster. Ia adalah perjalanan masuk ke dalam pikiran untuk menelusuri
                            benang-benang yang membentuk siapa kita sebenarnya. Sebuah ajakan untuk berani menjelajah ke
                            dalam, ke ruang-ruang batin yang paling sunyi, dan menemukan cahaya dari sana.
                        </p>

                    </div>

                    <div
                        class="hidden md:flex w-full md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/Juara2resize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/Juara2resize.jpg">
                    </div>
                </div>
                <div id="juara3" class="flex flex-col md:flex-row gap-10 pb-32">
                    <!-- <div class="w-full  md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/Juara3resize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/Juara3resize.jpg">
                    </div> -->
                    <div class="w-full  md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/Juara3resize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/Juara3resize.jpg">
                    </div>

                    <div class="w-full md:w-1/2">

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-emerald-500 to-transparent">
                            Juara 3</h1>
                        <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Mau Bohong Sampai Kapan?
                        </h1>
                        <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Romzi Nur Fadhli</h3>
                        <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"><a
                                href="https://instagram.com/@romujiii_">@romujiii_</a>
                        </p>
                        <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5"> Setiap saya bercermin,
                            saya selalu bertanya, “Mau bohong sampai kapan?”.rnKarya ini selain menjadi ajakan untuk
                            jujur kepada diri sendiri, namun juga menjadi gambaran ‘deep state of mind’ saya. rnSeorang
                            pria menatap ke cermin yang menggambarkan perasaan aslinya yang disembunyikan selama ini.
                            Meskipun menangis tersedu-sedu, namun itulah kenyataan yang memang ia rasakan selama ini.
                            Digambarkan dengan cahaya hangat dari jendela beserta bunga matahari yang memberikan kesan
                            pelukan hangat, kejujuran, dan realita. Warna dengan tone dingin dan hangat yang
                            bersinggungan di karya ini menggambarkan bahwa harapan akan datang jika kita berusaha jujur
                            kepada diri sendiri. ‘Refleksi’ di dalam cermin memeluk sebuah foto keluarga yang
                            mengisyarkatkan bahwa Ia merindukan pelukan hangat setulus keluarganya di masa lampau.rnDi
                            pinggir cermin juga terdapat retakan yang diisi bunga, menggambarkan bahwa kebahagiaan
                            datang ketika kita mulai memecahkan ‘batasan’ antara perasaan dan tindakan.rnrnMeski penuh
                            dengan tangisan, namun itulah kehangatan yang selama ini ingin saya rasakan.rn
                        </p>
                    </div>
                </div>

                <div id="juarafavorit" class="flex flex-col md:flex-row gap-10 pb-32">
                    <div class="w-full  md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/JuaraFavresize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/JuaraFavresize.jpg">
                    </div>

                    <div class="w-full md:w-1/2">

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-pink-500 to-transparent">
                            Juara Favorit</h1>
                        <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Siapa Aku, Sebenarnya?
                        </h1>
                        <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Gede Esa Nathan Dinata</h3>
                        <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"><a
                                href="https://instagram.com/@nathhsasasa">@nathhsasasa</a>
                        </p>
                        <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5"> &quot;Siapa Aku,
                            Sebenarnya?&quot; merefleksikan penyelaman pikiran terdalam untuk menemukan jawaban atas
                            ruang untuk kreativitas dan tekanan sosial. Seperti halnya Deep State of Mind
                            (bermonolog internal) pikiran tak terpengaruh oleh topeng sosial, ego dan otak sebagai
                            pusat cahaya pikiran mulai menemukan jati diri di tengah krisis identitas yang relevan
                            pada anak muda. &quot;Siapa Aku, Sebenarnya?&quot; kembali mengajak anak muda untuk
                            menjelajahi diri sendiri melalui pikiran yang dalam.
                        </p>
                    </div>
                </div>



                <div id="honourablemention" class="flex flex-col pb-32 w-full items-end">
                    <div class="flex flex-col w-full">

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-pink-500 to-transparent">
                            Honourable Mention</h1>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Ruang Tunggu Pikiran
                            </h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Hammam Hikmathiyar
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/hammamhikmathiyarr"
                                    target="_blank">@hammamhikmathiyarr</a>
                            </p>
                        </div>
                        <br><br>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Birthday Blues</h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Felicia Gracia
                                Aliwinoto
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/feliarty" target="_blank">@feliarty</a>
                            </p>
                        </div>
                        <br><br>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Wants to be Perfect to Breathe</h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Alisha Putri Husnaini
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/@llishaarw" target="_blank">@@llishaarw</a>
                            </p>
                        </div>
                        <br><br>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Nalaktak</h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Aisya Nur Rohmah
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/bassalganglia" target="_blank">@bassalganglia</a>
                            </p>
                        </div>
                        <br><br>

                        <div class="flex flex-col ">
                            <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Lost in Thought</h1>
                            <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Khoirul Aribah
                            </h3>
                            <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                <a href="https://instagram.com/_leriear" target="_blank">@_leriear</a>
                            </p>
                        </div>
                        <br><br>

                    </div>
                </div>
            </div>
            <br><br><br>
    </div>
    </section>
    </div>
    <br><br><br><br><br><br><br>


    <script>
        // Fungsi untuk mengaktifkan/menonaktifkan menu navigasi
        const navLinks = document.querySelector('.nav-links');
        function onToggleMenu(e) {
            e.name = e.name === 'menu' ? 'close' : 'menu';
            navLinks.classList.toggle('-bottom-52');
        }
    </script>

    <script>
        // Efek scroll untuk navbar
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
        // Animasi scroller
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        // Animasi scroller (duplikat, bisa dihapus salah satu jika sama)
        // const scrollers = document.querySelectorAll('.scroller'); // Sudah dideklarasikan di atas

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

    <div id="imageModal" class="fixed inset-0 z-50 bg-black bg-opacity-80 hidden items-center justify-center">
        <div class="relative">
            <button id="closeModal"
                class="absolute -top-0 -right-0 bg-white text-black rounded-xl px-4 p-2 hover:bg-gray-200 z-10">✕</button>
            <img id="modalImage" src="" class="max-w-[90vw] max-h-[90vh] rounded-xl shadow-2xl" />
        </div>
    </div>

    <script>
        // Ambil elemen-elemen yang dibutuhkan untuk modal gambar
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

    <script>
        // Inisialisasi Swiper
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,

            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1.2,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    </script>

</body>
<script src="https://unpkg.com/kursor"></script>
<script>
    // Inisialisasi kursor kustom
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
    const section = document.querySelector('.desain');
    const navTabs = document.querySelectorAll('.nav-tab');

    function highlightTabById(id) {
        navTabs.forEach(tab => {
            const tabId = tab.getAttribute('href').substring(1);
            if (tabId === id) {
                tab.classList.add('text-emerald-400', 'underline');
                tab.classList.remove('text-neutral-600');
            } else {
                tab.classList.remove('text-emerald-400', 'underline');
                tab.classList.add('text-neutral-600');
            }
        });
    }

    function updateActiveTab() {
        let closest = null;
        let minDiff = Infinity;
        const center = section.scrollLeft + section.offsetWidth / 2;

        section.querySelectorAll('div[id]').forEach(el => {
            const elCenter = el.offsetLeft + el.offsetWidth / 2;
            const diff = Math.abs(center - elCenter);
            if (diff < minDiff) {
                minDiff = diff;
                closest = el;
            }
        });

        if (closest) highlightTabById(closest.id);
    }

    // Scroll listener
    section.addEventListener('scroll', updateActiveTab);

    // Click tab → scroll + highlight
    navTabs.forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault();
            const targetId = tab.getAttribute('href').substring(1);
            const target = document.getElementById(targetId);
            if (target) {
                section.scrollTo({
                    left: target.offsetLeft - section.offsetLeft,
                    behavior: 'smooth'
                });
                highlightTabById(targetId);
            }
        });
    });

    // --- LOGIKA LOADING GAMBAR BARU ---
    window.addEventListener('load', () => {
        const loadingOverlay = document.getElementById('loading-overlay');
        const images = document.querySelectorAll('img');
        let loadedImages = 0;

        // Fungsi untuk menyembunyikan overlay loading
        const hideLoadingOverlay = () => {
            loadingOverlay.style.display = 'none';
            // Trigger updateActiveTab setelah semua gambar dimuat dan overlay disembunyikan
            updateActiveTab();
        };

        // Jika tidak ada gambar, sembunyikan overlay langsung
        if (images.length === 0) {
            hideLoadingOverlay();
            return;
        }

        images.forEach(img => {
            // Pastikan gambar memiliki src
            if (img.src) {
                const tempImg = new Image();
                tempImg.src = img.src;
                tempImg.onload = () => {
                    loadedImages++;
                    if (loadedImages === images.length) {
                        // Semua gambar telah dimuat
                        hideLoadingOverlay();
                    }
                };
                tempImg.onerror = () => {
                    // Tangani kesalahan pemuatan gambar jika diperlukan
                    loadedImages++;
                    if (loadedImages === images.length) {
                        hideLoadingOverlay();
                    }
                };
            } else {
                // Jika gambar tidak memiliki src, anggap sudah dimuat
                loadedImages++;
                if (loadedImages === images.length) {
                    hideLoadingOverlay();
                }
            }
        });
    });
    // --- AKHIR LOGIKA LOADING GAMBAR BARU ---

</script>

</html>