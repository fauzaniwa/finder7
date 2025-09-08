<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Pastikan hanya admin 'master' yang bisa mengakses
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    header("location: login.php");
    exit;
}

$success_message = '';
$error_message = '';
$karya_data = null;
$jenis_karya = [];
$kategori = [];

// Ambil data jenis karya dan kategori dari database untuk dropdown
$sql_jenis = "SELECT id_jenis, jenis FROM jenis_karya ORDER BY jenis ASC";
$result_jenis = mysqli_query($conn, $sql_jenis);
if ($result_jenis) {
    while ($row = mysqli_fetch_assoc($result_jenis)) {
        $jenis_karya[] = $row;
    }
}

$sql_kategori = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$result_kategori = mysqli_query($conn, $sql_kategori);
if ($result_kategori) {
    while ($row = mysqli_fetch_assoc($result_kategori)) {
        $kategori[] = $row;
    }
}

// Proses form jika ada POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan ID karya ada dari hidden input
    if (isset($_POST['id_karya']) && !empty(trim($_POST['id_karya']))) {
        $id_karya = trim($_POST['id_karya']);
        
        // Tentukan batasan ukuran file 100MB (dalam byte)
        $max_file_size = 100 * 1024 * 1024;
        $total_files_size = 0;
        
        // Periksa apakah ada file baru yang diunggah dan hitung ukurannya
        if (isset($_FILES['pict_karya']) && $_FILES['pict_karya']['error'] == UPLOAD_ERR_OK) {
            $total_files_size += $_FILES['pict_karya']['size'];
        }

        if ($total_files_size > $max_file_size) {
            $error_message = "Ukuran file yang diunggah melebihi batas 100MB.";
        } else {
            $judul_karya = trim($_POST['judul_karya']);
            $nama_karya = trim($_POST['nama_karya']);
            $instagram = trim($_POST['instagram']);
            $deskripsi = trim($_POST['deskripsi']);
            $id_jenis = intval($_POST['id_jenis']);
            $nim = trim($_POST['NIM']);
            $id_kategori = intval($_POST['id_kategori']);
            $optional_karya_to_db = trim($_POST['optional_karya']);

            // Dapatkan nama file lama untuk dihapus
            $sql_get_old_file = "SELECT pict_karya FROM karya WHERE id_karya = ?";
            $old_file_name = null;
            if ($stmt_old = mysqli_prepare($conn, $sql_get_old_file)) {
                mysqli_stmt_bind_param($stmt_old, "i", $id_karya);
                mysqli_stmt_execute($stmt_old);
                mysqli_stmt_store_result($stmt_old);
                if (mysqli_stmt_num_rows($stmt_old) == 1) {
                    mysqli_stmt_bind_result($stmt_old, $old_file_name);
                    mysqli_stmt_fetch($stmt_old);
                }
                mysqli_stmt_close($stmt_old);
            }

            $pict_karya_to_db = $old_file_name;

            // Proses unggah gambar/video utama (pict_karya)
            if (isset($_FILES['pict_karya']) && $_FILES['pict_karya']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = '../img/karya/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                // Hapus gambar/video lama jika ada
                if (!empty($old_file_name) && file_exists($upload_dir . $old_file_name)) {
                    unlink($upload_dir . $old_file_name);
                }
                $file_name = uniqid() . '_' . basename($_FILES['pict_karya']['name']);
                $file_path = $upload_dir . $file_name;
                if (move_uploaded_file($_FILES['pict_karya']['tmp_name'], $file_path)) {
                    $pict_karya_to_db = $file_name;
                } else {
                    $error_message = "Gagal mengunggah gambar/video utama.";
                }
            }

            if (empty($error_message)) {
                $sql = "UPDATE karya SET judul_karya = ?, nama_karya = ?, instagram = ?, deskripsi = ?, id_jenis = ?, NIM = ?, id_kategori = ?, pict_karya = ?, optional_karya = ? WHERE id_karya = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ssssiisssi", $param_judul, $param_nama, $param_instagram, $param_deskripsi, $param_id_jenis, $param_nim, $param_id_kategori, $param_pict, $param_optional, $param_id);
                    
                    $param_judul = $judul_karya;
                    $param_nama = $nama_karya;
                    $param_instagram = $instagram;
                    $param_deskripsi = $deskripsi;
                    $param_id_jenis = $id_jenis;
                    $param_nim = $nim;
                    $param_id_kategori = $id_kategori;
                    $param_pict = $pict_karya_to_db;
                    $param_optional = $optional_karya_to_db;
                    $param_id = $id_karya;

                    if (mysqli_stmt_execute($stmt)) {
                        log_admin_activity($conn, $_SESSION['id'], 'update', 'Mengedit karya: ' . $judul_karya);
                        $success_message = "Karya berhasil diperbarui!";
                        // Redirect setelah sukses untuk mencegah resubmisi form
                        header("location: edit_karya.php?id=" . $id_karya . "&status=success");
                        exit;
                    } else {
                        $error_message = "Terjadi kesalahan saat menyimpan data: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
    } else {
        $error_message = "ID karya tidak valid.";
    }
}

// Ambil data karya jika ada ID di URL
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $sql = "SELECT * FROM karya WHERE id_karya = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = trim($_GET['id']);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $karya_data = mysqli_fetch_assoc($result);
            } else {
                // ID tidak ditemukan, redirect ke halaman list
                header("location: karya_list.php");
                exit;
            }
        } else {
            $error_message = "Terjadi kesalahan saat memuat data.";
        }
        mysqli_stmt_close($stmt);
    }
} else if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Redirect jika halaman diakses tanpa ID (bukan dari POST request)
    header("location: karya_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Karya</title>
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
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
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
            <span class="text-lg font-semibold text-light-gray">Edit Karya</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Edit Karya</h1>
            <p class="text-mid-gray mb-6">Ubah data karya yang sudah ada.</p>

            <?php if ($success_message || (isset($_GET['status']) && $_GET['status'] == 'success')): ?>
                <div class="bg-green-500 text-white p-4 rounded mb-6">
                    Karya berhasil diperbarui!
                </div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="bg-red-error text-white p-4 rounded mb-6">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($karya_data): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="space-y-6" id="edit-karya-form">
                <input type="hidden" name="id_karya" value="<?php echo htmlspecialchars($karya_data['id_karya']); ?>">
                
                <div>
                    <label for="judul_karya" class="block text-sm font-medium text-light-gray">Judul Karya</label>
                    <input type="text" id="judul_karya" name="judul_karya" value="<?php echo htmlspecialchars($karya_data['judul_karya']); ?>" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="nama_karya" class="block text-sm font-medium text-light-gray">Nama Karya</label>
                    <input type="text" id="nama_karya" name="nama_karya" value="<?php echo htmlspecialchars($karya_data['nama_karya']); ?>" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="instagram" class="block text-sm font-medium text-light-gray">Instagram (Opsional)</label>
                    <input type="text" id="instagram" name="instagram" value="<?php echo htmlspecialchars($karya_data['instagram']); ?>" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-light-gray">Deskripsi (Opsional)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50"><?php echo htmlspecialchars($karya_data['deskripsi']); ?></textarea>
                </div>
                <div>
                    <label for="id_jenis" class="block text-sm font-medium text-light-gray">Jenis Karya</label>
                    <select id="id_jenis" name="id_jenis" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                        <option value="">Pilih Jenis Karya</option>
                        <?php foreach ($jenis_karya as $jenis): ?>
                            <option value="<?php echo $jenis['id_jenis']; ?>" <?php echo ($karya_data['id_jenis'] == $jenis['id_jenis']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($jenis['jenis']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="NIM" class="block text-sm font-medium text-light-gray">NIM (Opsional)</label>
                    <input type="text" id="NIM" name="NIM" value="<?php echo htmlspecialchars($karya_data['NIM']); ?>" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="id_kategori" class="block text-sm font-medium text-light-gray">Kategori Karya</label>
                    <select id="id_kategori" name="id_kategori" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                        <option value="">Pilih Kategori Karya</option>
                        <?php foreach ($kategori as $kat): ?>
                            <option value="<?php echo $kat['id_kategori']; ?>" <?php echo ($karya_data['id_kategori'] == $kat['id_kategori']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="pict_karya" class="block text-sm font-medium text-light-gray">Gambar/Video Utama</label>
                    <?php 
                        $media_path = '../img/karya/' . $karya_data['pict_karya'];
                        $is_video = false;
                        if (file_exists($media_path) && !empty($karya_data['pict_karya'])) {
                            $file_info = new finfo(FILEINFO_MIME_TYPE);
                            $mime_type = $file_info->buffer(file_get_contents($media_path));
                            $is_video = str_contains($mime_type, 'video');
                        }
                        $media_src = (file_exists($media_path) && !empty($karya_data['pict_karya'])) ? $media_path : '../img/noimage.png';
                    ?>
                    <div class="mt-2 mb-4">
                        <?php if ($is_video): ?>
                            <video controls class="h-48 w-full object-contain rounded-md" src="<?php echo htmlspecialchars($media_src); ?>"></video>
                        <?php else: ?>
                            <img alt="Media Utama Saat Ini" class="h-48 w-full object-cover rounded-md" src="<?php echo htmlspecialchars($media_src); ?>">
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-mid-gray mb-2">Unggah file baru untuk mengganti media. Ukuran maksimum 100MB.</p>
                    <input type="file" id="pict_karya" name="pict_karya" accept="image/*,video/*" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-green file:text-dark">
                </div>
                <div>
                    <label for="optional_karya" class="block text-sm font-medium text-light-gray">Link Gambar Opsional (Opsional)</label>
                    <input type="text" id="optional_karya" name="optional_karya" value="<?php echo htmlspecialchars($karya_data['optional_karya'] ?? ''); ?>" placeholder="Masukkan URL gambar opsional" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="karya_list.php" class="px-6 py-2 rounded-md bg-gray-500 text-white font-semibold hover:bg-opacity-80 transition-opacity">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity flex items-center justify-center" id="submit-btn">
                        <span>Simpan Perubahan</span>
                        <div id="loading-spinner" class="spinner ml-2 hidden"></div>
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </main>
    <script>
        // Fungsionalitas loading indicator
        const editForm = document.getElementById('edit-karya-form');
        const submitBtn = document.getElementById('submit-btn');
        const loadingSpinner = document.getElementById('loading-spinner');

        if (editForm) {
            editForm.addEventListener('submit', () => {
                submitBtn.setAttribute('disabled', 'disabled');
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                loadingSpinner.classList.remove('hidden');
            });
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