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

// Persiapkan query untuk mengambil data user berdasarkan user_id
$query_user = "SELECT nama, tgl_lahir, no_hp, instansi, email, kode_account FROM user WHERE id_user = ?";

// Persiapkan statement untuk data user
$stmt_user = mysqli_prepare($koneksi, $query_user);
if (!$stmt_user) {
    // Handle error jika prepare statement gagal
    die('Prepare statement user failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);

// Ambil hasil query data user
$result_user = mysqli_stmt_get_result($stmt_user);

// Periksa apakah data user ditemukan
if ($row_user = mysqli_fetch_assoc($result_user)) {
    // Simpan data user ke dalam session atau langsung gunakan
    $_SESSION['user_data'] = [
        'nama' => $row_user['nama'],
        'tgl_lahir' => $row_user['tgl_lahir'],
        'no_hp' => $row_user['no_hp'],
        'instansi' => $row_user['instansi'],
        'email' => $row_user['email'],
        'kode_account' => $row_user['kode_account'] // Pastikan kode_account disimpan
    ];
} else {
    // Jika data user tidak ditemukan, logout dan kembali ke halaman login
    session_destroy();
    header("Location: login.php");
    exit();
}

// Tutup statement data user
mysqli_stmt_close($stmt_user);

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


    <title>Profile - Finder 7 Mindspace</title>
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
                    <a href=""
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

                    <div class="bg-neutral-900 p-6 md:p-8 rounded-3xl mb-8 text-white">
                        <div class="flex items-center space-x-6">
                            <img src="img/profill.png" alt="Poster Event"
                                class="w-20 h-20 md:w-24 md:h-24 rounded-full md:flex-shrink-0 md:mx-0">
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold">
                                    <?php echo htmlspecialchars($_SESSION['user_data']['nama']); ?>
                                </h2>
                                <p class="text-lg md:text-xl text-neutral-400">
                                    <?php echo htmlspecialchars($_SESSION['user_data']['instansi']); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="w-full mx-auto p-8 rounded-3xl shadow-lg bg-neutral-900 text-white">
                        <h2 class="text-3xl font-bold text-center mb-8">Ubah Kata Sandi</h2>

                        <form action="#" method="POST" class="space-y-6">
                            <div>
                                <label for="current-password"
                                    class="block text-sm font-medium text-neutral-400">Kata Sandi Sebelumnya</label>
                                <input type="text" id="username" name="username"
                                    class="mt-1 block w-full px-4 py-3 bg-neutral-800 focus:border transition-colors duration-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="new-password" class="block text-sm font-medium text-neutral-400">Kata Sandi Baru</label>
                                <input type="text" id="tgl_lahir" name="tgl_lahir"
                                    class="mt-1 block w-full px-4 py-3 bg-neutral-800 focus:border transition-colors duration-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="confirm-password" class="block text-sm font-medium text-neutral-400">Konfirmasi Kata Sandi Baru</label>
                                <input type="email" id="email" name="email"
                                    class="mt-1 block w-full px-4 py-3 bg-neutral-800 focus:border transition-colors duration-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>

                            <div
                                class="flex flex-col sm:flex-row justify-between pt-4 space-y-4 sm:space-y-0 sm:space-x-4">
                                <a href="setting.php" class="w-full flex justify-center py-3 px-4 border border-transparent transition-colors duration-300 rounded-md shadow-sm text-sm font-medium text-neutral-900 bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">

                                    <button type="submit"
                                    class="">
                                    Simpan
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