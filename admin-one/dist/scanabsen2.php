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
            background: url('../../img/bghero.png') center/cover no-repeat;
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
        </select>
        <video id="qr-video" playsinline autoplay></video>
        <img class="success-animation" src="https://i.gifer.com/7efs.gif" alt="Success Animation">
        <div id="qr-result">Scan a QR code...</div>

        <div class="form-container">
            <form id="manualInputForm">
                <input type="text" id="manualInput" placeholder="Masukkan QR Code secara manual">
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <audio id="successAudio">
        <source src="audio/data_berhasil_dimasukkan.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        const video = document.getElementById('qr-video');
        const cameraSelection = document.getElementById('cameraSelection');
        let currentStream;
        let codeReader;

        // Fungsi untuk memulai video dari kamera yang dipilih (depan/belakang)
        function startVideo(facingMode) {
            const constraints = {
                video: { facingMode: facingMode }
            };

            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }

            navigator.mediaDevices.getUserMedia(constraints)
                .then(stream => {
                    currentStream = stream;
                    video.srcObject = stream;
                    video.play();

                    // Simpan status izin kamera agar tidak menanyakan izin lagi
                    localStorage.setItem('cameraAccess', 'granted');

                    if (codeReader) {
                        codeReader.reset();
                    }
                    codeReader = new ZXing.BrowserQRCodeReader();
                    codeReader.decodeFromVideoDevice(null, 'qr-video', (result, err) => {
                        if (result) {
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

        // Fungsi untuk mengecek apakah izin kamera sudah diberikan sebelumnya
        function checkCameraPermission(facingMode) {
            const cameraAccess = localStorage.getItem('cameraAccess');
            if (cameraAccess === 'granted') {
                // Jika akses kamera sudah diberikan, langsung mulai video
                startVideo(facingMode);
            } else {
                // Meminta izin akses kamera pertama kali
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(() => {
                        startVideo(facingMode);
                    })
                    .catch((error) => {
                        console.error('User denied camera access:', error);
                        alert('Kamera diperlukan untuk memindai QR Code. Harap berikan izin.');
                    });
            }
        }

        // Fungsi untuk mengirim QR code ke server
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
                    playSuccessAudio();
                    showSuccessAnimation();
                    setTimeout(() => {
                        window.location.reload();
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

        function playSuccessAudio() {
            const successAudio = document.getElementById('successAudio');
            successAudio.play();
        }

        function showSuccessAnimation() {
            const successAnimation = document.querySelector('.success-animation');
            const rect = video.getBoundingClientRect();
            successAnimation.style.top = rect.top + (rect.height / 2) + 'px';
            successAnimation.style.left = rect.left + (rect.width / 2) + 'px';
            successAnimation.style.display = 'block';
        }

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

        cameraSelection.addEventListener('change', function() {
            const facingMode = cameraSelection.value;
            checkCameraPermission(facingMode);  // Cek izin kamera saat mengganti kamera
        });

        // Cek izin kamera saat memulai
        checkCameraPermission('environment');
    </script>

</body>

</html>