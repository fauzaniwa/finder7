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
            transition: all 0.3s ease;
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
    <div class="w-2/3 h-3/4 blur-3xl absolute z-0 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
    </div>

    <section data-section-bg="dark" class="w-10/12 min-h-screen flex flex-col lg:flex-row items-center justify-center px-4 mx-auto ">
        <div class="order-last lg:order-first relative z-10 w-full text-center text-white space-y-4">
            <h2 class="text-4xl md:text-8xl font-bold text-center md:text-left leading-tight max-w-xs sm:max-w-fit">
                Ayo Lihat <br />Karya-Karya <br /> Finder!
            </h2>
            <div class="flex flex-col gap-4 items-center md:items-start">
                <a href="#scbar" class="w-48 sm:w-64 h-12 sm:h-16 flex items-center justify-center bg-[#008C62] text-white text-lg sm:text-xl font-medium rounded-xl sm:rounded-[20px] shadow-md hover:scale-105 transition-transform duration-300">
                    See More
                </a>
            </div>
        </div>
    </section>

    <section class="flex flex-col justify-center text-center gap-10">
        <h1 class="font-bold text-white text-3xl">Pilih Section Kamu!</h1>
        <div id="kategori-filter-container" class="hidden md:flex gap-10 text-white justify-center text-center font-bold">
            </div>

        <div id="kategori-filter-mobile-container" class="fixed flex md:hidden w-10/12 bottom-5 left-0 right-0 bg-neutral-800 z-50 shadow-md justify-center items-center grid-cols-4 mx-auto rounded-3xl">
            </div>
    </section>

    <br><br>

    <section id="scbar" class="flex w-10/12 md:w-6/12 justify-center mx-auto ">
        <form id="search-form" class=" flex flex-row w-full bg-white rounded-full ">
            <div class="relative gap-2 w-full">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-7">
                    <svg width="30px" height="30px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.545 15.467l-3.779-3.779a6.15 6.15 0 0 0 .898-3.21c0-3.417-2.961-6.377-6.378-6.377A6.185 6.185 0 0 0 2.1 8.287c0 3.416 2.961 6.377 6.377 6.377a6.15 6.15 0 0 0 3.115-.844l3.799 3.801a.953.953 0 0 0 1.346 0l.943-.943c.371-.371.236-.84-.135-1.211zM4.004 8.287a4.282 4.282 0 0 1 4.282-4.283c2.366 0 4.474 2.107 4.474 4.474a4.284 4.284 0 0 1-4.283 4.283c-2.366-.001-4.473-2.109-4.473-4.474z" />
                    </svg>
                </div>
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 w-30 bg-neutral-500 rounded-full p-3 px-10">
                    cari
                </button>
                <input type="text" id="search-input" name="searchinput" class="w-full h-16 rounded-full pl-16 font-work font-medium text-sm sm:text-lg text-black" placeholder="Ketik disini..">
            </div>
        </form>
    </section>

    <br><br>

    <section>
        <div id="jenis-karya-filter-container" class="flex w-10/12 gap-4 md:gap-16 justify-center mx-auto overflow-x-auto pb-4">
            </div>
    </section>

    <br><br>

    <section class="font-sans p-8">
        <div class="container mx-auto">
            <div id="karya-container" class="space-y-12">
                <div id="loading-spinner" class="text-center text-white">Memuat karya...</div>
            </div>
        </div>
    </section>


    <br><br><br>

    <?php
    require '_footer.php';
    ?>

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


    <script>
        const karyaContainer = document.getElementById('karya-container');
        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        const kategoriFilterContainer = document.getElementById('kategori-filter-container');
        const kategoriFilterMobileContainer = document.getElementById('kategori-filter-mobile-container');
        const jenisKaryaFilterContainer = document.getElementById('jenis-karya-filter-container');
        const loadingSpinner = document.getElementById('loading-spinner');

        // Modal elements
        const notificationModal = document.getElementById('notificationModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const modalButton = document.getElementById('modalButton');
        const closeNotificationModalBtn = document.getElementById('closeNotificationModalBtn');

        let currentFilter = {
            kategori: '',
            jenis: '',
            search: ''
        };
        let allJenisKarya = [];

        async function fetchFilters() {
            try {
                const response = await fetch('get_filter_data.php');
                const data = await response.json();
                if (data.success) {
                    displayCategories(data.data.categories);
                    allJenisKarya = data.data.jenis;
                    displayJenisKarya(allJenisKarya);
                }
            } catch (error) {
                console.error('Error fetching filters:', error);
            }
        }

        async function fetchData() {
            loadingSpinner.classList.remove('hidden');
            let url = 'get_pameran_data.php?';
            const params = new URLSearchParams(currentFilter).toString();
            url += params;

            try {
                const response = await fetch(url);
                const data = await response.json();

                loadingSpinner.classList.add('hidden');
                karyaContainer.innerHTML = '';
                if (data.success && data.data.length > 0) {
                    displayKarya(data.data);
                } else {
                    karyaContainer.innerHTML = `<div class="text-center text-white">Tidak ada karya yang ditemukan.</div>`;
                }

            } catch (error) {
                loadingSpinner.classList.add('hidden');
                karyaContainer.innerHTML = `<div class="text-center text-red-500">Gagal memuat data: ${error.message}</div>`;
                console.error('Error fetching data:', error);
            }
        }

        function displayCategories(categories) {
            kategoriFilterContainer.innerHTML = '';
            kategoriFilterMobileContainer.innerHTML = '';

            categories.forEach(kategori => {
                const desktopBtn = document.createElement('div');
                desktopBtn.className = 'flex-col border border-opacity-50 rounded-3xl px-7 py-4 hover-radial-bg duration-300 cursor-pointer';
                desktopBtn.dataset.kategori = kategori.nama_kategori;
                desktopBtn.style.setProperty('--hover-color', getCategoryColor(kategori.nama_kategori));
                desktopBtn.innerHTML = `<img src="./img/icon program/${kategori.nama_kategori.toLowerCase()}.svg" alt=""><p>${kategori.nama_kategori}</p>`;
                desktopBtn.addEventListener('click', () => {
                    selectCategory(kategori.nama_kategori);
                });
                kategoriFilterContainer.appendChild(desktopBtn);

                const mobileBtn = document.createElement('div');
                mobileBtn.className = 'flex-col aspect-square rounded-3xl px-4 py-2 hover-radial-bg duration-300 items-center justify-center cursor-pointer';
                mobileBtn.dataset.kategori = kategori.nama_kategori;
                mobileBtn.style.setProperty('--hover-color', getCategoryColor(kategori.nama_kategori));
                mobileBtn.innerHTML = `<img src="./img/icon program/${kategori.nama_kategori.toLowerCase()}.svg" alt="" class="w-full h-full">`;
                mobileBtn.addEventListener('click', () => {
                    selectCategory(kategori.nama_kategori);
                });
                kategoriFilterMobileContainer.appendChild(mobileBtn);
            });
        }

        function displayJenisKarya(jenisList) {
            jenisKaryaFilterContainer.innerHTML = '';
            const filteredJenis = jenisList.filter(jenis => currentFilter.kategori === '' || jenis.nama_kategori === currentFilter.kategori);

            filteredJenis.forEach(jenis => {
                const btn = document.createElement('button');
                btn.className = 'text-xs lg:text-xl text-white button';
                btn.textContent = jenis.jenis;
                btn.dataset.jenis = jenis.jenis;
                btn.addEventListener('click', () => {
                    selectJenis(jenis.jenis);
                });
                jenisKaryaFilterContainer.appendChild(btn);
            });
        }

        function displayKarya(karyaList) {
            karyaContainer.innerHTML = '';
            const groupedKarya = karyaList.reduce((acc, karya) => {
                const kategori = karya.nama_kategori || 'Tanpa Kategori';
                if (!acc[kategori]) {
                    acc[kategori] = [];
                }
                acc[kategori].push(karya);
                return acc;
            }, {});

            for (const kategori in groupedKarya) {
                const categoryDiv = document.createElement('div');
                categoryDiv.innerHTML = `<h2 class="text-2xl md:text-3xl font-bold text-white mb-6">${kategori}</h2>`;
                const gridDiv = document.createElement('div');
                gridDiv.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6';

                groupedKarya[kategori].forEach(karya => {
                    const artworkHTML = createArtworkCard(karya);
                    gridDiv.innerHTML += artworkHTML;
                });
                categoryDiv.appendChild(gridDiv);
                karyaContainer.appendChild(categoryDiv);
            }

            // Re-apply like button logic
            document.querySelectorAll('.like-button').forEach(button => {
                button.addEventListener('click', handleLike);
            });
        }

        function createArtworkCard(karya) {
    const mediaPath = karya.pict_karya ? `./img/karya/${karya.pict_karya}` : (karya.optional_karya ? `./img/karya/${karya.optional_karya}` : '');
    const isVideo = mediaPath.endsWith('.mp4');

    const isLiked = karya.user_liked > 0;
    const heartFill = isLiked ? 'currentColor' : 'none';
    const heartColor = isLiked ? 'text-red-500' : 'text-white';

    return `
        <a href="detailpameran.php?karya=${karya.slug}" class="relative w-full aspect-square-container rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all text-white duration-300 block zoom-container group">
            ${isVideo ?
                `<video src="${mediaPath}" class="absolute inset-0 w-full h-full object-cover zoom-img" autoplay loop muted playsinline></video>` :
                `<img src="${mediaPath}" alt="${karya.judul_karya}" class="absolute inset-0 w-full h-full object-cover zoom-img">`
            }
            <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 to-transparent flex flex-col justify-end p-4 gap-3 z-20">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl md:text-3xl font-bold text-white">${karya.judul_karya}</h3>
                    <div class="flex items-center space-x-2">
                        <span class="like-count text-sm" data-id="${karya.id_karya}">${karya.likes_count}</span>
                        <button class="like-button focus:outline-none transition-transform duration-200 hover:scale-110" aria-label="Suka karya ini" data-id="${karya.id_karya}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="md:h-10 md:w-10 w-6 h-6 transition-colors duration-200 ${heartColor}" fill="${heartFill}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="text-sm text-white">Kreator: ${karya.nama_karya}</p>
                <p class="text-xs md:text-sm">${karya.deskripsi ? karya.deskripsi.substring(0, 100) + '...' : ''}</p>
            </div>
        </a>
    `;
}
        
        async function handleLike(event) {
            event.preventDefault();
            event.stopPropagation();
            const button = event.currentTarget;
            const id_karya = button.dataset.id;
            const heartIcon = button.querySelector('svg');
            const likeCountSpan = document.querySelector(`.like-count[data-id="${id_karya}"]`);
            
            try {
                const response = await fetch('like_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idKarya: id_karya
                    })
                });
                const data = await response.json();

                if (data.success) {
                    if (data.action === 'unliked') {
                        heartIcon.setAttribute('fill', 'none');
                        heartIcon.classList.remove('text-red-500');
                        heartIcon.classList.add('text-white');
                    } else {
                        heartIcon.setAttribute('fill', 'currentColor');
                        heartIcon.classList.remove('text-white');
                        heartIcon.classList.add('text-red-500');
                    }
                    
                    if (likeCountSpan) {
                        likeCountSpan.textContent = data.likes;
                    }

                } else {
                    showNotificationModal('Gagal', data.message);
                }
            } catch (error) {
                console.error('Error handling like:', error);
                showNotificationModal('Error Jaringan', 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi.');
            }
        }
        
        function showNotificationModal(title, message, buttonText = 'Tutup', buttonHref = '#') {
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            modalButton.textContent = buttonText;
            modalButton.href = buttonHref;
            notificationModal.classList.remove('hidden');
            // Tambahkan animasi
            setTimeout(() => {
                notificationModal.querySelector('div').classList.remove('scale-95', 'opacity-0');
                notificationModal.querySelector('div').classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function hideNotificationModal() {
            notificationModal.querySelector('div').classList.remove('scale-100', 'opacity-100');
            notificationModal.querySelector('div').classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                notificationModal.classList.add('hidden');
            }, 300);
        }
        
        closeNotificationModalBtn.addEventListener('click', hideNotificationModal);
        modalButton.addEventListener('click', (e) => {
            if (modalButton.href.endsWith('#')) {
                e.preventDefault();
                hideNotificationModal();
            }
        });
        
        function selectCategory(kategori) {
            currentFilter.kategori = kategori;
            currentFilter.jenis = '';
            updateCategoryButtons();
            displayJenisKarya(allJenisKarya);
            fetchData();
        }

        function selectJenis(jenis) {
            currentFilter.jenis = jenis;
            updateJenisButtons();
            fetchData();
        }
        
        function updateCategoryButtons() {
            const allBtns = document.querySelectorAll('[data-kategori]');
            allBtns.forEach(btn => {
                if (btn.dataset.kategori === currentFilter.kategori) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
        
        function updateJenisButtons() {
            const allBtns = document.querySelectorAll('[data-jenis]');
            allBtns.forEach(btn => {
                if (btn.dataset.jenis === currentFilter.jenis) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
        
        function getCategoryColor(categoryName) {
            const colors = {
                'Feeling': '#db2777',
                'Thinking': '#618BFF',
                'Intuitive': '#FEE139',
                'Sensing': '#00D294'
            };
            return colors[categoryName] || '#FFFFFF'; // Default to white
        }

        // Event Listeners
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            currentFilter.search = searchInput.value;
            fetchData();
        });

        // Initial load
        document.addEventListener('DOMContentLoaded', () => {
            fetchFilters();
            fetchData();
        });
    </script>
</body>

</html>