<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Cek apakah pengguna sudah login dan memiliki peran yang diizinkan
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["role"], ['master', 'seminar', 'workshop'])) {
    header("location: login.php");
    exit;
}

// Ambil semua event untuk dropdown filter (hanya perlu ID dan Judul)
$all_events = [];
$sql_events = "SELECT `id_event`, `judul_event`, `kategori`, `statusbayar` FROM `event` ORDER BY `judul_event` ASC";
$result_events = mysqli_query($conn, $sql_events);
if ($result_events) {
    while ($row = mysqli_fetch_assoc($result_events)) {
        $all_events[] = $row;
    }
    mysqli_free_result($result_events);
}
mysqli_close($conn);

$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pendaftar Event</title>
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
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            justify-content: center;
            align-items: center;
            z-index: 50;
        }
        .modal-content {
            background-color: #1a1a1a;
            padding: 1.5rem;
            border-radius: 0.5rem;
            max-width: 90%;
            max-height: 90%;
            overflow: auto;
            position: relative;
        }
        .group-row .group-toggle-icon {
            transition: transform 0.3s ease-in-out;
        }
        .group-row.expanded .group-toggle-icon {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-dark text-white font-poppins flex min-h-screen">

    <?php include_once 'sidebar.php'; ?>

    <main class="flex-grow p-6 overflow-x-hidden">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
        
        <header class="bg-dark-card p-4 flex justify-between items-center lg:hidden sticky top-0 z-40">
            <button id="open-sidebar-btn" class="text-white">
                <span class="material-symbols-outlined text-3xl">menu</span>
            </button>
            <span class="text-lg font-semibold text-light-gray">Pendaftar Event</span>
            <div class="w-6 h-6"></div>
        </header>

        <div class="bg-dark-card p-8 rounded-xl shadow-lg mt-4 lg:mt-0">
            <h1 class="text-3xl font-bold text-primary-green mb-6">Data Pendaftar Event</h1>
            
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

            <div class="flex flex-col md:flex-row gap-4 mb-6 items-center justify-between">
                <div class="w-full md:w-1/2 flex items-center gap-2 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-mid-gray">search</span>
                    <input type="text" id="search_input" placeholder="Cari nama, email, atau event..." class="w-full pl-10 pr-4 py-2 rounded-md bg-dark-gray text-light-gray border border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>
                
                <div class="flex-grow flex flex-col md:flex-row items-center gap-4">
                     <div class="w-full md:w-auto flex items-center gap-2">
                        <label for="event_id" class="text-light-gray text-sm">Filter:</label>
                        <select id="event_id" class="block w-full px-4 py-2 rounded-md bg-dark-gray text-light-gray border border-gray-700 focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                            <option value="">Semua Event</option>
                            <?php foreach ($all_events as $event): ?>
                                <?php if ($_SESSION['role'] === 'master' || $event['kategori'] === $_SESSION['role']): ?>
                                    <option value="<?php echo $event['id_event']; ?>">
                                        <?php echo htmlspecialchars($event['judul_event']); ?> (<?php echo $event['statusbayar'] == 'yes' ? 'Berbayar' : 'Gratis'; ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-mid-gray text-sm">Urutkan:</span>
                        <button id="sort_terbaru" class="px-3 py-1 rounded-md bg-primary-green text-dark text-sm font-semibold transition-colors">Terbaru</button>
                        <button id="sort_terlama" class="px-3 py-1 rounded-md bg-dark-gray text-light-gray text-sm hover:bg-mid-gray transition-colors">Terlama</button>
                    </div>
                </div>
                
                <button id="download_csv" class="w-full md:w-auto px-4 py-2 rounded-md bg-blue-500 text-white text-sm font-semibold hover:bg-blue-600 transition-colors flex items-center justify-center gap-2 mt-4 md:mt-0">
                    <span class="material-symbols-outlined" style="font-size: 1rem;">download</span>
                    Download CSV
                </button>
            </div>

            <div class="flex justify-between items-center mb-4">
                 <p class="text-mid-gray text-sm">Total Pendaftar: <span id="total-records" class="text-primary-green font-semibold">...</span></p>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-700 shadow-md">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-dark-gray text-mid-gray uppercase text-sm font-semibold">
                            <th class="p-4 rounded-tl-lg">No.</th>
                            <th class="p-4">Nama User</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Nomor HP</th>
                            <th class="p-4">Event</th>
                            <th class="p-4">Kode Tiket</th>
                            <th class="p-4 text-center">Status Bayar</th>
                            <th class="p-4 text-center">Bukti</th>
                            <th class="p-4 text-center rounded-tr-lg">Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody id="registrant-data">
                        <tr><td colspan="9" class="p-4 text-center text-mid-gray bg-dark-card rounded-b-lg">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>

            <div id="pagination-controls" class="flex justify-between items-center mt-6">
                <button id="prev-page" class="px-4 py-2 rounded-md bg-dark-gray text-light-gray hover:bg-mid-gray transition-colors" disabled>
                    <span class="material-symbols-outlined">arrow_back</span>
                </button>
                <div id="page-info" class="text-sm text-mid-gray">Halaman 1 dari ...</div>
                <button id="next-page" class="px-4 py-2 rounded-md bg-dark-gray text-light-gray hover:bg-mid-gray transition-colors" disabled>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </div>
    </main>
    
    <div id="image-modal" class="modal-overlay">
        <div class="modal-content">
            <button id="close-modal-btn" class="absolute top-2 right-2 text-white bg-dark-gray rounded-full w-8 h-8 flex items-center justify-center hover:bg-mid-gray transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
            <img id="modal-image" src="" alt="Bukti Pembayaran" class="max-w-full max-h-[80vh] rounded-md">
        </div>
    </div>

    <div id="verification-modal" class="modal-overlay">
        <div class="bg-dark-card p-6 rounded-lg shadow-lg max-w-sm w-full mx-4">
            <h2 class="text-xl font-bold mb-4 text-light-gray">Konfirmasi Verifikasi</h2>
            <p class="text-mid-gray mb-6">Anda yakin ingin mengubah status verifikasi?</p>
            <div class="flex justify-end gap-4">
                <button id="cancel-verification-btn" class="px-4 py-2 rounded-md bg-dark-gray text-light-gray hover:bg-mid-gray transition-colors">Batal</button>
                <button id="confirm-verification-btn" class="px-4 py-2 rounded-md bg-primary-green text-dark font-semibold hover:bg-green-500 transition-colors">Ya</button>
            </div>
        </div>
    </div>

    <script>
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

        let currentPage = 1;
        let currentSort = 'terbaru';
        let verificationData = {}; // Untuk menyimpan data verifikasi sementara

        const totalRecordsEl = document.getElementById('total-records');
        const registrantDataBody = document.getElementById('registrant-data');
        const eventFilterEl = document.getElementById('event_id');
        const searchInputEl = document.getElementById('search_input');
        const prevPageBtn = document.getElementById('prev-page');
        const nextPageBtn = document.getElementById('next-page');
        const pageInfoEl = document.getElementById('page-info');
        const sortTerbaruBtn = document.getElementById('sort_terbaru');
        const sortTerlamaBtn = document.getElementById('sort_terlama');
        const downloadCsvBtn = document.getElementById('download_csv');

        // Modal Elements
        const imageModal = document.getElementById('image-modal');
        const modalImage = document.getElementById('modal-image');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const verificationModal = document.getElementById('verification-modal');
        const confirmVerificationBtn = document.getElementById('confirm-verification-btn');
        const cancelVerificationBtn = document.getElementById('cancel-verification-btn');

        async function fetchData(page) {
            const eventId = eventFilterEl.value;
            const searchTerm = searchInputEl.value;
            
            const params = new URLSearchParams({
                page: page,
                event_id: eventId,
                search: searchTerm,
                sort: currentSort
            });
            
            // Tampilkan loading state
            registrantDataBody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-mid-gray bg-dark-card rounded-b-lg">Memuat data...</td></tr>`;

            try {
                const response = await fetch(`get_data_pendaftar.php?${params.toString()}`);
                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                totalRecordsEl.textContent = data.total_records;
                currentPage = data.current_page;
                
                renderTable(data.data, (currentPage - 1) * 50);
                updatePagination(data.total_pages);

            } catch (error) {
                console.error("Gagal mengambil data:", error);
                registrantDataBody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-red-error bg-dark-card rounded-b-lg">Terjadi kesalahan: ${error.message}</td></tr>`;
                totalRecordsEl.textContent = '0';
                updatePagination(0);
            }
        }

        function renderTable(registrants, offset) {
            let html = '';
            if (registrants.length > 0) {
                registrants.forEach((r, index) => {
                    const paymentProofBtn = r.path_file 
                        ? `<button data-image="../${r.path_file}" class="view-image-btn px-3 py-1 rounded-md bg-blue-500 text-white text-sm hover:bg-blue-600 transition-colors">Lihat Bukti</button>`
                        : `<span class="text-mid-gray text-xs italic">—</span>`;
                    
                    const verificationButton = `<button
                                    data-id="${r.id_tiket}"
                                    data-status="${r.is_verified}"
                                    class="toggle-verification px-3 py-1 rounded-md text-xs font-semibold
                                    ${r.is_verified == 1 ? 'bg-primary-green text-dark' : 'bg-gray-500 text-white'} transition-colors">
                                    ${r.is_verified == 1 ? 'Verifikasi' : 'Belum'}
                                </button>`;

                    const paymentStatusSpan = `<span class="px-2 py-1 rounded-full text-xs font-semibold
                                    ${r.payment_status === 'paid' ? 'bg-primary-green text-dark' : 'text-white'}">
                                    ${r.payment_status === 'paid' ? 'Lunas' : 'Belum Dibayar'}
                                </span>`;
                    
                    // Helper function to handle null/empty data
                    const formatData = (value) => (value === null || value === '' || typeof value === 'undefined') ? '—' : value;

                    if (r.is_grouped) {
                        // Ini adalah baris grup
                        html += `
                        <tr class="group-row border-b border-gray-700 hover:bg-dark-gray transition-colors cursor-pointer" data-group-id="${r.id_user}-${r.id_event}-${r.created_tiket}">
                            <td class="p-4">${offset + index + 1}</td>
                            <td class="p-4 font-semibold ">
                                
                                ${formatData(r.nama_user)}
                            </td>
                            <td class="p-4 italic">${formatData(r.user_email)}</td>
                            <td class="p-4 italic">${formatData(r.user_no_hp)}</td>
                            <td class="p-4">${formatData(r.judul_event)}</td>
                            <td class="p-4"> <span class="px-3 py-1 rounded-md bg-primary-green text-dark text-sm font-semibold transition-colors"> Grup </span> <span class="material-symbols-outlined group-toggle-icon align-middle inline-block transform transition-transform mr-2">expand_more</span></td>
                            <td class="p-4 text-center">${paymentStatusSpan}</td>
                            <td class="p-4 text-center">${paymentProofBtn}</td>
                            <td class="p-4 text-center">Cek Verifikasi</td>
                        </tr>
                        `;
                        
                        // Loop untuk anggota grup yang akan disembunyikan
                        r.group_members.forEach(member => {
                             const memberVerificationButton = `<button
                                    data-id="${member.id_tiket}"
                                    data-status="${member.is_verified}"
                                    class="toggle-verification px-3 py-1 rounded-md text-xs font-semibold
                                    ${member.is_verified == 1 ? 'bg-primary-green text-dark' : 'bg-gray-500 text-white'} transition-colors">
                                    ${member.is_verified == 1 ? 'Verifikasi' : 'Belum'}
                                </button>`;
                            
                            html += `
                            <tr class="group-member-row border-b border-gray-700 hover:bg-dark-gray transition-colors hidden text-sm" data-group-id="${r.id_user}-${r.id_event}-${r.created_tiket}">
                                <td class="p-4"></td>
                                <td class="p-4 pl-8"><span class="material-symbols-outlined mr-2 text-xs">person</span>${formatData(member.nama_lengkap)}</td>
                                <td class="p-4 italic text-mid-gray">${formatData(member.user_email)}</td>
                                <td class="p-4 italic text-mid-gray">${formatData(member.user_no_hp)}</td>
                                <td class="p-4"></td>
                                <td class="p-4 font-mono text-mid-gray">${formatData(member.tiket_code)}</td>
                                <td class="p-4"></td>
                                <td class="p-4"></td>
                                <td class="p-4 text-center">${memberVerificationButton}</td>
                            </tr>
                            `;
                        });

                    } else {
                        // Ini adalah baris pendaftar tunggal
                        html += `
                            <tr class="border-b border-gray-700 hover:bg-dark-gray transition-colors">
                                <td class="p-4">${offset + index + 1}</td>
                                <td class="p-4">${formatData(r.nama_user)}</td>
                                <td class="p-4">${formatData(r.user_email)}</td>
                                <td class="p-4">${formatData(r.user_no_hp)}</td>
                                <td class="p-4">${formatData(r.judul_event)}</td>
                                <td class="p-4 font-mono text-mid-gray">${formatData(r.tiket_code)}</td>
                                <td class="p-4 text-center">
                                    ${paymentStatusSpan}
                                </td>
                                <td class="p-4 text-center">
                                    ${paymentProofBtn}
                                </td>
                                <td class="p-4 text-center">
                                    ${verificationButton}
                                </td>
                            </tr>
                        `;
                    }
                });
            } else {
                html = `<tr><td colspan="9" class="p-4 text-center text-mid-gray">Tidak ada data pendaftar.</td></tr>`;
            }
            registrantDataBody.innerHTML = html;
            addVerificationListeners();
            addModalListeners();
            addGroupListeners(); // Panggil fungsi baru untuk grup
        }

        function updatePagination(totalPages) {
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
            pageInfoEl.textContent = `Halaman ${currentPage} dari ${totalPages}`;
        }
        
        async function updateVerificationStatus(id, newStatus) {
            try {
                const response = await fetch('update_verification_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id, status: newStatus })
                });
                const result = await response.json();
                
                if (result.success) {
                    const button = document.querySelector(`.toggle-verification[data-id="${id}"]`);
                    if (button) {
                        button.setAttribute('data-status', newStatus);
                        if (newStatus == 1) {
                            button.textContent = 'Verifikasi';
                            button.classList.remove('bg-gray-500', 'text-white');
                            button.classList.add('bg-primary-green', 'text-dark');
                            const paymentStatusCell = button.closest('tr').querySelector('td:nth-child(7) span');
                            if (paymentStatusCell) {
                                paymentStatusCell.textContent = 'Lunas';
                                paymentStatusCell.classList.remove('');
                                paymentStatusCell.classList.add('bg-primary-green', 'text-dark');
                            }
                        } else {
                            button.textContent = 'Belum';
                            button.classList.remove('bg-primary-green', 'text-dark');
                            button.classList.add('bg-gray-500', 'text-white');
                            const paymentStatusCell = button.closest('tr').querySelector('td:nth-child(7) span');
                            if (paymentStatusCell) {
                                paymentStatusCell.textContent = 'Belum Dibayar';
                                paymentStatusCell.classList.remove('bg-primary-green', 'text-dark');
                                paymentStatusCell.classList.add('');
                            }
                        }
                    }
                    verificationModal.style.display = 'none';
                } else {
                    alert(result.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui status.');
            }
        }
        
        function addVerificationListeners() {
            document.querySelectorAll('.toggle-verification').forEach(button => {
                button.addEventListener('click', (e) => {
                    const id = e.target.getAttribute('data-id');
                    const currentStatus = e.target.getAttribute('data-status');
                    const newStatus = currentStatus == 1 ? 0 : 1;
                    
                    verificationData = { id, newStatus };
                    verificationModal.style.display = 'flex';
                });
            });
        }

        // Event listener untuk tombol 'Ya, Verifikasi' pada modal
        confirmVerificationBtn.addEventListener('click', () => {
            if (verificationData.id) {
                updateVerificationStatus(verificationData.id, verificationData.newStatus);
            }
        });

        // Event listener untuk tombol 'Batal' pada modal
        cancelVerificationBtn.addEventListener('click', () => {
            verificationModal.style.display = 'none';
            verificationData = {}; // Reset data
        });
        
        function addModalListeners() {
            document.querySelectorAll('.view-image-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const imagePath = e.target.getAttribute('data-image');
                    modalImage.src = imagePath;
                    imageModal.style.display = 'flex';
                });
            });
            
            closeModalBtn.addEventListener('click', () => {
                imageModal.style.display = 'none';
            });
            
            imageModal.addEventListener('click', (e) => {
                if (e.target === imageModal) {
                    imageModal.style.display = 'none';
                }
            });
        }
        
        function addGroupListeners() {
            document.querySelectorAll('.group-row').forEach(row => {
                row.addEventListener('click', (e) => {
                    const groupId = row.getAttribute('data-group-id');
                    
                    document.querySelectorAll(`.group-member-row[data-group-id="${groupId}"]`).forEach(memberRow => {
                        memberRow.classList.toggle('hidden');
                    });
                    
                    row.classList.toggle('expanded'); // Tambahkan kelas untuk animasi ikon
                });
            });
        }

        // Event Listeners
        eventFilterEl.addEventListener('change', () => fetchData(1));
        searchInputEl.addEventListener('input', () => fetchData(1));
        sortTerbaruBtn.addEventListener('click', () => {
            currentSort = 'terbaru';
            sortTerbaruBtn.classList.add('bg-primary-green', 'text-dark');
            sortTerbaruBtn.classList.remove('bg-dark-gray', 'text-light-gray', 'hover:bg-mid-gray');
            sortTerlamaBtn.classList.add('bg-dark-gray', 'text-light-gray', 'hover:bg-mid-gray');
            sortTerlamaBtn.classList.remove('bg-primary-green', 'text-dark');
            fetchData(1);
        });
        sortTerlamaBtn.addEventListener('click', () => {
            currentSort = 'terlama';
            sortTerlamaBtn.classList.add('bg-primary-green', 'text-dark');
            sortTerlamaBtn.classList.remove('bg-dark-gray', 'text-light-gray', 'hover:bg-mid-gray');
            sortTerbaruBtn.classList.add('bg-dark-gray', 'text-light-gray', 'hover:bg-mid-gray');
            sortTerbaruBtn.classList.remove('bg-primary-green', 'text-dark');
            fetchData(1);
        });
        downloadCsvBtn.addEventListener('click', () => {
            const eventId = eventFilterEl.value;
            const searchTerm = searchInputEl.value;
            const params = new URLSearchParams({
                event_id: eventId,
                search: searchTerm,
                sort: currentSort
            });
            window.location.href = `download_data.php?${params.toString()}`;
        });

        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                fetchData(currentPage - 1);
            }
        });

        nextPageBtn.addEventListener('click', () => {
            fetchData(currentPage + 1);
        }
        );

        // Muat data saat halaman pertama kali dibuka
        document.addEventListener('DOMContentLoaded', () => {
            fetchData(1);
        });
    </script>
</body>
</html>