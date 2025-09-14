<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Cek apakah user sudah login dan memiliki role 'master'
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "DELETE FROM qrcodes WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_id);
        $param_id = $id;
        
        if ($stmt->execute()) {
            // Berhasil dihapus, kembali ke halaman daftar dengan pesan sukses
            header("location: qrcode_list.php?success=2");
            exit;
        } else {
            // Gagal menghapus, kembali ke halaman daftar dengan pesan error
            header("location: qrcode_list.php?error=1");
            exit;
        }
        $stmt->close();
    }
}

$conn->close();

// Jika tidak ada ID, redirect kembali ke halaman daftar
header("location: qrcode_list.php");
exit;
?>