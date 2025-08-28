<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Add Event</title>

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
                    <a class="button blue" href="dataevent.php">
                        Back
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            <span class="icon"><i class="mdi mdi-account"></i></span>
                            Event
                        </p>
                    </header>
                    <div class="card-content">
                        <form action="process_addevent.php" method="POST" id="addadmin" enctype="multipart/form-data">
                            <div class="field">
                                <label class="label">Judul Event</label>
                                <p>Masukkan judul event</p>
                                <div class="control">
                                    <input type="text" name="judul" class="input is-static" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Speakers</label>
                                <p>Input nama pembicara dalam acara ini.</p>
                                <div class="control">
                                    <input type="text" name="speakers" class="input is-static" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Jadwal Event</label>
                                <p>Input tanggal acara.</p>
                                <div class="control">
                                    <input type="date" name="jadwal" class="input is-static" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Waktu Event</label>
                                <p>Isi dalam format sebagai berikut: <span
                                        class="font-semibold italic text-red-700">10.00 - 11.00 WIB</span></p>
                                <div class="control">
                                    <input type="text" name="waktu" class="input is-static" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Lokasi Event</label>
                                <p>Lokasi acara dilaksanakan.</p>
                                <div class="control">
                                    <input type="text" name="lokasi" class="input is-static" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Link Grup</label>
                                <p>Link Grup</p>
                                <div class="control">
                                    <input type="text" name="grup" class="input is-static">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Kuota Event</label>
                                <p>Masukkan kuota peserta untuk acara ini.</p>
                                <div class="control">
                                    <input type="number" name="kuota" class="input is-static" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Deskripsi Event</label>
                                <p>Masukkan deskripsi singkat tentang acara ini.</p>
                                <div class="control">
                                    <textarea name="deskripsi" class="textarea is-static" required></textarea>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Thumbnail Event</label>
                                <p>Pilih gambar thumbnail untuk acara ini.</p>
                                <div class="control">
                                    <input type="file" name="thumbnail_event" accept="image/*" id="thumbnailInput"
                                        required>
                                </div>
                                <div class="mt-2">
                                    <img id="thumbnailPreview" src="#" alt="Thumbnail Preview"
                                        style="display:none; max-width: 200px; max-height: 200px;" />
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Urutan Show</label>
                                <p>Dalam page akan disusun berdasarkan nomor terendah ke terbesar. skala prioritas jika pemateri sebagai orang penting, maka taruh di angka kecil.</p>
                                <div class="control">
                                    <input type="number" name="urutan_show" class="input">
                                </div>
                            </div>
                            <hr>
                            <div class="field">
                                <div class="control">
                                    <button type="submit" name="addevent" class="button green">Submit</button>
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
        document.getElementById('thumbnailInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('thumbnailPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        });
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