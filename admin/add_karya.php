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
$jenis_karya = [];
$kategori = [];

/**
 * Generate a unique random slug of 6 characters for the 'karya' table.
 *
 * @param mysqli $conn The database connection.
 * @return string The unique slug.
 */
function generateUniqueKaryaSlug($conn) {
    do {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $slug = substr(str_shuffle($characters), 0, 6);
        $sql = "SELECT slug FROM karya WHERE slug = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $slug);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $slugExists = mysqli_stmt_num_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
    } while ($slugExists);
    
    return $slug;
}

// Ambil data jenis karya dan kategori dari database untuk dropdown
$sql_jenis = "SELECT id_jenis, jenis, id_kategori FROM jenis_karya ORDER BY jenis ASC";
$result_jenis = mysqli_query($conn, $sql_jenis);
if ($result_jenis) {
    while ($row = mysqli_fetch_assoc($result_jenis)) {
        $jenis_karya[] = $row;
    }
}

$sql_kategori = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$result_kategori = mysqli_query($conn, $sql_kategori);
if ($result_kategori) {
    // Ubah array menjadi asosiatif dengan id_kategori sebagai kunci
    while ($row = mysqli_fetch_assoc($result_kategori)) {
        $kategori[$row['id_kategori']] = $row['nama_kategori'];
    }
}

