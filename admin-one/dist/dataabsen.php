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
      <!-- Tambahkan di atas tabel -->
      <div class="flex items-center mb-4">
        <!-- Filter Event -->
        <form action="" method="GET" class="mr-4">
          <label for="event_filter" class="mr-2">Filter Event:</label>
          <select name="event_filter" id="event_filter" onchange="this.form.submit()">
            <option value="">All Events</option>
            <?php
            require_once "koneksi.php";
            $query_events = "SELECT DISTINCT judul_event FROM event ORDER BY judul_event";
            $result_events = mysqli_query($koneksi, $query_events);
            while ($row_event = mysqli_fetch_assoc($result_events)) {
              $selected = ($_GET['event_filter'] ?? '') == $row_event['judul_event'] ? 'selected' : '';
              echo '<option value="' . $row_event['judul_event'] . '" ' . $selected . '>' . $row_event['judul_event'] . '</option>';
            }
            mysqli_close($koneksi);
            ?>
          </select>
        </form>

        <!-- Filter Tanggal -->
        <form action="" method="GET" class="mr-4">
          <label for="date_filter" class="mr-2">Filter Tanggal:</label>
          <input type="date" name="date_filter" id="date_filter" value="<?php echo $_GET['date_filter'] ?? ''; ?>"
            onchange="this.form.submit()">
        </form>

        <!-- Sort By Event -->
        <a href="?sort=event_asc" class="button ml-auto">Sort by Event Asc</a>
        <a href="?sort=event_desc" class="button ml-2">Sort by Event Desc</a>

        <!-- Sort By Created -->
        <a href="?sort=created_asc" class="button ml-2">Sort by Created Asc</a>
        <a href="?sort=created_desc" class="button ml-2">Sort by Created Desc</a>
      </div>
      <div class="card has-table">
        <div class="card-content">
          <?php
          // Konfigurasi halaman dan jumlah data per halaman
          $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 50;
          $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

          // Koneksi ke database (sesuaikan dengan konfigurasi Anda)
          require_once "koneksi.php";
          $koneksi = mysqli_connect($host, $username, $password, $database);

          // Periksa koneksi
          if (mysqli_connect_errno()) {
            die("Koneksi database gagal: " . mysqli_connect_error());
          }

          // Query dasar untuk mengambil data absen dengan join tabel tiket, event, dan user
          $query = "SELECT absen.id_absen, tiket.tiket_code, event.judul_event, absen.created_absen, user.nama
                FROM absen
                INNER JOIN tiket ON absen.kode_absen = tiket.tiket_code
                INNER JOIN event ON tiket.id_event = event.id_event
                INNER JOIN user ON tiket.id_user = user.id_user";

          // Filter Event
          if (!empty($_GET['event_filter'])) {
            $query .= " WHERE event.judul_event = ?";
          }

          // Filter Tanggal
          if (!empty($_GET['date_filter'])) {
            $date_filter = $_GET['date_filter'];
            if (!empty($_GET['event_filter'])) {
              $query .= " AND DATE(absen.created_absen) = ?";
            } else {
              $query .= " WHERE DATE(absen.created_absen) = ?";
            }
          }

          // Sorting
          $sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_desc';
          switch ($sort) {
            case 'event_asc':
              $query .= " ORDER BY event.judul_event ASC";
              break;
            case 'event_desc':
              $query .= " ORDER BY event.judul_event DESC";
              break;
            case 'created_asc':
              $query .= " ORDER BY absen.created_absen ASC";
              break;
            case 'created_desc':
            default:
              $query .= " ORDER BY absen.created_absen DESC";
              break;
          }

          // Pagination
          $offset = ($current_page - 1) * $per_page;
          $query .= " LIMIT ?, ?";
          $stmt = mysqli_prepare($koneksi, $query);

          // Bind parameters untuk filter
          if (!empty($_GET['event_filter'])) {
            mysqli_stmt_bind_param($stmt, "sii", $_GET['event_filter'], $offset, $per_page);
          } elseif (!empty($_GET['date_filter'])) {
            mysqli_stmt_bind_param($stmt, "sii", $date_filter, $offset, $per_page);
          } else {
            mysqli_stmt_bind_param($stmt, "ii", $offset, $per_page);
          }

          // Eksekusi statement
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          // Jika ada data
          if (mysqli_num_rows($result) > 0) {
            echo '<table class="min-w-full">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="text-left">No.</th>';
            echo '<th class="text-left">Nama</th>';
            echo '<th class="text-left">Kode</th>';
            echo '<th class="text-left">Event</th>';
            echo '<th class="text-left">Created</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $index = $offset + 1;
            while ($row = mysqli_fetch_assoc($result)) {
              echo '<tr>';
              echo '<td>' . $index . '</td>';
              echo '<td>' . $row['nama'] . '</td>'; // Menampilkan nama dari tabel user
              echo '<td>' . $row['tiket_code'] . '</td>'; // Sesuaikan dengan nama kolom yang sesuai
              echo '<td>' . $row['judul_event'] . '</td>'; // Sesuaikan dengan nama kolom yang sesuai
              echo '<td>' . date('M d, Y H:i:s', strtotime($row['created_absen'])) . '</td>'; // Menampilkan tanggal, jam, menit, dan detik
              echo '</tr>';
              $index++;
            }

            echo '</tbody>';
            echo '</table>';

            // Hitung total data untuk pagination
            $count_query = "SELECT COUNT(*) AS total FROM absen";
            $count_result = mysqli_query($koneksi, $count_query);
            $total_data = mysqli_fetch_assoc($count_result)['total'];

            // Tampilkan navigasi halaman jika lebih dari 50 data
            if ($total_data > $per_page) {
              echo '<div class="table-pagination mt-4">';
              echo '<div class="flex items-center justify-between">';
              echo '<div class="buttons">';
              $total_pages = ceil($total_data / $per_page);
              for ($i = 1; $i <= $total_pages; $i++) {
                echo '<a href="?page=' . $i . '&per_page=' . $per_page . '" class="button ' . ($current_page == $i ? 'active' : '') . '">' . $i . '</a>';
              }
              echo '</div>';
              echo '<small class="ml-2">Page ' . $current_page . ' of ' . $total_pages . '</small>';
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
            echo '<p>Nothing\'s here...</p>';
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