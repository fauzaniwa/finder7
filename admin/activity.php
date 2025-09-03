<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
$admin_logs = [];
$error_message = '';

// Query untuk mengambil data log aktivitas dengan nama admin
$sql = "SELECT al.id, al.action_type, al.description, al.created_at, a.name AS admin_name 
        FROM admin_logs al
        JOIN admin a ON al.admin_id = a.id
        ORDER BY al.created_at DESC";

if ($result = mysqli_query($conn, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $admin_logs[] = $row;
        }
        mysqli_free_result($result);
    } else {
        $error_message = "Tidak ada log aktivitas yang ditemukan.";
    }
} else {
    $error_message = "Terjadi kesalahan saat mengambil data: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Aktivitas Admin</title>
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
            <span class="text-lg font-semibold text-light-gray">Log Aktivitas</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Log Aktivitas Admin</h1>
            <p class="text-mid-gray mb-6">Daftar semua aktivitas yang dilakukan oleh admin.</p>

            <?php if ($error_message): ?>
                <div class="bg-red-error text-white p-4 rounded mb-6">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($admin_logs)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-dark-gray rounded-lg">
                        <thead>
                            <tr class="text-left text-light-gray bg-dark-gray">
                                <th class="py-3 px-4 uppercase font-semibold text-sm">ID</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Admin</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Jenis Aksi</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Deskripsi</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="text-mid-gray">
                            <?php foreach ($admin_logs as $log): ?>
                                <tr class="border-b border-gray-700 hover:bg-dark-card transition-colors">
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($log['id']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($log['admin_name']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($log['action_type']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($log['description']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars(date('d F Y, H:i:s', strtotime($log['created_at']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <script>
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