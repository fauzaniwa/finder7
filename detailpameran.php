<?php
session_start();
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

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

  <br><br><br><br><br><br>

  <section>
    <div class="w-10/12 mx-auto">
      <p class="text-sm text-white/70"><a href="homepage.php">Homepage</a> / <a href="pameran.php">Pameran</a> / <span
          class="text-yellow-400 italic">Detail Karya</span></p>
    </div>

    <div class="card-container mx-auto rounded-2xl shadow-xl p-6 sm:p-10 w-10/12 mt-6">
      <div class="flex flex-col md:flex-row gap-8 lg:gap-12">
        <div id="media-container"
          class="md:w-1/2 h-full rounded-xl overflow-hidden shadow-lg p-4 bg-gray-200 flex items-center justify-center main-image-container">
          <p class="text-gray-500">Memuat...</p>
        </div>

        <div class="md:w-1/2 flex flex-col justify-between">
          <div>
            <div class="flex items-start justify-between mb-2">
              <h1 id="artwork-title" class="text-3xl sm:text-4xl font-bold text-white leading-tight"></h1>
              <button id="like-button" class="icon-btn ml-4 transition-transform duration-300 hover:scale-110" aria-label="Suka karya ini" data-idkarya="">
                <div class="flex flex-col items-center">
                    <i id="like-icon" class="bi bi-heart text-white text-2xl h-8 w-8"></i>
                    <span id="likes-count" class="text-white text-xs mt-1"></span>
                </div>
              </button>
            </div>

            <p class="text-lg text-white mb-6">
              <span class="font-semibold">Oleh:</span> <span id="creator-name"
                class="text-blue-500 hover:underline cursor-pointer"></span>
            </p>

            <h2 class="text-xl font-semibold text-white mb-2">Deskripsi</h2>
            <p id="artwork-description" class="text-white leading-relaxed mb-6"></p>

            <div class="flex items-center space-x-4 border-t pt-4 mt-6">
              <div>
                <a href="#comment">
                  <button class="icon-btn group" aria-label="Komentar">
                    <i class="bi bi-chat-dots-fill h-8 w-8 text-white group-hover:text-white hover:scale-110"></i>
                  </button>
                </a>
              </div>

              <div>
                <button id="sharebutton" class="icon-btn group" aria-label="Bagikan">
                  <i class="bi bi-share-fill h-8 w-8 text-white group-hover:text-white hover:scale-110"></i>
                </button>
              </div>
            </div>

            <div id="shareModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-900 bg-opacity-75"
                style="display: none;">
                <div class="relative w-10/12 md:w-full max-w-sm mx-auto p-8 bg-white rounded-xl shadow-lg">
                  <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="document.getElementById('shareModal').style.display = 'none';">
                    <i class="bi bi-x-lg text-2xl"></i>
                  </button>

                  <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Share this Art:</h3>

                    <div class="flex items-center justify-center space-x-3  md:space-x-6">
                      <a href="#" class="text-gray-400 hover:text-green-500 transition-colors">
                        <i class="bi bi-whatsapp md:text-5xl text-4xl"></i>
                      </a>

                      <a href="#" class="text-gray-400 hover:text-gray-800 transition-colors">
                        <i class="bi bi-twitter-x md:text-5xl text-4xl"></i>
                      </a>

                      <a href="#" class="text-gray-400 hover:text-pink-500 transition-colors">
                        <i class="bi bi-instagram md:text-5xl text-4xl"></i>
                      </a>

                      <a href="#" class="text-gray-400 hover:text-blue-700 transition-colors">
                        <i class="bi bi-linkedin md:text-5xl text-4xl"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <br><br>
            <div class="flex w-full justify-center md:justify-start">

              <button
                class=" text-black hover:text-white bg-emerald-500 hover:bg-emerald-600 hover:scale-110 transition duration-300 md:p-5 md:px-8 p-3 px-5 text-sm md:text-base rounded-2xl font-semibold">
                See the artist!
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <section id="comment" class="flex flex-col justify-center items-center md:w-10/12 w-full mx-auto text-white py-8">
    <h1 class="font-chill text-3xl font-bold mb-6">Comments</h1>
    <form class="w-full space-y-4">
      <div class="flex">
        <i class="bi bi-chat-dots-fill w-20 h-20 pr-5 items-center hidden md:flex text-white"></i>
        <input id="modalbutton" placeholder="Tulis Komentar..."
          class="w-10/12 mx-auto md:w-full py-4 pl-4 text-base bg-neutral-800  bg-opacity-50 text-white font-chill rounded-2xl focus:outline-none transition resize-none"></input>
      </div>
    </form>


    <div id="commentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-900 bg-opacity-75"
      style="display: none;">
      <div class="relative w-10/12 md:w-8/12 mx-auto md:p-12 p-4 bg-white rounded-xl shadow-lg">
        <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
          onclick="document.getElementById('commentModal').style.display = 'none';">
          <i class="bi bi-x-lg h-6 w-6"></i>
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


  <div class="space-y-6 text-white p-6 rounded-3xl w-10/12 mx-auto border">

    <div id="commentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
      style="display: none;">
      <div class="relative w-full max-w-xl mx-auto p-8 bg-white rounded-xl shadow-lg">
        <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
          onclick="document.getElementById('commentModal').style.display = 'none';">
          <i class="bi bi-x-lg h-6 w-6"></i>
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
            <i class="bi bi-heart h-4 w-4"></i>
            <span id="like-count-adit">0</span>
          </button>
          <button id="reply-btn-adit"
            class="text-gray-400 hover:text-white transition-colors focus:outline-none flex items-center space-x-1"
            data-username="Adit Ramadhan" onclick="openReplyModal(this.getAttribute('data-username'))">
            <i class="bi bi-chat-dots-fill h-4 w-4"></i>
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
            <i class="bi bi-heart h-4 w-4"></i>
            <span id="like-count-denis">0</span>
          </button>
          <button id="reply-btn-denis"
            class="text-gray-400 hover:text-white transition-colors focus:outline-none flex items-center space-x-1"
            data-username="Denis" onclick="openReplyModal(this.getAttribute('data-username'))">
            <i class="bi bi-chat-dots-fill h-4 w-4"></i>
            <span>Reply</span>
          </button>
        </div>
      </div>
    </div>

  </div>
  </div>
  <br /><br /><br />

  <section class="font-sans p-8">
    <div class="flex w-full  md:w-10/12 mx-auto justify-between items-center text-white text-3xl md:text-5xl font-bold pb-2 border-b">
      <h1>More Like This</h1>
      <a href="pameran.php" class="hover:scale-125 transition-transform duration-300">
        <i class="bi bi-arrow-right h-16 w-16 text-white"></i>
      </a>
    </div>
    <div class="container mx-auto">

    <br><br>

      <div class="space-y-12">

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
                  <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110"
                    aria-label="Suka karya ini">
                    <i class="bi bi-heart h-10 w-10 text-white transition-colors duration-200"></i>
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
                  <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110"
                    aria-label="Suka karya ini">
                    <i class="bi bi-heart h-10 w-10 text-white transition-colors duration-200"></i>
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
                  <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110"
                    aria-label="Suka karya ini">
                    <i class="bi bi-heart h-10 w-10 text-white transition-colors duration-200"></i>
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
      const urlParams = new URLSearchParams(window.location.search);
      const artworkSlug = urlParams.get('karya');
      const mediaContainer = document.getElementById('media-container');
      const artworkTitle = document.getElementById('artwork-title');
      const creatorName = document.getElementById('creator-name');
      const artworkDescription = document.getElementById('artwork-description');
      const likeButton = document.getElementById('like-button');
      const likeIcon = document.getElementById('like-icon');
      const likesCountSpan = document.getElementById('likes-count');

      if (artworkSlug) {
        fetch(`get_detailpameran.php?karya=${artworkSlug}`)
          .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.error);
                });
            }
            return response.json();
          })
          .then(data => {
            if (data.error) {
                mediaContainer.innerHTML = `<p class='text-center text-red-500 mt-10'>${data.error}</p>`;
                return;
            }

            // Memperbarui konten HTML
            artworkTitle.textContent = data.judul_karya;
            creatorName.textContent = data.nama_karya;
            artworkDescription.textContent = data.deskripsi;
            likeButton.dataset.idkarya = data.id_karya;
            likesCountSpan.textContent = `${data.likes_count} Likes`;

            if (data.user_liked > 0) {
              likeIcon.classList.remove('bi-heart');
              likeIcon.classList.add('bi-heart-fill', 'text-red-500');
            } else {
              likeIcon.classList.remove('bi-heart-fill', 'text-red-500');
              likeIcon.classList.add('bi-heart');
            }

            // Memeriksa jenis file dan menampilkan media yang sesuai
            const fileExtension = data.pict_karya.split('.').pop().toLowerCase();
            const videoExtensions = ['mp4', 'webm', 'mov'];
            if (videoExtensions.includes(fileExtension)) {
                mediaContainer.innerHTML = `
                    <video id="artwork-video" controls autoplay loop muted class="w-full h-full object-cover">
                        <source src="./img/Karya/${data.pict_karya}" type="video/${fileExtension}">
                        Your browser does not support the video tag.
                    </video>`;
            } else {
                mediaContainer.innerHTML = `
                    <img id="artwork-image" src="./img/Karya/${data.pict_karya}" alt="${data.judul_karya}"
                        class="w-full h-full main-image object-contain">`;
            }

          })
          .catch(error => {
            mediaContainer.innerHTML = `<p class='text-center text-red-500 mt-10'>Terjadi kesalahan: ${error.message}</p>`;
            console.error('Error:', error);
          });
      } else {
        mediaContainer.innerHTML = `<p class='text-center text-red-500 mt-10'>Slug karya tidak valid.</p>`;
      }
      
      const loggedIn = <?= json_encode(isset($_SESSION['user_id'])); ?>;
      likeButton.addEventListener('click', async () => {
        if (!loggedIn) {
            alert('Anda harus login untuk menyukai karya ini.');
            window.location.href = 'login.php';
            return;
        }

        const artworkId = likeButton.dataset.idkarya;

        try {
          const response = await fetch('like_handler.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ idKarya: artworkId }),
          });

          const data = await response.json();

          if (data.success) {
            likesCountSpan.textContent = `${data.likes} Likes`;
            if (data.action === 'liked') {
              likeIcon.classList.remove('bi-heart');
              likeIcon.classList.add('bi-heart-fill', 'text-red-500');
            } else {
              likeIcon.classList.remove('bi-heart-fill', 'text-red-500');
              likeIcon.classList.add('bi-heart');
            }
          } else {
            alert(data.message);
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan. Silakan coba lagi.');
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
      const heartIcon = button.querySelector('i');

      if (button.classList.contains('liked')) {
        // If already liked, unlike it
        button.classList.remove('liked', 'text-red-500');
        button.classList.add('text-gray-400');
        heartIcon.classList.remove('bi-heart-fill');
        heartIcon.classList.add('bi-heart');
        count--;
      } else {
        // If not liked, like it
        button.classList.add('liked', 'text-red-500');
        button.classList.remove('text-gray-400');
        heartIcon.classList.remove('bi-heart');
        heartIcon.classList.add('bi-heart-fill');
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

          const heartIcon = button.querySelector('i');

          // Mengubah status like
          isLiked = !isLiked;

          if (isLiked) {
            // Mengubah ikon menjadi terisi dan berwarna merah
            heartIcon.classList.remove('bi-heart', 'text-white');
            heartIcon.classList.add('bi-heart-fill', 'text-red-500');
          } else {
            // Mengubah ikon menjadi kosong dan berwarna putih
            heartIcon.classList.remove('bi-heart-fill', 'text-red-500');
            heartIcon.classList.add('bi-heart', 'text-white');
          }
        });
      });
    });
  </script>
</body>

</html>