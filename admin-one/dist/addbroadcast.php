<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Add Broadcast</title>
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
                    <div class="card-content">
                        <form action="process_addbroadcast.php" method="POST" id="addbroadcast"
                            enctype="multipart/form-data">
                            <div class="field">
                                <label class="label">Pilih Penerima Pesan</label>
                                <p>Pilih penerima pesan:</p>
                                <div class="control">
                                    <select name="penerima" class="input" id="penerima" onchange="toggleEmailInput(this)">
                                        <option value="individu">Individu</option>
                                        <option value="semua">Semua Email Terdaftar</option>
                                        <option value="array">Beberapa Email</option>
                                    </select>
                                </div>
                            </div>

                            <div id="individuEmail" style="display: none;">
                                <p>Masukkan Email Penerima:</p>
                                <input type="text" name="penerima_individual" class="input" placeholder="Email penerima"
                                    oninput="updateEmailPreview(this.value)">
                                <div id="individualPreview" style="margin-top: 10px;"></div>
                            </div>

                            <div id="arrayEmails" style="display: none;">
                                <p>Masukkan beberapa Email Penerima (pisahkan dengan koma):</p>
                                <input type="text" name="penerima_array" class="input"
                                    placeholder="Email1, Email2, Email3" oninput="updateArrayPreview(this.value)">
                                <div id="arrayPreview" style="margin-top: 10px;"></div>
                            </div>

                            <div class="field">
                                <label class="label">Upload Gambar</label>
                                <input type="file" name="foto_broadcast" accept="image/*" id="foto_broadcast"
                                    onchange="previewImage(event)">
                                <div id="preview"></div>
                                <img id="imgPreview" style="max-width: 200px; margin-top: 10px; display: none;" />
                            </div>

                            <div class="field">
                                <label class="label">Judul</label>
                                <p>Masukkan Judul Pesan</p>
                                <div class="control">
                                    <input type="text" name="title" class="input" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Pesan</label>
                                <p>Input pesan yang ingin dikirim.</p>
                                <div class="control">
                                    <textarea name="pesan" class="textarea" required></textarea>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Status</label>
                                <p>Setting status apakah langsung dikirim atau disimpan ke dalam draft.</p>
                                <div class="control">
                                    <select name="status" class="input" required>
                                        <option value="Kirim">Kirim</option>
                                        <option value="Draft">Draft</option>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div class="field">
                                <div class="control">
                                    <button type="submit" name="addbroadcast" class="button green">Submit</button>
                                </div>
                            </div>
                        </form>

                        <script>
                            function toggleEmailInput(select) {
                                const individuEmail = document.getElementById("individuEmail");
                                const arrayEmails = document.getElementById("arrayEmails");

                                if (select.value === "individu") {
                                    individuEmail.style.display = "block";
                                    arrayEmails.style.display = "none";
                                } else if (select.value === "array") {
                                    individuEmail.style.display = "none";
                                    arrayEmails.style.display = "block";
                                } else {
                                    individuEmail.style.display = "none";
                                    arrayEmails.style.display = "none";
                                }
                            }

                            function updateEmailPreview(email) {
                                const individualPreview = document.getElementById("individualPreview");
                                individualPreview.innerHTML = email ? `<span style="background-color: #f0f0f0; padding: 5px; display: inline-block; margin-top: 5px;">${email}</span>` : '';
                            }

                            function updateArrayPreview(emails) {
                                const arrayPreview = document.getElementById("arrayPreview");
                                const emailArray = emails.split(',').map(email => email.trim()).filter(email => email);
                                arrayPreview.innerHTML = emailArray.map(email => `<span style="background-color: #f0f0f0; padding: 5px; display: inline-block; margin-top: 5px; margin-right: 5px;">${email}</span>`).join('');
                            }

                            function previewImage(event) {
                                const imgPreview = document.getElementById("imgPreview");
                                const file = event.target.files[0];
                                const reader = new FileReader();

                                reader.onload = function (e) {
                                    imgPreview.src = e.target.result;
                                    imgPreview.style.display = "block";
                                };

                                if (file) {
                                    reader.readAsDataURL(file);
                                }
                            }
                        </script>



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