<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Data User</title>

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
        <?php
        // Koneksi ke database
        require_once "koneksi.php";
        $koneksi = mysqli_connect($host, $username, $password, $database);

        // Periksa koneksi
        if (mysqli_connect_errno()) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }

        // Ambil id_event dari URL
        $id_event = isset($_GET['id_event']) ? intval($_GET['id_event']) : 0;

        // Ambil filter dari form
        $filter_nama = isset($_GET['filter_nama']) ? $_GET['filter_nama'] : '';
        $filter_instansi = isset($_GET['filter_instansi']) ? $_GET['filter_instansi'] : '';

        // Ambil parameter sort dan order dari URL
        $sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'nama'; // Default sort by nama
        $sort_order = isset($_GET['sort_order']) && in_array($_GET['sort_order'], ['ASC', 'DESC']) ? $_GET['sort_order'] : 'ASC'; // Default order ASC
        
        // Daftar kolom yang dapat diurutkan
        $sortable_columns = ['nama', 'tgl_lahir', 'no_hp', 'instansi', 'email'];

        // Validasi kolom yang digunakan untuk sorting
        if (!in_array($sort_column, $sortable_columns)) {
            $sort_column = 'nama'; // Default ke kolom nama jika kolom tidak valid
        }

        // Query untuk mendapatkan detail event, termasuk event_status dan show_event
        $query_event = "SELECT * FROM event WHERE id_event = ?";
        $stmt_event = mysqli_prepare($koneksi, $query_event);
        mysqli_stmt_bind_param($stmt_event, "i", $id_event);
        mysqli_stmt_execute($stmt_event);
        $result_event = mysqli_stmt_get_result($stmt_event);
        $row_event = mysqli_fetch_assoc($result_event);

        // Jika tidak ada event dengan id tersebut
        if (!$row_event) {
            die("Event tidak ditemukan.");
        }

        // Tampilkan status event dan show event
        $event_status = $row_event['event_status'] ? 'Open' : 'Close';
        $show_event = $row_event['show_event'] ? 'Show' : 'Hide';

        // Query untuk mengambil pengguna yang mendaftar untuk event ini dengan filter dan sorting
        $query_users = "SELECT u.id_user, u.nama, u.tgl_lahir, u.no_hp, u.instansi, u.email 
    FROM tiket t
    JOIN user u ON t.id_user = u.id_user
    WHERE t.id_event = ?
    AND u.nama LIKE ?
    AND u.instansi LIKE ?
    ORDER BY $sort_column $sort_order";
        $stmt_users = mysqli_prepare($koneksi, $query_users);
        $filter_nama = "%$filter_nama%";
        $filter_instansi = "%$filter_instansi%";
        mysqli_stmt_bind_param($stmt_users, "iss", $id_event, $filter_nama, $filter_instansi);
        mysqli_stmt_execute($stmt_users);
        $result_users = mysqli_stmt_get_result($stmt_users);

        // Hitung total pengguna
        $total_users = mysqli_num_rows($result_users);
        ?>


        <section class="section main-section">
            <div class="field">
                <div class="control">
                    <a class="button blue" href="dataevent.php">Back</a>
                </div>
            </div>

            <!-- Tombol untuk mengubah Status dan Show Event -->
            <form method="POST" action="update_event.php">
                <input type="hidden" name="id_event" value="<?php echo $row_event['id_event']; ?>">

                <!-- Dropdown untuk Event Status -->
                <div class="field">
                    <label class="label">Event Status</label>
                    <div class="control">
                        <div class="select">
                            <select name="event_status">
                                <option value="1" <?php echo $row_event['event_status'] == 1 ? 'selected' : ''; ?>>Open
                                </option>
                                <option value="0" <?php echo $row_event['event_status'] == 0 ? 'selected' : ''; ?>>Close
                                </option>
                                <option value="2" <?php echo $row_event['event_status'] == 2 ? 'selected' : ''; ?>>Pending
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Dropdown untuk Show Event -->
                <div class="field">
                    <label class="label">Show Event</label>
                    <div class="control">
                        <div class="select">
                            <select name="show_event">
                                <option value="1" <?php echo $row_event['show_event'] == 1 ? 'selected' : ''; ?>>Show
                                </option>
                                <option value="0" <?php echo $row_event['show_event'] == 0 ? 'selected' : ''; ?>>Hide
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Urutan Show</label>
                    <p>Dalam page akan disusun berdasarkan nomor terendah ke terbesar. skala prioritas jika pemateri
                        sebagai orang penting, maka taruh di angka kecil.</p>
                    <div class="control">
                        <input type="number" value="<?php echo $row_event['urutan_show']; ?>" name="urutan_show" class="input">
                    </div>
                </div>
                <!-- Tombol Submit -->
                <div class="field">
                    <div class="control">
                        <button type="submit" class="button blue">Update Event</button>
                    </div>
                </div>


            </form>


            <!-- Event Details Section -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            <span class="icon"><i class="mdi mdi-account"></i></span>
                            Event Details
                        </p>
                    </header>
                    <div class="card-content">
                        <?php
                        $event_fields = [
                            'Judul Event' => 'judul_event',
                            'Speakers' => 'speakers_event',
                            'Tanggal' => 'jadwal_event',
                            'Waktu' => 'waktu_event',
                            'Lokasi' => 'lokasi_event'
                        ];
                        foreach ($event_fields as $label => $field) {
                            echo "
                            <div class='field'>
                                <label class='label'>$label</label>
                                <div class='control'>
                                    <input type='text' readonly value='" . htmlspecialchars($row_event[$field]) . "' class='input is-static'>
                                </div>
                            </div>
                        ";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="mb-6">
                <form method="GET" action="">
                    <input type="hidden" name="id_event" value="<?= $id_event ?>">
                    <div class="field is-horizontal">
                        <div class="field-body">
                            <div class="field">
                                <label class="label">Urutkan</label>
                                <div class="control">
                                    <select name="sort_order" class="input">
                                        <option value="DESC" <?= $sort_order === 'DESC' ? 'selected' : '' ?>>Terbaru
                                        </option>
                                        <option value="ASC" <?= $sort_order === 'ASC' ? 'selected' : '' ?>>Terlama</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button blue">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel data pengguna -->
            <div class="mt-8">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold mb-2">Total Users: <?= $total_users ?></h2>

                        <div class="overflow-x-auto">
                            <table class="table-auto w-full">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="border px-4 py-2">#</th>
                                        <th class="border px-4 py-2">
                                            <a
                                                href="?id_event=<?= $id_event ?>&sort=nama&sort_order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC' ?>">Nama
                                                <?= $sort_column === 'nama' ? ($sort_order === 'ASC' ? '↑' : '↓') : '' ?></a>
                                        </th>
                                        <th class="border px-4 py-2">
                                            <a
                                                href="?id_event=<?= $id_event ?>&sort=tgl_lahir&sort_order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC' ?>">Tanggal
                                                Lahir
                                                <?= $sort_column === 'tgl_lahir' ? ($sort_order === 'ASC' ? '↑' : '↓') : '' ?></a>
                                        </th>
                                        <th class="border px-4 py-2">
                                            <a
                                                href="?id_event=<?= $id_event ?>&sort=no_hp&sort_order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC' ?>">No
                                                HP
                                                <?= $sort_column === 'no_hp' ? ($sort_order === 'ASC' ? '↑' : '↓') : '' ?></a>
                                        </th>
                                        <th class="border px-4 py-2">
                                            <a
                                                href="?id_event=<?= $id_event ?>&sort=instansi&sort_order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC' ?>">Instansi
                                                <?= $sort_column === 'instansi' ? ($sort_order === 'ASC' ? '↑' : '↓') : '' ?></a>
                                        </th>
                                        <th class="border px-4 py-2">
                                            <a
                                                href="?id_event=<?= $id_event ?>&sort=email&sort_order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC' ?>">Email
                                                <?= $sort_column === 'email' ? ($sort_order === 'ASC' ? '↑' : '↓') : '' ?></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    while ($row_user = mysqli_fetch_assoc($result_users)) {
                                        echo "
        <tr>
            <td class='border px-4 py-2'>$counter</td>
            <td class='border px-4 py-2'>" . htmlspecialchars($row_user['nama']) . "</td>
            <td class='border px-4 py-2'>" . htmlspecialchars($row_user['tgl_lahir']) . "</td>
            <td class='border px-4 py-2'>" . htmlspecialchars($row_user['no_hp']) . "</td>
            <td class='border px-4 py-2'>" . htmlspecialchars($row_user['instansi']) . "</td>
            <td class='border px-4 py-2'>" . htmlspecialchars($row_user['email']) . "</td>
            <td class='border px-4 py-2'>
                <form method='POST' action='delete_tiket.php'>
                    <input type='hidden' name='id_user' value='" . $row_user['id_user'] . "' />
                    <input type='hidden' name='id_event' value='" . $id_event . "' />
                    <button type='submit' class='button small red --jb-modal'><i class='mdi mdi-trash-can'></i></button>
                </form>
            </td>
        </tr>
        ";
                                        $counter++;
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </section>

        <?php
        // Tutup statement dan koneksi
        mysqli_stmt_close($stmt_event);
        mysqli_stmt_close($stmt_users);
        mysqli_close($koneksi);
        ?>

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