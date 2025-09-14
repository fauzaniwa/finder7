<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Cek apakah user sudah login dan memiliki role 'master'
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$admin_id = $_SESSION["id"];
$admin_name = $_SESSION["name"];

// Ambil semua data QR code dari database
$sql = "SELECT * FROM qrcodes ORDER BY created_at DESC";
$result = $conn->query($sql);
$qrcodes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $qrcodes[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen QR Code</title>
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
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
</head>
<body class="bg-dark text-white font-poppins flex">

    <?php include_once 'sidebar.php'; ?>

    <main class="flex-grow p-6 overflow-x-hidden">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
        
        <header class="bg-dark-card p-4 flex justify-between items-center lg:hidden sticky top-0 z-40">
            <button id="open-sidebar-btn" class="text-white">
                <span class="material-symbols-outlined text-3xl">menu</span>
            </button>
            <span class="text-lg font-semibold text-light-gray">Manajemen QR Code</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Manajemen QR Code</h1>
            <p class="text-mid-gray mb-6">Kelola QR code untuk berbagai konten di website Anda.</p>

            <div class="flex justify-end mb-4">
                <a href="generate_qrcode.php" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                    <span class="material-symbols-outlined align-middle mr-1">add</span> Buat QR Code
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-dark-gray">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Konten</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">QR Code</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-light-gray uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-light-gray uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="qrcode-list" class="divide-y divide-gray-700">
                        <?php if (!empty($qrcodes)): ?>
                            <?php foreach ($qrcodes as $item): ?>
                                <tr class="bg-dark-card hover:bg-dark-gray transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray"><?= htmlspecialchars($item['name']) ?></td>
                                    <td class="px-6 py-4 max-w-xs truncate text-sm text-mid-gray" title="<?= htmlspecialchars($item['content']) ?>"><?= htmlspecialchars($item['content']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">
                                        <div id="qrcode-<?= $item['id'] ?>" class="p-2 bg-white rounded-lg"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-mid-gray">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $item['is_active'] === '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= $item['is_active'] === '1' ? 'Aktif' : 'Tidak Aktif' ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="edit_qrcode.php?id=<?= $item['id'] ?>" class="text-yellow-500 hover:opacity-80 transition-opacity">
                                                <span class="material-symbols-outlined">edit</span>
                                            </a>
                                            <button onclick="confirmDelete(<?= $item['id'] ?>, '<?= htmlspecialchars($item['name']) ?>')" class="text-red-error hover:opacity-80 transition-opacity">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                            <button onclick="showDownloadModal('<?= htmlspecialchars($item['content']) ?>', '<?= htmlspecialchars($item['name']) ?>')" class="text-primary-green hover:opacity-80 transition-opacity">
                                                <span class="material-symbols-outlined">download</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-mid-gray">Tidak ada data QR code.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="downloadModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-dark-card text-white p-6 md:p-8 rounded-xl w-full max-w-sm shadow-lg max-h-[90vh] overflow-y-auto relative text-center">
                <div class="flex justify-between items-start mb-4 bg-dark-card pb-4 -mx-6 -mt-6 px-6 pt-6 rounded-t-xl">
                    <h3 class="text-xl font-semibold text-primary-green">Download QR Code</h3>
                    <button class="text-mid-gray hover:text-light-gray transition-colors" onclick="closeDownloadModal()">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div id="qr-preview" class="p-4 bg-white rounded-xl mx-auto my-4 w-48 h-48"></div>
                <p class="text-mid-gray mb-4">Pilih ukuran untuk download:</p>
                <div class="flex flex-col space-y-2">
                    <button onclick="downloadQR(1080)" class="w-full px-4 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                        1080 x 1080 px
                    </button>
                    <button onclick="downloadQR(800)" class="w-full px-4 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                        800 x 800 px
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        let currentQRContent = '';
        let currentQRName = '';

        document.addEventListener('DOMContentLoaded', function() {
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

            // Generate QR codes untuk setiap item
            <?php foreach ($qrcodes as $item): ?>
                new QRCode(document.getElementById('qrcode-<?= $item['id'] ?>'), {
                    text: '<?= addslashes($item['content']) ?>',
                    width: 80,
                    height: 80,
                });
            <?php endforeach; ?>
        });

        function confirmDelete(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus QR code "${name}"?`)) {
                window.location.href = `delete_qrcode.php?id=${id}`;
            }
        }

        function showDownloadModal(content, name) {
            currentQRContent = content;
            currentQRName = name;
            const modal = document.getElementById('downloadModal');
            const preview = document.getElementById('qr-preview');
            preview.innerHTML = '';
            
            // Generate QR code preview di dalam modal
            new QRCode(preview, {
                text: content,
                width: 150,
                height: 150
            });
            modal.classList.remove('hidden');
        }

        function closeDownloadModal() {
            document.getElementById('downloadModal').classList.add('hidden');
        }

        function downloadQR(size) {
            const tempDiv = document.createElement('div');
            tempDiv.style.position = 'absolute';
            tempDiv.style.left = '-9999px';
            document.body.appendChild(tempDiv);
            
            const qrcode = new QRCode(tempDiv, {
                text: currentQRContent,
                width: size,
                height: size,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
            
            setTimeout(() => {
                const canvas = tempDiv.querySelector('canvas');
                const image = canvas.toDataURL("image/png");
                const link = document.createElement('a');
                link.href = image;
                link.download = `qrcode-${currentQRName.replace(/\s+/g, '-')}-${size}x${size}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                document.body.removeChild(tempDiv);
                
                closeDownloadModal();
            }, 100); // Penundaan untuk memastikan QR code digenerate
        }
    </script>
</body>
</html>