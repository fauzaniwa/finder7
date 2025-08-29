<?php
// ... Kode PHP di sini tetap sama ...

// Sertakan file konfigurasi database dan file fungsi
require_once 'config.php';
require_once 'functions.php';

// Inisialisasi variabel dan pesan error
$name = $email = $password = $role = "";
$name_err = $email_err = $password_err = $role_err = "";

// Proses data form ketika dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validasi input
    if (empty(trim($_POST["name"]))) {
        $name_err = "Mohon masukkan nama.";
    } else {
        $name = trim($_POST["name"]);
    }
    
    if (empty(trim($_POST["email"]))) {
        $email_err = "Mohon masukkan email.";
    } else {
        $sql = "SELECT id FROM admin WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "Email ini sudah terdaftar.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                error_log("Gagal mengecek email: " . mysqli_error($conn));
                echo "Ada yang salah. Silakan coba lagi nanti.";
            }
        }
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Mohon masukkan password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password harus memiliki minimal 6 karakter.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    if (empty(trim($_POST["role"]))) {
        $role_err = "Mohon pilih kategori admin.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Jika tidak ada error, masukkan data ke database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($role_err)) {
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO admin (name, email, password, role) VALUES (?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_email, $param_password, $param_role);
            
            $param_name = $name;
            $param_email = $email;
            $param_password = $hashed_password;
            $param_role = $role;
            
            if (mysqli_stmt_execute($stmt)) {
                
                $new_admin_id = mysqli_insert_id($conn);
                log_admin_activity($conn, $new_admin_id, 'register', 'Pendaftaran admin baru berhasil: ' . $email);

                echo "<script>alert('Pendaftaran admin berhasil!'); window.location.href = 'login.php';</script>";
                exit();
            } else {
                error_log("Gagal memasukkan data admin: " . mysqli_error($conn));
                echo "Ada yang salah. Silakan coba lagi nanti.";
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
    <title>Pendaftaran Admin</title>
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
        <h2 class="text-3xl font-semibold text-primary-green mb-2">Pendaftaran Admin</h2>
        <p class="text-mid-gray text-sm mb-6">Silakan isi formulir ini untuk membuat akun admin.</p>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-5 text-left">
                <label for="name" class="block text-light-gray font-medium mb-2">Nama</label>
                <input type="text" name="name" id="name" class="w-full p-3 rounded-lg bg-dark-gray text-white border border-transparent focus:border-primary-green focus:outline-none transition-colors" value="<?php echo htmlspecialchars($name); ?>">
                <span class="text-red-error text-xs mt-1 block"><?php echo $name_err; ?></span>
            </div>
            
            <div class="mb-5 text-left">
                <label for="email" class="block text-light-gray font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" class="w-full p-3 rounded-lg bg-dark-gray text-white border border-transparent focus:border-primary-green focus:outline-none transition-colors" value="<?php echo htmlspecialchars($email); ?>">
                <span class="text-red-error text-xs mt-1 block"><?php echo $email_err; ?></span>
            </div>
            
            <div class="mb-5 text-left">
                <label for="password" class="block text-light-gray font-medium mb-2">Password</label>
                <input type="password" name="password" id="password" class="w-full p-3 rounded-lg bg-dark-gray text-white border border-transparent focus:border-primary-green focus:outline-none transition-colors">
                <span class="text-red-error text-xs mt-1 block"><?php echo $password_err; ?></span>
            </div>
            
            <div class="mb-6 text-left">
                <label for="role" class="block text-light-gray font-medium mb-2">Kategori Admin</label>
                <select name="role" id="role" class="w-full p-3 rounded-lg bg-dark-gray text-white border border-transparent focus:border-primary-green focus:outline-none transition-colors">
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option value="master">Master Admin</option>
                    <option value="pameran">Admin Pameran</option>
                    <option value="seminar">Admin Seminar</option>
                    <option value="workshop">Admin Workshop</option>
                    <option value="lomba">Admin Lomba</option>
                </select>
                <span class="text-red-error text-xs mt-1 block"><?php echo $role_err; ?></span>
            </div>
            
            <div class="mb-4">
                <button type="submit" class="w-full py-3 px-4 bg-primary-green text-dark font-semibold rounded-lg hover:bg-opacity-80 transition-colors">Daftar</button>
            </div>
        </form>
    </div>
</body>
</html>