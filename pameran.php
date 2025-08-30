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

  <style>
    /* Mengatur agar gambar dan elemen inner lainnya proporsional (1:1) */
    .aspect-square-container::before {
      content: '';
      display: block;
      padding-top: 100%;
      /* Rasio 1:1 */
    }

    .zoom-img {
      transition: transform 0.5s ease-in-out;
    }

    .zoom-container:hover .zoom-img {
      transform: scale(1.1);
      /* Zoom-in sebesar 10% */
    }
  </style>

  <title>Finder 7 - Pameran</title>
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

  <!-- Hero Section -->
  <section data-section-bg="dark"
    class="w-10/12 min-h-screen flex flex-col lg:flex-row items-center justify-center px-4 mx-auto ">
    <!-- Central Content -->
    <div class="order-last lg:order-first relative z-10 w-full text-center text-white space-y-4">
      <h2 class="text-4xl md:text-8xl font-bold text-center md:text-left leading-tight max-w-xs sm:max-w-fit">
        Ayo Lihat <br />Karya-Karya <br /> Finder!
      </h2>
      <div class="flex flex-col gap-4 items-center md:items-start">
        <a href="#scbar"
          class="w-48 sm:w-64 h-12 sm:h-16 flex items-center justify-center bg-[#008C62] text-white text-lg sm:text-xl font-medium rounded-xl sm:rounded-[20px] shadow-md hover:scale-105 transition-transform duration-300">
          See More
        </a>
      </div>
    </div>
  </section>

  <!-- Kategori Section -->
  <section class="flex flex-col justify-center text-center gap-10">
    <h1 class="font-bold text-white text-3xl">Pilih Section Kamu!</h1>
    <div class="hidden md:flex gap-10 text-white justify-center text-center font-bold">
      <div
        class="feeling flex-col border border-opacity-50 hover:border-pink-600 rounded-3xl px-7 py-4 hover-radial-bg duration-300 "
        style="--hover-color: #db2777  ">
        <img src="./img/icon program/feeling.svg" alt="">
        <p>Feeling</p>
      </div>
      <div
        class="thinking flex-col border border-opacity-50 hover:border-blue-600 rounded-3xl px-7 py-4 hover-radial-bg duration-300"
        style="--hover-color: #618BFF  ">
        <img src="./img/icon program/thinking.svg" alt="">
        <p>Thinking</p>
      </div>
      <div
        class="intuitive flex-col border border-opacity-50 hover:border-yellow-400 rounded-3xl px-7 py-4 hover-radial-bg duration-300"
        style="--hover-color: #FEE139  ">
        <img src="./img/icon program/intuitive.svg" alt="">
        <p>intuitive</p>
      </div>
      <div
        class="sensing flex-col border border-opacity-50 hover:border-emerald-600 rounded-3xl px-7 py-4 hover-radial-bg duration-300"
        style="--hover-color: #00D294  ">
        <img src="./img/icon program/sensing.svg" alt="">
        <p>sensing</p>
      </div>
    </div>

    <div class="fixed flex md:hidden w-10/12 bottom-5 left-0 right-0 bg-neutral-800 z-50 shadow-md justify-center items-center grid-cols-4 mx-auto rounded-3xl">
      <div
        class="feeling flex-col aspect-square hover:border-pink-600 rounded-3xl px-4 py-2 hover-radial-bg duration-300 items-center justify-center "
        style="--hover-color: #db2777  ">
        <img src="./img/icon program/feeling.svg" alt="" class="w-full h-full">
      </div>
      <div
        class="thinking flex-col aspect-square hover:border-blue-600 rounded-3xl px-4 py-2 hover-radial-bg duration-300 items-center justify-center"
        style="--hover-color: #618BFF  ">
        <img src="./img/icon program/thinking.svg" alt="" class="w-full h-full">
      </div>
      <div
        class="intuitive flex-col aspect-square hover:border-yellow-400 rounded-3xl px-4 py-2 hover-radial-bg duration-300 items-center justify-center"
        style="--hover-color: #FEE139  ">
        <img src="./img/icon program/intuitive.svg" alt="" class="w-full h-full">
      </div>
      <div
        class="sensing flex-col aspect-square hover:border-emerald-600 rounded-3xl px-4 py-2 hover-radial-bg duration-300 items-center justify-center"
        style="--hover-color: #00D294  ">
        <img src="./img/icon program/sensing.svg" alt="" class="w-full h-full">
      </div>
    </div>
  </section>

  <br><br>

  <!-- Search Bar -->
  <section id="scbar" class="flex w-10/12 md:w-6/12 justify-center mx-auto ">
    <form action="" class=" flex flex-row w-full bg-white rounded-full " method="post">
      <div class="relative gap-2 w-full">
        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-7">
          <svg width="30px" height="30px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M17.545 15.467l-3.779-3.779a6.15 6.15 0 0 0 .898-3.21c0-3.417-2.961-6.377-6.378-6.377A6.185 6.185 0 0 0 2.1 8.287c0 3.416 2.961 6.377 6.377 6.377a6.15 6.15 0 0 0 3.115-.844l3.799 3.801a.953.953 0 0 0 1.346 0l.943-.943c.371-.371.236-.84-.135-1.211zM4.004 8.287a4.282 4.282 0 0 1 4.282-4.283c2.366 0 4.474 2.107 4.474 4.474a4.284 4.284 0 0 1-4.283 4.283c-2.366-.001-4.473-2.109-4.473-4.474z" />
          </svg>
        </div>
        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 w-30 bg-neutral-500 rounded-full p-3 px-10">
          cari
        </button>
        <input type="text" name="searchinput" class="w-full h-16 rounded-full pl-16 font-work font-medium text-sm sm:text-lg"
          placeholder="Ketik disini.." required>
      </div>
    </form>
  </section>

  <br><br>

  <!-- kategori -->
  <section>
    <div class="flex w-10/12 gap-4 md:gap-16 justify-center mx-auto">
      <a href="" style="font-family: 'Work Sans'" class="flex"><button
          class="text-xs lg:text-xl text-white txt1">Animated Motion</button></a>
      <a href="" style="font-family: 'Work Sans'" class="flex"><button
          class="text-xs lg:text-xl text-white txt2">Ilustrasi Digital</button></a>
      <a href="" style="font-family: 'Work Sans'" class="flex"><button
          class="text-xs lg:text-xl text-white txt">Fotografi</button></a>
      <a href="" style="font-family: 'Work Sans'" class="flex"><button
          class="text-xs lg:text-xl text-white txt">Poster</button></a>
    </div>
  </section>

  <br><br>

  <!-- pameran -->
  <section class="font-sans p-8">

    <div class="container mx-auto">

      <!-- Container untuk semua grid -->
      <div class="space-y-12">

        <!-- Kategori 1: Ilustrasi -->
        <div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="detailpameran.php"
              class="relative w-full aspect-square-container rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all text-white duration-300 block zoom-container group">
              <img src="./img/Lomba/deschar1resize.jpg" alt="Ilustrasi Karya 2"
                class="absolute inset-0 w-full h-full object-cover zoom-img">
              <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 to-transparent  flex flex-col justify-end p-4 gap-3">
                <div class="flex items-center justify-between">
                  <h3 class="text-xl md:text-3xl font-bold text-white">Judul Ilustrasi</h3>
                  <!-- Tombol Like -->
                  <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110"
                    aria-label="Suka karya ini">
                    <svg xmlns="http://www.w3.org/2000/svg" class="md:h-10 md:w-10 w-6 h-6 text-white transition-colors duration-200"
                      fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </div>
                <p class="text-sm text-white">Kreator: Budi Setiawan</p>
                <p class="text-xs md:text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Et at, eligendi dolor magnam cupiditate
                  commodi iure temporibus officia nostrum consequuntur!</p>
              </div>
            </a>
            <a href="detailpameran.php"
              class="relative w-full aspect-square-container rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all text-white duration-300 block zoom-container group">
              <img src="./img/Lomba/Juara2resize.jpg" alt="Ilustrasi Karya 2"
                class="absolute inset-0 w-full h-full object-cover zoom-img">
              <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 to-transparent flex flex-col justify-end p-4 gap-3">
                <div class="flex items-center justify-between">
                  <h3 class="text-xl md:text-3xl font-bold text-white">Judul Ilustrasi</h3>
                  <!-- Tombol Like -->
                  <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110"
                    aria-label="Suka karya ini">
                    <svg xmlns="http://www.w3.org/2000/svg" class="md:h-10 md:w-10 w-6 h-6 text-white transition-colors duration-200"
                      fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </div>
                <p class="text-sm text-white">Kreator: Budi Setiawan</p>
                <p class="text-xs md:text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Et at, eligendi dolor magnam cupiditate
                  commodi iure temporibus officia nostrum consequuntur!</p>
              </div>
            </a>
            <a href="detailpameran.php"
              class="relative w-full aspect-square-container rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all text-white duration-300 block zoom-container group">
              <img src="./img/Lomba/Juara3resize.jpg" alt="Ilustrasi Karya 2"
                class="absolute inset-0 w-full h-full object-cover zoom-img">
              <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 to-transparent  flex flex-col justify-end p-4 gap-3">
                <div class="flex items-center justify-between">
                  <h3 class="text-xl md:text-3xl font-bold text-white">Judul Ilustrasi</h3>
                  <!-- Tombol Like -->
                  <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110"
                    aria-label="Suka karya ini">
                    <svg xmlns="http://www.w3.org/2000/svg" class="md:h-10 md:w-10 w-6 h-6 text-white transition-colors duration-200"
                      fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </div>
                <p class="text-sm text-white">Kreator: Budi Setiawan</p>
                <p class="text-xs md:text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Et at, eligendi dolor magnam cupiditate
                  commodi iure temporibus officia nostrum consequuntur!</p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>

  </section>


  <br><br><br>

  <?php
  require '_footer.php';
  ?>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const likeButtons = document.querySelectorAll('.like-button');

      likeButtons.forEach(button => {
        let isLiked = false;
        button.addEventListener('click', (event) => {
          // Mencegah navigasi ke halaman detail
          event.preventDefault();

          // Menghentikan penyebaran event ke elemen induk (tag <a>)
          event.stopPropagation();

          const heartIcon = button.querySelector('svg');

          // Mengubah status like
          isLiked = !isLiked;

          if (isLiked) {
            // Mengubah ikon menjadi terisi dan berwarna merah
            heartIcon.setAttribute('fill', 'currentColor');
            heartIcon.classList.remove('text-white');
            heartIcon.classList.add('text-red-500');
          } else {
            // Mengubah ikon menjadi kosong dan berwarna putih
            heartIcon.setAttribute('fill', 'none');
            heartIcon.classList.remove('text-red-500');
            heartIcon.classList.add('text-white');
          }
        });
      });
    });
  </script>
</body>

</html>