<?php
// Mulai session
session_start();

// Hapus semua variabel session
$_SESSION = array();

// Jika ingin menghapus session cookie juga, hapus komentar bagian ini
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Alihkan pengguna kembali ke halaman homepage.php
header("Location: homepage.php");
exit;
?>
