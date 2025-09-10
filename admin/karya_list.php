<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Karya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#080808',
                        'dark-card': '#1a1a1a',
                        'primary-green': '#00D294',
                        'light-gray': '#e0e0e0',
                        'mid-gray': '#bbbbbb',
                        'dark-gray': '#2a2a2a',
                        'red-error': '#ff6b6b',
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-dark text-white font-poppins flex">

    <?php include_once 'sidebar.php'; ?>

    <main class="flex-grow p-6 overflow-x-hidden">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
        
        <header class="bg-dark-card p-4 flex justify-between items-center lg:hidden sticky top-0 z-40">
            <button id="open-sidebar-btn" class="text-white">
                <span class="material-symbols-outlined text-3xl">menu</span>
            </button>
            <span class="text-lg font-semibold text-light-gray">Manajemen Karya</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Manajemen Karya</h1>
            <p class="text-mid-gray mb-6">Kelola data karya yang akan tampil di website.</p>

            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
                <div class="w-full md:w-1/2">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Cari berdasarkan judul atau nama karya..." class="w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50 pr-10">
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-mid-gray">search</span>
                    </div>
                </div>
                <div class="w-full md:w-auto flex justify-end">
                    <a href="add_karya.php" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity whitespace-nowrap">
                        Tambah Karya
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-dark-gray">
                        <tr>
                            <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Gambar/Video</th> -->
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Judul Karya</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Jenis</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-light-gray uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="karya-list" class="divide-y divide-gray-700">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-mid-gray">Memuat data karya...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="karyaModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-dark-card text-white p-6 md:p-8 rounded-xl w-full max-w-lg shadow-lg max-h-[90vh] overflow-y-auto relative">
                <div class="flex justify-between items-start mb-4 bg-dark-card pb-4 -mx-6 -mt-6 px-6 pt-6 rounded-t-xl">
                    <h3 class="text-xl md:text-2xl font-semibold text-primary-green">Detail Karya</h3>
                    <button class="text-mid-gray hover:text-light-gray transition-colors" onclick="closeModal()">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div id="modalBody" class="space-y-4 text-mid-gray"></div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchKarya();

            const searchInput = document.getElementById('search-input');
            searchInput.addEventListener('input', debounce(function() {
                fetchKarya(searchInput.value);
            }, 300));
        });

        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        async function fetchKarya(query = '') {
            const karyaList = document.getElementById('karya-list');
            try {
                let url = 'get_karya.php';
                if (query) {
                    url += `?q=${encodeURIComponent(query)}`;
                }
                const response = await fetch(url);
                const data = await response.json();

                if (data.error) {
                    karyaList.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-red-error">${data.message}</td></tr>`;
                    return;
                }

                if (data.karya.length > 0) {
                    karyaList.innerHTML = '';
                    data.karya.forEach(karya => {
                        const row = document.createElement('tr');
                        row.className = 'bg-dark-card hover:bg-dark-gray transition-colors';
                        
                        let mediaPreview = '';
                        if (karya.file_type === 'video') {
                            mediaPreview = `<video src="${karya.pict_url}" class="h-10 w-10 object-cover rounded-md"></video>`;
                        } else {
                            mediaPreview = `<img src="${karya.pict_url}" alt="${karya.judul_karya}" class="h-10 w-10 object-cover rounded-md">`;
                        }

                        row.innerHTML = `
                            // <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">
                            //     ${mediaPreview}
                            // </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">${karya.judul_karya}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">${karya.jenis}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">${karya.nama_kategori}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="showDetails(${karya.id_karya})" class="text-blue-500 hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined">info</span>
                                    </button>
                                    <a href="edit_karya.php?id=${karya.id_karya}" class="text-yellow-500 hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <button onclick="confirmDelete(${karya.id_karya}, '${karya.judul_karya}')" class="text-red-error hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        karyaList.appendChild(row);
                    });
                } else {
                    karyaList.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-mid-gray">Tidak ada data karya yang cocok.</td></tr>`;
                }
            } catch (error) {
                console.error('Error fetching karya data:', error);
                karyaList.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-red-error">Gagal memuat data karya.</td></tr>`;
            }
        }

        const karyaModal = document.getElementById('karyaModal');
        const modalBody = document.getElementById('modalBody');

        async function showDetails(id) {
            try {
                const response = await fetch(`details_karya.php?id=${id}`);
                const data = await response.json();
                
                if (data.error) {
                    modalBody.innerHTML = `<p class="text-red-500">${data.message}</p>`;
                } else {
                    const karya = data.karya;
                    const optionalImageHtml = karya.optional_karya 
                        ? `<p><strong class="text-light-gray block mb-1">Link Opsional:</strong> <a href="${karya.optional_karya}" target="_blank" class="text-primary-green hover:underline break-all">${karya.optional_karya}</a></p>`
                        : `<p><strong class="text-light-gray block mb-1">Link Opsional:</strong> Tidak ada link.</p>`;
                    
                    let mediaPreviewHtml = '';
                    if (karya.file_type === 'video') {
                        mediaPreviewHtml = `<video controls class="h-48 w-full object-contain rounded-md" src="${karya.pict_url}"></video>`;
                    } else {
                        mediaPreviewHtml = `<img src="${karya.pict_url}" alt="${karya.judul_karya}" class="h-48 w-full object-cover rounded-md">`;
                    }

                    modalBody.innerHTML = `
                        <div class="flex flex-col items-center text-center">
                            ${mediaPreviewHtml}
                            <h4 class="text-xl font-bold text-primary-green mt-4 mb-1">${karya.judul_karya}</h4>
                            <p class="text-sm italic text-mid-gray mb-4">oleh ${karya.nama_karya}</p>
                        </div>
                        <div class="space-y-4 pt-4 border-t border-gray-700">
                            <p>
                                <strong class="text-light-gray block mb-1">Deskripsi:</strong>
                                ${karya.deskripsi || 'Tidak ada deskripsi'}
                            </p>
                            <p>
                                <strong class="text-light-gray block mb-1">Jenis:</strong>
                                ${karya.jenis}
                            </p>
                            <p>
                                <strong class="text-light-gray block mb-1">Kategori:</strong>
                                ${karya.nama_kategori}
                            </p>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <p><strong class="text-light-gray block mb-1">NIM:</strong> ${karya.NIM || 'Tidak ada NIM'}</p>
                                <p><strong class="text-light-gray block mb-1">Dibuat Pada:</strong> ${karya.created_at}</p>
                                <p><strong class="text-light-gray block mb-1">Likes:</strong> ${karya.likes}</p>
                                <p><strong class="text-light-gray block mb-1">Comments:</strong> ${karya.comments}</p>
                            </div>
                            ${optionalImageHtml}
                        </div>
                    `;
                }
                karyaModal.classList.remove('hidden');
            } catch (error) {
                modalBody.innerHTML = '<p class="text-red-500">Gagal memuat detail karya.</p>';
                console.error('Error fetching karya details:', error);
            }
        }

        function closeModal() {
            karyaModal.classList.add('hidden');
        }

        function confirmDelete(id, judul) {
            if (confirm(`Apakah Anda yakin ingin menghapus karya "${judul}"?`)) {
                window.location.href = `delete_karya.php?id=${id}`;
            }
        }

        // Fungsionalitas sidebar mobile
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-sidebar-btn');
        const overlay = document.getElementById('overlay');
        
        if (openBtn) {
            openBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            });
        }
            
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>
</body>
</html>