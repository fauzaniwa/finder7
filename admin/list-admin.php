<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Pastikan pengguna sudah login dan memiliki peran yang valid
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["role"], ['master', 'admin', 'pameran', 'seminar', 'workshop', 'lomba'])) {
    header("location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

// Proses form edit data admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'edit_admin') {
    $id_to_edit = intval(trim($_POST['edit_id']));
    $new_name = trim($_POST['new_name']);
    $new_email = trim($_POST['new_email']);

    // Ambil data admin yang akan diedit dari database terlebih dahulu
    $sql_get_old_data = "SELECT `role` FROM `admin` WHERE `id` = ?";
    $old_role = '';
    if ($stmt_old = mysqli_prepare($conn, $sql_get_old_data)) {
        mysqli_stmt_bind_param($stmt_old, "i", $id_to_edit);
        if (mysqli_stmt_execute($stmt_old)) {
            $result_old = mysqli_stmt_get_result($stmt_old);
            if ($row_old = mysqli_fetch_assoc($result_old)) {
                $old_role = $row_old['role'];
            }
        }
        mysqli_stmt_close($stmt_old);
    }
    
    // Tentukan role baru. Jika master, ambil dari POST. Jika tidak, gunakan role lama.
    $new_role = $_SESSION['role'] === 'master' && isset($_POST['new_role']) ? trim($_POST['new_role']) : $old_role;

    // Validasi input
    if (empty($new_name) || empty($new_email)) {
        $error_message = "Nama dan email tidak boleh kosong.";
    } else {
        // Cek izin: master boleh edit siapa saja, admin biasa hanya boleh edit dirinya sendiri
        if ($_SESSION['role'] !== 'master' && $_SESSION['id'] != $id_to_edit) {
            $error_message = "Anda tidak memiliki izin untuk mengedit data ini.";
        } else {
            // Cek apakah password juga diubah
            if (!empty($_POST['new_password'])) {
                $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $sql = "UPDATE admin SET name = ?, email = ?, role = ?, password = ? WHERE id = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ssssi", $new_name, $new_email, $new_role, $new_password, $id_to_edit);
                    if (mysqli_stmt_execute($stmt)) {
                        $success_message = "Data admin berhasil diperbarui!";
                        log_admin_activity($conn, $_SESSION['id'], 'update', 'Memperbarui data admin ' . $new_name . ' (ID: ' . $id_to_edit . ')');
                    } else {
                        $error_message = "Terjadi kesalahan saat memperbarui data: " . mysqli_stmt_error($stmt);
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                // Tanpa perubahan password
                $sql = "UPDATE admin SET name = ?, email = ?, role = ? WHERE id = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sssi", $new_name, $new_email, $new_role, $id_to_edit);
                    if (mysqli_stmt_execute($stmt)) {
                        $success_message = "Data admin berhasil diperbarui!";
                        log_admin_activity($conn, $_SESSION['id'], 'update', 'Memperbarui data admin ' . $new_name . ' (ID: ' . $id_to_edit . ')');
                    } else {
                        $error_message = "Terjadi kesalahan saat memperbarui data: " . mysqli_stmt_error($stmt);
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
}

// Ambil semua data admin
$admins = [];
$sql = "SELECT `id`, `name`, `email`, `created_at`, `role` FROM `admin`";
if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $admins[] = $row;
    }
    mysqli_free_result($result);
} else {
    $error_message = "Terjadi kesalahan saat mengambil data admin.";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Admin</title>
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
            <span class="text-lg font-semibold text-light-gray">Daftar Admin</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-primary-green">Daftar Admin</h1>
                <?php if ($_SESSION['role'] === 'master'): ?>
                    <a href="create_admin.php" class="bg-primary-green text-dark px-4 py-2 rounded-md font-semibold hover:bg-opacity-80 transition-opacity">
                        Create Admin
                    </a>
                <?php endif; ?>
            </div>
            
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

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-mid-gray uppercase text-sm font-semibold border-b border-gray-700">
                            <th class="p-4">ID</th>
                            <th class="p-4">Nama</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">Created At</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $admin): ?>
                            <tr class="border-b border-gray-700 hover:bg-dark-gray transition-colors">
                                <td class="p-4"><?php echo htmlspecialchars($admin['id']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($admin['name']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($admin['email']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($admin['role']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($admin['created_at']); ?></td>
                                <td class="p-4 flex justify-center items-center space-x-2">
                                    <?php if ($_SESSION['role'] === 'master' || $_SESSION['id'] == $admin['id']): ?>
                                        <button 
                                            onclick="openEditModal(<?php echo htmlspecialchars(json_encode($admin)); ?>)"
                                            class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600 transition-colors"
                                        >
                                            <span class="material-symbols-outlined" style="font-size: 1rem;">edit</span>
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($_SESSION['role'] === 'master' && $_SESSION['id'] != $admin['id']): ?>
                                        <a 
                                            href="delete_admin.php?id=<?php echo htmlspecialchars($admin['id']); ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus admin ini?')"
                                            class="bg-red-error text-white px-3 py-1 rounded-md text-sm hover:bg-red-600 transition-colors"
                                        >
                                            <span class="material-symbols-outlined" style="font-size: 1rem;">delete</span>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-dark-card p-8 rounded-xl shadow-lg w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-primary-green">Edit Admin</h2>
                <button onclick="closeModal()" class="text-light-gray hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="edit_admin">
                <input type="hidden" id="edit_id" name="edit_id">
                
                <div>
                    <label for="new_name" class="block text-sm font-medium text-light-gray">Nama</label>
                    <input type="text" id="new_name" name="new_name" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="new_email" class="block text-sm font-medium text-light-gray">Email</label>
                    <input type="email" id="new_email" name="new_email" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <?php if ($_SESSION['role'] === 'master'): ?>
                <div>
                    <label for="new_role" class="block text-sm font-medium text-light-gray">Role</label>
                    <select id="new_role" name="new_role" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity50">
                        <option value="master">Master</option>
                        <option value="pameran">Pameran</option>
                        <option value="seminar">Seminar</option>
                        <option value="workshop">Workshop</option>
                        <option value="lomba">Lomba</option>
                    </select>
                </div>
                <?php else: ?>
                <input type="hidden" id="new_role" name="new_role" value="admin">
                <?php endif; ?>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-light-gray">Password Baru (kosongkan jika tidak diubah)</label>
                    <input type="password" id="new_password" name="new_password" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // JavaScript untuk modal edit
        const editModal = document.getElementById('editModal');
        const overlay = document.getElementById('overlay');
        
        function openEditModal(admin) {
            document.getElementById('edit_id').value = admin.id;
            document.getElementById('new_name').value = admin.name;
            document.getElementById('new_email').value = admin.email;
            const roleSelect = document.getElementById('new_role');
            if (roleSelect) {
                roleSelect.value = admin.role;
            }
            editModal.classList.remove('hidden');
        }

        function closeModal() {
            editModal.classList.add('hidden');
        }

        // Fungsionalitas sidebar mobile
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-sidebar-btn');
        
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