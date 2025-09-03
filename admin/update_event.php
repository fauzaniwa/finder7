<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Cek apakah pengguna sudah login dan memiliki peran yang diizinkan
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["role"], ['master', 'seminar', 'workshop'])) {
    header("location: login.php");
    exit;
}

// Hanya proses jika metode POST dan aksi adalah 'edit_event'
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'edit_event') {
    $id_to_edit = intval(trim($_POST['edit_id']));

    // Ambil data event yang sudah ada dari database
    $sql_fetch_event = "SELECT * FROM event WHERE id_event = ?";
    if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch_event)) {
        mysqli_stmt_bind_param($stmt_fetch, "i", $id_to_edit);
        mysqli_stmt_execute($stmt_fetch);
        $result_fetch = mysqli_stmt_get_result($stmt_fetch);
        $event_data = mysqli_fetch_assoc($result_fetch);
        mysqli_stmt_close($stmt_fetch);

        if (!$event_data) {
            $_SESSION['error_message'] = "Event tidak ditemukan.";
            header("location: event.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat mengambil data event: " . mysqli_error($conn);
        header("location: event.php");
        exit;
    }

    // Ambil data dari POST, jika kosong, gunakan data yang sudah ada
    $judul_event = isset($_POST['judul_event']) ? trim($_POST['judul_event']) : $event_data['judul_event'];
    $kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : $event_data['kategori'];
    $audiens = isset($_POST['audiens']) ? trim($_POST['audiens']) : $event_data['audiens'];
    $jadwal_event = isset($_POST['jadwal_event']) ? trim($_POST['jadwal_event']) : $event_data['jadwal_event'];
    $waktu_event = isset($_POST['waktu_event']) ? trim($_POST['waktu_event']) : $event_data['waktu_event'];
    $lokasi_event = isset($_POST['lokasi_event']) ? trim($_POST['lokasi_event']) : $event_data['lokasi_event'];
    $tiket_event = isset($_POST['tiket_event']) ? intval(trim($_POST['tiket_event'])) : $event_data['tiket_event'];
    $kuota = isset($_POST['kuota']) ? intval(trim($_POST['kuota'])) : $event_data['kuota'];
    $link_grup = isset($_POST['link_grup']) ? trim($_POST['link_grup']) : $event_data['link_grup'];
    $statusbayar = isset($_POST['statusbayar']) ? trim($_POST['statusbayar']) : $event_data['statusbayar'];
    $event_status = isset($_POST['event_status']) ? intval(trim($_POST['event_status'])) : $event_data['event_status'];
    $show_event = isset($_POST['show_event']) ? intval(trim($_POST['show_event'])) : $event_data['show_event'];
    $urutan_show = isset($_POST['urutan_show']) ? intval(trim($_POST['urutan_show'])) : $event_data['urutan_show'];
    $deskripsi_event = isset($_POST['deskripsi_event']) ? trim($_POST['deskripsi_event']) : $event_data['deskripsi_event'];
    $selected_speakers = isset($_POST['speakers']) ? $_POST['speakers'] : [];

    // Validasi izin: master bisa edit semua, seminar hanya seminar, workshop hanya workshop
    if ($_SESSION['role'] !== 'master' && $_SESSION['role'] !== $kategori) {
        $_SESSION['error_message'] = "Anda tidak memiliki izin untuk mengedit event di kategori ini.";
        header("location: event.php");
        exit;
    }

    // Mulai transaksi untuk memastikan konsistensi
    mysqli_begin_transaction($conn);
    $update_success = true;

    // 1. Update data event
    $sql_event = "UPDATE event SET judul_event=?, kategori=?, audiens=?, statusbayar=?, jadwal_event=?, waktu_event=?, lokasi_event=?, tiket_event=?, kuota=?, link_grup=?, event_status=?, show_event=?, urutan_show=?, deskripsi_event=? WHERE id_event=?";
    if ($stmt_event = mysqli_prepare($conn, $sql_event)) {
        // PERBAIKAN: Mengubah string tipe data agar cocok dengan 15 parameter
        // sssssssiisiiisi (15 karakter)
        mysqli_stmt_bind_param($stmt_event, "sssssssiisiiisi", 
            $judul_event, 
            $kategori, 
            $audiens, 
            $statusbayar,
            $jadwal_event, 
            $waktu_event, 
            $lokasi_event, 
            $tiket_event, 
            $kuota, 
            $link_grup, 
            $event_status, 
            $show_event, 
            $urutan_show,
            $deskripsi_event,
            $id_to_edit
        );
        if (!mysqli_stmt_execute($stmt_event)) {
            $_SESSION['error_message'] = "Terjadi kesalahan saat memperbarui event: " . mysqli_stmt_error($stmt_event);
            $update_success = false;
        }
        mysqli_stmt_close($stmt_event);
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat mempersiapkan statement event: " . mysqli_error($conn);
        $update_success = false;
    }

    // 2. Update speakers event
    if ($update_success) {
        // Hapus speakers lama
        $sql_delete_speakers = "DELETE FROM event_speakers WHERE id_event = ?";
        if ($stmt_delete = mysqli_prepare($conn, $sql_delete_speakers)) {
            mysqli_stmt_bind_param($stmt_delete, "i", $id_to_edit);
            if (!mysqli_stmt_execute($stmt_delete)) {
                $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus speakers lama: " . mysqli_stmt_error($stmt_delete);
                $update_success = false;
            }
            mysqli_stmt_close($stmt_delete);
        } else {
            $_SESSION['error_message'] = "Terjadi kesalahan saat mempersiapkan statement delete speakers: " . mysqli_error($conn);
            $update_success = false;
        }
    }

    // 3. Tambahkan speakers baru jika ada yang dipilih
    if ($update_success && !empty($selected_speakers)) {
        $sql_insert_speakers = "INSERT INTO event_speakers (id_event, id_speaker) VALUES (?, ?)";
        if ($stmt_insert = mysqli_prepare($conn, $sql_insert_speakers)) {
            foreach ($selected_speakers as $speaker_id) {
                mysqli_stmt_bind_param($stmt_insert, "ii", $id_to_edit, $speaker_id);
                if (!mysqli_stmt_execute($stmt_insert)) {
                    $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan speaker baru: " . mysqli_stmt_error($stmt_insert);
                    $update_success = false;
                    break;
                }
            }
            mysqli_stmt_close($stmt_insert);
        } else {
            $_SESSION['error_message'] = "Terjadi kesalahan saat mempersiapkan statement insert speakers: " . mysqli_error($conn);
            $update_success = false;
        }
    }

    // Komit atau rollback transaksi
    if ($update_success) {
        mysqli_commit($conn);
        $_SESSION['success_message'] = "Data event berhasil diperbarui!";
        log_admin_activity($conn, $_SESSION['id'], 'update', 'Memperbarui event: ' . $judul_event . ' (ID: ' . $id_to_edit . ')');
    } else {
        mysqli_rollback($conn);
    }

    mysqli_close($conn);
    header("location: event.php");
    exit;
} else {
    // Jika diakses tidak melalui POST, arahkan kembali ke event.php
    header("location: event.php");
    exit;
}
?>