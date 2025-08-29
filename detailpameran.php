<!DOCTYPE html>
<html lang="en">

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
    /* Custom styles for an elegant detail page */
    .card-container {
      max-width: 1200px;
    }

    .main-image {
      /* object-fit: contain ensures the whole image is visible without being cropped */
      object-fit: cover;
    }

    .icon-btn {
      @apply flex items-center justify-center h-12 w-12 rounded-full transition-colors duration-200;
    }

    .icon-btn:hover {
      @apply bg-gray-200;
    }

    .social-btn {
      @apply text-gray-500 hover:text-blue-500 transition-colors duration-200;
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
    class="w-2/3 h-3/4 blur-3xl absolute -z-10 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
  </div>

  <!-- deskripsi karya
  <section class="flex">
  <div class="w-1/2">
    <img src="" alt="">
  </div>
  <div class="flex flex-col w-1/2">
    <h1></h1>
    <h2></h2>

    <div class="flex flex-row ">
      <div></div>
      <div class="flex justify-between">
        <div></div>
        <div></div>
      </div>
    </div>

    <p></p>
    
    <button></button>
  </div>
  </section> -->

  <br><br><br>



  <div class="card-container mx-auto rounded-2xl shadow-xl p-6 sm:p-10 w-10/12">
    <div class="flex flex-col md:flex-row gap-8 lg:gap-12">

      <!-- Left side: Artwork Image -->
      <div
        class="md:w-1/2 rounded-xl overflow-hidden shadow-lg p-4 bg-gray-200 flex items-center justify-center main-image-container">
        <img id="artwork-image" src="./img/Lomba/Juara1resize.jpg" alt="Artwork detail"
          class="w-full h-full main-image">
      </div>

      <!-- Right side: Details & Actions -->
      <div class="md:w-1/2 flex flex-col justify-between">
        <div>
          <!-- Title & Like Button -->
          <div class="flex items-start justify-between mb-2">
            <h1 id="artwork-title" class="text-3xl sm:text-4xl font-bold text-white leading-tight">Judul Karya</h1>
            <button id="like-button" class="icon-btn ml-4 transition-transform duration-300 hover:scale-110"
              aria-label="Suka karya ini">
              <svg id="like-icon" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
          </div>

          <!-- Creator name -->
          <p class="text-lg text-white mb-6">
            <span class="font-semibold">Oleh:</span> <span id="creator-name"
              class="text-blue-500 hover:underline cursor-pointer">Nama Pembuat</span>
          </p>

          <!-- Description -->
          <h2 class="text-xl font-semibold text-white mb-2">Deskripsi</h2>
          <p id="artwork-description" class="text-white leading-relaxed mb-6">
            Ini adalah deskripsi rinci dari karya seni. Deskripsi ini menjelaskan konsep di balik karya, inspirasi, dan
            teknik yang digunakan oleh pembuatnya. Deskripsi ini dapat diperbarui dengan data spesifik dari setiap
            karya.
          </p>

          <!-- Social Actions -->
          <div class="flex items-center space-x-4 border-t pt-4 mt-6">
            <!-- Comment Button (Placeholder) -->
            <button class="icon-btn group" aria-label="Komentar">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white group-hover:text-white hover:scale-110" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
            </button>

            <!-- Share Button (Placeholder) -->
            <button class="icon-btn group" aria-label="Bagikan">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white group-hover:text-white hover:scale-110" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8.684 13.342C8.882 13.123 9 12.872 9 12.617v-1.234c0-.255-.118-.506-.316-.725L5.293 7.293a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414l3.39-3.39z" />
                <path d="M18 10a2 2 0 11-4 0 2 2 0 014 0zM8 18a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </button>
          </div>
          <br><br>
          <button class="text-white bg-emerald-600 hover:bg-emerald-800 hover:scale-110 transition duration-300 p-5 px-8 rounded-2xl">
            See the artist!
          </button>
        </div>
      </div>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const likeButton = document.getElementById('like-button');
      const likeIcon = document.getElementById('like-icon');
      let isLiked = false;

      likeButton.addEventListener('click', () => {
        isLiked = !isLiked;
        if (isLiked) {
          likeIcon.setAttribute('fill', 'currentColor');
          likeIcon.classList.remove('text-white');
          likeIcon.classList.add('text-red-500');
        } else {
          likeIcon.setAttribute('fill', 'none');
          likeIcon.classList.remove('text-red-500');
          likeIcon.classList.add('text-white');
        }
      });
    });
  </script>
</body>

</html>