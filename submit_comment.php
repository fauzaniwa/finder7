<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "admin-one/dist/koneksi.php"; // Sesuaikan dengan lokasi file koneksi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_karya = isset($_POST['id_karya']) ? intval($_POST['id_karya']) : 0;
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    $komentar = isset($_POST['komentar']) ? trim($_POST['komentar']) : '';

    if ($id_karya > 0 && $user_id > 0 && !empty($komentar)) {
        // Gunakan transaksi untuk memastikan operasi dilakukan bersama-sama
        mysqli_autocommit($koneksi, false);

        // 1. Insert komentar
        $query_insert = "INSERT INTO komentar (id_karya, user_id, komentar, commented_at) VALUES (?, ?, ?, NOW())";
        $stmt_insert = mysqli_prepare($koneksi, $query_insert);
        if ($stmt_insert === false) {
            mysqli_autocommit($koneksi, true);
            die("Error preparing the statement: " . mysqli_error($koneksi));
        }
        mysqli_stmt_bind_param($stmt_insert, "iis", $id_karya, $user_id, $komentar);
        mysqli_stmt_execute($stmt_insert);

        // 2. Update kolom comments di tabel karya
        $query_update = "UPDATE karya SET comments = comments + 1 WHERE id_karya = ?";
        $stmt_update = mysqli_prepare($koneksi, $query_update);
        if ($stmt_update === false) {
            mysqli_autocommit($koneksi, true);
            die("Error preparing the update statement: " . mysqli_error($koneksi));
        }
        mysqli_stmt_bind_param($stmt_update, "i", $id_karya);
        mysqli_stmt_execute($stmt_update);

        // Commit transaksi
        mysqli_commit($koneksi);

        mysqli_stmt_close($stmt_insert);
        mysqli_stmt_close($stmt_update);

        // Redirect kembali ke halaman detail karya
        header("Location: detailkarya.php?id=$id_karya");
        exit();
    } else {
        // Handle the error appropriately
        echo "<script>
                alert('Harap login terlebih dahulu!.');
                document.location='detailkarya.php?id=$id_karya';
              </script>";
    }
} else {
    // Handle invalid request method
    echo "Invalid request method.";
}
?>
