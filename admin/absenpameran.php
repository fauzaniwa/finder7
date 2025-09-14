<?php
// Mulai sesi
session_start();

// Aktifkan tampilan error untuk debugging (hapus di lingkungan produksi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sertakan file koneksi database
require_once 'config.php';

// Periksa apakah admin sudah login, jika tidak, alihkan ke halaman login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Tentukan limit dan halaman
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$offset = ($page - 1) * $limit;

// Query untuk mengambil data absensi pameran terbaru dengan nama user
$query_absen = "
    SELECT 
        ap.id_absenpameran, 
        ap.kode_absen, 
        ap.created_absen, 
        u.nama,
        u.instansi
    FROM 
        absenpameran ap
    JOIN 
        user u ON ap.kode_absen = u.kode_account
    WHERE 
        u.nama LIKE ? OR u.instansi LIKE ?
    ORDER BY 
        ap.created_absen DESC
    LIMIT ?, ?
";

// Query untuk menghitung total data
$query_count = "
    SELECT 
        COUNT(*) 
    FROM 
        absenpameran ap
    JOIN 
        user u ON ap.kode_absen = u.kode_account
    WHERE 
        u.nama LIKE ? OR u.instansi LIKE ?
";

// Lakukan pencarian dan paginasi
$stmt_absen = mysqli_prepare($conn, $query_absen);
$stmt_count = mysqli_prepare($conn, $query_count);

$search_param = "%{$search_query}%";

mysqli_stmt_bind_param($stmt_absen, "ssii", $search_param, $search_param, $offset, $limit);
mysqli_stmt_execute($stmt_absen);
$result_absen = mysqli_stmt_get_result($stmt_absen);
$data_absensi = mysqli_fetch_all($result_absen, MYSQLI_ASSOC);

mysqli_stmt_bind_param($stmt_count, "ss", $search_param, $search_param);
mysqli_stmt_execute($stmt_count);
$result_count = mysqli_stmt_get_result($stmt_count);
$total_data = mysqli_fetch_row($result_count)[0];
$total_pages = ceil($total_data / $limit);

mysqli_stmt_close($stmt_absen);
mysqli_stmt_close($stmt_count);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir Pameran</title>
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
            <span class="text-lg font-semibold text-light-gray">Daftar Hadir Pameran</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Daftar Hadir Pameran</h1>
            <p class="text-mid-gray mb-6">Berikut adalah 50 data kehadiran pameran terbaru. Total: <?php echo $total_data; ?></p>
            
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
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-dark-gray">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">No.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Instansi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Waktu Absen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php if (!empty($data_absensi)): ?>
                            <?php $no = $offset + 1; ?>
                            <?php foreach ($data_absensi as $absen): ?>
                                <tr class="bg-dark-card hover:bg-dark-gray transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray"><?php echo $no++; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray"><?php echo htmlspecialchars($absen['nama']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray"><?php echo htmlspecialchars($absen['instansi']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray"><?php echo htmlspecialchars($absen['created_absen']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-mid-gray">Tidak ada data absensi pameran.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center items-center mt-6 space-x-2">
                <?php 
                $base_url = "?limit=" . $limit . (!empty($search_query) ? "&q=" . urlencode($search_query) : "");
                
                if ($page > 1): ?>
                    <a href="<?php echo $base_url . "&page=1"; ?>" class="py-2 px-4 rounded-lg text-sm bg-dark-gray text-light-gray hover:bg-mid-gray">Awal</a>
                <?php endif; 
                
                $start_page = max(1, $page - 1);
                $end_page = min($total_pages, $start_page + 2);
                
                for ($i = $start_page; $i <= $end_page; $i++): 
                ?>
                    <a href="<?php echo $base_url . "&page=" . $i; ?>" class="py-2 px-4 rounded-lg text-sm <?php echo $page == $i ? 'bg-primary-green text-dark font-bold' : 'bg-dark-gray text-light-gray hover:bg-mid-gray'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; 
                
                if ($total_pages > 3 && $end_page < $total_pages): ?>
                    <span class="py-2 px-4 text-mid-gray">...</span>
                <?php endif;

                if ($page < $total_pages): ?>
                    <a href="<?php echo $base_url . "&page=" . $total_pages; ?>" class="py-2 px-4 rounded-lg text-sm bg-dark-gray text-light-gray hover:bg-mid-gray">Akhir</a>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
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