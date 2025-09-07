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


// Query untuk mendapatkan data tiket dan event berdasarkan id_user
$query_tiket_event = "
    SELECT tiket.tiket_code, event.judul_event, event.jadwal_event, event.waktu_event, event.lokasi_event, event.link_grup, event.thumbnail_event
    FROM tiket
    JOIN event ON tiket.id_event = event.id_event
    WHERE tiket.id_user = ?";

// Persiapkan statement untuk data tiket dan event
$stmt_tiket_event = mysqli_prepare($koneksi, $query_tiket_event);
if (!$stmt_tiket_event) {
    // Handle error jika prepare statement gagal
    die('Prepare statement tiket event failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_tiket_event, "i", $user_id);
mysqli_stmt_execute($stmt_tiket_event);

// Ambil hasil query data tiket dan event
$result_tiket_event = mysqli_stmt_get_result($stmt_tiket_event);

// Array untuk menyimpan data tiket dan event
$tiket_data = [];
while ($row_tiket_event = mysqli_fetch_assoc($result_tiket_event)) {
    $tiket_data[] = $row_tiket_event;
}

// Tutup statement data tiket dan event
mysqli_stmt_close($stmt_tiket_event);
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
    <title>Profile - Finder 7 Mindspace</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-neutral-950 font-['Work_Sans'] text-white">
    <?php require '_navbar.php'; ?>
    <div class="w-full min-h-screen pt-32 pb-32 bg-neutral-950 font-work px-4 md:px-8 lg:px-16">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row md:items-start md:space-x-8 lg:space-x-12">
                <div
                    class="flex flex-row md:flex-col md:w-1/4 lg:w-1/5 bg-neutral-900 rounded-2xl md:p-6 md:space-y-2 justify-around mb-6 md:mb-0 items-center md:items-start">
                    <a href=""
                        class="flex  md:w-full items-center space-x-3 p-3 md:rounded-lg md:bg-neutral-800 text-emerald-500 transition-colors font-semibold duration-300 border-b-2 border-emerald-500
                         ">
                        <ion-icon name="person-circle-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Profile</span>
                    </a>
                    <a href="liked-post.php"
                        class="flex md:w-full items-center space-x-3 p-3 md:rounded-lg text-neutral-400 hover:bg-neutral-800 hover:text-white transition-colors duration-300 ">
                        <ion-icon name="heart-outline" class="md:text-2xl text-4xl"></ion-icon>
                        <span class="hidden md:flex text-base">Liked Post</span>
                    </a>
                    <a href="setting.php"
                        class="flex  md:w-full items-center space-x-3 p-3 md:rounded-lg text-neutral-400 hover:bg-neutral-800 hover:text-white transition-colors duration-300">
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

                    <div class="bg-neutral-900 p-6 md:p-8 rounded-3xl mb-8">
                        <div class="flex items-center space-x-6">
                            <img src="img/profill.png" alt="Poster Event"
                                class="w-20 h-20 md:w-24 md:h-24 rounded-full flex-shrink-0 mx-auto md:mx-0">
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold">
                                    <?php echo htmlspecialchars($_SESSION['user_data']['nama']); ?></h2>
                                <p class="text-lg md:text-xl text-neutral-400">
                                    <?php echo htmlspecialchars($_SESSION['user_data']['instansi']); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-neutral-900 p-6 md:p-8 rounded-3xl mb-8">
                        <h2 class="text-2xl md:text-3xl font-bold mb-6">Tiket Pameran</h2>
                        <div
                            class="bg-neutral-800 rounded-2xl p-6 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                            <div
                                class="w-32 h-32 md:w-40 md:h-40 rounded-xl flex-shrink-0 p-2 bg-white flex justify-center items-center">
                                <div id="qr-exhibition-ticket"></div>
                            </div>
                            <div class="text-center md:text-left">
                                <p class="text-base md:text-lg text-neutral-300 leading-relaxed mb-2">Gunakan tiket ini
                                    untuk masuk pada area pameran.</p>
                                <p class="text-sm font-semibold text-neutral-400">Ticket Code:
                                    <?php echo htmlspecialchars($_SESSION['user_data']['kode_account']); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-neutral-900 p-6 md:p-8 rounded-3xl">
                        <h2 class="text-2xl md:text-3xl font-bold mb-6">Seminar dan Workshop</h2>
                        <div class="space-y-8 md:space-y-12">
                            <?php if (!empty($tiket_data)): ?>
                                <?php foreach ($tiket_data as $tiket): ?>
                                    <div class="ticket-card cursor-pointer"
                                        data-title="<?php echo htmlspecialchars($tiket['judul_event']); ?>"
                                        data-code="<?php echo htmlspecialchars($tiket['tiket_code']); ?>">
                                        <div
                                            class="flex flex-col md:flex-row md:items-start space-y-4 md:space-y-0 md:space-x-6">
                                            <img src="img/thumbnail/<?php echo htmlspecialchars($tiket['thumbnail_event']); ?>"
                                                alt="Poster Event"
                                                class="w-24 h-24 md:w-36 md:h-36 rounded-xl object-cover flex-shrink-0 mx-auto md:mx-0">
                                            <div class="flex-grow text-center md:text-left">
                                                <h3 class="text-xl md:text-2xl font-bold mb-2">
                                                    <?php echo htmlspecialchars($tiket['judul_event']); ?></h3>
                                                <div class="space-y-1 text-sm md:text-base text-neutral-300">
                                                    <p>Tanggal: <span
                                                            class="text-neutral-400"><?php echo htmlspecialchars($tiket['jadwal_event']); ?></span>
                                                    </p>
                                                    <p>Waktu: <span
                                                            class="text-neutral-400"><?php echo htmlspecialchars($tiket['waktu_event']); ?></span>
                                                    </p>
                                                    <p>Lokasi: <span
                                                            class="text-neutral-400"><?php echo htmlspecialchars($tiket['lokasi_event']); ?></span>
                                                    </p>
                                                    <p>Ticket Code: <span
                                                            class="text-neutral-400"><?php echo htmlspecialchars($tiket['tiket_code']); ?></span>
                                                    </p>
                                                </div>
                                                <a href="<?php echo htmlspecialchars($tiket['link_grup']); ?>" target="_blank"
                                                    class="mt-4 md:mt-6 inline-block bg-[#008C62] text-white font-medium py-2 px-6 rounded-full hover:bg-[#007b56] transition-colors duration-300">Group
                                                    Whatsapp</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-hr md:hidden"></div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center text-neutral-400">Anda belum memiliki tiket seminar atau workshop.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-tiket"
        class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center z-50 hidden modal">
        <div class="bg-neutral-900 p-8 rounded-2xl w-11/12 max-w-lg relative">
            <button id="close-modal" class="absolute top-4 right-4 text-white hover:text-neutral-400">
                <ion-icon name="close-circle-outline" class="text-3xl"></ion-icon>
            </button>
            <h3 id="modal-title" class="text-2xl md:text-3xl font-bold text-center mb-6"></h3>
            <div class="bg-white p-4 rounded-xl flex justify-center items-center mb-6">
                <div id="modal-qr-code"></div>
            </div>
            <p class="text-center text-lg md:text-xl text-neutral-400 mb-2">Tunjukkan QR code ini kepada panitia.</p>
            <p id="modal-ticket-code" class="text-center text-base md:text-lg font-semibold text-neutral-300"></p>
        </div>
    </div>

    <script>
        // Function to generate and display QR code
        function generateQRCode(elementId, data) {
            const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(data)}`;
            document.getElementById(elementId).innerHTML = `<img src="${qrCodeUrl}" alt="QR Code">`;
        }

        // Generate QR code for Exhibition Ticket
        document.addEventListener("DOMContentLoaded", function () {
            const exhibitionCode = "<?php echo htmlspecialchars($_SESSION['user_data']['kode_account']); ?>";
            generateQRCode('qr-exhibition-ticket', exhibitionCode);
        });

        // Event listener for Seminar and Workshop ticket cards
        document.querySelectorAll('.ticket-card').forEach(card => {
            card.addEventListener('click', function () {
                const title = this.getAttribute('data-title');
                const code = this.getAttribute('data-code');

                document.getElementById('modal-title').innerText = title;
                document.getElementById('modal-ticket-code').innerText = `Ticket Code: ${code}`;

                generateQRCode('modal-qr-code', code);

                document.getElementById('modal-tiket').classList.remove('hidden');
            });
        });

        // Event listener for close modal button
        document.getElementById('close-modal').addEventListener('click', function () {
            document.getElementById('modal-tiket').classList.add('hidden');
        });
    </script>

    <?php require '_footer.php'; ?>
</body>



</html>