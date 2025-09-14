<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Absen Pameran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        body {
            overflow: hidden;
            font-family: Arial, sans-serif;
        }
        #qr-video {
            width: 100%;
            max-width: 600px;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
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
<body class="bg-gray-900 text-white flex flex-col items-center justify-center h-screen p-4">
    
    <div class="text-center mb-6">
        <h1 class="text-4xl font-bold mb-2 text-green-400">Scan Absen Pameran</h1>
        <p class="text-gray-400">Arahkan kamera ke QR Code peserta atau masukkan kode secara manual.</p>
    </div>

    <div class="relative w-full max-w-xl mx-auto flex flex-col items-center bg-gray-800 p-6 rounded-2xl shadow-lg">
        <video id="qr-video" playsinline autoplay></video>
        <img class="success-animation" src="https://i.gifer.com/7efs.gif" alt="Success Animation">
        
        <div class="mt-4 w-full">
            <label for="cameraSelection" class="text-gray-400 block mb-2">Pilih Kamera:</label>
            <select id="cameraSelection" class="w-full p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                </select>
        </div>

        <div class="mt-8 w-full px-4">
            <form id="manualInputForm">
                <input type="text" id="manualInput" placeholder="Atau masukkan kode di sini..." class="w-full p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit" class="mt-4 w-full p-3 rounded-lg bg-green-600 hover:bg-green-700 transition-colors font-bold">Submit Kode</button>
            </form>
        </div>
    </div>

    <button onclick="window.location.href='index.php'" class="mt-8 text-sm text-gray-400 hover:text-gray-200 transition-colors flex items-center">
        <span class="material-symbols-outlined mr-1 text-base">arrow_back</span> Kembali ke Dashboard
    </button>
    
    <audio id="successAudio" src="https://www.finderdkvupi.id/admin/audio/data_berhasil_dimasukkan.mp3" preload="auto"></audio>

    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        const video = document.getElementById('qr-video');
        const cameraSelection = document.getElementById('cameraSelection');
        const manualInputForm = document.getElementById('manualInputForm');
        const manualInput = document.getElementById('manualInput');
        const successAudio = document.getElementById('successAudio');
        const successAnimation = document.querySelector('.success-animation');
        let currentStream;
        let codeReader;

        // Fungsi untuk memulai video dari kamera yang dipilih
        async function startVideo(deviceId) {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }
            if (codeReader) {
                codeReader.reset();
            }

            try {
                const constraints = { video: { deviceId: { exact: deviceId } } };
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                currentStream = stream;
                video.srcObject = stream;
                video.play();
                
                codeReader = new ZXing.BrowserQRCodeReader();
                codeReader.decodeFromVideoDevice(deviceId, 'qr-video', (result, err) => {
                    if (result) {
                        sendQRCodeToServer(result.text);
                        codeReader.reset(); // Hentikan scanning setelah berhasil
                    }
                    if (err && !(err instanceof ZXing.NotFoundException)) {
                        console.error('QR code decoding error:', err);
                    }
                });

            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Error accessing camera. Please allow camera access and try again.');
            }
        }

        // Fungsi untuk mengisi dropdown kamera
        async function populateCameraSelection() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                cameraSelection.innerHTML = '';
                if (videoDevices.length > 0) {
                    videoDevices.forEach(device => {
                        const option = document.createElement('option');
                        option.value = device.deviceId;
                        option.textContent = device.label || `Kamera ${cameraSelection.length + 1}`;
                        cameraSelection.appendChild(option);
                    });
                    // Mulai dengan kamera belakang (jika ada) atau kamera pertama
                    const rearCamera = videoDevices.find(device => device.label.toLowerCase().includes('back') || device.label.toLowerCase().includes('environment'));
                    const defaultDeviceId = rearCamera ? rearCamera.deviceId : videoDevices[0].deviceId;
                    startVideo(defaultDeviceId);
                } else {
                    cameraSelection.innerHTML = '<option>Tidak ada kamera ditemukan</option>';
                    alert('Tidak ada kamera yang ditemukan.');
                }
            } catch (err) {
                console.error('Error enumerating devices:', err);
                alert('Gagal mendapatkan daftar kamera.');
            }
        }

        // Fungsi untuk mengirim nilai QR code ke server
        async function sendQRCodeToServer(qrCodeValue) {
            try {
                const response = await fetch('process_absenpameran.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ qrCode: qrCodeValue })
                });
                const data = await response.json();
                
                if (data.status === 'success' || data.status === 'exists') {
                    playSuccessAudio();
                    showSuccessAnimation();
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    console.error('Error:', data.message);
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error sending QR code to server:', error);
                alert('Terjadi kesalahan pada server.');
            }
        }

        // Fungsi untuk memainkan audio sukses
        function playSuccessAudio() {
            if (successAudio) {
                successAudio.play().catch(e => console.error("Error playing audio:", e));
            }
        }

        // Fungsi untuk menampilkan animasi sukses
        function showSuccessAnimation() {
            successAnimation.style.display = 'block';
            setTimeout(() => {
                successAnimation.style.display = 'none';
            }, 1500); // Durasi animasi
        }

        // Event listener untuk menangani perubahan pilihan kamera
        cameraSelection.addEventListener('change', (event) => {
            const selectedDeviceId = event.target.value;
            startVideo(selectedDeviceId);
        });

        // Tangani submit form manual input
        manualInputForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const qrCodeValue = manualInput.value.trim();
            if (qrCodeValue) {
                sendQRCodeToServer(qrCodeValue);
                manualInput.value = '';
            } else {
                alert('Mohon masukkan QR Code terlebih dahulu.');
            }
        });
        
        // Mulai proses
        populateCameraSelection();
    </script>
</body>
</html>