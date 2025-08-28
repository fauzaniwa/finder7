<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: url('https://finderdkvupi.com/img/bghero.png') center/cover no-repeat;
            font-family: Arial, sans-serif;
        }

        #qr-scanner {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: whitesmoke;
            border-radius: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        #qr-video {
            width: 100%;
            max-width: 600px;
            border-radius: 40px;
            font-style: italic;
            color: #0D0D0D;
        }

        #qr-result {
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
            color: white;
        }

        .form-container {
            margin-top: 20px;
            color: #0D0D0D;
            text-align: center;
        }

        .form-container input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            max-width: 100%;
            margin-right: 10px;
            border-radius: 20px;
            border: none;
            outline: none;
        }

        .form-container button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #0D0D0D;
            color: white;
            border: none;
            border-radius: 20px;
            outline: none;
        }

        .success-animation {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <div id="qr-scanner">
        <h1 style="text-[#0D0D0D];">Scan QR Code kamu!</h1>
        <select id="cameraSelection">
            <option value="environment">Kamera Belakang</option>
            <option value="user">Kamera Depan</option>
        </select> <!-- Dropdown untuk memilih kamera depan atau belakang -->
        <video id="qr-video" playsinline autoplay></video>
        <!-- Animasi sukses -->
        <img class="success-animation" src="https://i.gifer.com/7efs.gif" alt="Success Animation">
        <div id="qr-result">Scan a QR code...</div>

        <!-- Form jika data tidak dapat di-scan -->
        <div class="form-container">
            <form id="manualInputForm">
                <input type="text" id="manualInput" placeholder="Masukkan QR Code secara manual">
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <audio id="successAudio">
        <source src="https://finderdkvupi.com/admin-one/dist/audio/data_berhasil_dimasukkan.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        const video = document.getElementById('qr-video');
        const cameraSelection = document.getElementById('cameraSelection');
        let currentStream;  // Variabel untuk menyimpan stream aktif
        let codeReader;

        // Fungsi untuk memulai video dari kamera yang dipilih (depan/belakang)
        function startVideo(facingMode) {
            const constraints = {
                video: { facingMode: facingMode } // Menggunakan facingMode untuk menentukan kamera
            };

            // Hentikan stream aktif sebelum memulai stream baru
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }

            navigator.mediaDevices.getUserMedia(constraints)
                .then(stream => {
                    currentStream = stream;  // Simpan stream baru
                    video.srcObject = stream;
                    video.play();

                    // Inisialisasi ulang ZXing untuk membaca QR Code dari stream video
                    if (codeReader) {
                        codeReader.reset();
                    }
                    codeReader = new ZXing.BrowserQRCodeReader();
                    codeReader.decodeFromVideoDevice(null, 'qr-video', (result, err) => {
                        if (result) {
                            // Kirim nilai QR code ke server
                            sendQRCodeToServer(result.text);
                        }
                        if (err && !(err instanceof ZXing.NotFoundException)) {
                            console.error('QR code decoding error:', err);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error accessing camera:', error);
                    alert('Error accessing camera. Please allow camera access and try again.');
                });
        }

        // Fungsi untuk mengirim nilai QR code ke server
        function sendQRCodeToServer(qrCodeValue) {
            fetch('process_absen.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ qrCode: qrCodeValue })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' || data.status === 'exists') {
                    playSuccessAudio(); // Memainkan audio jika berhasil atau sudah ada
                    showSuccessAnimation(); // Tampilkan animasi sukses
                    setTimeout(() => {
                        window.location.reload(); // Reload halaman setelah 1 detik
                    }, 1000);
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error sending QR code to server:', error);
                alert('Error sending QR code to server');
            });
        }

        // Memainkan audio sukses
        function playSuccessAudio() {
            const successAudio = document.getElementById('successAudio');
            successAudio.play();
        }

        // Menampilkan animasi sukses di tengah video
        function showSuccessAnimation() {
            const successAnimation = document.querySelector('.success-animation');
            const rect = video.getBoundingClientRect();
            successAnimation.style.top = rect.top + (rect.height / 2) + 'px';
            successAnimation.style.left = rect.left + (rect.width / 2) + 'px';
            successAnimation.style.display = 'block';
        }

        // Tangani submit form manual input
        const manualInputForm = document.getElementById('manualInputForm');
        manualInputForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const qrCodeValue = document.getElementById('manualInput').value.trim();
            if (qrCodeValue) {
                sendQRCodeToServer(qrCodeValue);
            } else {
                alert('Mohon masukkan QR Code terlebih dahulu.');
            }
        });

        // Event listener untuk menangani perubahan pilihan kamera
        cameraSelection.addEventListener('change', function() {
            const facingMode = cameraSelection.value;
            startVideo(facingMode);  // Mulai video dengan kamera depan atau belakang yang dipilih
        });

        // Memulai dengan kamera belakang (default)
        startVideo('environment');
    </script>

</body>



</html>