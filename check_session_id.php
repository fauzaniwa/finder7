<?php
session_start();
echo "ID Pengguna Anda: " . ($_SESSION['id'] ?? 'Tidak ada ID');
echo "<br>";
echo "Status Login: " . ($_SESSION["loggedin"] ? 'Sudah Login' : 'Belum Login');
?>