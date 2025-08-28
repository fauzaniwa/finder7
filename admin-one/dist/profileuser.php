<?php
// Ambil id_user dari parameter GET
$id_user = isset($_GET['id']) ? $_GET['id'] : null;

// Lakukan koneksi ke database dan query data pengguna berdasarkan id_user
require_once "koneksi.php"; // Pastikan file koneksi.php sesuai dengan konfigurasi koneksi Anda

// Query untuk mengambil data user berdasarkan id_user
$query_user = "SELECT * FROM user WHERE id_user = ?";
$stmt_user = mysqli_prepare($koneksi, $query_user);

if ($stmt_user) {
    mysqli_stmt_bind_param($stmt_user, "i", $id_user);
    mysqli_stmt_execute($stmt_user);
    $result_user = mysqli_stmt_get_result($stmt_user);

    if ($row_user = mysqli_fetch_assoc($result_user)) {
        // Jika data pengguna ditemukan, simpan ke dalam variabel untuk tampilan
        $nama = $row_user['nama'];
        $tgl_lahir = $row_user['tgl_lahir'];
        $no_hp = $row_user['no_hp'];
        $email = $row_user['email'];
    } else {
        // Jika id_user tidak valid atau data tidak ditemukan, mungkin tampilkan pesan kesalahan atau redirect
        echo "Data pengguna tidak ditemukan.";
        exit;
    }

    // Tutup statement setelah selesai menggunakan
    mysqli_stmt_close($stmt_user);
} else {
    // Jika prepare statement gagal, tampilkan pesan kesalahan atau log error
    echo "Error: " . mysqli_error($koneksi);
    exit;
}

// Query untuk mengambil data tiket dan event berdasarkan id_user
$query_event = "
    SELECT
        e.judul_event,
        t.tiket_code AS kode_event,
        IF(a.kode_absen IS NOT NULL, 'Hadir', 'Tidak Hadir') AS status_kehadiran
    FROM
        tiket t
    JOIN
        event e ON t.id_event = e.id_event
    LEFT JOIN
        (SELECT a.kode_absen FROM absen a JOIN tiket t ON a.kode_absen = t.tiket_code WHERE t.id_user = ?) a
    ON t.tiket_code = a.kode_absen
    WHERE
        t.id_user = ?
";
$stmt_event = mysqli_prepare($koneksi, $query_event);

if ($stmt_event) {
    mysqli_stmt_bind_param($stmt_event, "ii", $id_user, $id_user);
    mysqli_stmt_execute($stmt_event);
    $result_event = mysqli_stmt_get_result($stmt_event);
} else {
    // Jika prepare statement gagal, tampilkan pesan kesalahan atau log error
    echo "Error: " . mysqli_error($koneksi);
    exit;
}

// Tutup koneksi setelah selesai menggunakan
mysqli_close($koneksi);
?>

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
        <section class="section main-section">
            <div class="field">
                <div class="control">
                    <a class="button blue" href="datauser.php">
                        Back
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            <span class="icon"><i class="mdi mdi-account"></i></span>
                            Profile
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="field">
                            <label class="label">Name</label>
                            <div class="control">
                                <input type="text" readonly value="<?php echo htmlspecialchars($nama); ?>"
                                    class="input is-static">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Tgl Lahir</label>
                            <div class="control">
                                <input type="text" readonly value="<?php echo htmlspecialchars($tgl_lahir); ?>"
                                    class="input is-static">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">No HP</label>
                            <div class="control">
                                <input type="text" readonly value="<?php echo htmlspecialchars($no_hp); ?>"
                                    class="input is-static">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">E-mail</label>
                            <div class="control">
                                <input type="text" readonly value="<?php echo htmlspecialchars($email); ?>"
                                    class="input is-static">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Event -->
            <div class="mt-8">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold mb-2">Information Event</h2>
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="border px-4 py-2">#</th>
                                        <th class="border px-4 py-2">Judul Event</th>
                                        <th class="border px-4 py-2">Kode Event</th>
                                        <th class="border px-4 py-2">Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    while ($row_event = mysqli_fetch_assoc($result_event)):
                                        ?>
                                        <tr>
                                            <td class="border px-4 py-2"><?= $counter ?></td>
                                            <td class="border px-4 py-2"><?= htmlspecialchars($row_event['judul_event']) ?>
                                            </td>
                                            <td class="border px-4 py-2"><?= htmlspecialchars($row_event['kode_event']) ?>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <?= htmlspecialchars($row_event['status_kehadiran']) ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $counter++;
                                    endwhile;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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