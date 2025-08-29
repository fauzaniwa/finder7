<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
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
    <title>Manajemen Speakers</title>
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
            <span class="text-lg font-semibold text-light-gray">Manajemen Speakers</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Manajemen Speakers</h1>
            <p class="text-mid-gray mb-6">Kelola data speakers yang akan tampil di website.</p>

            <div class="flex justify-end mb-4">
                <a href="add_speakers.php" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                    Tambah Speakers
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-dark-gray">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Foto</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Instansi</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-light-gray uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="speakers-list" class="divide-y divide-gray-700">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-mid-gray">Memuat data speakers...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="speakerModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-dark-card text-white p-6 md:p-8 rounded-xl w-full max-w-lg shadow-lg max-h-[90vh] overflow-y-auto relative">
                <div class="flex justify-between items-start mb-4 bg-dark-card pb-4 -mx-6 -mt-6 px-6 pt-6 rounded-t-xl">
                    <h3 class="text-xl md:text-2xl font-semibold text-primary-green">Detail Speaker</h3>
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
            fetchSpeakers();
        });

        async function fetchSpeakers() {
            const speakersList = document.getElementById('speakers-list');
            try {
                const response = await fetch('get_speakers.php');
                const data = await response.json();

                if (data.error) {
                    speakersList.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-red-error">${data.error}</td></tr>`;
                    return;
                }

                if (data.speakers.length > 0) {
                    speakersList.innerHTML = '';
                    data.speakers.forEach(speaker => {
                        const row = document.createElement('tr');
                        row.className = 'bg-dark-card hover:bg-dark-gray transition-colors';
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">
                                ${speaker.foto_speaker ? `<img src="../img/speakers/${speaker.foto_speaker}" alt="${speaker.nama_speaker}" class="h-10 w-10 object-cover">` : 'Tidak Ada Foto'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">${speaker.nama_speaker}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">${speaker.instansi}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="showDetails(${speaker.id_speaker})" class="text-blue-500 hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined">info</span>
                                    </button>
                                    <a href="edit_speakers.php?id=${speaker.id_speaker}" class="text-yellow-500 hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <button onclick="confirmDelete(${speaker.id_speaker}, '${speaker.nama_speaker}')" class="text-red-error hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        speakersList.appendChild(row);
                    });
                } else {
                    speakersList.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-mid-gray">Tidak ada data speakers.</td></tr>`;
                }
            } catch (error) {
                console.error('Error fetching speakers data:', error);
                speakersList.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-red-error">Gagal memuat data speakers.</td></tr>`;
            }
        }

        const speakerModal = document.getElementById('speakerModal');
        const modalBody = document.getElementById('modalBody');

        async function showDetails(id) {
            try {
                const response = await fetch(`details_speakers.php?id=${id}`);
                const data = await response.json();
                
                if (data.error) {
                    modalBody.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                } else {
                    const speaker = data.speaker;
                    modalBody.innerHTML = `
                        <div class="flex flex-col items-center text-center">
                            ${speaker.foto_speaker ? `<img src="../img/speakers/${speaker.foto_speaker}" alt="${speaker.nama_speaker}" class="h-32 w-32 object-cover mb-4">` : ''}
                            <h4 class="text-xl font-bold text-primary-green mb-1">${speaker.nama_speaker}</h4>
                            <p class="text-sm italic text-mid-gray mb-4">${speaker.instansi}</p>
                        </div>
                        <div class="space-y-4 pt-4 border-t border-gray-700">
                            <p>
                                <strong class="text-light-gray block mb-1">Deskripsi:</strong>
                                ${speaker.deskripsi || 'Tidak ada deskripsi'}
                            </p>
                            <p>
                                <strong class="text-light-gray block mb-1">Kontak:</strong>
                                ${speaker.kontak || 'Tidak ada kontak'}
                            </p>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <p><strong class="text-light-gray block mb-1">Urutan:</strong> ${speaker.urutan}</p>
                                <p><strong class="text-light-gray block mb-1">Dibuat Pada:</strong> ${speaker.created_at}</p>
                            </div>
                        </div>
                    `;
                }
                speakerModal.classList.remove('hidden');
            } catch (error) {
                modalBody.innerHTML = '<p class="text-red-500">Gagal memuat detail speaker.</p>';
                console.error('Error fetching speaker details:', error);
            }
        }

        function closeModal() {
            speakerModal.classList.add('hidden');
        }

        function confirmDelete(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus speaker "${nama}"?`)) {
                window.location.href = `delete_speakers.php?id=${id}`;
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