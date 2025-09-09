<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Periksa apakah session user ada dan tidak kosong
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'admin-one/dist/koneksi.php';
// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Persiapkan query untuk mengambil data user berdasarkan user_id
$query_user = "SELECT nama, tgl_lahir, no_hp, instansi, email, kode_account FROM user WHERE id_user = ?";

// Persiapkan statement untuk data user
$stmt_user = mysqli_prepare($koneksi, $query_user);
if (!$stmt_user) {
    // Handle error jika prepare statement gagal
    die('Prepare statement user failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);

// Ambil hasil query data user
$result_user = mysqli_stmt_get_result($stmt_user);

// Periksa apakah data user ditemukan
if ($row_user = mysqli_fetch_assoc($result_user)) {
    // Simpan data user ke dalam session atau langsung gunakan
    $_SESSION['user_data'] = [
        'nama' => $row_user['nama'],
        'tgl_lahir' => $row_user['tgl_lahir'],
        'no_hp' => $row_user['no_hp'],
        'instansi' => $row_user['instansi'],
        'email' => $row_user['email'],
        'kode_account' => $row_user['kode_account'] // Pastikan kode_account disimpan
    ];
} else {
    // Jika data user tidak ditemukan, logout dan kembali ke halaman login
    session_destroy();
    header("Location: login.php");
    exit();
}

// Tutup statement data user
mysqli_stmt_close($stmt_user);

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
                },
            },
        };
    </script>
    <style>
        .custom-hr {
            border: none;
            height: 1px;
            background-color: #4a4a4a;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 768px) {
            .custom-hr {
                margin-top: 4rem;
                margin-bottom: 4rem;
            }
        }

        .modal {
            transition: all 0.3s ease-in-out;
        }

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
    <title>Profile - Finder 7 Mindspace</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-neutral-950">
    <?php require '_navbar.php'; ?>
    <div
        class="w-2/3 h-3/4 blur-3xl absolute -z-20 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
    </div>

    <div class="w-full flex min-h-screen pt-32 pb-32 font-work px-4 md:px-8 lg:px-16 z-10">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row md:items-start md:space-x-8 lg:space-x-12 gap-10 md:gap-0 ">
                <div
                    class="flex flex-row md:flex-col md:w-1/4 lg:w-1/5 bg-neutral-900 rounded-2xl md:p-6 md:space-y-2 justify-around mb-6 md:mb-0 items-center md:items-start">
                    <a href="account.php"
                        class="flex md:w-full items-center space-x-3 p-3 md:rounded-lg text-neutral-400 hover:bg-neutral-800 hover:text-white transition-colors duration-300  ">
                        <ion-icon name="person-circle-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Profile</span>
                    </a>
                    <a href="#"
                        class="flex  md:w-full items-center space-x-3 p-3 md:rounded-lg md:bg-neutral-800 text-emerald-500 transition-colors font-semibold duration-300 border-b-2 border-emerald-500">
                        <ion-icon name="heart-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Liked Post</span>
                    </a>
                    <a href="setting.php"
                        class="flex  md:w-full items-center space-x-3 p-3 md:rounded-lg text-neutral-400 hover:bg-neutral-800 hover:text-white transition-colors duration-300">
                        <ion-icon name="settings-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Setting</span>
                    </a>
                    <a href="logout-reminder.php"
                        class="flex  md:w-full items-center space-x-3 p-3 md:rounded-lg text-neutral-400 hover:bg-neutral-800 hover:text-white transition-colors duration-300">
                        <ion-icon name="log-out-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Logout</span>
                    </a>
                </div>


                <div class="md:w-3/4 lg:w-4/5 space-y-12">
                    <div class="flex justify-center ">

                        <h1
                            class="text-white text-center md:text-4xl text-2xl font-bold border-b-2 pb-5 px-5 border-emerald-500">
                            Karya Yang Disukai </h1>
                    </div>
                    <div>
                        <div id="liked-artworks-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <p class="text-center text-gray-400 col-span-full">Memuat...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    
    <div id="confirmUnlikeModal"
        class="fixed inset-0 bg-black bg-opacity-80 z-[70] flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center relative transform transition-all scale-95 opacity-0">
            <h2 class="text-xl font-bold mb-4">Batalkan Suka?</h2>
            <p class="text-gray-700 mb-6">Apakah Anda yakin ingin membatalkan suka pada karya ini?</p>
            <div class="flex justify-center space-x-4">
                <button id="cancelUnlikeBtn" class="bg-gray-300 text-gray-800 font-semibold px-6 py-2 rounded-xl hover:bg-gray-400 transition-colors">
                    Batal
                </button>
                <button id="confirmUnlikeBtn" class="bg-red-500 text-white font-semibold px-6 py-2 rounded-xl hover:bg-red-600 transition-colors" data-idkarya="">
                    Ya, Batalkan Suka
                </button>
            </div>
        </div>
    </div>

    <?php require '_footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const likedArtworksContainer = document.getElementById('liked-artworks-container');
            const loggedIn = <?= json_encode(isset($_SESSION['user_id'])); ?>;
            const notificationModal = document.getElementById('notificationModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalButton = document.getElementById('modalButton');
            const closeNotificationModalBtn = document.getElementById('closeNotificationModalBtn');

            const confirmUnlikeModal = document.getElementById('confirmUnlikeModal');
            const confirmUnlikeBtn = document.getElementById('confirmUnlikeBtn');
            const cancelUnlikeBtn = document.getElementById('cancelUnlikeBtn');
            
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
            
            function showConfirmUnlikeModal(artworkId) {
                confirmUnlikeBtn.dataset.idkarya = artworkId;
                confirmUnlikeModal.classList.remove('hidden');
                setTimeout(() => {
                    confirmUnlikeModal.querySelector('div').classList.remove('scale-95', 'opacity-0');
                    confirmUnlikeModal.querySelector('div').classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function hideConfirmUnlikeModal() {
                confirmUnlikeModal.querySelector('div').classList.remove('scale-100', 'opacity-100');
                confirmUnlikeModal.querySelector('div').classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    confirmUnlikeModal.classList.add('hidden');
                }, 300);
            }

            cancelUnlikeBtn.addEventListener('click', hideConfirmUnlikeModal);
            confirmUnlikeModal.addEventListener('click', (e) => {
                if (e.target === confirmUnlikeModal) {
                    hideConfirmUnlikeModal();
                }
            });
            
            confirmUnlikeBtn.addEventListener('click', () => {
                const artworkId = confirmUnlikeBtn.dataset.idkarya;
                hideConfirmUnlikeModal();
                toggleLikeArtwork(null, artworkId, true);
            });
            

            async function fetchLikedArtworks() {
                try {
                    const response = await fetch('get_liked_karya.php');
                    const data = await response.json();

                    likedArtworksContainer.innerHTML = ''; // Hapus pesan "Memuat..."

                    if (data.success && data.data.length > 0) {
                        data.data.forEach(artwork => {
                            const artworkCard = createArtworkCardElement(artwork);
                            likedArtworksContainer.appendChild(artworkCard);
                        });
                    } else {
                        likedArtworksContainer.innerHTML = `<p class="text-center text-gray-400 col-span-full">Belum ada karya yang Anda sukai. Jelajahi Pameran untuk menemukan karya favorit Anda!</p>`;
                    }
                } catch (error) {
                    console.error('Error fetching liked artworks:', error);
                    likedArtworksContainer.innerHTML = `<p class="text-center text-red-500 col-span-full">Terjadi kesalahan saat memuat karya.</p>`;
                }
            }
            
            function createArtworkCardElement(artwork) {
                const card = document.createElement('a');
                card.href = `detailpameran.php?karya=${artwork.slug}`;
                card.className = "relative w-full aspect-square-container rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all text-white duration-300 block zoom-container group";
                
                const imagePath = artwork.pict_karya ? `./img/Karya/${artwork.pict_karya}` : `./img/noimage.png`;
                const isVideo = artwork.pict_karya && (artwork.pict_karya.split('.').pop().toLowerCase() === 'mp4' || artwork.pict_karya.split('.').pop().toLowerCase() === 'mov');
                
                const mediaTag = isVideo 
                    ? `<video autoplay loop muted class="absolute inset-0 w-full h-full object-cover zoom-img"><source src="./img/Karya/${artwork.pict_karya}" type="video/mp4"></video>`
                    : `<img src="${imagePath}" alt="${artwork.judul_karya}" class="absolute inset-0 w-full h-full object-cover zoom-img">`;
                
                card.innerHTML = `
                    ${mediaTag}
                    <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 to-transparent flex flex-col justify-end p-4 gap-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl md:text-3xl font-bold text-white">${artwork.judul_karya}</h3>
                            <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110" aria-label="Suka karya ini" data-idkarya="${artwork.id_karya}">
                                <i class="${artwork.user_liked > 0 ? 'bi bi-heart-fill text-red-500' : 'bi bi-heart'} h-6 w-6 md:h-10 md:w-10 text-white transition-colors duration-200"></i>
                            </button>
                        </div>
                        <p class="text-sm text-white">Kreator: ${artwork.nama_karya}</p>
                        <p class="text-xs md:text-sm">${artwork.deskripsi.substring(0, 100)}...</p>
                    </div>
                `;
                
                const likeBtn = card.querySelector('.like-button');
                likeBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    const artworkId = likeBtn.dataset.idkarya;
                    const heartIcon = likeBtn.querySelector('i');
                    
                    // Check if the current action is to UN-like
                    if (heartIcon.classList.contains('bi-heart-fill')) {
                        showConfirmUnlikeModal(artworkId);
                    } else {
                        // If it's to LIKE, proceed immediately
                        toggleLikeArtwork(heartIcon, artworkId);
                    }
                });
                
                return card;
            }
            
            async function toggleLikeArtwork(heartIcon, artworkId, forceUnlike = false) {
                if (!loggedIn) {
                    showNotificationModal('Login Diperlukan', 'Anda harus login untuk menyukai karya ini.', 'Login Sekarang', 'login.php');
                    return;
                }

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
                        if (data.action === 'liked') {
                            if (heartIcon) {
                                heartIcon.classList.remove('bi-heart');
                                heartIcon.classList.add('bi-heart-fill', 'text-red-500');
                            }
                        } else {
                            if (heartIcon) {
                                heartIcon.classList.remove('bi-heart-fill', 'text-red-500');
                                heartIcon.classList.add('bi-heart');
                            }
                            // Refresh the list after unliking to remove the card
                            fetchLikedArtworks(); 
                        }
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            }

            fetchLikedArtworks();

        });
    </script>
</body>

</html>