// Proses form jika ada POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tentukan batasan ukuran file 100MB (dalam byte)
    $max_file_size = 100 * 1024 * 1024;

    $total_files_size = 0;
    // Hitung total ukuran file yang diunggah
    if (isset($_FILES['pict_karya']['tmp_name'])) {
        foreach ($_FILES['pict_karya']['tmp_name'] as $tmp_name) {
            $total_files_size += filesize($tmp_name);
        }
    }

    if ($total_files_size > $max_file_size) {
        $error_message = "Total ukuran gambar/video melebihi batas 100MB. Silakan kurangi jumlah atau ukuran file.";
    } else {
        $success_count = 0;
        $error_count = 0;

        // Ambil data form sebagai array
        $judul_karya = $_POST['judul_karya'];
        $nama_karya = $_POST['nama_karya'];
        $instagram = $_POST['instagram'];
        $deskripsi = $_POST['deskripsi'];
        $id_jenis = $_POST['id_jenis'];
        $nim = $_POST['NIM'];
        $id_kategori = $_POST['id_kategori'];
        $optional_karya = $_POST['optional_karya'];

        // Loop melalui setiap entri form
        foreach ($judul_karya as $index => $judul) {
            $current_judul = trim($judul);
            $current_nama = trim($nama_karya[$index]);
            $current_instagram = trim($instagram[$index]);
            $current_deskripsi = trim($deskripsi[$index]);
            $current_id_jenis = intval($id_jenis[$index]);
            $current_nim = trim($nim[$index]);
            $current_id_kategori = intval($id_kategori[$index]);
            $current_optional_karya = trim($optional_karya[$index]);

            if (empty($current_judul) || empty($current_nama) || empty($current_id_jenis)) {
                $error_count++;
                continue; // Lewati entri yang tidak lengkap
            }

            $pict_karya_to_db = null;

            // Proses unggah gambar/video utama (pict_karya)
            if (isset($_FILES['pict_karya']['error'][$index]) && $_FILES['pict_karya']['error'][$index] == UPLOAD_ERR_OK) {
                $upload_dir = '../img/karya/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $file_name = uniqid() . '_' . basename($_FILES['pict_karya']['name'][$index]);
                $file_path = $upload_dir . $file_name;
                
                // Pindahkan file yang diunggah ke direktori
                if (move_uploaded_file($_FILES['pict_karya']['tmp_name'][$index], $file_path)) {
                    $pict_karya_to_db = $file_name;
                }
            }

            // Panggil fungsi untuk mendapatkan slug unik
            $current_slug = generateUniqueKaryaSlug($conn);

            // Siapkan dan jalankan query INSERT
            // TAMBAH: `slug` pada daftar kolom dan VALUES
            $sql = "INSERT INTO karya (slug, judul_karya, nama_karya, instagram, deskripsi, id_jenis, NIM, id_kategori, pict_karya, optional_karya) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            if ($stmt = mysqli_prepare($conn, $sql)) {
                // PERBAIKI DAN TAMBAH: Tambahkan tipe data 's' untuk slug dan perbaiki urutan tipe data lainnya
                mysqli_stmt_bind_param($stmt, "sssssisiss", $param_slug, $param_judul, $param_nama, $param_instagram, $param_deskripsi, $param_id_jenis, $param_nim, $param_id_kategori, $param_pict, $param_optional);
                
                $param_slug = $current_slug; // TAMBAH: Parameter untuk slug
                $param_judul = $current_judul;
                $param_nama = $current_nama;
                $param_instagram = $current_instagram;
                $param_deskripsi = $current_deskripsi;
                $param_id_jenis = $current_id_jenis;
                $param_nim = $current_nim;
                $param_id_kategori = $current_id_kategori;
                $param_pict = $pict_karya_to_db;
                $param_optional = $current_optional_karya;

                if (mysqli_stmt_execute($stmt)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
                mysqli_stmt_close($stmt);
            } else {
                $error_count++;
            }
        }
        
        if ($success_count > 0) {
            log_admin_activity($conn, $_SESSION['id'], 'create', 'Menambah ' . $success_count . ' karya baru.');
            $success_message = "$success_count karya berhasil ditambahkan!";
        }
        if ($error_count > 0) {
            $error_message = "Terjadi kesalahan saat menyimpan $error_count data. Mohon periksa kembali.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Karya</title>
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
            <span class="text-lg font-semibold text-light-gray">Tambah Karya</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Tambah Karya</h1>
            <p class="text-mid-gray mb-6">Tambahkan data karya baru ke sistem secara massal.</p>

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

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="space-y-6" id="add-karya-form">
                <div id="form-container" class="space-y-8">
                    <div class="karya-form-group p-6 rounded-md border border-gray-700 space-y-4">
                        <div class="flex justify-end">
                            <button type="button" class="remove-karya-btn text-red-error hover:opacity-80 transition-opacity hidden">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>
                        <div>
                            <label for="judul_karya_0" class="block text-sm font-medium text-light-gray">Judul Karya</label>
                            <input type="text" id="judul_karya_0" name="judul_karya[]" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="nama_karya_0" class="block text-sm font-medium text-light-gray">Nama Karya</label>
                            <input type="text" id="nama_karya_0" name="nama_karya[]" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="instagram_0" class="block text-sm font-medium text-light-gray">Instagram (Opsional)</label>
                            <input type="text" id="instagram_0" name="instagram[]" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="deskripsi_0" class="block text-sm font-medium text-light-gray">Deskripsi (Opsional)</label>
                            <textarea id="deskripsi_0" name="deskripsi[]" rows="4" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50"></textarea>
                        </div>
                        <div>
                            <label for="id_jenis_0" class="block text-sm font-medium text-light-gray">Jenis Karya</label>
                            <select id="id_jenis_0" name="id_jenis[]" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                                <option value="">Pilih Jenis Karya</option>
                                <?php foreach ($jenis_karya as $jenis): ?>
                                    <?php
                                        // Dapatkan nama kategori yang sesuai dari array $kategori
                                        $nama_kategori = isset($kategori[$jenis['id_kategori']]) ? $kategori[$jenis['id_kategori']] : 'Tidak Diketahui';
                                    ?>
                                    <option value="<?php echo htmlspecialchars($jenis['id_jenis']); ?>">
                                        <?php echo htmlspecialchars($jenis['jenis']); ?> | <?php echo htmlspecialchars($nama_kategori); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="NIM_0" class="block text-sm font-medium text-light-gray">NIM (Opsional)</label>
                            <input type="text" id="NIM_0" name="NIM[]" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="id_kategori_0" class="block text-sm font-medium text-light-gray">Kategori Karya</label>
                            <select id="id_kategori_0" name="id_kategori[]" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                                <option value="">Pilih Kategori Karya</option>
                                <?php foreach ($kategori as $id_kat => $nama_kat): ?>
                                    <option value="<?php echo htmlspecialchars($id_kat); ?>"><?php echo htmlspecialchars($nama_kat); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="pict_karya_0" class="block text-sm font-medium text-light-gray">Gambar/Video Utama</label>
                            <div class="mt-2 mb-4 media-preview-container">
                                </div>
                            <input type="file" id="pict_karya_0" name="pict_karya[]" accept="image/*,video/*" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-green file:text-dark">
                        </div>
                        <div>
                            <label for="optional_karya_0" class="block text-sm font-medium text-light-gray">Link Gambar Opsional (Opsional)</label>
                            <input type="text" id="optional_karya_0" name="optional_karya[]" placeholder="Masukkan URL gambar opsional" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" id="add-form-btn" class="px-6 py-2 rounded-md bg-gray-600 text-white font-semibold hover:bg-gray-500 transition-colors">
                        Tambah Form
                    </button>
                    <button type="submit" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity flex items-center justify-center" id="submit-btn">
                        <span>Tambah Karya</span>
                        <div id="loading-spinner" class="spinner ml-2 hidden"></div>
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formContainer = document.getElementById('form-container');
            const addFormBtn = document.getElementById('add-form-btn');
            const submitBtn = document.getElementById('submit-btn');
            const loadingSpinner = document.getElementById('loading-spinner');
            const mainForm = document.getElementById('add-karya-form');

            let formCount = 1;

            // Fungsi untuk membuat dan menambahkan pratinjau media
            function setupMediaPreview(fileInput) {
                const container = fileInput.closest('.karya-form-group').querySelector('.media-preview-container');
                fileInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    container.innerHTML = ''; // Hapus pratinjau sebelumnya
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (file.type.startsWith('image/')) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.alt = 'Pratinjau Gambar Utama';
                                img.className = 'h-48 w-full object-cover rounded-md';
                                container.appendChild(img);
                            } else if (file.type.startsWith('video/')) {
                                const video = document.createElement('video');
                                video.src = e.target.result;
                                video.controls = true;
                                video.className = 'h-48 w-full object-contain rounded-md';
                                container.appendChild(video);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Atur pratinjau untuk form pertama
            setupMediaPreview(document.getElementById('pict_karya_0'));

            addFormBtn.addEventListener('click', () => {
                const template = document.querySelector('.karya-form-group');
                const newForm = template.cloneNode(true);
                
                // Perbarui ID dan nama untuk form yang diduplikasi
                newForm.querySelectorAll('[id]').forEach(el => {
                    const originalId = el.id;
                    const newId = originalId.replace(/_0$/, `_${formCount}`);
                    el.id = newId;
                });
                
                // Bersihkan nilai input dari form duplikasi
                newForm.querySelectorAll('input, textarea').forEach(input => {
                    input.value = '';
                });

                // Hapus pratinjau media dari template
                const mediaPreviewContainer = newForm.querySelector('.media-preview-container');
                mediaPreviewContainer.innerHTML = '';

                // Atur ulang pratinjau media untuk form baru
                const fileInput = newForm.querySelector('input[type="file"]');
                setupMediaPreview(fileInput);

                // Tampilkan tombol hapus untuk form yang diduplikasi
                newForm.querySelector('.remove-karya-btn').classList.remove('hidden');

                formContainer.appendChild(newForm);
                formCount++;
            });

            // Tambahkan event listener untuk tombol hapus form
            formContainer.addEventListener('click', (event) => {
                if (event.target.closest('.remove-karya-btn')) {
                    const formToRemove = event.target.closest('.karya-form-group');
                    formToRemove.remove();
                }
            });
            
            // Indikator loading saat form dikirim
            mainForm.addEventListener('submit', () => {
                submitBtn.setAttribute('disabled', 'disabled');
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                loadingSpinner.classList.remove('hidden');
            });

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
        });
    </script>
</body>
</html>