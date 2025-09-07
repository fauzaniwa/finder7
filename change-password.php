<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Periksa apakah session user ada dan tidak kosong
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'admin-one/dist/koneksi.php';

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// Handle form submission untuk update password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Query untuk mengambil hash password user
    $query_password = "SELECT password FROM user WHERE id_user = ?";
    $stmt_password = mysqli_prepare($koneksi, $query_password);
    mysqli_stmt_bind_param($stmt_password, "i", $user_id);
    mysqli_stmt_execute($stmt_password);
    $result_password = mysqli_stmt_get_result($stmt_password);
    $row_password = mysqli_fetch_assoc($result_password);
    $hashed_password_db = $row_password['password'];
    mysqli_stmt_close($stmt_password);

    // Verifikasi password lama
    if (password_verify($current_password, $hashed_password_db)) {
        // Cek apakah password baru dan konfirmasi cocok
        if ($new_password === $confirm_password) {
            // Hash password baru
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password di database
            $update_query = "UPDATE user SET password = ? WHERE id_user = ?";
            $stmt_update = mysqli_prepare($koneksi, $update_query);
            if ($stmt_update) {
                mysqli_stmt_bind_param($stmt_update, "si", $new_hashed_password, $user_id);
                if (mysqli_stmt_execute($stmt_update)) {
                    $message = 'Kata sandi berhasil diubah!';
                    $message_type = 'success';
                } else {
                    $message = 'Gagal memperbarui kata sandi: ' . mysqli_error($koneksi);
                    $message_type = 'error';
                }
                mysqli_stmt_close($stmt_update);
            } else {
                $message = 'Gagal mempersiapkan statement: ' . mysqli_error($koneksi);
                $message_type = 'error';
            }
        } else {
            $message = 'Kata sandi baru dan konfirmasi tidak cocok.';
            $message_type = 'error';
        }
    } else {
        $message = 'Kata sandi lama salah.';
        $message_type = 'error';
    }
}

// Query untuk mengambil data user (sama seperti di setting.php)
$query_user = "SELECT nama, instansi FROM user WHERE id_user = ?";
$stmt_user = mysqli_prepare($koneksi, $query_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$row_user = mysqli_fetch_assoc($result_user);
mysqli_stmt_close($stmt_user);
mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        work: ['Work Sans'],
                    },
                },
            },
        };
    </script>
    <style>
        .custom-hr {
            border: none;
            height: 1px;
            background-color: #4a4a4a;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 768px) {
            .custom-hr {
                margin-top: 4rem;
                margin-bottom: 4rem;
            }
        }

        .modal {
            transition: all 0.3s ease-in-out;
        }
    </style>

    <style>
        /* Mengatur agar gambar dan elemen inner lainnya proporsional (1:1) */
        .aspect-square-container::before {
            content: '';
            display: block;
            padding-top: 100%;
            /* Rasio 1:1 */
        }

        .zoom-img {
            transition: transform 0.5s ease-in-out;
        }

        .zoom-container:hover .zoom-img {
            transform: scale(1.1);
            /* Zoom-in sebesar 10% */
        }
    </style>


    <title>Ubah Kata Sandi - Finder 7 Mindspace</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-neutral-950">
    <?php require '_navbar.php'; ?>
    <div
        class="w-2/3 h-3/4 blur-3xl absolute -z-20 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
    </div>

    <div class="w-full flex min-h-screen pt-32 pb-32 font-work px-4 md:px-8 lg:px-16 z-10">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row md:items-start md:space-x-8 lg:space-x-12 md:gap-0 ">
                <div
                    class="flex flex-row md:flex-col md:w-1/4 lg:w-1/5 bg-neutral-900 rounded-2xl md:p-6 md:space-y-2 justify-around mb-6 md:mb-0 items-center md:items-start">
                    <a href="account.php"
                        class="flex md:w-full items-center space-x-3 p-3 md:rounded-lg text-neutral-400 hover:bg-neutral-800 hover:text-white transition-colors duration-300  ">
                        <ion-icon name="person-circle-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Profile</span>
                    </a>
                    <a href="liked-post.php"
                        class="flex  md:w-full items-center space-x-3 p-3 md:rounded-lg text-neutral-400 hover:bg-neutral-800 hover:text-white transition-colors duration-300">
                        <ion-icon name="heart-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Liked Post</span>
                    </a>
                    <a href="setting.php"
                        class="flex  md:w-full items-center space-x-3 p-3 md:rounded-lg md:bg-neutral-800 text-emerald-500 transition-colors font-semibold duration-300 border-b-2 border-emerald-500">
                        <ion-icon name="settings-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Setting</span>
                    </a>
                    <a href="logout-reminder.php"
                        class="flex  md:w-full items-center space-x-3 p-3 md:rounded-lg text-neutral-400 hover:bg-neutral-800 hover:text-white transition-colors duration-300">
                        <ion-icon name="log-out-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Logout</span>
                    </a>
                </div>

                <div class="w-full md:w-3/4 lg:w-4/5">
                    <?php if ($message): ?>
                        <div class="mb-4 p-4 text-sm rounded-xl <?php echo $message_type === 'success' ? 'bg-emerald-900 text-emerald-300' : 'bg-red-900 text-red-300'; ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <div class="bg-neutral-900 p-6 md:p-8 rounded-3xl mb-8 text-white">
                        <div class="flex items-center space-x-6">
                            <img src="img/profill.png" alt="Poster Event"
                                class="w-20 h-20 md:w-24 md:h-24 rounded-full md:flex-shrink-0 md:mx-0">
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold">
                                    <?php echo htmlspecialchars($row_user['nama'] ?? 'Pengguna'); ?>
                                </h2>
                                <p class="text-lg md:text-xl text-neutral-400">
                                    <?php echo htmlspecialchars($row_user['instansi'] ?? 'Instansi'); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="w-full mx-auto p-8 rounded-3xl shadow-lg bg-neutral-900 text-white">
                        <h2 class="text-3xl font-bold text-center mb-8">Ubah Kata Sandi</h2>

                        <form action="change-password.php" method="POST" class="space-y-6">
                            <div>
                                <label for="current_password"
                                    class="block text-sm font-medium text-neutral-400">Kata Sandi Saat Ini</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="mt-1 block w-full px-4 py-3 bg-neutral-800 focus:border transition-colors duration-300 rounded-2xl shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-neutral-400">Kata Sandi Baru</label>
                                <input type="password" id="new_password" name="new_password"
                                    class="mt-1 block w-full px-4 py-3 bg-neutral-800 focus:border transition-colors duration-300 rounded-2xl shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-neutral-400">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    class="mt-1 block w-full px-4 py-3 bg-neutral-800 focus:border transition-colors duration-300 rounded-2xl shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>

                            <div
                                class="flex flex-col sm:flex-row justify-between pt-4 space-y-4 sm:space-y-0 sm:space-x-4">
                                <button type="submit"
                                    class="w-full flex justify-center py-3 px-4 border border-transparent transition-colors duration-300 rounded-2xl shadow-sm text-sm font-semibold text-neutral-900 bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    Simpan
                                </button>
                                <a href="setting.php" class="w-full flex justify-center py-3 px-4 focus:border transition-colors duration-300 rounded-2xl shadow-sm text-sm font-semibold text-emerald-500 bg-neutral-800 hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-600">
                                    <button type="button" class="">
                                        Kembali ke Pengaturan
                                    </button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require '_footer.php'; ?>
</body>

</html>