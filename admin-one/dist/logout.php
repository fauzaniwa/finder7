<?php
session_start();

// Hapus semua session
session_destroy();

// Redirect ke halaman login atau halaman utama setelah logout
header("Location: login.php");
exit();
?>
