<?php
session_start();

// Memasukkan file koneksi
include 'admin-one/dist/koneksi.php';

// Ambil email dari session
$email = $_SESSION['email'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify'])) {
    // Ambil OTP yang diinput pengguna dan bersihkan input
    $user_otp = bersihkanInput($_POST['otp']);

    if ($email && $user_otp) {
        // Periksa OTP di database
        $check_otp_query = "SELECT * FROM resetpassword WHERE email = ? AND otp_code = ? AND expires_at > NOW() AND is_verified = 0";
        $stmt = $koneksi->prepare($check_otp_query);
        $stmt->bind_param('ss', $email, $user_otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika OTP cocok dan masih valid, tandai OTP sebagai terverifikasi
            $update_otp_query = "UPDATE resetpassword SET is_verified = 1 WHERE email = ? AND otp_code = ?";
            $stmt = $koneksi->prepare($update_otp_query);
            $stmt->bind_param('ss', $email, $user_otp);
            $stmt->execute();

            // Redirect ke halaman reset password baru
            header("Location: new_password.php");
            exit();
        } else {
            // OTP tidak valid atau kedaluwarsa
            $status_message = "Kode OTP salah atau telah kedaluwarsa.";
        }
    } else {
        $status_message = "Harap masukkan kode OTP.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<html lang="en" class="scroll-smooth">

</html>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- CDN Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
    <!--  Font -->

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        work: ['Work Sans'],
                    },
                    animation: {
                        'spin-slow': 'spin 4s linear infinite',
                        'loop-scroll': 'loop-scroll 10s linear infinite',
                    },
                    keyframes: {
                        'loop-scroll': {
                            from: { transform: 'translateX(0)' },
                            to: { transform: 'translateX(-100%)' },
                        },
                    },
                },
            },
        };
    </script>

    <!-- ----------- -->

    <style type="text/tailwindcss">
        * {
        /* border: 1px solid red; */
      }
      .navbar-scrolled {
        box-shadow: 2px 2px 30px #000000;
      }
      .ext-scrolled {
        color: black;
      }
      .navbar {
        transition: all 0.5s;
      }
    </style>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .otp-input {
            width: 100%;
            height: 48px;
            text-align: center;
            font-size: 1.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
            background-color: #e5e5e5  ;
            letter-spacing: 0.5rem; /* Menambahkan jarak antar karakter */
        }
        .otp-input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
    </style>

    <!-- Title Web & Icon -->
    <title>Reset Password</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <!-- Script Navbar Menu -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Script Cursor -->
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <!-- Script Cursor -->
</head>

<body class="bg-neutral-950 ">
    <section id="reset" class="w-10/12 mx-auto h-screen flex items-center justify-center">

        <form action="otpform" method="POST" class="space-y-6 w-full md:w-1/2 bg-white rounded-3xl px-10">
            <div class="flex flex-col items-center py-10 rounded-xl gap-4 ">
                <h1 class="text-2xl md:text-3xl text-black font-semibold">Reset Password</h1>
                <hr class="w-full">
                <p class="text-black text-center flex-col text-base">
                    Masukkan 6 digit OTP yang telah dikirim pada alamat email kamu.
                </p>

                <?php if (isset($status_message)) { ?>
                    <p class="text-black text-center flex-col text-base">
                        <span><a href="register.php" class="font-bold text-black"><?= $status_message; ?></a></span>
                    </p>
                <?php } ?>


                <div class="flex justify-center">
                    <input type="text" class="otp-input" id="otp-input" maxlength="6" autocomplete="one-time-code"
                        inputmode="numeric">
                </div>

                <div class="mt-4 w-1/2">
                    <button type="submit"
                        class="w-full py-3 px-4 bg-emerald-500 text-black font-semibold rounded-xl hover:bg-emerald-600 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-gray-800">
                        Verifikasi
                    </button>
                </div>



                <!-- Countdown Timer dan Tombol Resend Code -->
                <div class=" text-black text-center w-1/2">
                    <span id="countdown">3:00</span><br>
                    <a href="javascript:history.back()">
                        <button id="resendBtn" type="button"
                            class="text-base w-full font-semibold text-black px-10 py-3 bg-neutral-300 rounded-2xl font-work cursor-not-allowed"
                            disabled>
                            Kirim Ulang Kode
                        </button>
                    </a>
                </div>

            </div>
        </form>
    </section>

    <script>
        // Waktu countdown (3 menit)
        let countdownTime = 180;
        const countdownElement = document.getElementById('countdown');
        const resendButton = document.getElementById('resendBtn');

        // Fungsi untuk memulai countdown
        const countdownInterval = setInterval(function () {
            // Hitung menit dan detik
            const minutes = Math.floor(countdownTime / 60);
            const seconds = countdownTime % 60;

            // Tampilkan waktu dalam format mm:ss
            countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            // Jika countdown selesai, aktifkan tombol
            if (countdownTime === 0) {
                clearInterval(countdownInterval);
                resendButton.disabled = false;
                resendButton.classList.remove('bg-gray-500', 'cursor-not-allowed');
                resendButton.classList.add('bg-[#BA1F36]', 'hover:bg-[#ba1f1f]');
            }

            // Kurangi waktu
            countdownTime--;
        }, 1000);
    </script>

    <script>
        const otpInput = document.getElementById('otp-input');

        // Pastikan hanya angka yang bisa dimasukkan
        otpInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // Contoh bagaimana Anda bisa mengambil nilai OTP saat form disubmit
        document.getElementById('otpForm').addEventListener('submit', (e) => {
            e.preventDefault(); // Mencegah form disubmit secara default
            
            const otpCode = otpInput.value;

            if (otpCode.length === 6) {
                // Kode OTP lengkap, Anda bisa mengirimnya ke server
                console.log('Kode OTP:', otpCode);
                alert('Kode OTP berhasil diverifikasi!');
                // Tambahkan kode untuk mengirim data ke server
            } else {
                alert('Kode OTP tidak lengkap. Silahkan cek kembali.');
            }
        });
    </script>

</body>

<!-- Cursor CDN -->
<script src="https://unpkg.com/kursor"></script>
<script>
    new kursor({
        type: 4,
        removeDefaultCursor: true,
        color: '#ffffff',
    });
</script>
<!-- Cursor CDN -->

</html>