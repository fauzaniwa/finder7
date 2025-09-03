<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Cek apakah pengguna sudah login dan memiliki peran yang diizinkan untuk melihat halaman ini
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["role"], ['master', 'seminar', 'workshop'])) {
    header("location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

// Proses form tambah event baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'create_event') {
    $judul_event = trim($_POST['judul_event']);
    $kategori = trim($_POST['kategori']);
    $audiens = trim($_POST['audiens']);
    $jadwal_event = trim($_POST['jadwal_event']);
    $waktu_event = trim($_POST['waktu_event']);
    $lokasi_event = trim($_POST['lokasi_event']);
    $tiket_event = trim($_POST['tiket_event']);
    $kuota = trim($_POST['kuota']);
    $link_grup = trim($_POST['link_grup']);
    $statusbayar = trim($_POST['statusbayar']);
    $event_status = trim($_POST['event_status']);
    $show_event = trim($_POST['show_event']);
    $urutan_show = trim($_POST['urutan_show']);
    $deskripsi_event = trim($_POST['deskripsi_event']);
    $selected_speakers = isset($_POST['speakers']) ? $_POST['speakers'] : [];

    // Validasi izin berdasarkan role admin
    if ($_SESSION['role'] !== 'master' && $_SESSION['role'] !== $kategori) {
        $error_message = "Anda tidak memiliki izin untuk membuat event di kategori ini.";
    } else {
        // Mulai transaksi untuk memastikan konsistensi
        mysqli_begin_transaction($conn);
        $insert_success = true;

        // 1. Insert data event baru
        $sql_event = "INSERT INTO event (judul_event, kategori, audiens, statusbayar, jadwal_event, waktu_event, lokasi_event, tiket_event, kuota, link_grup, event_status, show_event, urutan_show, deskripsi_event) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt_event = mysqli_prepare($conn, $sql_event)) {
            mysqli_stmt_bind_param($stmt_event, "sssssssiissssi", 
                $judul_event, $kategori, $audiens, $statusbayar, $jadwal_event, $waktu_event, $lokasi_event, $tiket_event, $kuota, $link_grup, $event_status, $show_event, $urutan_show, $deskripsi_event
            );
            if (!mysqli_stmt_execute($stmt_event)) {
                $error_message = "Terjadi kesalahan saat menambahkan event: " . mysqli_stmt_error($stmt_event);
                $insert_success = false;
            } else {
                $new_event_id = mysqli_insert_id($conn);
            }
            mysqli_stmt_close($stmt_event);
        } else {
            $error_message = "Terjadi kesalahan saat mempersiapkan statement event: " . mysqli_error($conn);
            $insert_success = false;
        }

        // 2. Tambahkan speakers baru
        if ($insert_success && !empty($selected_speakers)) {
            $sql_insert_speakers = "INSERT INTO event_speakers (id_event, id_speaker) VALUES (?, ?)";
            if ($stmt_insert = mysqli_prepare($conn, $sql_insert_speakers)) {
                foreach ($selected_speakers as $speaker_id) {
                    mysqli_stmt_bind_param($stmt_insert, "ii", $new_event_id, $speaker_id);
                    if (!mysqli_stmt_execute($stmt_insert)) {
                        $error_message = "Terjadi kesalahan saat menambahkan speaker: " . mysqli_stmt_error($stmt_insert);
                        $insert_success = false;
                        break;
                    }
                }
                mysqli_stmt_close($stmt_insert);
            } else {
                $error_message = "Terjadi kesalahan saat mempersiapkan statement insert speakers: " . mysqli_error($conn);
                $insert_success = false;
            }
        }

        // Komit atau rollback transaksi
        if ($insert_success) {
            mysqli_commit($conn);
            $success_message = "Event baru berhasil dibuat!";
            log_admin_activity($conn, $_SESSION['id'], 'create', 'Membuat event baru: ' . $judul_event . ' (ID: ' . $new_event_id . ')');
            
            // Redirect ke halaman daftar event setelah sukses
            header("refresh:2;url=event.php");
        } else {
            mysqli_rollback($conn);
        }
    }
}

