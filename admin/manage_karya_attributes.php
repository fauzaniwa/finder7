<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Pastikan hanya admin 'master' yang bisa mengakses
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Atribut Karya</title>
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
            <span class="text-lg font-semibold text-light-gray">Kelola Atribut Karya</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="space-y-8 mt-4 lg:mt-0">
            <div class="bg-dark-card p-8 rounded-xl shadow-lg">
                <h1 class="text-3xl font-bold mb-4 text-primary-green">Kelola Jenis Karya</h1>
                
                <form id="jenis-form" class="mb-6 space-y-4">
                    <input type="hidden" name="id" id="jenis-id">
                    <div>
                        <label for="jenis-input" class="block text-sm font-medium text-light-gray">Nama Jenis Karya</label>
                        <input type="text" id="jenis-input" name="jenis" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity" id="jenis-submit-btn">
                            Tambah Jenis
                        </button>
                        <button type="button" class="px-6 py-2 rounded-md bg-gray-500 text-white font-semibold hover:bg-gray-400 transition-colors hidden" id="jenis-cancel-btn">
                            Batal Edit
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-dark-gray">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Jenis Karya</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-light-gray uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="jenis-list" class="divide-y divide-gray-700">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-sm text-mid-gray">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-dark-card p-8 rounded-xl shadow-lg">
                <h1 class="text-3xl font-bold mb-4 text-primary-green">Kelola Kategori</h1>
                
                <form id="kategori-form" class="mb-6 space-y-4">
                    <input type="hidden" name="id" id="kategori-id">
                    <div>
                        <label for="kategori-input" class="block text-sm font-medium text-light-gray">Nama Kategori</label>
                        <input type="text" id="kategori-input" name="nama_kategori" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity" id="kategori-submit-btn">
                            Tambah Kategori
                        </button>
                        <button type="button" class="px-6 py-2 rounded-md bg-gray-500 text-white font-semibold hover:bg-gray-400 transition-colors hidden" id="kategori-cancel-btn">
                            Batal Edit
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-dark-gray">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Nama Kategori</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-light-gray uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kategori-list" class="divide-y divide-gray-700">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-sm text-mid-gray">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load data saat halaman pertama dimuat
            loadData('jenis');
            loadData('kategori');

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

            // --- Fungsi CRUD untuk Jenis Karya dan Kategori ---
            function loadData(type) {
                fetch(`api_karya_attributes.php?type=${type}`)
                    .then(response => response.json())
                    .then(data => {
                        const listElement = document.getElementById(`${type}-list`);
                        listElement.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const row = document.createElement('tr');
                                row.className = 'bg-dark-card hover:bg-dark-gray transition-colors';
                                row.innerHTML = `
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">${item.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <button onclick="editItem('${type}', ${item.id}, '${item.name}')" class="text-yellow-500 hover:opacity-80 transition-opacity mr-2">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                        <button onclick="deleteItem('${type}', ${item.id}, '${item.name}')" class="text-red-error hover:opacity-80 transition-opacity">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </td>
                                `;
                                listElement.appendChild(row);
                            });
                        } else {
                            listElement.innerHTML = `<tr><td colspan="2" class="px-6 py-4 text-center text-sm text-mid-gray">Tidak ada data.</td></tr>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        document.getElementById(`${type}-list`).innerHTML = `<tr><td colspan="2" class="px-6 py-4 text-center text-sm text-red-error">Gagal memuat data.</td></tr>`;
                    });
            }

            window.editItem = function(type, id, name) {
                document.getElementById(`${type}-id`).value = id;
                document.getElementById(`${type}-input`).value = name;
                document.getElementById(`${type}-submit-btn`).textContent = `Simpan Perubahan`;
                document.getElementById(`${type}-cancel-btn`).classList.remove('hidden');
                document.getElementById(`${type}-input`).focus();
            };

            window.deleteItem = function(type, id, name) {
                if (confirm(`Apakah Anda yakin ingin menghapus '${name}'?`)) {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('type', type);
                    formData.append('id', id);

                    fetch('api_karya_attributes.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(`Data ${name} berhasil dihapus.`);
                            loadData(type);
                        } else {
                            alert(`Gagal menghapus data: ${data.message}`);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting data:', error);
                        alert('Terjadi kesalahan saat menghapus data.');
                    });
                }
            };

            // Event listener untuk form Jenis Karya
            document.getElementById('jenis-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const id = formData.get('id');
                formData.append('action', id ? 'edit' : 'add');
                formData.append('type', 'jenis');
                
                fetch('api_karya_attributes.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Jenis karya berhasil ${id ? 'diperbarui' : 'ditambahkan'}.`);
                        this.reset();
                        document.getElementById('jenis-id').value = '';
                        document.getElementById('jenis-submit-btn').textContent = `Tambah Jenis`;
                        document.getElementById('jenis-cancel-btn').classList.add('hidden');
                        loadData('jenis');
                    } else {
                        alert(`Gagal menyimpan data: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error saving data:', error);
                    alert('Terjadi kesalahan saat menyimpan data.');
                });
            });

            document.getElementById('jenis-cancel-btn').addEventListener('click', function() {
                document.getElementById('jenis-form').reset();
                document.getElementById('jenis-id').value = '';
                document.getElementById('jenis-submit-btn').textContent = `Tambah Jenis`;
                this.classList.add('hidden');
            });

            // Event listener untuk form Kategori
            document.getElementById('kategori-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const id = formData.get('id');
                formData.append('action', id ? 'edit' : 'add');
                formData.append('type', 'kategori');
                
                fetch('api_karya_attributes.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Kategori berhasil ${id ? 'diperbarui' : 'ditambahkan'}.`);
                        this.reset();
                        document.getElementById('kategori-id').value = '';
                        document.getElementById('kategori-submit-btn').textContent = `Tambah Kategori`;
                        document.getElementById('kategori-cancel-btn').classList.add('hidden');
                        loadData('kategori');
                    } else {
                        alert(`Gagal menyimpan data: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error saving data:', error);
                    alert('Terjadi kesalahan saat menyimpan data.');
                });
            });

            document.getElementById('kategori-cancel-btn').addEventListener('click', function() {
                document.getElementById('kategori-form').reset();
                document.getElementById('kategori-id').value = '';
                document.getElementById('kategori-submit-btn').textContent = `Tambah Kategori`;
                this.classList.add('hidden');
            });
        });
    </script>
</body>
</html>