<?php
session_start();

// Pengecekan sesi login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Catatan: require_once 'koneksi.php'; sudah benar.
// Path '../admin-one/dist/koneksi.php' yang Anda sebutkan akan error
// karena process_wacom.php berada di admin-one/dist/,
// dan koneksi.php juga berada di folder yang sama.
require_once '../admin-one/dist/koneksi.php';

// Ambil semua data dari tabel pendaftaran_wacom
$sql = "SELECT * FROM pendaftaran_wacom ORDER BY tanggal_pendaftaran DESC";
$result = $koneksi->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
    <style>
        .table-container {
            overflow-x: auto;
        }
        .modal {
            display: none;
        }
        body {
            font-family: 'Work Sans', sans-serif;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        work: ['Work Sans'],
                    },
                    animation: {
                        'spin-slow': 'spin 4s linear infinite',
                        'loop-scroll': 'loop-scroll 10s linear infinite',
                    },
                    keyframes: {
                        'loop-scroll': {
                            from: { transform: 'translateX(0)' },
                            to: { transform: 'translateX(-100%)' },
                        },
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-neutral-950 text-white font-work">

    <div id="paymentModal" class="modal hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center p-4">
        <div class="bg-neutral-900 rounded-lg p-6 max-w-xl w-full relative">
            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-200 text-2xl font-bold" onclick="closeModal('paymentModal')">&times;</button>
            <h2 class="text-xl font-bold mb-4 text-emerald-400">Pratinjau Bukti Pembayaran</h2>
            <img id="paymentImage" src="" alt="Bukti Pembayaran" class="w-full h-auto rounded-md">
        </div>
    </div>

    <div id="dataModal" class="modal hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center p-4">
        <div class="bg-neutral-900 rounded-lg p-6 max-w-2xl w-full relative">
            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-200 text-2xl font-bold" onclick="closeModal('dataModal')">&times;</button>
            <h2 class="text-xl font-bold mb-4 text-emerald-400">Detail Pendaftaran</h2>
            <div id="dataContent" class="space-y-2">
                </div>
        </div>
    </div>

    <div class="container mx-auto p-4 sm:p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-emerald-400">Dashboard Admin</h1>
            <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</a>
        </div>
        
        <div class="bg-neutral-900 text-white p-6 rounded-lg shadow-lg mb-6">
            <h2 class="text-xl font-semibold">Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h2>
            <p class="text-sm mt-2 text-neutral-400">Anda berhasil masuk ke halaman admin Lomba Wacom x Finder.</p>
        </div>

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl sm:text-2xl font-semibold">Data Pendaftaran</h2>
            <a href="download_data.php" class="bg-emerald-500 text-black px-4 py-2 rounded-lg hover:bg-emerald-600">Unduh Data CSV</a>
        </div>
        
        <div class="bg-neutral-900 shadow-lg rounded-lg overflow-hidden">
            <div class="table-container">
                <table class="min-w-full leading-normal text-neutral-300">
                    <thead>
                        <tr>
                            <th class="px-3 sm:px-5 py-3 border-b-2 border-neutral-700 bg-neutral-800 text-left text-xs font-semibold uppercase tracking-wider">No.</th>
                            <th class="px-3 sm:px-5 py-3 border-b-2 border-neutral-700 bg-neutral-800 text-left text-xs font-semibold uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-3 sm:px-5 py-3 border-b-2 border-neutral-700 bg-neutral-800 text-left text-xs font-semibold uppercase tracking-wider">Judul Karya</th>
                            <th class="px-3 sm:px-5 py-3 border-b-2 border-neutral-700 bg-neutral-800 text-left text-xs font-semibold uppercase tracking-wider">Drive</th>
                            <th class="px-3 sm:px-5 py-3 border-b-2 border-neutral-700 bg-neutral-800 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($result && $result->num_rows > 0) {
        $row_number = 1;
        while($row = $result->fetch_assoc()) {
            $data_json = htmlspecialchars(json_encode($row));
            echo "<tr>";
            echo "<td class='px-3 sm:px-5 py-5 border-b border-neutral-800 bg-neutral-900 text-sm'>" . $row_number . "</td>";
            echo "<td class='px-3 sm:px-5 py-5 border-b border-neutral-800 bg-neutral-900 text-sm'>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
            echo "<td class='px-3 sm:px-5 py-5 border-b border-neutral-800 bg-neutral-900 text-sm'>" . htmlspecialchars($row['judul_karya']) . "</td>";
            
            // Perubahan di sini: Mengganti teks link dengan tombol
            echo "<td class='px-3 sm:px-5 py-5 border-b border-neutral-800 bg-neutral-900 text-sm'>";
            echo "<a href='" . htmlspecialchars($row['link_karya']) . "' target='_blank' class='bg-purple-500 text-white px-3 py-1 rounded text-xs hover:bg-purple-700'>Lihat Drive</a>";
            echo "</td>";
            
            echo "<td class='px-3 sm:px-5 py-5 border-b border-neutral-800 bg-neutral-900 text-sm whitespace-nowrap'>";
            echo "<button class='bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 mr-2' onclick='openPaymentModal(\"../../wacom/uploads/" . htmlspecialchars($row['bukti_pembayaran']) . "\")'>Bukti Pembayaran</button>";
            echo "<button class='bg-emerald-500 text-white px-3 py-1 rounded text-xs hover:bg-emerald-700' onclick='openDataModal({$data_json})'>Data Lengkap</button>";
            echo "</td>";
            echo "</tr>";
            $row_number++;
        }
    } else {
        echo "<tr><td colspan='5' class='px-5 py-5 border-b border-gray-200 bg-white text-sm text-center'>Belum ada data pendaftaran.</td></tr>";
    }
    ?>
</tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        function openPaymentModal(imageSrc) {
            document.getElementById('paymentImage').src = imageSrc;
            document.getElementById('paymentModal').style.display = 'flex';
        }
        function openDataModal(data) {
            const dataContent = document.getElementById('dataContent');
            dataContent.innerHTML = '';
            const fields = [
                { label: 'Nama Lengkap', key: 'nama_lengkap' }, { label: 'Nomor Telepon', key: 'nomor_telepon' },
                { label: 'Email', key: 'email' }, { label: 'Instansi', key: 'instansi' },
                { label: 'Judul Karya', key: 'judul_karya' }, { label: 'Media Sosial', key: 'media_sosial' },
                { label: 'Deskripsi Karya', key: 'deskripsi_karya' }, { label: 'Link Karya', key: 'link_karya' },
                { label: 'Kategori Karya', key: 'kategori_karya' }, { label: 'Tanggal Pendaftaran', key: 'tanggal_pendaftaran' },
            ];
            fields.forEach(field => {
                const value = data[field.key] || '-';
                dataContent.innerHTML += `<p class="border-b pb-2"><strong class="font-semibold">${field.label}:</strong> ${value}</p>`;
            });
            document.getElementById('dataModal').style.display = 'flex';
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>
</html>

<?php $koneksi->close(); ?>