// Ambil semua data speakers untuk modal
$all_speakers = [];
$sql_speakers = "SELECT `id_speaker`, `nama_speaker`, `foto_speaker` FROM `speakers` ORDER BY `nama_speaker` ASC";
if ($result_speakers = mysqli_query($conn, $sql_speakers)) {
    while ($row_speakers = mysqli_fetch_assoc($result_speakers)) {
        $all_speakers[] = $row_speakers;
    }
    mysqli_free_result($result_speakers);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
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
        .speaker-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
        .speaker-card {
            background-color: #2a2a2a;
            border-radius: 0.5rem;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            position: relative;
        }
        .speaker-card.selected {
            outline: 2px solid #00D294;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 210, 148, 0.2);
        }
        .speaker-card img {
            width: 100%;
            height: 100px;
            object-fit: cover;
        }
        .speaker-card-content {
            padding: 0.75rem;
            text-align: center;
        }
        .speaker-checkbox {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 1.5rem;
            height: 1.5rem;
            pointer-events: none;
            border-radius: 50%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }
        .speaker-card.selected .speaker-checkbox {
            background-color: #00D294;
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
            <span class="text-lg font-semibold text-light-gray">Create Event</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0 max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-primary-green">Create Event Baru</h1>
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

            <form id="createForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create_event">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="judul_event" class="block text-sm font-medium text-light-gray">Judul Event</label>
                        <input type="text" id="judul_event" name="judul_event" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-light-gray">Kategori</label>
                        <?php if ($_SESSION['role'] === 'master'): ?>
                            <select id="kategori" name="kategori" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                                <option value="seminar">Seminar</option>
                                <option value="workshop">Workshop</option>
                            </select>
                        <?php else: ?>
                            <input type="text" id="kategori_text" name="kategori" value="<?php echo htmlspecialchars($_SESSION['role']); ?>" readonly class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700">
                            <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($_SESSION['role']); ?>">
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="audiens" class="block text-sm font-medium text-light-gray">Audiens</label>
                        <select id="audiens" name="audiens" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                            <option value="Umum">Umum</option>
                            <option value="SMA/SMK">SMA/SMK</option>
                        </select>
                    </div>
                    <div>
                        <label for="jadwal_event" class="block text-sm font-medium text-light-gray">Jadwal Event</label>
                        <input type="date" id="jadwal_event" name="jadwal_event" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="waktu_event" class="block text-sm font-medium text-light-gray">Waktu Event</label>
                        <input type="text" id="waktu_event" name="waktu_event" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="lokasi_event" class="block text-sm font-medium text-light-gray">Lokasi Event</label>
                        <input type="text" id="lokasi_event" name="lokasi_event" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="tiket_event" class="block text-sm font-medium text-light-gray">Harga Tiket</label>
                        <input type="number" id="tiket_event" name="tiket_event" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="kuota" class="block text-sm font-medium text-light-gray">Kuota</label>
                        <input type="number" id="kuota" name="kuota" required class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="link_grup" class="block text-sm font-medium text-light-gray">Link Grup</label>
                        <input type="text" id="link_grup" name="link_grup" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="statusbayar" class="block text-sm font-medium text-light-gray">Status Bayar</label>
                        <select id="statusbayar" name="statusbayar" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                            <option value="Free">Free</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                    <div>
                        <label for="event_status" class="block text-sm font-medium text-light-gray">Status Event</label>
                        <select id="event_status" name="event_status" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                            <option value="Dibuka">Dibuka</option>
                            <option value="Tutup">Tutup</option>
                            <option value="Kuota Penuh">Kuota Penuh</option>
                        </select>
                    </div>
                    <div>
                        <label for="show_event" class="block text-sm font-medium text-light-gray">Tampilkan di Halaman Utama</label>
                        <select id="show_event" name="show_event" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div>
                        <label for="urutan_show" class="block text-sm font-medium text-light-gray">Urutan Tampil (0 untuk default)</label>
                        <input type="number" id="urutan_show" name="urutan_show" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-light-gray">Pilih Speakers</label>
                    <input type="text" id="speaker_search" placeholder="Cari speakers..." class="mb-2 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    <div id="speakers-container" class="speaker-grid mt-1 max-h-48 overflow-y-auto">
                        </div>
                </div>

                <div class="mt-4">
                    <label for="deskripsi_event" class="block text-sm font-medium text-light-gray">Deskripsi Event</label>
                    <textarea id="deskripsi_event" name="deskripsi_event" rows="4" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50"></textarea>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-opacity-80 transition-opacity">
                        Create Event
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const speakersContainer = document.getElementById('speakers-container');
        const speakerSearch = document.getElementById('speaker_search');
        
        // Data speakers dari PHP
        const allSpeakers = <?php echo json_encode($all_speakers); ?>;
        
        // Fungsi untuk membuat elemen card speaker
        function createSpeakerCard(speaker, isSelected) {
            const card = document.createElement('div');
            card.className = `speaker-card ${isSelected ? 'selected' : ''}`;
            card.setAttribute('data-speaker-id', speaker.id_speaker);
            
            card.innerHTML = `
                <img src="../img/speakers/${speaker.foto_speaker}" alt="${speaker.nama_speaker}" class="w-full h-24 object-cover">
                <div class="speaker-card-content">
                    <p class="text-sm font-semibold truncate">${speaker.nama_speaker}</p>
                </div>
                <div class="speaker-checkbox">
                    <span class="material-symbols-outlined text-white text-base">${isSelected ? 'check_circle' : 'radio_button_unchecked'}</span>
                </div>
                <input type="checkbox" name="speakers[]" value="${speaker.id_speaker}" class="hidden" ${isSelected ? 'checked' : ''}>
            `;

            card.addEventListener('click', () => {
                const checkbox = card.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                card.classList.toggle('selected', checkbox.checked);
                const icon = card.querySelector('.material-symbols-outlined');
                icon.textContent = checkbox.checked ? 'check_circle' : 'radio_button_unchecked';
            });

            return card;
        }

        // Fungsi untuk render daftar speakers
        function renderSpeakers(speakers, selectedSpeakers = []) {
            speakersContainer.innerHTML = '';
            speakers.forEach(speaker => {
                const isSelected = selectedSpeakers.includes(parseInt(speaker.id_speaker));
                speakersContainer.appendChild(createSpeakerCard(speaker, isSelected));
            });
        }

        // Event listener untuk pencarian speakers
        speakerSearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const filteredSpeakers = allSpeakers.filter(speaker => 
                speaker.nama_speaker.toLowerCase().includes(searchTerm)
            );
            
            // Saat di halaman buat, tidak ada speaker yang terpilih secara default
            renderSpeakers(filteredSpeakers, []);
        });
        
        // Render semua speaker saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            renderSpeakers(allSpeakers);
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
    </script>
</body>
</html>