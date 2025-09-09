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
          <span id="replyToText" class="text-sm text-gray-500 mb-4 block"></span>
          <textarea id="commentInput"
            class=" text-sm md:text-base w-full h-24 md:h-64 p-4 mb-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all resize-none"
            placeholder="Add your comment..."></textarea>
            <input type="hidden" id="parent-id">
          <button id="send-comment-button"
            class="w-6/12 md:w-4/12 mx-auto px-6 py-3 text-white font-medium bg-emerald-600 rounded-lg hover:bg-emerald-800 transition-colors">
            Send
          </button>
        </div>
      </div>
    </div>
  </section>

  <div id="comments-container" class="space-y-6 text-white p-6 rounded-3xl w-10/12 mx-auto border">
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
      <div id="more-like-this-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        </div>
    </div>
  </section>
  
  <div id="notificationModal"
      class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center p-4 hidden">
      <div
        class="bg-white rounded-2xl p-8 max-w-md w-full text-center relative transform transition-all scale-95 opacity-0">

        <button type="button" id="closeNotificationModalBtn"
          class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>

        <h2 id="modalTitle" class="text-2xl font-bold mb-4"></h2>

        <p id="modalMessage" class="text-gray-700 mb-6"></p>

        <a id="modalButton" href="#"
          class="inline-block bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-2xl text-lg transition-all">
          Lanjutkan
        </a>

      </div>
  </div>

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
      const commentsContainer = document.getElementById('comments-container');
      const sendCommentButton = document.getElementById('send-comment-button');
      const commentInput = document.getElementById('commentInput');
      const parentIdInput = document.getElementById('parent-id');
      const notificationModal = document.getElementById('notificationModal');
      const modalTitle = document.getElementById('modalTitle');
      const modalMessage = document.getElementById('modalMessage');
      const modalButton = document.getElementById('modalButton');
      const closeNotificationModalBtn = document.getElementById('closeNotificationModalBtn');
      const moreLikeThisContainer = document.getElementById('more-like-this-container');


      const loggedIn = <?= json_encode(isset($_SESSION['user_id'])); ?>;
      const currentUserId = <?= json_encode($_SESSION['user_id'] ?? null); ?>;

      function showNotificationModal(title, message, buttonText, buttonLink) {
          modalTitle.textContent = title;
          modalMessage.textContent = message;
          modalButton.textContent = buttonText;
          modalButton.href = buttonLink;
          notificationModal.classList.remove('hidden');
          setTimeout(() => {
              notificationModal.querySelector('div').classList.remove('scale-95', 'opacity-0');
              notificationModal.querySelector('div').classList.add('scale-100', 'opacity-100');
          }, 10);
      }

      function hideNotificationModal() {
          notificationModal.querySelector('div').classList.remove('scale-100', 'opacity-100');
          notificationModal.querySelector('div').classList.add('scale-95', 'opacity-0');
          setTimeout(() => {
              notificationModal.classList.add('hidden');
          }, 300);
      }

      closeNotificationModalBtn.addEventListener('click', hideNotificationModal);
      notificationModal.addEventListener('click', (e) => {
          if (e.target === notificationModal) {
              hideNotificationModal();
          }
      });

      function fetchComments(artworkId) {
          fetch(`get_comments.php?id_karya=${artworkId}`)
              .then(response => response.json())
              .then(data => {
                  commentsContainer.innerHTML = '';
                  if (data.success) {
                      if (data.comments.length === 0 && data.display_message) {
                          commentsContainer.innerHTML = `<p class="text-center text-gray-400">${data.display_message}</p>`;
                      } else {
                          data.comments.forEach(comment => {
                              commentsContainer.appendChild(createCommentElement(comment, false));
                              if (comment.replies.length > 0) {
                                  comment.replies.forEach(reply => {
                                      commentsContainer.appendChild(createCommentElement(reply, true, comment.username));
                                  });
                              }
                          });
                      }
                  } else {
                      commentsContainer.innerHTML = `<p class="text-center text-red-500">${data.message}</p>`;
                  }
              })
              .catch(error => {
                  console.error('Error fetching comments:', error);
                  commentsContainer.innerHTML = `<p class="text-center text-red-500">Terjadi kesalahan saat memuat komentar.</p>`;
              });
      }

      function createCommentElement(comment, isReply = false, parentUsername = null) {
          const commentDiv = document.createElement('div');
          let classes = 'flex items-start space-x-4 p-4 border rounded-lg';
          if (isReply) {
              classes += ' ml-8 md:ml-16';
          }
          commentDiv.className = classes;
          commentDiv.innerHTML = `
              <div class="flex-shrink-0 md:w-14 md:h-14 w-10 h-10 bg-gray-600 rounded-full"></div>
              <div>
                  <div class="flex flex-col md:flex-row md:space-x-2 md:items-center text-lg text-gray-300">
                      <span class="font-semibold text-white">${comment.username}</span>
                      <span class="text-xs">${comment.created_at}</span>
                      ${isReply ? `<span class="text-xs text-gray-500">| Replying to ${parentUsername}</span>` : ''}
                  </div>
                  <p class="mt-2 text-gray-200 text-sm md:text-base">${comment.comment_text}</p>
                  <div class="mt-2 flex items-center space-x-4 text-sm">
                      <button id="like-btn-${comment.id_comment}"
                          class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none flex items-center space-x-1"
                          onclick="toggleLikeComment(${comment.id_comment}, 'like-btn-${comment.id_comment}', 'like-count-${comment.id_comment}')">
                          <i class="${comment.user_liked > 0 ? 'bi bi-heart-fill text-red-500' : 'bi bi-heart'} h-4 w-4"></i>
                          <span id="like-count-${comment.id_comment}">${comment.likes_count}</span>
                      </button>
                      <button id="reply-btn-${comment.id_comment}"
                          class="text-gray-400 hover:text-white transition-colors focus:outline-none flex items-center space-x-1"
                          data-username="${comment.username}"
                          data-commentid="${comment.id_comment}"
                          onclick="openReplyModal(this.getAttribute('data-username'), this.getAttribute('data-commentid'))">
                          <i class="bi bi-chat-dots-fill h-4 w-4"></i>
                          <span>Reply</span>
                      </button>
                  </div>
              </div>
          `;
          return commentDiv;
      }

      window.toggleLikeComment = function (commentId, buttonId, countId) {
          if (!loggedIn) {
              showNotificationModal('Login Diperlukan', 'Anda harus login untuk menyukai komentar ini.', 'Login Sekarang', 'login.php');
              return;
          }

          const button = document.getElementById(buttonId);
          const countSpan = document.getElementById(countId);
          const heartIcon = button.querySelector('i');

          fetch('like_comment_handler.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ idKomentar: commentId }),
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  countSpan.textContent = data.likes;
                  if (data.action === 'liked') {
                      heartIcon.classList.remove('bi-heart');
                      heartIcon.classList.add('bi-heart-fill', 'text-red-500');
                  } else {
                      heartIcon.classList.remove('bi-heart-fill', 'text-red-500');
                      heartIcon.classList.add('bi-heart');
                  }
              } else {
                  alert(data.message);
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan. Silakan coba lagi.');
          });
      }

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

            // Update HTML content
            const artworkData = data;
            artworkTitle.textContent = artworkData.judul_karya;
            creatorName.textContent = artworkData.nama_karya;
            artworkDescription.textContent = artworkData.deskripsi;
            likeButton.dataset.idkarya = artworkData.id_karya;
            likesCountSpan.textContent = `${artworkData.likes_count} Likes`;

            if (artworkData.user_liked > 0) {
              likeIcon.classList.remove('bi-heart');
              likeIcon.classList.add('bi-heart-fill', 'text-red-500');
            } else {
              likeIcon.classList.remove('bi-heart-fill', 'text-red-500');
              likeIcon.classList.add('bi-heart');
            }

            // Check file type and display the corresponding media
            const fileExtension = artworkData.pict_karya.split('.').pop().toLowerCase();
            const videoExtensions = ['mp4', 'webm', 'mov'];
            if (videoExtensions.includes(fileExtension)) {
                mediaContainer.innerHTML = `
                    <video id="artwork-video" controls autoplay loop muted class="w-full h-full object-cover">
                        <source src="./img/Karya/${artworkData.pict_karya}" type="video/${fileExtension}">
                        Your browser does not support the video tag.
                    </video>`;
            } else {
                mediaContainer.innerHTML = `
                    <img id="artwork-image" src="./img/Karya/${artworkData.pict_karya}" alt="${artworkData.judul_karya}"
                        class="w-full h-full main-image object-contain">`;
            }

            // Fetch comments after loading artwork details
            fetchComments(artworkData.id_karya);
            fetchRandomArtworks();

          })
          .catch(error => {
            mediaContainer.innerHTML = `<p class='text-center text-red-500 mt-10'>Terjadi kesalahan: ${error.message}</p>`;
            console.error('Error:', error);
          });
      } else {
        mediaContainer.innerHTML = `<p class='text-center text-red-500 mt-10'>Slug karya tidak valid.</p>`;
      }
      
      likeButton.addEventListener('click', async () => {
        if (!loggedIn) {
          showNotificationModal('Login Diperlukan', 'Anda harus login untuk menyukai karya ini.', 'Login Sekarang', 'login.php');
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

      // Show comment modal
      document.getElementById('modalbutton').addEventListener('click', function () {
        if (!loggedIn) {
          showNotificationModal('Login Diperlukan', 'Anda harus login untuk berkomentar.', 'Login Sekarang', 'login.php');
          return;
        }
        document.getElementById('commentModal').style.display = 'flex';
        parentIdInput.value = '';
        document.getElementById('replyToText').textContent = 'Tambahkan komentar Anda.';
      });

      document.getElementById('sharebutton').addEventListener('click', function () {
        document.getElementById('shareModal').style.display = 'flex';
      });

      // Handle comment submission
      sendCommentButton.addEventListener('click', async () => {
          const commentText = commentInput.value.trim();
          const artworkId = likeButton.dataset.idkarya;
          const parentId = parentIdInput.value || null;

          if (!commentText) {
              alert('Komentar tidak boleh kosong.');
              return;
          }

          try {
              const response = await fetch('post_comment.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({
                      idKarya: artworkId,
                      commentText: commentText,
                      parentId: parentId,
                  }),
              });

              const data = await response.json();

              if (data.success) {
                  alert(data.message);
                  document.getElementById('commentModal').style.display = 'none';
                  commentInput.value = '';
                  fetchComments(artworkId);
              } else {
                  alert(data.message);
              }
          } catch (error) {
              console.error('Error:', error);
              alert('Terjadi kesalahan saat mengirim komentar.');
          }
      });
      
      // Fetch and display random artworks
      function fetchRandomArtworks() {
        fetch('get_random_karya.php')
          .then(response => response.json())
          .then(data => {
            moreLikeThisContainer.innerHTML = '';
            if (data.success && data.data.length > 0) {
              data.data.forEach(artwork => {
                const artworkCard = createArtworkCardElement(artwork);
                moreLikeThisContainer.appendChild(artworkCard);
              });
            } else {
              moreLikeThisContainer.innerHTML = `<p class="text-center text-gray-400">Tidak ada karya lain yang bisa ditampilkan.</p>`;
            }
          })
          .catch(error => {
            console.error('Error fetching random artworks:', error);
            moreLikeThisContainer.innerHTML = `<p class="text-center text-red-500">Terjadi kesalahan saat memuat karya serupa.</p>`;
          });
      }

      function createArtworkCardElement(artwork) {
        const card = document.createElement('a');
        card.href = `detailpameran.php?karya=${artwork.slug}`;
        card.className = "relative w-full aspect-square-container rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all text-white duration-300 block zoom-container group";

        const imagePath = artwork.pict_karya ? `./img/Karya/${artwork.pict_karya}` : `./img/noimage.png`;
        const isVideo = artwork.pict_karya && artwork.pict_karya.split('.').pop().toLowerCase() === 'mp4';
        const mediaTag = isVideo 
          ? `<video autoplay loop muted class="absolute inset-0 w-full h-full object-cover zoom-img"><source src="./img/Karya/${artwork.pict_karya}" type="video/mp4"></video>`
          : `<img src="${imagePath}" alt="${artwork.judul_karya}" class="absolute inset-0 w-full h-full object-cover zoom-img">`;

        card.innerHTML = `
          ${mediaTag}
          <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 to-transparent flex flex-col justify-end p-4 gap-3">
            <div class="flex items-center justify-between">
              <h3 class="text-3xl font-bold text-white">${artwork.judul_karya}</h3>
              <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110" aria-label="Suka karya ini" data-idkarya="${artwork.id_karya}">
                <i class="${artwork.user_liked > 0 ? 'bi bi-heart-fill text-red-500' : 'bi bi-heart'} h-10 w-10 text-white transition-colors duration-200"></i>
              </button>
            </div>
            <p class="text-sm text-white">Kreator: ${artwork.nama_karya}</p>
            <p>${artwork.deskripsi.substring(0, 100)}...</p>
          </div>
        `;

        const likeBtn = card.querySelector('.like-button');
        likeBtn.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleLikeArtwork(likeBtn);
        });
        
        return card;
      }
      
      function toggleLikeArtwork(button) {
          if (!loggedIn) {
              showNotificationModal('Login Diperlukan', 'Anda harus login untuk menyukai karya ini.', 'Login Sekarang', 'login.php');
              return;
          }

          const artworkId = button.dataset.idkarya;
          const heartIcon = button.querySelector('i');

          fetch('like_handler.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ idKarya: artworkId }),
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  if (data.action === 'liked') {
                      heartIcon.classList.remove('bi-heart', 'text-white');
                      heartIcon.classList.add('bi-heart-fill', 'text-red-500');
                  } else {
                      heartIcon.classList.remove('bi-heart-fill', 'text-red-500');
                      heartIcon.classList.add('bi-heart', 'text-white');
                  }
              } else {
                  alert(data.message);
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan. Silakan coba lagi.');
          });
      }

    });

    window.openReplyModal = function (username, commentId) {
        const loggedIn = <?= json_encode(isset($_SESSION['user_id'])); ?>;
        if (!loggedIn) {
            showNotificationModal('Login Diperlukan', 'Anda harus login untuk membalas komentar ini.', 'Login Sekarang', 'login.php');
            return;
        }

        const modal = document.getElementById('commentModal');
        const replyToText = document.getElementById('replyToText');
        const commentInput = document.getElementById('commentInput');
        const parentIdInput = document.getElementById('parent-id');

        replyToText.textContent = `Membalas komentar ${username}`;
        parentIdInput.value = commentId;

        modal.style.display = 'flex';
        commentInput.focus();
    }
  </script>
</body>

</html>