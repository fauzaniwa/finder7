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

        // Query untuk mendapatkan detail event
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
        ?>

        <section class="section main-section">
            <div class="field">
                <div class="control">
                    <a class="button blue" href="dataevent.php">Back</a>
                </div>
            </div>
            <form action="proses_edit_event.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_event" value="<?php echo $id_event; ?>">

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
                            // Menampilkan fields dalam form input
                            $event_fields = [
                                'Judul Event' => 'judul_event',
                                'Speakers' => 'speakers_event',
                                'Tanggal' => 'jadwal_event',
                                'Waktu' => 'waktu_event',
                                'Kuota' => 'kuota', // Field Kuota ditambahkan di sini
                                'Lokasi' => 'lokasi_event'
                            ];

                            foreach ($event_fields as $label => $field) {
                                // Untuk field Tanggal, gunakan type="date"
                                if ($field === 'jadwal_event') {
                                    echo "
        <div class='field'>
            <label class='label'>$label</label>
            <div class='control'>
                <input type='date' name='$field' value='" . htmlspecialchars($row_event[$field]) . "' class='input'>
            </div>
        </div>
        ";
                                } elseif ($field === 'kuota') {
                                    // Untuk field Kuota, gunakan type="number"
                                    echo "
        <div class='field'>
            <label class='label'>$label</label>
            <div class='control'>
                <input type='number' name='$field' value='" . htmlspecialchars($row_event[$field]) . "' class='input' min='1'>
            </div>
        </div>
        ";
                                } else {
                                    echo "
        <div class='field'>
            <label class='label'>$label</label>
            <div class='control'>
                <input type='text' name='$field' value='" . htmlspecialchars($row_event[$field]) . "' class='input'>
            </div>
        </div>
        ";
                                }
                            }
                            ?>



                            <!-- Input Thumbnail File -->
                            <div class="field">
                                <label class="label">Thumbnail Event</label>
                                <div class="control">
                                    <input type="file" id="thumbnailInput" name="thumbnail_event" class="input"
                                        accept="image/*" onchange="previewThumbnail()">
                                </div>
                                <div class="field mt-4">
                                    <label class="label">Preview Thumbnail</label>
                                    <figure class="image is-128x128">
                                        <img id="thumbnailPreview"
                                            src="<?php echo !empty($row_event['thumbnail_event']) ? '../../img/event/' . htmlspecialchars($row_event['thumbnail_event']) : '../../img/bghero.png'; ?>"
                                            alt="Thumbnail Event">
                                    </figure>
                                </div>
                            </div>


                            <!-- Deskripsi Event -->
                            <div class="field">
                                <label class="label">Deskripsi Event</label>
                                <div class="control">
                                    <textarea name="deskripsi_event"
                                        class="textarea"><?php echo isset($row_event['deskripsi_event']) ? htmlspecialchars($row_event['deskripsi_event']) : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Link Grup -->
                            <div class="field">
                                <label class="label">Link Grup</label>
                                <div class="control">
                                    <input type="text" name="link_grup"
                                        value="<?php echo htmlspecialchars($row_event['link_grup']); ?>" class="input">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Tombol Edit -->
                <div class="field">
                    <div class="control">
                        <button type="submit" class="button blue">Edit</button>
                    </div>
                </div>
            </form>
        </section>

        <?php
        // Tutup statement dan koneksi
        mysqli_stmt_close($stmt_event);
        mysqli_close($koneksi);
        ?>

        <!-- Content Here End -->
        <?php include 'footer.php'; ?>

    </div>

    <script>
        function previewThumbnail() {
            const thumbnailInput = document.getElementById('thumbnailInput');
            const thumbnailPreview = document.getElementById('thumbnailPreview');

            // Cek apakah ada file yang diinputkan
            const file = thumbnailInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    thumbnailPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                // Jika tidak ada file, gunakan gambar dari database
                thumbnailPreview.src = "<?php echo !empty($row_event['thumbnail_event']) ? '../../img/event/' . htmlspecialchars($row_event['thumbnail_event']) : '../../img/bghero.png'; ?>";
            }
        }
    </script>


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