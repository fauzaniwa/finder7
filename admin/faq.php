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

// Proses form untuk menambah data FaQ (Sistem Insert)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add_faq') {
    $topik = trim($_POST['topik']);
    $jawaban = trim($_POST['jawaban']);
    $status = 'active'; // Status default adalah 'active'

    // Validasi input
    if (empty($topik) || empty($jawaban)) {
        $error_message = "Topik dan Jawaban tidak boleh kosong.";
    } else {
        $sql = "INSERT INTO qna (topik, jawaban, status) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $topik, $jawaban, $status);
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "FaQ baru berhasil ditambahkan!";
                // Log aktivitas
                log_admin_activity($conn, $_SESSION['id'], 'create', 'Menambah FaQ baru: ' . $topik);
            } else {
                $error_message = "Terjadi kesalahan saat menambahkan FaQ: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Terjadi kesalahan saat menyiapkan statement: " . mysqli_error($conn);
        }
    }
}

// Proses form untuk mengubah status FaQ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    $faq_id = intval($_POST['id']);
    $new_status = trim($_POST['status']);

    // Validasi input
    if (empty($faq_id) || ($new_status !== 'active' && $new_status !== 'inactive')) {
        $error_message = "Data yang dikirim tidak valid.";
    } else {
        $sql = "UPDATE qna SET status = ?, updated_at = NOW() WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $new_status, $faq_id);
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Status FaQ berhasil diubah menjadi " . $new_status . ".";
                // Log aktivitas
                log_admin_activity($conn, $_SESSION['id'], 'update', 'Mengubah status FaQ ID: ' . $faq_id . ' menjadi ' . $new_status);
            } else {
                $error_message = "Terjadi kesalahan saat memperbarui status: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Terjadi kesalahan saat menyiapkan statement: " . mysqli_error($conn);
        }
    }
}

// Proses form untuk menghapus FaQ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete_faq') {
    $faq_id = intval($_POST['id']);

    // Validasi ID
    if (empty($faq_id)) {
        $error_message = "ID FaQ tidak ditemukan.";
    } else {
        $sql = "DELETE FROM qna WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $faq_id);
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "FaQ berhasil dihapus!";
                // Log aktivitas
                log_admin_activity($conn, $_SESSION['id'], 'delete', 'Menghapus FaQ ID: ' . $faq_id);
            } else {
                $error_message = "Terjadi kesalahan saat menghapus FaQ: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Terjadi kesalahan saat menyiapkan statement: " . mysqli_error($conn);
        }
    }
}


// Ambil semua data FaQ dari tabel qna
$faqs = [];
$sql = "SELECT `id`, `topik`, `jawaban`, `created_at`, `updated_at`, `status` FROM `qna` ORDER BY created_at DESC";
if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $faqs[] = $row;
    }
    mysqli_free_result($result);
} else {
    $error_message = "Terjadi kesalahan saat mengambil data FaQ.";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen FaQ</title>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .faq-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .toggle-icon {
            transition: transform 0.3s ease-in-out;
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
            <span class="text-lg font-semibold text-light-gray">FaQ</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-primary-green">Manajemen FaQ</h1>
                <button onclick="openModal()" class="bg-primary-green text-dark px-4 py-2 rounded-md font-semibold hover:bg-opacity-80 transition-opacity">
                    Tambah FaQ
                </button>
            </div>

            <?php if ($success_message) : ?>
                <div class="bg-green-500 text-white p-4 rounded mb-6">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <?php if ($error_message) : ?>
                <div class="bg-red-error text-white p-4 rounded mb-6">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="space-y-4">
                <?php if (count($faqs) > 0) : ?>
                    <?php foreach ($faqs as $faq) : ?>
                        <div class="bg-dark-gray p-4 rounded-lg">
                            <div class="flex justify-between items-center faq-item" onclick="toggleAnswer(this)">
                                <div class="flex items-center">
                                    <h2 class="text-lg font-semibold text-light-gray">
                                        <?php echo htmlspecialchars($faq['topik']); ?>
                                    </h2>
                                </div>
                                <div class="flex space-x-2 items-center">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="inline-block">
                                        <input type="hidden" name="action" value="toggle_status">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($faq['id']); ?>">
                                        <?php if ($faq['status'] === 'active') : ?>
                                            <input type="hidden" name="status" value="inactive">
                                            <button type="submit" class="bg-red-error text-white text-sm px-3 py-1 rounded-md hover:bg-opacity-80 transition-opacity">
                                                Deactivate
                                            </button>
                                        <?php else : ?>
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="bg-primary-green text-dark text-sm px-3 py-1 rounded-md hover:bg-opacity-80 transition-opacity">
                                                Activate
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus FaQ ini? Aksi ini tidak dapat dibatalkan.');">
                                        <input type="hidden" name="action" value="delete_faq">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($faq['id']); ?>">
                                        <button type="submit" class="bg-red-error text-white text-sm px-3 py-1 rounded-md hover:bg-opacity-80 transition-opacity">
                                            Delete
                                        </button>
                                    </form>
                                    <span class="material-symbols-outlined text-mid-gray toggle-icon">expand_more</span>
                                </div>
                            </div>
                            <div class="mt-4 text-mid-gray leading-relaxed hidden faq-answer">
                                <p class="mb-2"><?php echo nl2br(htmlspecialchars($faq['jawaban'])); ?></p>
                                <hr class="border-gray-600 my-2">
                                <p class="text-sm">Status: <span class="font-semibold <?php echo $faq['status'] === 'active' ? 'text-green-400' : 'text-red-400'; ?>"><?php echo htmlspecialchars(ucfirst($faq['status'])); ?></span></p>
                                <p class="text-sm">Dibuat: <?php echo htmlspecialchars($faq['created_at']); ?></p>
                                <p class="text-sm">Diperbarui: <?php echo htmlspecialchars($faq['updated_at']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="text-center text-mid-gray p-8">
                        <p>Tidak ada FaQ yang tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-dark-card p-8 rounded-xl shadow-lg w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-primary-green">Tambah FaQ Baru</h2>
                <button onclick="closeModal()" class="text-light-gray hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="addForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="add_faq">

                <div>
                    <label for="topik" class="block text-sm font-medium text-light-gray">Topik/Pertanyaan</label>
                    <input type="text" id="topik" name="topik" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                <div>
                    <label for="jawaban" class="block text-sm font-medium text-light-gray">Jawaban</label>
                    <textarea id="jawaban" name="jawaban" rows="5" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsionalitas sidebar mobile
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-sidebar-btn');
        const overlay = document.getElementById('overlay');
        const closeBtn = document.getElementById('close-sidebar-btn');

        // Modal logic
        const addModal = document.getElementById('addModal');

        function openModal() {
            addModal.classList.remove('hidden');
        }

        function closeModal() {
            addModal.classList.add('hidden');
        }

        // Accordion functionality for FAQ list
        function toggleAnswer(element) {
            const answer = element.nextElementSibling;
            const icon = element.querySelector('.toggle-icon');

            // Find the closest parent with the class 'faq-item'
            const faqItem = element.closest('.faq-item');
            if (faqItem) {
                const answer = faqItem.nextElementSibling;
                const icon = element.querySelector('.toggle-icon');

                if (answer && icon) {
                    if (answer.classList.contains('hidden')) {
                        answer.classList.remove('hidden');
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        answer.classList.add('hidden');
                        icon.style.transform = 'rotate(0deg)';
                    }
                }
            }
        }

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
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>
</body>

</html>