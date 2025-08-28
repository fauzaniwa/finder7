<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Data Absen</title>

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
        <!-- Content Start -->
        <section class="section main-section">

            <div class="field">
                <div class="control">

                    <a href="download_lomba.php" class="button blue">
                        Download Semua Data
                    </a>
                </div>
            </div>
            <div class="card has-table">
                <div class="card-content">
                    <?php
                    // Konfigurasi pagination
                    $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 50;
                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $offset = ($current_page - 1) * $per_page;

                    require_once "koneksi.php";

                    // Query data dari tabel Lomba
                    $query = "SELECT * FROM Lomba ORDER BY created_at DESC LIMIT ?, ?";
                    $stmt = mysqli_prepare($koneksi, $query);
                    mysqli_stmt_bind_param($stmt, "ii", $offset, $per_page);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        echo '<table class="min-w-full">';
                        echo '<thead><tr>
                <th>No</th>
                <th>Nama</th>
                <th>Nomor Telp</th>
                <th>Email</th>
                <th>Instansi</th>
                <th>Media Sosial</th>
                <th>Kategori Karya</th>
                <th>Judul Karya</th>
                <th>Deskripsi</th>
                <th>Link</th>
                <th>Created At</th>
              </tr></thead>';
                        echo '<tbody>';

                        $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Batasi panjang deskripsi dan tampilkan "Lihat Selengkapnya"
                            $deskripsi_full = $row['deskripsi_karya'];
                            $deskripsi_preview = strlen($deskripsi_full) > 100 ? substr($deskripsi_full, 0, 100) . '...' : $deskripsi_full;
                            $show_more = strlen($deskripsi_full) > 100;

                            echo '<tr>';
                            echo '<td>' . $no++ . '</td>';
                            echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['nomor_telp']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['instansi']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['media_sosial']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['kategori_karya']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['judul_karya']) . '</td>';

                            // Deskripsi with see more
                            echo '<td>';
                            echo '<span class="short-desc">' . nl2br(htmlspecialchars($deskripsi_preview)) . '</span>';
                            if ($show_more) {
                                echo '<span class="full-desc hidden">' . nl2br(htmlspecialchars($deskripsi_full)) . '</span>';
                                echo '<button class="text-blue-500 hover:underline see-more-btn" onclick="toggleDesc(this)">Lihat Selengkapnya</button>';
                            }
                            echo '</td>';

                            echo '<td><a href="' . htmlspecialchars($row['link_karya']) . '" target="_blank" class="text-blue-500 hover:underline">Link</a></td>';
                            echo '<td>' . date('Y-m-d H:i:s', strtotime($row['created_at'])) . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody></table>';

                        // Total count for pagination
                        $count_result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM Lomba");
                        $total_data = mysqli_fetch_assoc($count_result)['total'];
                        $total_pages = ceil($total_data / $per_page);

                        if ($total_pages > 1) {
                            echo '<div class="table-pagination mt-4">';
                            echo '<div class="buttons">';
                            for ($i = 1; $i <= $total_pages; $i++) {
                                $active = $current_page == $i ? 'bg-blue-500 text-white px-3 py-1 rounded' : 'px-3 py-1';
                                echo '<a href="?page=' . $i . '&per_page=' . $per_page . '" class="button ' . $active . '">' . $i . '</a> ';
                            }
                            echo '</div>';
                            echo '<small class="ml-2">Page ' . $current_page . ' of ' . $total_pages . '</small>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-center text-gray-500">Tidak ada data lomba.</p>';
                    }

                    mysqli_stmt_close($stmt);
                    mysqli_close($koneksi);
                    ?>
                </div>
            </div>
        </section>

        <!-- Script untuk toggle deskripsi -->
        <script>
            function toggleDesc(button) {
                const td = button.parentElement;
                const shortDesc = td.querySelector('.short-desc');
                const fullDesc = td.querySelector('.full-desc');

                if (fullDesc.classList.contains('hidden')) {
                    shortDesc.style.display = 'none';
                    fullDesc.classList.remove('hidden');
                    button.textContent = 'Lihat Lebih Sedikit';
                } else {
                    shortDesc.style.display = 'inline';
                    fullDesc.classList.add('hidden');
                    button.textContent = 'Lihat Selengkapnya';
                }
            }
        </script>


        <!-- Content End -->
        <!-- Content Here End -->

        <?php include 'footer.php'; ?>

    </div>

    <!-- Scripts below are for demo only -->
    <script type="text/javascript" src="js/main.min.js?v=1628755089081"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <script type="text/javascript" src="js/chart.sample.min.js"></script>

    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=658339141622648&ev=PageView&noscript=1" /></noscript>

    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>