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

<body>
    <section id="reset" style="background-image: url(./img/bgregister.png)"
        class="bg-fil bg-cover w-full h-screen flex items-center justify-center">

        <form action="" method="POST">
            <div class="flex flex-col items-center w-fit px-6 py-20 bg-white bg-opacity-10 rounded-xl gap-4">
                <h1 class="text-2xl md:text-3xl text-white font-semibold">Reset Password</h1>
                <hr class="w-full">
                <p class="text-white text-center flex-col w-[350px]">
                    Masukkan 6 digit OTP yang telah dikirim pada alamat email kamu.
                </p>

                <?php if (isset($status_message)) { ?>
                    <p class="text-white text-center flex-col w-[350px]">
                        <span><a href="register.php" class="font-bold text-[#BA1F36]"><?= $status_message; ?></a></span>
                    </p>
                <?php } ?>
                <div class="flex-col gap-2 w-[350px]">
                    <h1 class="text-lg md:text-xl text-white font-normal font-work">OTP</h1>
                    <input type="text" class="w-[350px] h-10 rounded-lg px-2 font-work font-medium" name="otp"
                        placeholder="Masukkan kode OTP" required>
                </div>

                <button type="submit" name="verify"
                    class="text-base w-full lg:text-xl text-white px-6 py-4 bg-[#BA1F36] rounded-lg font-work hover:bg-[#ba1f1f] duration-150 hover:drop-shadow-md">
                    Verifikasi OTP
                </button>

                <!-- Countdown Timer dan Tombol Resend Code -->
                <div class="mt-4 text-white text-center">
                    <span id="countdown">3:00</span><br>
                    <button id="resendBtn" type="button"
                        class="text-base w-full lg:text-xl text-white px-6 py-4 bg-gray-500 rounded-lg font-work cursor-not-allowed"
                        disabled>
                        <a href="javascript:history.back()">
                            Kirim Ulang Kode
                        </a>
                    </button>
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