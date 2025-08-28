<!DOCTYPE html>
<html lang="en" class="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Data Pameran</title>

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
          <a class="button blue" href="addkarya.php">
            <i class="mdi mdi-plus"></i> Add New
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

          // Query untuk mengambil data karya dengan pagination
          $offset = ($current_page - 1) * $per_page;
          $query = "SELECT 
            k.id_karya, 
            k.judul_karya, 
            k.nama_karya, 
            k.deskripsi, 
            j.jenis, 
            k.created_at, 
            COUNT(DISTINCT l.id_like) AS likes, 
            COUNT(DISTINCT c.id_komentar) AS comments 
          FROM karya k 
          LEFT JOIN jenis_karya j ON k.id_jenis = j.id_jenis 
          LEFT JOIN likes l ON k.id_karya = l.id_karya 
          LEFT JOIN komentar c ON k.id_karya = c.id_karya 
          GROUP BY k.id_karya 
          ORDER BY k.created_at DESC 
          LIMIT ?, ?";
          $stmt = mysqli_prepare($koneksi, $query);

          // Periksa apakah statement berhasil diprepare
          if ($stmt === false) {
            die('Query prepare error: ' . htmlspecialchars(mysqli_error($koneksi)));
          }

          mysqli_stmt_bind_param($stmt, "ii", $offset, $per_page);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          // Jika ada data
          if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>No.</th>';
            echo '<th>Judul Karya</th>';
            echo '<th>Nama Karya</th>';
            echo '<th>Deskripsi</th>';
            echo '<th>Jenis Karya</th>';
            echo '<th>Created</th>';
            echo '<th>Likes</th>';
            echo '<th>Comments</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $index = $offset + 1;
            while ($row = mysqli_fetch_assoc($result)) {
              echo '<tr>';
              echo '<td>' . $index . '</td>';
              echo '<td><a class="underline" href="../../detailkarya.php?id=' . htmlspecialchars($row['id_karya']) . '">' . htmlspecialchars($row['judul_karya']) . '</a></td>';
              echo '<td>' . htmlspecialchars($row['nama_karya']) . '</td>';

              // Memotong deskripsi maksimal 50 karakter
              $deskripsi = htmlspecialchars($row['deskripsi']);
              $deskripsi_short = strlen($deskripsi) > 50 ? substr($deskripsi, 0, 50) . '...' : $deskripsi;

              echo '<td>';
              echo '<span class="short-description">' . $deskripsi_short . '</span>';

              // Tampilkan tombol "(see more)" untuk deskripsi lengkap
              if (strlen($deskripsi) > 50) {
                echo '<span class="full-description" style="display: none;">' . $deskripsi . '</span>';
                echo '<br><button class="see-more-button text-red-500 font-semibold" onclick="showFullDescription(this)">See more</button>';
              }

              echo '</td>';

              echo '<td>' . htmlspecialchars($row['jenis']) . '</td>';
              echo '<td>' . date('M d, Y', strtotime($row['created_at'])) . '</td>';
              echo '<td>' . htmlspecialchars($row['likes']) . '</td>';
              echo '<td>' . htmlspecialchars($row['comments']) . '</td>';
              echo '<td class="actions-cell">';
              echo '<div class="buttons right nowrap">';
              echo '<a class="button small red --jb-modal" onclick="confirmDelete(' . $row['id_karya'] . ')">';
              echo '<span class="icon"><i class="mdi mdi-trash-can"></i></span>';
              echo '</a>';
              echo '<a class="button small blue --jb-modal" href="../../detailkarya.php?id=' . htmlspecialchars($row['id_karya']) . '">';
              echo '<span class="icon"><i class="mdi mdi-eye"></i></span>';
              echo '</a>';
              echo '</div>';
              echo '</td>';
              echo '</tr>';
              $index++;
            }

            echo '</tbody>';
            echo '</table>';

            // Hitung total data
            $count_query = "SELECT COUNT(*) AS total FROM karya";
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

          <script>
            function showFullDescription(button) {
              let tr = button.closest('tr');
              let shortDescSpan = tr.querySelector('.short-description');
              let fullDescSpan = tr.querySelector('.full-description');
              if (shortDescSpan && fullDescSpan) {
                shortDescSpan.style.display = 'none';
                fullDescSpan.style.display = 'inline';
                button.innerText = 'See less';
                button.classList.remove('text-red-500');
                button.classList.remove('font-semibold');
                button.classList.add('text-blue-500');
                button.classList.add('font-normal');
                button.onclick = function () { showLessDescription(this); };
              }
            }

            function showLessDescription(button) {
              let tr = button.closest('tr');
              let shortDescSpan = tr.querySelector('.short-description');
              let fullDescSpan = tr.querySelector('.full-description');
              if (shortDescSpan && fullDescSpan) {
                shortDescSpan.style.display = 'inline';
                fullDescSpan.style.display = 'none';
                button.innerText = 'See more';
                button.classList.remove('text-blue-500');
                button.classList.remove('font-normal');
                button.classList.add('text-red-500');
                button.classList.add('font-semibold');
                button.onclick = function () { showFullDescription(this); };
              }
            }
          </script>


        </div>
      </div>
    </section>

    <script>
      function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this item?')) {
          window.location.href = 'deletekarya.php?id=' + id;
        }
      }
    </script>




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