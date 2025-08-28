<?php
session_start();

// Memasukkan file koneksi
include 'admin-one/dist/koneksi.php';

// Ambil email dari session
$email = $_SESSION['email'] ?? null;

if (!$email) {
    // Jika tidak ada email di session, redirect ke halaman reset password
    header("Location: reset_password.php");
    exit();
}

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    // Ambil kata sandi baru dan konfirmasi kata sandi dari form
    $new_password = bersihkanInput($_POST['new_password']);
    $confirm_password = bersihkanInput($_POST['confirm_password']);

    // Periksa apakah kedua kata sandi cocok
    if ($new_password === $confirm_password) {
        // Hash kata sandi baru untuk keamanan
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update password pengguna di tabel `user`
        $update_password_query = "UPDATE `user` SET `password` = ? WHERE `email` = ?";
        $stmt = $koneksi->prepare($update_password_query);
        $stmt->bind_param('ss', $hashed_password, $email);

        if ($stmt->execute()) {
            // Jika berhasil mengganti password, hapus session dan redirect ke halaman login
            session_unset();
            session_destroy();
            header("Location: login.php?status=password_changed");
            exit();
        } else {
            // Jika gagal mengganti password
            $status_message = "Gagal mengubah kata sandi. Silakan coba lagi.";
        }
    } else {
        // Jika konfirmasi password tidak cocok
        $status_message = "Kata sandi baru dan konfirmasi kata sandi tidak cocok.";
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
                    Masukkan password baru kamu.
                </p>
                <div class="flex-col gap-2 w-[350px]">
                    <h1 class="text-lg md:text-xl text-white font-normal font-work">Password</h1>
                    <input type="password" name="new_password" id="new_password"
                        class="w-[350px] h-10 rounded-lg px-2 font-work font-medium"
                        placeholder="Masukkan kata sandi baru" required minlength="8">
                </div>

                <div class="flex-col gap-2 w-[350px]">
                    <h1 class="text-lg md:text-xl text-white font-normal font-work">Konfirmasi Password</h1>
                    <input type="password" name="confirm_password" id="confirm_password"
                        class="w-[350px] h-10 rounded-lg px-2 font-work font-medium"
                        placeholder="Ulangi kata sandi baru" required minlength="8">
                </div>

                <button type="submit" name="change_password"
                    class="text-base w-full lg:text-xl text-white px-6 py-4 bg-[#BA1F36] rounded-lg font-work hover:bg-[#ba1f1f] duration-150 hover:drop-shadow-md">
                    Simpan
                </button>

                <!-- Tempat untuk notifikasi -->
                <?php if (isset($status_message)) { ?>
                    <div class="mt-4 text-white text-center">
                        <?= $status_message; ?>
                    </div>
                <?php } ?>
            </div>
        </form>
    </section>

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