<?php
// Koneksi ke database
include 'koneksi.php';

// Tentukan jumlah data per halaman
$per_page = isset($_GET['per_page']) && ($_GET['per_page'] == 50 || $_GET['per_page'] == 100) ? $_GET['per_page'] : 50;

// Hitung jumlah total data
$query_count = "SELECT COUNT(*) AS total FROM user";
$result_count = mysqli_query($koneksi, $query_count);
$total_data = mysqli_fetch_assoc($result_count)['total'];

// Tentukan halaman saat ini
$current_page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
$offset = ($current_page - 1) * $per_page;

// Query untuk mengambil data pengguna dengan limit dan offset
$query_users = "SELECT * FROM user LIMIT $per_page OFFSET $offset";
$result_users = mysqli_query($koneksi, $query_users);

// Array untuk menyimpan data pengguna
$users_data = [];
if ($result_users && mysqli_num_rows($result_users) > 0) {
  while ($row = mysqli_fetch_assoc($result_users)) {
    $users_data[] = $row;
  }
}

// Tutup koneksi
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
      <!-- Jika Tersedia Data Maka Masukkan ke Tabel -->
      <div class="card has-table">
        <div class="card-content">
          <?php if (!empty($users_data)): ?>
            <table>
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama</th>
                  <th>No. Hp</th>
                  <th>Tgl. Lahir</th>
                  <th>Institusi</th>
                  <th>Email</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users_data as $index => $user): ?>
                  <tr>
                    <td data-label="No"><?php echo $index + 1; ?></td>
                    <td data-label="Name"><?php echo $user['nama']; ?></td>
                    <td data-label="NoHp"><?php echo $user['no_hp']; ?></td>
                    <td data-label="Tgl Lahir"><?php echo date('d F', strtotime($user['tgl_lahir'])); ?></td>
                    <td data-label="Institusi"><?php echo $user['instansi']; ?></td>
                    <td data-label="Email"><?php echo $user['email']; ?></td>
                    <td data-label="Created">
                      <small class="text-gray-500"
                        title="<?php echo date('M d, Y', strtotime($user['created'])); ?>"><?php echo date('M d, Y', strtotime($user['created'])); ?></small>
                    </td>
                    <td class="actions-cell">
                      <div class="buttons right nowrap">
                        <a class="button small green --jb-modal" href="profileuser.php?id=<?php echo $user['id_user']; ?>">
                          <span class="icon"><i class="mdi mdi-eye"></i></span>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <!-- Pagination -->
            <?php if ($total_data > $per_page): ?>
              <div class="table-pagination">
                <div class="flex items-center justify-between">
                  <div class="buttons">
                    <?php
                    $total_pages = ceil($total_data / $per_page);
                    for ($i = 1; $i <= $total_pages; $i++):
                      ?>
                      <a href="?page=<?php echo $i; ?>&per_page=<?php echo $per_page; ?>"
                        class="button <?php echo $current_page == $i ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                  </div>
                  <small>Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></small>
                </div>
              </div>
            <?php endif; ?>

          <?php else: ?>
            <!-- Jika Tidak Ada Data dalam Tabel -->
            <div class="card empty">
              <div class="card-content">
                <div>
                  <span class="icon large"><i class="mdi mdi-emoticon-sad mdi-48px"></i></span>
                </div>
                <p>Nothing's here…</p>
              </div>
            </div>
          <?php endif; ?>
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