<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'master') {
    header("location: login.php");
    exit;
}

$speaker = [];
$success_message = '';
$error_message = '';

if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id_speaker = trim($_GET['id']);
    
    // Ambil data speaker yang akan diedit
    $sql = "SELECT * FROM speakers WHERE id_speaker = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_speaker);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $speaker = mysqli_fetch_assoc($result);
            if (!$speaker) {
                $error_message = "Speaker tidak ditemukan.";
            }
        } else {
            $error_message = "Terjadi kesalahan saat mengambil data.";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $error_message = "ID speaker tidak valid.";
}

// Proses form jika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error_message)) {
    $id_speaker = trim($_POST['id_speaker']);
    $nama_speaker = trim($_POST['nama_speaker']);
    $instansi = trim($_POST['instansi']);
    $deskripsi = trim($_POST['deskripsi']);
    $kontak = trim($_POST['kontak']);
    $urutan = intval($_POST['urutan']);
    $existing_foto = trim($_POST['existing_foto']); // Nilai dari hidden input

    if (empty($nama_speaker) || empty($instansi)) {
        $error_message = "Nama speaker dan instansi tidak boleh kosong.";
    } else {
        $foto_speaker_to_db = $existing_foto;

        // Proses unggah foto baru jika ada
        if (isset($_FILES['foto_speaker']) && $_FILES['foto_speaker']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = '../img/speakers/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_name = uniqid() . '_' . basename($_FILES['foto_speaker']['name']);
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['foto_speaker']['tmp_name'], $file_path)) {
                // Hapus foto lama jika ada
                if (!empty($existing_foto) && file_exists($upload_dir . $existing_foto)) {
                    unlink($upload_dir . $existing_foto);
                }
                $foto_speaker_to_db = $file_name; // HANYA simpan nama file ke database
            } else {
                $error_message = "Gagal mengunggah foto speaker baru.";
            }
        }

        if (empty($error_message)) {
            $sql = "UPDATE speakers SET nama_speaker=?, instansi=?, deskripsi=?, kontak=?, foto_speaker=?, urutan=? WHERE id_speaker=?";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssssii", $param_nama, $param_instansi, $param_deskripsi, $param_kontak, $param_foto, $param_urutan, $param_id);
                
                $param_nama = $nama_speaker;
                $param_instansi = $instansi;
                $param_deskripsi = $deskripsi;
                $param_kontak = $kontak;
                $param_foto = $foto_speaker_to_db;
                $param_urutan = $urutan;
                $param_id = $id_speaker;

                if (mysqli_stmt_execute($stmt)) {
                    $success_message = "Data speaker berhasil diperbarui!";
                    // Perbarui data speaker di halaman
                    $speaker['nama_speaker'] = $nama_speaker;
                    $speaker['instansi'] = $instansi;
                    $speaker['deskripsi'] = $deskripsi;
                    $speaker['kontak'] = $kontak;
                    $speaker['foto_speaker'] = $foto_speaker_to_db;
                    $speaker['urutan'] = $urutan;
                } else {
                    $error_message = "Terjadi kesalahan saat memperbarui data: " . mysqli_error($conn);
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
    <title>Edit Speaker</title>
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
            <span class="text-lg font-semibold text-light-gray">Edit Speaker</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold mb-4 text-primary-green">Edit Speaker</h1>
            <p class="text-mid-gray mb-6">Ubah data untuk speaker: <?php echo htmlspecialchars($speaker['nama_speaker'] ?? ''); ?></p>

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

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . htmlspecialchars($id_speaker ?? ''); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="id_speaker" value="<?php echo htmlspecialchars($speaker['id_speaker'] ?? ''); ?>">
                <input type="hidden" name="existing_foto" value="<?php echo htmlspecialchars($speaker['foto_speaker'] ?? ''); ?>">
                
                <div>
                    <label for="nama_speaker" class="block text-sm font-medium text-light-gray">Nama Speaker</label>
                    <input type="text" id="nama_speaker" name="nama_speaker" value="<?php echo htmlspecialchars($speaker['nama_speaker'] ?? ''); ?>" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="instansi" class="block text-sm font-medium text-light-gray">Instansi</label>
                    <input type="text" id="instansi" name="instansi" value="<?php echo htmlspecialchars($speaker['instansi'] ?? ''); ?>" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-light-gray">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50"><?php echo htmlspecialchars($speaker['deskripsi'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label for="kontak" class="block text-sm font-medium text-light-gray">Kontak</label>
                    <input type="text" id="kontak" name="kontak" value="<?php echo htmlspecialchars($speaker['kontak'] ?? ''); ?>" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="foto_speaker" class="block text-sm font-medium text-light-gray">Ganti Foto Speaker</label>
                    <div class="mt-2 mb-4 flex items-center space-x-4">
                        <img id="image-preview" src="#" alt="Pratinjau Foto Baru" class="h-24 w-24 object-cover hidden">
                        <img id="existing-image" src="../img/speakers/<?php echo htmlspecialchars($speaker['foto_speaker'] ?? ''); ?>" alt="Foto saat ini" class="h-24 w-24 object-cover <?php echo empty($speaker['foto_speaker']) ? 'hidden' : ''; ?>">
                    </div>
                    <input type="file" id="foto_speaker" name="foto_speaker" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-green file:text-dark">
                </div>
                <div>
                    <label for="urutan" class="block text-sm font-medium text-light-gray">Urutan Tampil</label>
                    <input type="number" id="urutan" name="urutan" value="<?php echo htmlspecialchars($speaker['urutan'] ?? '0'); ?>" min="0" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        const fotoInput = document.getElementById('foto_speaker');
        const imagePreview = document.getElementById('image-preview');
        const existingImage = document.getElementById('existing-image');

        if (fotoInput) {
            fotoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        existingImage.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.src = '#';
                    imagePreview.classList.add('hidden');
                    existingImage.classList.remove('hidden');
                }
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