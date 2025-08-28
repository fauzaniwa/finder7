<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Add Event</title>
    <link rel="stylesheet" href="style.css" />
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
            <div class="field">
                <div class="control">
                    <a class="button blue" href="datapameran.php">
                        Back
                    </a>
                    <button class="button blue" id="openModalBtn">
                        <i class="mdi mdi-plus"></i> Add New Category
                    </button>
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
                        <form action="process_addkarya.php" method="POST" id="addkarya" enctype="multipart/form-data">
                            <div class="field">
                                <label class="label">Upload Gambar Karya</label>
                                <input type="file" name="file_karya" accept="image/*">
                                <p>Atau, masukkan link gambar karya (jika tidak dapat di-upload)</p>
                                <div class="control">
                                    <input type="text" name="optional_karya" class="input">
                                </div>
                                <div id="preview"></div>
                            </div>
                            <div class="field">
                                <label class="label">Judul Karya</label>
                                <p>Masukkan judul karya</p>
                                <div class="control">
                                    <input type="text" name="judul_karya" class="input">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Nama Creator</label>
                                <p>Input nama creator pada karya ini.</p>
                                <div class="control">
                                    <input type="text" name="nama_karya" class="input">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">NIM / Angkatan</label>
                                <p>Jika nim tidak diketahui, dapat menulis angkatan dengan format : 21/22/23</p>
                                <div class="control">
                                    <input type="text" name="nim" class="input">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Deskripsi</label>
                                <p>Input deskripsi karya.</p>
                                <div class="control">
                                    <textarea name="deskripsi" class="textarea"></textarea>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Jenis Karya</label>
                                <p>Jenis atau kategori karya.</p>
                                <div class="control">
                                    <select name="jenis_karya" class="input">
                                        <option value="">Pilih Jenis Karya</option>
                                        <?php
                                        // Koneksi ke database
                                        require_once "koneksi.php";
                                        $koneksi = mysqli_connect($host, $username, $password, $database);

                                        // Query untuk mengambil jenis karya
                                        $query = "SELECT * FROM jenis_karya";
                                        $result = mysqli_query($koneksi, $query);

                                        // Tampilkan opsi jenis karya
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row['id_jenis'] . '">' . $row['jenis'] . '</option>';
                                        }

                                        // Tutup koneksi
                                        mysqli_close($koneksi);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Instagram</label>
                                <p>Media sosial kreator.</p>
                                <div class="control">
                                    <input type="text" name="instagram" class="input">
                                </div>
                            </div>
                            <hr>
                            <div class="field">
                                <div class="control">
                                    <button type="submit" name="addkarya" class="button green">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Add New Category</h2>
                <form id="categoryForm" action="submit_form.php" method="POST">
                    <div id="inputContainer">
                        <div class="input-group">
                            <label for="name1">Kategori 1:</label>
                            <input type="text" id="name1" name="name[]" required>
                        </div>
                    </div>
                    <button type="button" id="addInputBtn">+</button>
                    <button type="submit">Tambah</button>
                </form>
            </div>
        </div>
        <script>
            // Script untuk menampilkan preview gambar sebelum di-upload
            const fileInput = document.querySelector('input[type="file"]');
            const preview = document.getElementById('preview');

            fileInput.addEventListener('change', function () {
                while (preview.firstChild) {
                    preview.removeChild(preview.firstChild);
                }

                const file = fileInput.files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100%';
                    img.style.height = 'auto';
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            });
        </script>

        <?php include 'footer.php'; ?>

    </div>

    <!-- Scripts below are for demo only -->
    <script type="text/javascript" src="js/main.min.js?v=1628755089081"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <script type="text/javascript" src="js/chart.sample.min.js"></script>
    <script src="system.js"></script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=658339141622648&ev=PageView&noscript=1" /></noscript>

    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>