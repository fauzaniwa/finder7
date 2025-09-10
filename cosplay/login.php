<?php
require_once '../admin-one/dist/koneksi.php';
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, name, email, password FROM admin WHERE email = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $admin['name'];
        header('Location: donotenter.php');
        exit;
    } else {
        $error = 'Email atau password salah. Silakan coba lagi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
</head>
<body class="bg-neutral-950 font-work text-white flex items-center justify-center min-h-screen">
    <div class="p-8 md:p-16 w-full max-w-md">
        <h1 class="text-3xl font-bold mb-8 text-center text-emerald-400">Login Admin</h1>
        <?php if ($error): ?>
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-sm">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post" class="flex flex-col gap-5">
            <input type="email" name="email" placeholder="Email" required
                class="rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
            <input type="password" name="password" placeholder="Password" required
                class="rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
            <div class="flex justify-center mt-4">
                <button type="submit"
                    class="bg-emerald-400 hover:bg-emerald-600 text-black px-20 py-2 rounded-xl shadow font-semibold">
                    Login
                </button>
            </div>
        </form>
    </div>
</body>
</html>