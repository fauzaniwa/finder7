<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Cek apakah pengguna sudah login dan memiliki peran yang diizinkan untuk melihat halaman ini
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["role"], ['master', 'seminar', 'workshop'])) {
    header("location: login.php");
    exit;
}

$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Ambil data event berdasarkan role admin
$events = [];
$sql = "SELECT `id_event`, `judul_event`, `kategori`, `audiens`, `statusbayar`, `jadwal_event`, `waktu_event`, `lokasi_event`, `tiket_event`, `kuota`, `link_grup`, `event_status`, `show_event`, `urutan_show`, `deskripsi_event` FROM `event`";

// Filter berdasarkan role jika bukan master
if ($_SESSION['role'] !== 'master') {
    $role = $_SESSION['role'];
    $sql .= " WHERE `kategori` = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $role);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = "Terjadi kesalahan saat mengambil data event: " . mysqli_error($conn);
    }
} else {
    // Jika master, ambil semua data
    $sql .= " ORDER BY `urutan_show` ASC, `created_event` DESC";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
        mysqli_free_result($result);
    } else {
        $error_message = "Terjadi kesalahan saat mengambil data event: " . mysqli_error($conn);
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

// Ambil speakers untuk setiap event
foreach ($events as &$event) {
    $event['speakers'] = [];
    $sql_event_speakers = "SELECT `id_speaker` FROM `event_speakers` WHERE `id_event` = ?";
    if ($stmt_event_speakers = mysqli_prepare($conn, $sql_event_speakers)) {
        mysqli_stmt_bind_param($stmt_event_speakers, "i", $event['id_event']);
        mysqli_stmt_execute($stmt_event_speakers);
        $result_event_speakers = mysqli_stmt_get_result($stmt_event_speakers);
        while ($row_speaker = mysqli_fetch_assoc($result_event_speakers)) {
            $event['speakers'][] = $row_speaker['id_speaker'];
        }
        mysqli_stmt_close($stmt_event_speakers);
    }
}
unset($event); // Hapus referensi

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Event</title>
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
            <span class="text-lg font-semibold text-light-gray">Daftar Event</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-primary-green">Daftar Event</h1>
                <a href="create_event.php" class="bg-primary-green text-dark px-4 py-2 rounded-md font-semibold hover:bg-opacity-80 transition-opacity">
                    Create Event
                </a>
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
                            <th class="p-4">No.</th>
                            <th class="p-4">Judul Event</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Audiens</th>
                            <th class="p-4">Jadwal</th>
                            <th class="p-4">Harga Tiket</th>
                            <th class="p-4">Status Bayar</th>
                            <th class="p-4">Kuota</th>
                            <th class="p-4">Status Event</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        foreach ($events as $event): ?>
                            <tr class="border-b border-gray-700 hover:bg-dark-gray transition-colors">
                                <td class="p-4"><?php echo $i++; ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($event['judul_event']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($event['kategori']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($event['audiens']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($event['jadwal_event']); ?></td>
                                <td class="p-4">Rp <?php echo number_format($event['tiket_event'], 0, ',', '.'); ?></td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        <?php echo $event['statusbayar'] == 'yes' ? 'bg-primary-green text-dark' : 'bg-green-500 text-white'; ?>">
                                        <?php echo $event['statusbayar'] == 'yes' ? 'Paid' : 'Free'; ?>
                                    </span>
                                </td>
                                <td class="p-4"><?php echo htmlspecialchars($event['kuota']); ?></td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        <?php 
                                            switch($event['event_status']) {
                                                case 0: echo 'bg-green-500 text-white'; break;
                                                case 1: echo 'bg-red-error text-white'; break;
                                                case 2: echo 'bg-orange-500 text-white'; break;
                                                default: echo 'bg-mid-gray text-dark';
                                            }
                                        ?>">
                                        <?php
                                            switch($event['event_status']) {
                                                case 0: echo 'Dibuka'; break;
                                                case 1: echo 'Tutup'; break;
                                                case 2: echo 'Kuota Penuh'; break;
                                                default: echo 'Tidak Diketahui';
                                            }
                                        ?>
                                    </span>
                                </td>
                                <td class="p-4 flex justify-center items-center space-x-2">
                                    <button 
                                        onclick="openEditModal(<?php echo htmlspecialchars(json_encode($event)); ?>)"
                                        class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600 transition-colors"
                                    >
                                        <span class="material-symbols-outlined" style="font-size: 1rem;">edit</span>
                                    </button>
                                    <?php if ($_SESSION['role'] === 'master'): ?>
                                        <a 
                                            href="delete_event.php?id=<?php echo htmlspecialchars($event['id_event']); ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')"
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

    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-dark-card p-8 rounded-xl shadow-lg w-full max-w-4xl max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-primary-green">Edit Event</h2>
                <button onclick="closeModal()" class="text-light-gray hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" action="update_event.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="edit_event">
                <input type="hidden" id="edit_id" name="edit_id">

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
                            <input type="text" id="kategori_text" readonly class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700">
                            <input type="hidden" id="kategori_hidden" name="kategori">
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
                            <option value="no">Free</option>
                            <option value="yes">Paid</option>
                        </select>
                    </div>
                    <div>
                        <label for="event_status" class="block text-sm font-medium text-light-gray">Status Event</label>
                        <select id="event_status" name="event_status" class="mt-1 block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                            <option value="0">Dibuka</option>
                            <option value="1">Tutup</option>
                            <option value="2">Kuota Penuh</option>
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
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const editModal = document.getElementById('editModal');
        const overlay = document.getElementById('overlay');
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
            
            const eventId = document.getElementById('edit_id').value;
            const eventData = eventsData.find(e => e.id_event == eventId);
            const selectedSpeakers = eventData ? eventData.speakers : [];

            renderSpeakers(filteredSpeakers, selectedSpeakers);
        });

        function openEditModal(event) {
            document.getElementById('edit_id').value = event.id_event;
            document.getElementById('judul_event').value = event.judul_event;
            
            // Mengisi kategori, baik untuk master (select) maupun non-master (input)
            const kategoriSelect = document.getElementById('kategori');
            if (kategoriSelect) {
                kategoriSelect.value = event.kategori;
            } else {
                document.getElementById('kategori_text').value = event.kategori;
                document.getElementById('kategori_hidden').value = event.kategori;
            }

            // Mengisi dropdown
            document.getElementById('audiens').value = event.audiens;
            document.getElementById('statusbayar').value = event.statusbayar;
            // Mengisi dropdown event_status dengan nilai integer yang benar
            document.getElementById('event_status').value = event.event_status;
            // Mengisi dropdown show_event dengan nilai integer yang benar
            document.getElementById('show_event').value = event.show_event;
            
            // Mengisi input teks dan number
            document.getElementById('jadwal_event').value = event.jadwal_event;
            document.getElementById('waktu_event').value = event.waktu_event;
            document.getElementById('lokasi_event').value = event.lokasi_event;
            document.getElementById('tiket_event').value = event.tiket_event;
            document.getElementById('kuota').value = event.kuota;
            document.getElementById('link_grup').value = event.link_grup;
            document.getElementById('urutan_show').value = event.urutan_show;
            document.getElementById('deskripsi_event').value = event.deskripsi_event;

            // Render speakers untuk event ini
            renderSpeakers(allSpeakers, event.speakers);
            
            editModal.classList.remove('hidden');
        }

        function closeModal() {
            editModal.classList.add('hidden');
            speakerSearch.value = ''; // Reset search field
        }

        const eventsData = <?php echo json_encode($events); ?>;

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