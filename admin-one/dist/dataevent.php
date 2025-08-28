<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Data Event</title>

    <!-- Tailwind is included -->
    <link rel="stylesheet" href="css/main.css?v=1628755089081">

    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png" />
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#00b4b6" />

    <meta name="description" content="Admin One - free Tailwind dashboard">

    <meta property="og:url" content="https://justboil.github.io/admin-one-tailwind/">
    <meta property="og:site_name" content="JustBoil.me">
    <meta property="og:title" content="Admin One HTML">
    <meta property="og:description" content="Admin One - free Tailwind dashboard">
    <meta property="og:image" content="https://justboil.me/images/one-tailwind/repository-preview-hi-res.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1920">
    <meta property="og:image:height" content="960">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="Admin One HTML">
    <meta property="twitter:description" content="Admin One - free Tailwind dashboard">
    <meta property="twitter:image:src" content="https://justboil.me/images/one-tailwind/repository-preview-hi-res.png">
    <meta property="twitter:image:width" content="1920">
    <meta property="twitter:image:height" content="960">

</head>

<body>

    <div id="app">

        <?php include 'navbar.php'; ?>

        <!-- Content Here Start -->


        <section class="section main-section">
            <p>Add Event and click this button</p>
            <div class="field">
                <div class="control">
                    <a class="button blue" href="index.php">Back</a>
                    <a class="button blue" href="addevent.php">
                        <i class="mdi mdi-plus"></i> Add New Event
                    </a>
                </div>
            </div>

            <!-- Jika Tersedia Data Maka Masukkan ke Tabel -->
            <div class="card has-table">
                <div class="card-content">
                    <?php
                    // Konfigurasi halaman dan jumlah data per halaman
                    $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 10;
                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                    // Koneksi ke database
                    require_once "koneksi.php";
                    $koneksi = mysqli_connect($host, $username, $password, $database);

                    // Periksa koneksi
                    if (mysqli_connect_errno()) {
                        die("Koneksi database gagal: " . mysqli_connect_error());
                    }

                    // Query untuk mengambil data event dengan pagination dan menghitung pendaftar
                    $offset = ($current_page - 1) * $per_page;
                    $query = "
    SELECT e.*, e.kuota, 
    (SELECT COUNT(*) FROM tiket WHERE id_event = e.id_event) AS total_pendaftar 
    FROM event e 
    ORDER BY e.created_event DESC LIMIT ?, ?";

                    $stmt = mysqli_prepare($koneksi, $query);
                    mysqli_stmt_bind_param($stmt, "ii", $offset, $per_page);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    // Jika ada data
                    if (mysqli_num_rows($result) > 0) {
                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>No.</th>';
                        echo '<th>Urutan</th>';
                        echo '<th>Judul</th>';
                        echo '<th>Speakers</th>';
                        echo '<th>Tanggal</th>';
                        echo '<th>Waktu</th>';
                        echo '<th>Lokasi</th>';
                        echo '<th>Created</th>';
                        echo '<th>Pendaftar</th>';
                        echo '<th>Kuota</th>';
                        echo '<th>Status</th>';
                        echo '<th>Show</th>';
                        echo '<th>Action</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        $index = $offset + 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $index . '</td>';
                            echo '<td>' . htmlspecialchars($row['urutan_show']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['judul_event']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['speakers_event']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['jadwal_event']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['waktu_event']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['lokasi_event']) . '</td>';
                            echo '<td>' . date('M d, Y', strtotime($row['created_event'])) . '</td>';
                            echo '<td>' . $row['total_pendaftar'] . '</td>'; // Menampilkan total pendaftar
                            echo '<td>' . htmlspecialchars($row['kuota']) . '</td>'; // Menampilkan nilai kuota

                            // Menampilkan status event (Open/Close/Pending)
                            $status_text = '';
                            switch ($row['event_status']) {
                                case 1:
                                    $status_text = 'Open';
                                    break;
                                case 0:
                                    $status_text = 'Close';
                                    break;
                                case 2:
                                    $status_text = 'Pending';
                                    break;
                                default:
                                    $status_text = 'Unknown'; // Untuk menangani status yang tidak terduga
                            }
                            echo '<td>' . $status_text . '</td>';

                            // Menampilkan show event (Show/Hide)
                            echo '<td>' . ($row['show_event'] == 1 ? 'Show' : 'Hide') . '</td>';

                            // Action buttons
                            echo '<td class="actions-cell">';
                            echo '<div class="buttons right nowrap">';
                            echo '<a class="button small green" href="edit_event.php?id_event=' . $row['id_event'] . '">';
                            echo '<span class="icon"><i class="mdi mdi-pen"></i></span>';
                            echo '</a>';
                            echo '<a class="button small blue" href="details_event.php?id_event=' . $row['id_event'] . '">';
                            echo '<span class="icon"><i class="mdi mdi-eye"></i></span>';
                            echo '</a>';
                            echo '<a class="button small red --jb-modal" onclick="confirmDelete(' . $row['id_event'] . ')">';
                            echo '<span class="icon"><i class="mdi mdi-trash-can"></i></span>';
                            echo '</a>';
                            echo '</div>';
                            echo '</td>';
                            echo '</tr>';
                            $index++;
                        }

                        echo '</tbody>';
                        echo '</table>';

                        // Hitung total data
                        $count_query = "SELECT COUNT(*) AS total FROM event";
                        $count_result = mysqli_query($koneksi, $count_query);
                        $total_data = mysqli_fetch_assoc($count_result)['total'];

                        // Tampilkan navigasi halaman jika lebih dari 1 halaman
                        if ($total_data > $per_page) {
                            echo '<div class="table-pagination">';
                            echo '<div class="flex items-center justify-between">';
                            echo '<div class="buttons">';
                            $total_pages = ceil($total_data / $per_page);
                            for ($i = 1; $i <= $total_pages; $i++) {
                                echo '<a href="?page=' . $i . '&per_page=' . $per_page . '" class="button ' . ($current_page == $i ? 'active' : '') . '">' . $i . '</a>';
                            }
                            echo '</div>';
                            echo '<small>Page ' . $current_page . ' of ' . $total_pages . '</small>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        // Jika tidak ada data
                        echo '<div class="card empty">';
                        echo '<div class="card-content">';
                        echo '<div>';
                        echo '<span class="icon large"><i class="mdi mdi-emoticon-sad mdi-48px"></i></span>';
                        echo '</div>';
                        echo '<p>Nothing\'s here…</p>';
                        echo '</div>';
                        echo '</div>';
                    }

                    // Tutup statement dan koneksi
                    mysqli_stmt_close($stmt);
                    mysqli_close($koneksi);
                    ?>

                </div>
            </div>
        </section>



        <!-- Content Here End -->

        <?php include 'footer.php'; ?>

    </div>

    <!-- Scripts below are for demo only -->
    <script type="text/javascript" src="js/main.min.js?v=1628755089081"></script>
    <script>
        function confirmDelete(eventId) {
            if (confirm('Apakah kamu yakin untuk menghapus data ini?')) {
                window.location.href = 'deleteevent.php?id=' + eventId;
            }
        }
    </script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <script type="text/javascript" src="js/chart.sample.min.js"></script>

    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=658339141622648&ev=PageView&noscript=1" /></noscript>

    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>