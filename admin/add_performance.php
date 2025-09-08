<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    header("location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_penampil = trim($_POST['nama_penampil']);
    $tanggal_tampil = trim($_POST['tanggal_tampil']);
    $jam_tampil = trim($_POST['jam_tampil']);
    $lokasi_tampil = trim($_POST['lokasi_tampil']);
    $status_view = isset($_POST['status_view']) ? 1 : 0;
    $image_penampil_to_db = null;
    $image_logo_to_db = null;

    if (empty($nama_penampil) || empty($tanggal_tampil) || empty($jam_tampil) || empty($lokasi_tampil)) {
        $error_message = "Semua field wajib diisi.";
    } else {
        // Handle image_penampil upload
        if (isset($_FILES['image_penampil']) && $_FILES['image_penampil']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = '../img/performance/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_name = uniqid() . '_' . basename($_FILES['image_penampil']['name']);
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image_penampil']['tmp_name'], $file_path)) {
                $image_penampil_to_db = $file_name;
            } else {
                $error_message = "Gagal mengunggah gambar penampil.";
            }
        }

        // Handle image_logo upload
        if (isset($_FILES['image_logo']) && $_FILES['image_logo']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = '../img/performance/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_name = uniqid() . '_logo_' . basename($_FILES['image_logo']['name']);
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image_logo']['tmp_name'], $file_path)) {
                $image_logo_to_db = $file_name;
            } else {
                $error_message .= " Gagal mengunggah logo penampil.";
            }
        }

        if (empty($error_message)) {
            $sql = "INSERT INTO performance (nama_penampil, tanggal_tampil, jam_tampil, lokasi_tampil, path_image_penampil, path_image_logo_penampil, status_view) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssssssi", $param_nama, $param_tanggal, $param_jam, $param_lokasi, $param_image_penampil, $param_image_logo, $param_status);
                
                $param_nama = $nama_penampil;
                $param_tanggal = $tanggal_tampil;
                $param_jam = $jam_tampil;
                $param_lokasi = $lokasi_tampil;
                $param_image_penampil = $image_penampil_to_db;
                $param_image_logo = $image_logo_to_db;
                $param_status = $status_view;

                if (mysqli_stmt_execute($stmt)) {
                    if (isset($_SESSION['id'])) {
                        log_admin_activity($conn, $_SESSION['id'], 'create', 'Menambah penampil baru: ' . $nama_penampil);
                    }
                    header("location: performance_list.php");
                    exit;
                } else {
                    $error_message = "Terjadi kesalahan saat menyimpan data: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penampil</title>
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
            <span class="text-lg font-semibold text-light-gray">Tambah Penampil</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Tambah Penampil</h1>
            <p class="text-mid-gray mb-6">Tambahkan data penampil baru untuk halaman performance.</p>
            
            <?php if ($success_message): ?>
                <div class="bg-green-500 text-white p-4 rounded mb-6">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="bg-red-error text-white p-4 rounded mb-6">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="nama_penampil" class="block text-sm font-medium text-light-gray">Nama Penampil</label>
                    <input type="text" id="nama_penampil" name="nama_penampil" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="tanggal_tampil" class="block text-sm font-medium text-light-gray">Tanggal Tampil</label>
                    <input type="date" id="tanggal_tampil" name="tanggal_tampil" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="jam_tampil" class="block text-sm font-medium text-light-gray">Jam Tampil</label>
                    <input type="time" id="jam_tampil" name="jam_tampil" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="lokasi_tampil" class="block text-sm font-medium text-light-gray">Lokasi Tampil</label>
                    <input type="text" id="lokasi_tampil" name="lokasi_tampil" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="image_penampil" class="block text-sm font-medium text-light-gray">Foto Penampil</label>
                    <div class="mt-2 mb-4">
                        <img id="image-penampil-preview" src="#" alt="Pratinjau Foto Penampil" class="h-24 w-24 rounded-full object-cover hidden">
                    </div>
                    <input type="file" id="image_penampil" name="image_penampil" accept="image/*" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-green file:text-dark">
                </div>
                <div>
                    <label for="image_logo" class="block text-sm font-medium text-light-gray">Logo Penampil</label>
                    <div class="mt-2 mb-4">
                        <img id="image-logo-preview" src="#" alt="Pratinjau Logo Penampil" class="h-16 w-16 object-contain hidden">
                    </div>
                    <input type="file" id="image_logo" name="image_logo" accept="image/*" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-green file:text-dark">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="status_view" name="status_view" checked class="h-4 w-4 text-primary-green bg-dark-gray border-gray-700 rounded focus:ring-primary-green">
                    <label for="status_view" class="ml-2 block text-sm text-light-gray">Aktif</label>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                        Tambah Penampil
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        const imagePenampilInput = document.getElementById('image_penampil');
        const imagePenampilPreview = document.getElementById('image-penampil-preview');
        const imageLogoInput = document.getElementById('image_logo');
        const imageLogoPreview = document.getElementById('image-logo-preview');

        function setupImagePreview(inputElement, previewElement) {
            if (inputElement) {
                inputElement.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewElement.src = e.target.result;
                            previewElement.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewElement.src = '#';
                        previewElement.classList.add('hidden');
                    }
                });
            }
        }

        setupImagePreview(imagePenampilInput, imagePenampilPreview);
        setupImagePreview(imageLogoInput, imageLogoPreview);

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