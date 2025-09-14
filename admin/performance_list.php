<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"]) {
    header("location: login.php");
    exit;
}

$admin_id = $_SESSION["id"];
$admin_name = $_SESSION["name"];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Performance</title>
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
            <span class="text-lg font-semibold text-light-gray">Manajemen Performance</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Manajemen Performance</h1>
            <p class="text-mid-gray mb-6">Kelola data penampil yang akan tampil di website.</p>

            <div class="flex justify-end mb-4">
                <a href="add_performance.php" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                    Tambah Penampil
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-dark-gray">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Tanggal & Waktu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Lokasi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-light-gray uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="performance-list" class="divide-y divide-gray-700">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-mid-gray">Memuat data penampil...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="performanceModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-dark-card text-white p-6 md:p-8 rounded-xl w-full max-w-lg shadow-lg max-h-[90vh] overflow-y-auto relative">
                <div class="flex justify-between items-start mb-4 bg-dark-card pb-4 -mx-6 -mt-6 px-6 pt-6 rounded-t-xl">
                    <h3 class="text-xl md:text-2xl font-semibold text-primary-green">Detail Penampil</h3>
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
            fetchPerformance();
        });

        async function fetchPerformance() {
            const performanceList = document.getElementById('performance-list');
            try {
                const response = await fetch('get_performance.php');
                const data = await response.json();

                if (data.error) {
                    performanceList.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-red-error">${data.error}</td></tr>`;
                    return;
                }

                if (data.performance.length > 0) {
                    performanceList.innerHTML = '';
                    data.performance.forEach(item => {
                        const row = document.createElement('tr');
                        row.className = 'bg-dark-card hover:bg-dark-gray transition-colors';
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">${item.nama_penampil}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">${item.tanggal_tampil} / ${item.jam_tampil}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">${item.lokasi_tampil}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${item.status_view === '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${item.status_view === '1' ? 'Aktif' : 'Tidak Aktif'}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="showDetails(${item.id})" class="text-blue-500 hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined">info</span>
                                    </button>
                                    <a href="edit_performance.php?id=${item.id}" class="text-yellow-500 hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <button onclick="confirmDelete(${item.id}, '${item.nama_penampil}')" class="text-red-error hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        performanceList.appendChild(row);
                    });
                } else {
                    performanceList.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-mid-gray">Tidak ada data penampil.</td></tr>`;
                }
            } catch (error) {
                console.error('Error fetching performance data:', error);
                performanceList.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-red-error">Gagal memuat data penampil.</td></tr>`;
            }
        }

        const performanceModal = document.getElementById('performanceModal');
        const modalBody = document.getElementById('modalBody');

        async function showDetails(id) {
            try {
                const response = await fetch(`details_performance.php?id=${id}`);
                const data = await response.json();
                
                if (data.error) {
                    modalBody.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                } else {
                    const item = data.performance;
                    modalBody.innerHTML = `
                        <div class="flex flex-col items-center text-center">
                            ${item.path_image_penampil ? `<img src="../img/performance/${item.path_image_penampil}" alt="${item.nama_penampil}" class="h-32 w-32 object-cover rounded-full mb-4">` : ''}
                            ${item.path_image_logo_penampil ? `<img src="../img/performance/${item.path_image_logo_penampil}" alt="Logo ${item.nama_penampil}" class="h-16 w-16 object-contain mb-4">` : ''}
                            <h4 class="text-xl font-bold text-primary-green mb-1">${item.nama_penampil}</h4>
                            <p class="text-sm italic text-mid-gray mb-4">${item.lokasi_tampil}</p>
                        </div>
                        <div class="space-y-4 pt-4 border-t border-gray-700">
                            <p>
                                <strong class="text-light-gray block mb-1">Tanggal & Jam Tampil:</strong>
                                ${item.tanggal_tampil} / ${item.jam_tampil}
                            </p>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <p><strong class="text-light-gray block mb-1">Status:</strong> ${item.status_view === '1' ? 'Aktif' : 'Tidak Aktif'}</p>
                                <p><strong class="text-light-gray block mb-1">Dibuat Pada:</strong> ${item.created_at}</p>
                            </div>
                        </div>
                    `;
                }
                performanceModal.classList.remove('hidden');
            } catch (error) {
                modalBody.innerHTML = '<p class="text-red-500">Gagal memuat detail penampil.</p>';
                console.error('Error fetching performance details:', error);
            }
        }

        function closeModal() {
            performanceModal.classList.add('hidden');
        }

        function confirmDelete(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus penampil "${nama}"?`)) {
                window.location.href = `delete_performance.php?id=${id}`;
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