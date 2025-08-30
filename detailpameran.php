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

  <br><br><br><br><br><br>



  <div class="w-10/12 mx-auto">
    <p class="text-sm text-white/70"><a href="homepage.php">Homepage</a> / <a href="pameran.php">Pameran</a> / <span
        class="text-yellow-400 italic">Detail Karya</span></p>
  </div>



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
            <div>

              <a href="#comment">
                <button class="icon-btn group" aria-label="Komentar">
                  <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 text-white group-hover:text-white hover:scale-110" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                </button>
              </a>
            </div>

            <!-- Share Button (Placeholder) -->
            <div>

              <button id="sharebutton" class="icon-btn group" aria-label="Bagikan">
                <svg fill="#000000" class="h-8 w-8 text-white group-hover:text-white hover:scale-110"
                  viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" data-iconid="share-communication-arrow"
                  data-svgname="Share communication arrow">

                  <title fill="#fff" style=""></title>

                  <g data-name="Layer 2" id="Layer_2" fill="#fff" style="">

                    <path
                      d="M29.28,12.47,18.6,3.62a2,2,0,0,0-2.17-.27,2,2,0,0,0-1.15,1.81v2A19.82,19.82,0,0,0,2,25.94a19.18,19.18,0,0,0,.25,3.11,1,1,0,0,0,.82.83h.17a1,1,0,0,0,.88-.53,17.29,17.29,0,0,1,11.16-8.68v2.16a2,2,0,0,0,1.15,1.81,2.09,2.09,0,0,0,.88.2,2,2,0,0,0,1.29-.48l4.86-4,.09-.07,5.73-4.75a2,2,0,0,0,0-3.06Zm-6.93,6.2-.09.07-5,4.1V19.42a.19.19,0,0,0,0-.08s0-.06,0-.09,0-.07-.05-.11a1.34,1.34,0,0,0-.07-.18A.57.57,0,0,0,17,18.8a.49.49,0,0,0-.12-.13,1,1,0,0,0-.17-.12l-.15-.07-.22,0-.1,0-.08,0h-.09A19.19,19.19,0,0,0,4,25.85a17.81,17.81,0,0,1,12.56-17l.05,0a1.11,1.11,0,0,0,.19-.09A1.43,1.43,0,0,0,17,8.63l.12-.14a.54.54,0,0,0,.1-.16.85.85,0,0,0,.06-.17,1.3,1.3,0,0,0,0-.21.43.43,0,0,0,0,0l0-2.74L28,14Z"
                      fill="#fff" style=""></path>

                  </g>

                </svg>
              </button>
            </div>


            


          </div>

          <!-- share modal -->
            <div id="shareModal"
              class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-900 bg-opacity-75"
              style="display: none;">
              <div class="relative w-10/12 md:w-full max-w-sm mx-auto p-8 bg-white rounded-xl shadow-lg">
                <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
                  onclick="document.getElementById('shareModal').style.display = 'none';">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>

                <div class="text-center">
                  <h3 class="text-xl font-semibold text-gray-800 mb-6">Share this Art:</h3>

                  <div class="flex items-center justify-center space-x-3  md:space-x-6">
                    <a href="#" class="text-gray-400 hover:text-green-500 transition-colors">
                      <svg fill="#000000" class="md:w-16 md:h-16 w-10 h-10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                        data-name="Layer 1" data-iconid="whatsapp-alt" data-svgname="Whatsapp alt">
                        <path
                          d="M22,6.55a12.61,12.61,0,0,0-.1-1.29,4.29,4.29,0,0,0-.37-1.08,3.66,3.66,0,0,0-.71-1,3.91,3.91,0,0,0-1-.71,4.28,4.28,0,0,0-1.08-.36A10.21,10.21,0,0,0,17.46,2H6.55a12.61,12.61,0,0,0-1.29.1,4.29,4.29,0,0,0-1.08.37,3.66,3.66,0,0,0-1,.71,3.91,3.91,0,0,0-.71,1,4.28,4.28,0,0,0-.36,1.08A10.21,10.21,0,0,0,2,6.54C2,6.73,2,7,2,7.08v9.84c0,.11,0,.35,0,.53a12.61,12.61,0,0,0,.1,1.29,4.29,4.29,0,0,0,.37,1.08,3.66,3.66,0,0,0,.71,1,3.91,3.91,0,0,0,1,.71,4.28,4.28,0,0,0,1.08.36A10.21,10.21,0,0,0,6.54,22H17.45a12.61,12.61,0,0,0,1.29-.1,4.29,4.29,0,0,0,1.08-.37,3.66,3.66,0,0,0,1-.71,3.91,3.91,0,0,0,.71-1,4.28,4.28,0,0,0,.36-1.08A10.21,10.21,0,0,0,22,17.46c0-.19,0-.43,0-.54V7.08C22,7,22,6.73,22,6.55ZM12.23,19h0A7.12,7.12,0,0,1,8.8,18.1L5,19.1l1-3.72a7.11,7.11,0,0,1-1-3.58A7.18,7.18,0,1,1,12.23,19Zm0-13.13A6,6,0,0,0,7.18,15l.14.23-.6,2.19L9,16.8l.22.13a6,6,0,0,0,3,.83h0a6,6,0,0,0,6-6,6,6,0,0,0-6-6Zm3.5,8.52a1.82,1.82,0,0,1-1.21.85,2.33,2.33,0,0,1-1.12-.07,8.9,8.9,0,0,1-1-.38,8,8,0,0,1-3.06-2.7,3.48,3.48,0,0,1-.73-1.85,2,2,0,0,1,.63-1.5.65.65,0,0,1,.48-.22H10c.11,0,.26,0,.4.31s.51,1.24.56,1.33a.34.34,0,0,1,0,.31,1.14,1.14,0,0,1-.18.3c-.09.11-.19.24-.27.32s-.18.18-.08.36a5.56,5.56,0,0,0,1,1.24,5,5,0,0,0,1.44.89c.18.09.29.08.39,0s.45-.52.57-.7.24-.15.4-.09,1.05.49,1.23.58.29.13.34.21A1.56,1.56,0,0,1,15.73,14.36Z"
                          fill="#000" style=""></path>
                      </svg>
                    </a>

                    <a href="#" class="text-gray-400 hover:text-gray-800 transition-colors">
                      <svg fill="#000000" class="md:w-16 md:h-16 w-10 h-10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                        data-iconid="brand-twitter-sq" data-svgname="brand twitter sq">
                        <path
                          d="M21,2H3A1,1,0,0,0,2,3V21a1,1,0,0,0,1,1H21a1,1,0,0,0,1-1V3A1,1,0,0,0,21,2ZM17.1,9.386c.005.113.008.226.008.34a7.481,7.481,0,0,1-11.518,6.3,5.216,5.216,0,0,0,.628.038,5.276,5.276,0,0,0,3.267-1.127,2.633,2.633,0,0,1-2.457-1.826,2.689,2.689,0,0,0,.5.046,2.64,2.64,0,0,0,.693-.092,2.633,2.633,0,0,1-2.11-2.58v-.033a2.621,2.621,0,0,0,1.192.329,2.631,2.631,0,0,1-.814-3.512A7.471,7.471,0,0,0,11.9,10.02a2.632,2.632,0,0,1,4.483-2.4,5.265,5.265,0,0,0,1.671-.639A2.642,2.642,0,0,1,16.9,8.437a5.276,5.276,0,0,0,1.511-.415A5.376,5.376,0,0,1,17.1,9.386Z">
                        </path>
                      </svg>
                    </a>

                    <a href="#" class="text-gray-400 hover:text-pink-500 transition-colors">
                      <svg fill="#000000" class="md:w-16 md:h-16 w-10 h-10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                        data-name="Layer 1" data-iconid="instagram-alt" data-svgname="Instagram alt">
                        <path
                          d="M12,9.52A2.48,2.48,0,1,0,14.48,12,2.48,2.48,0,0,0,12,9.52Zm9.93-2.45a6.53,6.53,0,0,0-.42-2.26,4,4,0,0,0-2.32-2.32,6.53,6.53,0,0,0-2.26-.42C15.64,2,15.26,2,12,2s-3.64,0-4.93.07a6.53,6.53,0,0,0-2.26.42A4,4,0,0,0,2.49,4.81a6.53,6.53,0,0,0-.42,2.26C2,8.36,2,8.74,2,12s0,3.64.07,4.93a6.86,6.86,0,0,0,.42,2.27,3.94,3.94,0,0,0,.91,1.4,3.89,3.89,0,0,0,1.41.91,6.53,6.53,0,0,0,2.26.42C8.36,22,8.74,22,12,22s3.64,0,4.93-.07a6.53,6.53,0,0,0,2.26-.42,3.89,3.89,0,0,0,1.41-.91,3.94,3.94,0,0,0,.91-1.4,6.6,6.6,0,0,0,.42-2.27C22,15.64,22,15.26,22,12S22,8.36,21.93,7.07Zm-2.54,8A5.73,5.73,0,0,1,19,16.87,3.86,3.86,0,0,1,16.87,19a5.73,5.73,0,0,1-1.81.35c-.79,0-1,0-3.06,0s-2.27,0-3.06,0A5.73,5.73,0,0,1,7.13,19a3.51,3.51,0,0,1-1.31-.86A3.51,3.51,0,0,1,5,16.87a5.49,5.49,0,0,1-.34-1.81c0-.79,0-1,0-3.06s0-2.27,0-3.06A5.49,5.49,0,0,1,5,7.13a3.51,3.51,0,0,1,.86-1.31A3.59,3.59,0,0,1,7.13,5a5.73,5.73,0,0,1,1.81-.35h0c.79,0,1,0,3.06,0s2.27,0,3.06,0A5.73,5.73,0,0,1,16.87,5a3.51,3.51,0,0,1,1.31.86A3.51,3.51,0,0,1,19,7.13a5.73,5.73,0,0,1,.35,1.81c0,.79,0,1,0,3.06S19.42,14.27,19.39,15.06Zm-1.6-7.44a2.38,2.38,0,0,0-1.41-1.41A4,4,0,0,0,15,6c-.78,0-1,0-3,0s-2.22,0-3,0a4,4,0,0,0-1.38.26A2.38,2.38,0,0,0,6.21,7.62,4.27,4.27,0,0,0,6,9c0,.78,0,1,0,3s0,2.22,0,3a4.27,4.27,0,0,0,.26,1.38,2.38,2.38,0,0,0,1.41,1.41A4.27,4.27,0,0,0,9,18.05H9c.78,0,1,0,3,0s2.22,0,3,0a4,4,0,0,0,1.38-.26,2.38,2.38,0,0,0,1.41-1.41A4,4,0,0,0,18.05,15c0-.78,0-1,0-3s0-2.22,0-3A3.78,3.78,0,0,0,17.79,7.62ZM12,15.82A3.81,3.81,0,0,1,8.19,12h0A3.82,3.82,0,1,1,12,15.82Zm4-6.89a.9.9,0,0,1,0-1.79h0a.9.9,0,0,1,0,1.79Z">
                        </path>
                      </svg>
                    </a>

                    <a href="#" class="text-gray-400 hover:text-blue-700 transition-colors">
                      <svg fill="#000000" version="1.1" id="icon" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" class="md:w-16 md:h-16 w-10 h-10" viewBox="0 0 32 32"
                        xml:space="preserve" data-iconid="logo-linkedin" data-svgname="Logo linkedin">
                        <style type="text/css">
                          .st0 {
                            fill: none;
                          }
                        </style>
                        <path d="M26.2,4H5.8C4.8,4,4,4.8,4,5.7v20.5c0,0.9,0.8,1.7,1.8,1.7h20.4c1,0,1.8-0.8,1.8-1.7V5.7C28,4.8,27.2,4,26.2,4z M11.1,24.4
                              H7.6V13h3.5V24.4z M9.4,11.4c-1.1,0-2.1-0.9-2.1-2.1c0-1.2,0.9-2.1,2.1-2.1c1.1,0,2.1,0.9,2.1,2.1S10.5,11.4,9.4,11.4z M24.5,24.3
                              H21v-5.6c0-1.3,0-3.1-1.9-3.1c-1.9,0-2.1,1.5-2.1,2.9v5.7h-3.5V13h3.3v1.5h0.1c0.5-0.9,1.7-1.9,3.4-1.9c3.6,0,4.3,2.4,4.3,5.5V24.3z
                              "></path>
                        <rect id="_x3C_Transparent_Rectangle_x3E_" y="0" class="st0" width="32" height="32"></rect>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <br><br>
          <div class="flex w-full justify-center md:justify-start">

            <button
              class=" text-white bg-emerald-600 hover:bg-emerald-800 hover:scale-110 transition duration-300 md:p-5 md:px-8 p-3 px-5 text-sm md:text-base rounded-2xl">
              See the artist!
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>


  <!-- Comment Section -->

  <section id="comment" class="flex flex-col justify-center items-center md:w-10/12 w-full mx-auto text-white py-8">
    <h1 class="font-chill text-3xl font-bold mb-6">Comments</h1>
    <form class="w-full space-y-4">
      <div class="flex">
        <svg class="w-20 h-20 pr-5 items-center hidden md:flex" viewBox="0 0 24 24" fill="none"
          xmlns="http://www.w3.org/2000/svg" data-iconid="comment-line" data-svgname="Comment line">
          <path
            d="M19 4H5C3.89543 4 3 4.89543 3 6V18V21L6.46667 18.4C6.81286 18.1404 7.23393 18 7.66667 18H19C20.1046 18 21 17.1046 21 16V6C21 4.89543 20.1046 4 19 4Z"
            stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style=""></path>
        </svg>
        <input id="modalbutton" placeholder="Tulis Komentar..."
          class="w-10/12 mx-auto md:w-full py-4 pl-4 text-base bg-neutral-800  bg-opacity-50 text-white font-chill rounded-2xl focus:outline-none transition resize-none"></input>
      </div>
    </form>


    <!-- modal comment -->
    <div id="commentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-900 bg-opacity-75"
      style="display: none;">
      <div class="relative w-10/12 md:w-8/12 mx-auto md:p-12 p-4 bg-white rounded-xl shadow-lg">
        <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
          onclick="document.getElementById('commentModal').style.display = 'none';">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <div class="text-center">
          <h3 class="text-lg md:text-xl font-semibold text-gray-800 md:mb-6 mb-3 pt-8">Add your Comment here</h3>
          <textarea
            class=" text-sm md:text-base w-full h-24 md:h-64 p-4 mb-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all resize-none"
            placeholder="Add your comment..."></textarea>
          <button
            class="w-6/12 md:w-4/12 mx-auto px-6 py-3 text-white font-medium bg-emerald-600 rounded-lg hover:bg-emerald-800 transition-colors">
            Send
          </button>
        </div>
      </div>
    </div>
  </section>


  <!-- Comment history -->
  <div class="space-y-6 text-white p-6 rounded-3xl w-10/12 mx-auto border">

    <div id="commentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
      style="display: none;">
      <div class="relative w-full max-w-xl mx-auto p-8 bg-white rounded-xl shadow-lg">
        <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
          onclick="document.getElementById('commentModal').style.display = 'none';">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <div class="text-center">
          <h3 class="text-xl font-semibold text-gray-800 mb-2">Add your Comment here</h3>
          <span id="replyToText" class="text-sm text-gray-500 mb-4 block"></span>
          <textarea id="commentInput"
            class="w-full h-32 p-4 mb-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all resize-none"
            placeholder="Add your comment..."></textarea>
          <button
            class="w-full px-6 py-3 text-white font-medium bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
            Send
          </button>
        </div>
      </div>
    </div>

    <div class="flex items-start space-x-4">
      <div class="flex-shrink-0 md:w-14 md:h-14 w-10 h-10 bg-gray-600 rounded-full"></div>
      <div>
        <div class="flex flex-col md:flex-row md:space-x-2 md:items-center text-lg text-gray-300">
          <span class="font-semibold text-white">Adit Ramadhan</span>
          <span class="text-xs">10:21 · 21 Juli 2025</span>
        </div>
        <p class="mt-2 text-gray-200 text-sm md:text-base">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nisi arcu, lobortis quis ligula vel, accumsan
          congue diam. Nullam porta enim ut tristique fermentum. Sed vestibulum sit amet arcu eu sodales.
        </p>
        <div class="mt-2 flex items-center space-x-4 text-sm">
          <button id="like-btn-adit"
            class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none flex items-center space-x-1"
            onclick="toggleLike('like-btn-adit', 'like-count-adit')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span id="like-count-adit">0</span>
          </button>
          <button id="reply-btn-adit"
            class="text-gray-400 hover:text-white transition-colors focus:outline-none flex items-center space-x-1"
            data-username="Adit Ramadhan" onclick="openReplyModal(this.getAttribute('data-username'))">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span>Reply</span>
          </button>
        </div>
      </div>
    </div>
    <br>
    <div class="flex items-start space-x-4 ml-16">
      <div class="flex-shrink-0 md:w-14 md:h-14 w-10 h-10 bg-gray-600 rounded-full"></div>
      <div>
        <div class="flex items-centertext-lg text-gray-300">
          <div class="flex flex-col md:flex-row md:space-x-2">
            <span class="font-semibold text-white">Denis</span>
            <div>
              <span class="text-xs">10:21 · 21 Juli 2025</span>
              <span class="text-xs text-gray-500">| Replying to Adit</span>
            </div>
          </div>
        </div>
        <p class="mt-2 text-gray-200 text-sm md:text-base">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nisi arcu, lobortis quis ligula vel, accumsan
          congue diam. Nullam porta enim ut tristique fermentum.
        </p>
        <div class="mt-2 flex items-center space-x-4 text-sm">
          <button id="like-btn-denis"
            class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none flex items-center space-x-1"
            onclick="toggleLike('like-btn-denis', 'like-count-denis')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span id="like-count-denis">0</span>
          </button>
          <button id="reply-btn-denis"
            class="text-gray-400 hover:text-white transition-colors focus:outline-none flex items-center space-x-1"
            data-username="Denis" onclick="openReplyModal(this.getAttribute('data-username'))">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span>Reply</span>
          </button>
        </div>
      </div>
    </div>

  </div>
  </div>
  <!-- Comment history akhir -->

  <br /><br /><br />

  <!-- you might like -->
  <section class="font-sans p-8">
    <div class="flex w-full  md:w-10/12 mx-auto justify-between items-center text-white text-3xl md:text-5xl font-bold pb-2 border-b">
      <h1>More Like This</h1>
      <a href="pameran.php" class="hover:scale-125 transition-transform duration-300">
        <svg class="w-16 h-16" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" data-iconid="right"
          data-svgname="Right">
          <rect width="48" height="48" fill="#fff" fill-opacity="0.01" style=""></rect>
          <path d="M19 12L31 24L19 36" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"
            style=""></path>
        </svg>
      </a>
    </div>
    <div class="container mx-auto">

    <br><br>

      <!-- Container untuk semua grid -->
      <div class="space-y-12">

        <!-- Kategori 1: Ilustrasi -->
        <div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href=""
              class="relative w-full aspect-square-container rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all text-white duration-300 block zoom-container group">
              <img src="./img/Lomba/deschar1resize.jpg" alt="Ilustrasi Karya 2"
                class="absolute inset-0 w-full h-full object-cover zoom-img">
              <div
                class="absolute inset-0 bg-gradient-to-t from-neutral-950 to-transparent  flex flex-col justify-end p-4 gap-3">
                <div class="flex items-center justify-between">
                  <h3 class="text-3xl font-bold text-white">Judul Ilustrasi</h3>
                  <!-- Tombol Like -->
                  <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110"
                    aria-label="Suka karya ini">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white transition-colors duration-200"
                      fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </div>
                <p class="text-sm text-white">Kreator: Budi Setiawan</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et at, eligendi dolor magnam cupiditate
                  commodi iure temporibus officia nostrum consequuntur!</p>
              </div>
            </a>
            <a href=""
              class="relative w-full aspect-square-container rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all text-white duration-300 block zoom-container group">
              <img src="./img/Lomba/Juara2resize.jpg" alt="Ilustrasi Karya 2"
                class="absolute inset-0 w-full h-full object-cover zoom-img">
              <div
                class="absolute inset-0 bg-gradient-to-t from-neutral-950 to-transparent flex flex-col justify-end p-4 gap-3">
                <div class="flex items-center justify-between">
                  <h3 class="text-3xl font-bold text-white">Judul Ilustrasi</h3>
                  <!-- Tombol Like -->
                  <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110"
                    aria-label="Suka karya ini">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white transition-colors duration-200"
                      fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </div>
                <p class="text-sm text-white">Kreator: Budi Setiawan</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et at, eligendi dolor magnam cupiditate
                  commodi iure temporibus officia nostrum consequuntur!</p>
              </div>
            </a>
            <a href=""
              class="relative w-full aspect-square-container rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all text-white duration-300 block zoom-container group">
              <img src="./img/Lomba/Juara3resize.jpg" alt="Ilustrasi Karya 2"
                class="absolute inset-0 w-full h-full object-cover zoom-img">
              <div
                class="absolute inset-0 bg-gradient-to-t from-neutral-950 to-transparent  flex flex-col justify-end p-4 gap-3">
                <div class="flex items-center justify-between">
                  <h3 class="text-3xl font-bold text-white">Judul Ilustrasi</h3>
                  <!-- Tombol Like -->
                  <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110"
                    aria-label="Suka karya ini">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white transition-colors duration-200"
                      fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </div>
                <p class="text-sm text-white">Kreator: Budi Setiawan</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et at, eligendi dolor magnam cupiditate
                  commodi iure temporibus officia nostrum consequuntur!</p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>

  </section>

  <?php
  require '_footer.php';
  ?>

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

    // tampilkan modal
    document.getElementById('modalbutton').addEventListener('click', function () {
      document.getElementById('commentModal').style.display = 'flex';
    });
    document.getElementById('sharebutton').addEventListener('click', function () {
      document.getElementById('shareModal').style.display = 'flex';
    });


    // reply dan like
    function toggleLike(buttonId, countId) {
      const button = document.getElementById(buttonId);
      const countSpan = document.getElementById(countId);
      let count = parseInt(countSpan.textContent);

      if (button.classList.contains('liked')) {
        // If already liked, unlike it
        button.classList.remove('liked', 'text-red-500');
        button.classList.add('text-gray-400');
        count--;
      } else {
        // If not liked, like it
        button.classList.add('liked', 'text-red-500');
        button.classList.remove('text-gray-400');
        count++;
      }

      countSpan.textContent = count;
    }

    function openReplyModal(username) {
      const modal = document.getElementById('commentModal');
      const replyToText = document.getElementById('replyToText');
      const commentInput = document.getElementById('commentInput');

      // Set the reply-to text
      replyToText.textContent = `Replying to ${username}`;

      // Show the modal
      modal.style.display = 'flex';

      // Focus the input field for user convenience
      commentInput.focus();
    }

  </script>
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