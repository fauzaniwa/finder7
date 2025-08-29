<?php
// ... Kode PHP di sini tetap sama ...
session_start();
// Sertakan file koneksi database dan fungsi
require_once 'config.php';
require_once 'functions.php';

// Inisialisasi variabel
$email = $password = "";
$email_err = $password_err = "";
$feedback_message = "";
$feedback_status = "";

// Proses data form saat dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["email"]))) {
        $email_err = "Mohon masukkan email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Mohon masukkan password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, name, email, password, role FROM admin WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $name, $email, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;
                            $_SESSION["email"] = $email;
                            $_SESSION["role"] = $role;

                            log_admin_activity($conn, $id, 'login', 'Login berhasil.');

                            $feedback_status = "success";
                            $feedback_message = "Login berhasil! Anda akan diarahkan ke halaman utama.";
                        } else {
                            $feedback_status = "error";
                            $feedback_message = "Password yang Anda masukkan salah.";
                            log_admin_activity($conn, $id, 'login_failed', 'Upaya login gagal (password salah).');
                        }
                    }
                } else {
                    $feedback_status = "error";
                    $feedback_message = "Tidak ada akun yang ditemukan dengan email tersebut.";
                }
            } else {
                $feedback_status = "error";
                $feedback_message = "Oops! Ada yang salah. Silakan coba lagi nanti.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#080808',
                        'dark-card': '#1a1a1a',
                        'primary-green': '#00D294',
                        'light-gray': '#e0e0e0',
                        'mid-gray': '#bbbbbb',
                        'dark-gray': '#2a2a2a',
                        'red-error': '#ff6b6b',
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-dark text-white font-poppins flex items-center justify-center min-h-screen p-4">

    <div class="bg-dark-card p-8 sm:p-10 rounded-xl shadow-lg w-full max-w-md text-center">
        <h2 class="text-3xl font-semibold text-primary-green mb-2">Login Admin</h2>
        <p class="text-mid-gray text-sm mb-6">Silakan login untuk masuk ke halaman admin.</p>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-5 text-left">
                <label for="email" class="block text-light-gray font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" class="w-full p-3 rounded-lg bg-dark-gray text-white border border-transparent focus:border-primary-green focus:outline-none transition-colors" value="<?php echo htmlspecialchars($email); ?>">
                <span class="text-red-error text-xs mt-1 block"><?php echo $email_err; ?></span>
            </div>
            
            <div class="mb-6 text-left">
                <label for="password" class="block text-light-gray font-medium mb-2">Password</label>
                <input type="password" name="password" id="password" class="w-full p-3 rounded-lg bg-dark-gray text-white border border-transparent focus:border-primary-green focus:outline-none transition-colors">
                <span class="text-red-error text-xs mt-1 block"><?php echo $password_err; ?></span>
            </div>
            
            <div class="mb-4">
                <button type="submit" class="w-full py-3 px-4 bg-primary-green text-dark font-semibold rounded-lg hover:bg-opacity-80 transition-colors">Login</button>
            </div>
        </form>
    </div>

    <div id="feedbackModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="bg-dark-card p-8 rounded-lg w-full max-w-sm text-center border-t-4" id="modalContent">
            <h3 class="text-xl font-semibold mb-2" id="modalTitle"></h3>
            <p class="text-mid-gray text-sm mb-4" id="modalMessage"></p>
            <button class="py-2 px-4 rounded-lg font-medium text-dark bg-primary-green hover:bg-opacity-80 transition-colors" onclick="closeModal()">Tutup</button>
        </div>
    </div>

    <script>
        const modal = document.getElementById('feedbackModal');
        const modalContent = document.getElementById('modalContent');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');

        function showModal(status, message) {
            modalTitle.innerText = status === 'success' ? 'Berhasil!' : 'Gagal!';
            modalMessage.innerText = message;
            modalContent.classList.remove('border-primary-green', 'border-red-error');
            modalContent.classList.add(status === 'success' ? 'border-primary-green' : 'border-red-error');
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        <?php if (!empty($feedback_message)): ?>
            const status = "<?php echo $feedback_status; ?>";
            const message = "<?php echo htmlspecialchars($feedback_message); ?>";
            
            showModal(status, message);

            if (status === 'success') {
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 2000);
            }
        <?php endif; ?>
    </script>
</body>
</html>