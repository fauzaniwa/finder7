<?php
// Mulai sesi
session_start();

// Aktifkan tampilan error untuk debugging (hapus di lingkungan produksi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sertakan file koneksi database dan fungsi
require_once 'config.php';
require_once 'functions.php';
require_once 'get_data_user.php';

// Periksa apakah admin sudah login, jika tidak, alihkan ke halaman login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Ambil data admin dari sesi
$admin_id = $_SESSION["id"];
$admin_name = $_SESSION["name"];
$admin_role = $_SESSION["role"];

// Tentukan limit dan halaman
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$offset = ($page - 1) * $limit;

// Ambil data user dari database
$userData = getUsersData($limit, $offset, $search_query);
$users = $userData['data'];
$total_users = $userData['total'];
$total_pages = ceil($total_users / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna</title>
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
        .dropdown-menu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        .dropdown-menu.active { max-height: 500px; }
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
            <span class="text-lg font-semibold text-light-gray">Manajemen Pengguna</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Data Pengguna</h1>
            <p class="text-mid-gray mb-6">Berikut adalah daftar semua pengguna terdaftar. Total: <?php echo $total_users; ?></p>
            
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-2 w-full sm:w-auto">
                    <span class="text-mid-gray">Tampilkan:</span>
                    <select id="limit-select" class="px-4 py-2 rounded-md bg-dark-gray text-light-gray focus:outline-none focus:ring-2 focus:ring-primary-green">
                        <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo $limit == 100 ? 'selected' : ''; ?>>100</option>
                        <option value="500" <?php echo $limit == 500 ? 'selected' : ''; ?>>500</option>
                        <option value="99999" <?php echo $limit == 99999 ? 'selected' : ''; ?>>Semua</option>
                    </select>
                </div>
                <div class="flex items-center w-full sm:w-auto space-x-2">
                    <form action="" method="GET" class="flex items-center w-full">
                        <input type="hidden" name="limit" value="<?php echo $limit; ?>">
                        <input type="text" name="q" placeholder="Cari nama atau instansi..." value="<?php echo htmlspecialchars($search_query); ?>" class="w-full sm:w-auto px-4 py-2 rounded-md bg-dark-gray text-light-gray focus:outline-none focus:ring-2 focus:ring-primary-green">
                        <button type="submit" class="ml-2 px-4 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">Cari</button>
                    </form>
                    <a href="download_data.php" class="py-2 px-4 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity whitespace-nowrap">
                        <span class="material-symbols-outlined">download</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-dark-gray">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Instansi</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-light-gray uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="bg-dark-card hover:bg-dark-gray transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray"><?php echo htmlspecialchars($user['nama']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray"><?php echo htmlspecialchars($user['instansi']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button onclick="showDetails(<?php echo htmlspecialchars(json_encode($user)); ?>)" class="text-primary-green hover:opacity-80 transition-opacity">
                                                <span class="material-symbols-outlined">info</span>
                                            </button>
                                            <button onclick="confirmDelete(<?php echo $user['id_user']; ?>, '<?php echo htmlspecialchars($user['nama']); ?>')" class="text-red-error hover:opacity-80 transition-opacity">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-mid-gray">Tidak ada data pengguna.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center items-center mt-6 space-x-2">
                <?php 
                $base_url = "?limit=" . $limit . (!empty($search_query) ? "&q=" . urlencode($search_query) : "");
                
                // Tampilkan tombol untuk halaman pertama jika tidak di halaman pertama
                if ($page > 1): ?>
                    <a href="<?php echo $base_url . "&page=1"; ?>" class="py-2 px-4 rounded-lg text-sm bg-dark-gray text-light-gray hover:bg-mid-gray">Awal</a>
                <?php endif; 
                
                // Tentukan tombol halaman yang akan ditampilkan
                $start_page = max(1, $page - 1);
                $end_page = min($total_pages, $start_page + 2);
                
                // Tampilkan tombol halaman
                for ($i = $start_page; $i <= $end_page; $i++): 
                ?>
                    <a href="<?php echo $base_url . "&page=" . $i; ?>" class="py-2 px-4 rounded-lg text-sm <?php echo $page == $i ? 'bg-primary-green text-dark font-bold' : 'bg-dark-gray text-light-gray hover:bg-mid-gray'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; 
                
                // Tampilkan tombol elipsis dan halaman terakhir jika perlu
                if ($total_pages > 3 && $end_page < $total_pages): ?>
                    <span class="py-2 px-4 text-mid-gray">...</span>
                <?php endif;

                if ($page < $total_pages): ?>
                    <a href="<?php echo $base_url . "&page=" . $total_pages; ?>" class="py-2 px-4 rounded-lg text-sm bg-dark-gray text-light-gray hover:bg-mid-gray">Akhir</a>
                <?php endif; ?>
            </div>
        </div>

        <div id="userModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
            <div class="bg-white text-dark p-8 rounded-lg w-full max-w-3xl text-left">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-primary-green">Detail Pengguna</h3>
                    <button class="text-gray-600 hover:text-gray-900" onclick="closeModal()">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div id="modalBody" class="space-y-4 text-gray-800"></div>
            </div>
        </div>

        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
            <div class="bg-white text-dark p-8 rounded-lg w-full max-w-sm text-center">
                <span class="material-symbols-outlined text-4xl text-red-error mb-4">warning</span>
                <h3 class="text-xl font-semibold mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data pengguna ini?</p>
                <div class="flex justify-center space-x-4">
                    <button class="py-2 px-4 rounded-md bg-gray-300 text-gray-800 hover:bg-gray-400 transition-colors" onclick="closeDeleteModal()">Batal</button>
                    <a href="#" id="deleteConfirmBtn" class="py-2 px-4 rounded-md bg-red-error text-white hover:bg-opacity-80 transition-colors">Hapus</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Fungsi untuk toggle dropdown
        function toggleDropdown(id) {
            const element = document.getElementById(id);
            element.classList.toggle('active');
        }

        // Fungsionalitas sidebar mobile
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-sidebar-btn');
        const closeBtn = document.getElementById('close-sidebar-btn');
        const overlay = document.getElementById('overlay');
        
        if (openBtn) {
            openBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
            
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        // Fungsionalitas modal
        const userModal = document.getElementById('userModal');
        const modalBody = document.getElementById('modalBody');
        const deleteModal = document.getElementById('deleteModal');
        const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');

        async function showDetails(user) {
            // Tampilkan detail user dasar terlebih dahulu
            modalBody.innerHTML = `
                <h4 class="text-lg font-semibold text-primary-green mb-2">Informasi Dasar</h4>
                <p><strong>ID:</strong> ${user.id_user}</p>
                <p><strong>Nama:</strong> ${user.nama}</p>
                <p><strong>Email:</strong> ${user.email}</p>
                <p><strong>Tanggal Lahir:</strong> ${user.tgl_lahir}</p>
                <p><strong>No. HP:</strong> ${user.no_hp}</p>
                <p><strong>Instansi:</strong> ${user.instansi}</p>
                <p><strong>Kode Akun:</strong> ${user.kode_account}</p>
                <p><strong>Dibuat Pada:</strong> ${user.created}</p>
                
                <h4 class="text-lg font-semibold text-primary-green mt-6 mb-2">Pendaftaran Event</h4>
                <div id="eventList">
                    <p class="text-gray-500">Memuat data event...</p>
                </div>
            `;
            userModal.classList.remove('hidden');

            try {
                // Lakukan panggilan AJAX untuk mengambil data event
                const response = await fetch(`get_user_events.php?id_user=${user.id_user}`);
                const data = await response.json();
                
                const eventListDiv = document.getElementById('eventList');
                if (data.events && data.events.length > 0) {
                    let eventHtml = `
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Tiket</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Tiket</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal Pengambilan</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                    `;
                    data.events.forEach(event => {
                        eventHtml += `
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${event.judul_event}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${event.nilai_tiket}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${event.kode_event}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${event.jadwal_pengambilan_tiket}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${event.status_kehadiran}</td>
                            </tr>
                        `;
                    });
                    eventHtml += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    eventListDiv.innerHTML = eventHtml;
                } else {
                    eventListDiv.innerHTML = `<p class="text-gray-500">${data.message || 'Tidak ada data pendaftaran.'}</p>`;
                }
            } catch (error) {
                document.getElementById('eventList').innerHTML = '<p class="text-red-500">Gagal memuat data event.</p>';
                console.error('Error fetching event data:', error);
            }
        }

        function closeModal() {
            userModal.classList.add('hidden');
        }
        
        function confirmDelete(id, nama) {
            deleteConfirmBtn.href = 'delete_user.php?id=' + id;
            deleteModal.classList.remove('hidden');
        }
        
        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }

        // Fungsionalitas dropdown
        document.getElementById('limit-select').addEventListener('change', function() {
            const newLimit = this.value;
            const currentQuery = '<?php echo htmlspecialchars($search_query); ?>';
            const currentPage = '<?php echo htmlspecialchars($page); ?>';
            let newUrl = `?limit=${newLimit}&page=${currentPage}`;
            if (currentQuery) {
                newUrl += `&q=${encodeURIComponent(currentQuery)}`;
            }
            window.location.href = newUrl;
        });
    </script>
</body>
</html>