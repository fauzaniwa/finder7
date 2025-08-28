<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Profile Admin</title>

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

        <section class="section main-section">
            <?php
            // Sertakan file koneksi.php untuk menginisialisasi koneksi ke database
            include_once 'koneksi.php';

            // Query untuk mengambil jumlah total baris data
            $query_total_rows = "SELECT COUNT(*) AS total_rows FROM informasi";
            $result_total_rows = $koneksi->query($query_total_rows);
            $row_total_rows = mysqli_fetch_assoc($result_total_rows);
            $total_rows = $row_total_rows['total_rows'];

            // Jumlah data per halaman
            $rows_per_page = 50;

            // Hitung jumlah halaman
            $total_pages = ceil($total_rows / $rows_per_page);

            // Tentukan halaman saat ini
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

            // Hitung offset berdasarkan halaman saat ini
            $offset = ($current_page - 1) * $rows_per_page;

            // Query untuk mengambil data dengan limit dan offset
            $query_paginated = "SELECT * FROM informasi ORDER BY created_at DESC LIMIT $offset, $rows_per_page";
            $result_paginated = $koneksi->query($query_paginated);

            // Cek apakah ada data yang ditemukan
            if (mysqli_num_rows($result_paginated) > 0) {
                ?>
                <!-- Jika terdapat Data Start -->
                <div class="card has-table">
                    <header class="card-header">
                        <p class="card-header-title">
                            <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                            Information Activity
                        </p>
                        <a href="#" class="card-header-icon">
                            <span class="icon"><i class="mdi mdi-reload"></i></span>
                        </a>
                    </header>
                    <div class="card-content">
                        <table>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Filter</th>
                                    <th>Report</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Inisialisasi variabel nomor
                                $nomor = $offset + 1;

                                // Loop untuk menampilkan data
                                while ($row = mysqli_fetch_assoc($result_paginated)) {
                                    ?>
                                    <tr>
                                        <td data-label="Nomor"><?php echo $nomor; ?></td>
                                        <td data-label="Filter"><?php echo $row['filter']; ?></td>
                                        <td data-label="Report"><?php echo $row['report']; ?></td>
                                        <td data-label="Time"><?php echo $row['created_at']; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    // Tambahkan nomor
                                    $nomor++;
                                }
                                ?>
                            </tbody>
                        </table>

                        <div class="table-pagination">
                            <div class="flex items-center justify-between">
                                <div class="buttons">
                                    <?php
                                    // Tombol halaman
                                    for ($page = 1; $page <= $total_pages; $page++) {
                                        $active_class = ($page == $current_page) ? 'active' : '';
                                        echo '<a href="?page=' . $page . '" class="button ' . $active_class . '">' . $page . '</a>';
                                    }
                                    ?>
                                </div>
                                <small>Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></small>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Jika terdapat Data End -->
                <?php
            } else {
                ?>
                <!-- Jika Data Tidak Ada Start -->
                <div class="card empty">
                    <div class="card-content">
                        <div>
                            <span class="icon large"><i class="mdi mdi-emoticon-sad mdi-48px"></i></span>
                        </div>
                        <p>Nothing's here…</p>
                    </div>
                </div>
                <!-- Jika Data Tidak Ada End -->
                <?php
            }

            // Tutup koneksi ke database (dari koneksi.php)
            $koneksi->close();
            ?>
            <!-- Jika Data Tidak Ada End -->
        </section>


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