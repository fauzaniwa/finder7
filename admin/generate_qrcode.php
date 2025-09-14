<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Cek apakah user sudah login dan memiliki role 'master'
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $content = trim($_POST['content']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validasi input
    if (empty($name) || empty($content)) {
        $error = "Nama dan konten QR code tidak boleh kosong.";
    } else {
        $sql = "INSERT INTO qrcodes (name, content, is_active) VALUES (?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssi", $param_name, $param_content, $param_active);
            
            $param_name = $name;
            $param_content = $content;
            $param_active = $is_active;

            if ($stmt->execute()) {
                $message = "QR Code berhasil ditambahkan.";
                // Redirect ke halaman daftar setelah berhasil
                header("location: qrcode_list.php?success=1");
                exit;
            } else {
                $error = "Terjadi kesalahan saat menyimpan data.";
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat QR Code Baru</title>
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
            <span class="text-lg font-semibold text-light-gray">Buat QR Code Baru</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0 max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Buat QR Code Baru</h1>
            <p class="text-mid-gray mb-6">Isi formulir di bawah ini untuk membuat QR code baru.</p>

            <?php if (!empty($error)): ?>
                <div class="bg-red-error text-white p-4 rounded-md mb-4"><?= $error ?></div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="space-y-6">
                <div>
                    <label for="name" class="block text-light-gray font-semibold mb-2">Nama QR Code</label>
                    <input type="text" id="name" name="name" class="w-full bg-dark-gray text-light-gray border border-dark-gray p-3 rounded-md focus:ring-2 focus:ring-primary-green focus:outline-none" required>
                </div>
                <div>
                    <label for="content" class="block text-light-gray font-semibold mb-2">Konten QR Code (Link/Teks)</label>
                    <textarea id="content" name="content" rows="4" class="w-full bg-dark-gray text-light-gray border border-dark-gray p-3 rounded-md focus:ring-2 focus:ring-primary-green focus:outline-none" required></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" class="h-5 w-5 text-primary-green rounded border-dark-gray focus:ring-primary-green" checked>
                    <label for="is_active" class="ml-2 text-light-gray">Aktifkan QR Code ini</label>
                </div>
                
                <div class="flex space-x-4">
                    <button type="submit" class="w-full px-6 py-3 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                        <span class="material-symbols-outlined align-middle mr-2">add_box</span> Buat QR Code
                    </button>
                    <a href="qrcode_list.php" class="w-full text-center px-6 py-3 rounded-md bg-mid-gray text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                        Batal
                    </a>
                </div>
            </form>
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