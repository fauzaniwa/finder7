<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Add Speakers</title>
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
                    <a class="button blue" href="speakers.php">
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
                        <form action="process_addspeakers.php" method="POST" id="addkarya"
                            enctype="multipart/form-data">
                            <div class="field">
                                <label class="label">Upload Gambar Karya</label>
                                <input type="file" name="foto_speakers" accept="image/*" id="foto_speakers">
                                <div id="preview"></div>
                                <img id="imgPreview" style="max-width: 200px; margin-top: 10px;" />
                            </div>

                            <div class="field">
                                <label class="label">Nama</label>
                                <p>Masukkan nama speakers</p>
                                <div class="control">
                                    <input type="text" name="namaspeakers" class="input" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Instansi</label>
                                <p>Tuliskan informasi singkat tentang speakers. Contoh : Founder DangDang</p>
                                <div class="control">
                                    <input type="text" name="instansi" class="input" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Deskripsi</label>
                                <p>Input deskripsi singkat.</p>
                                <div class="control">
                                    <textarea name="deskripsi" class="textarea"></textarea>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Kontak</label>
                                <p>Kontak / Media sosial speakers.</p>
                                <div class="control">
                                    <input type="text" name="contact" class="input">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Urutan Show</label>
                                <p>Dalam page akan disusun berdasarkan nomor terendah ke terbesar. skala prioritas jika pemateri sebagai orang penting, maka taruh di angka kecil.</p>
                                <div class="control">
                                    <input type="number" name="urutan" class="input">
                                </div>
                            </div>
                            <hr>
                            <div class="field">
                                <div class="control">
                                    <button type="submit" name="addspeakers" class="button green">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'footer.php'; ?>

    </div>
    <script>
        document.getElementById('foto_speakers').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('imgPreview').src = e.target.result; // Set preview image
                }
                reader.readAsDataURL(file); // Convert the file into a data URL
            }
        });
    </script>

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