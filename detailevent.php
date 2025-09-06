<?php
session_start();
require_once 'detailevent_logic.php';

// Pastikan koneksi ditutup di akhir skrip
if (isset($koneksi)) {
    mysqli_close($koneksi);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        work: ['Work Sans'],
                    },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        .navbar-scrolled { box-shadow: 2px 2px 30px #0000001a; }
        .navbar { transition: all 0.5s; }
    </style>
    <title>Detail Seminar</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
</head>

<body class="bg-[#0D0D0D]">
    <?php require '_navbar.php'; ?>

    <section class="w-full min-h-screen pt-32 pb-32 bg-[#0D0D0D] font-work">
    <div class="w-[90%] mx-auto flex flex-col lg:flex-row gap-10 lg:items-center bg-[#131313] p-6 rounded-xl">
        <div class="order-first lg:order-last w-full lg:w-1/2 flex justify-center">
            <div class="relative w-full max-w-[650px] aspect-square overflow-hidden">
                <img src="./img/thumbnail/<?php echo htmlspecialchars($row_event['thumbnail_event']); ?>" class="object-cover w-full h-full rounded-2xl">
                </div>
        </div>

        <div class="order-last lg:order-first w-full lg:w-1/2 space-y-5">
            <p class="text-sm text-white/70">Homepage / Jadwal / <span class="text-yellow-400 italic">Detail Acara</span></p>

            <?php if ($row_event['statusbayar'] === 'no'): ?>
                <span class="inline-block border border-green-400 text-green-400 text-sm font-semibold px-4 py-1 rounded-full">FREE ENTRY</span>
            <?php else: ?>
                <div class="flex items-center gap-2 bg-gray-800/50 text-white font-semibold px-4 py-2 rounded-full w-fit">
                    <i class="fa-solid fa-money-bill-1-wave text-[#00E091] mr-1"></i><span>Rp <?php echo number_format($row_event['tiket_event'], 0, ',', '.'); ?>,-</span>
                </div>
            <?php endif; ?>

            <h1 class="text-white text-3xl md:text-4xl font-semibold leading-snug">
                <?php echo htmlspecialchars($row_event['judul_event']); ?>
            </h1>

            <p class="text-white italic text-lg">
                <?php
                $speaker_names = array_map(function ($speaker) {
                    return htmlspecialchars($speaker['nama_speaker']);
                }, $speakers_data);
                echo implode(', ', $speaker_names);
                ?>
            </p>

            <div class="space-y-2 text-white">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-calendar-days w-5 h-5"></i>
                    <span><?php echo htmlspecialchars(date('d F Y', strtotime($row_event['jadwal_event']))); ?> | <?php echo htmlspecialchars($row_event['waktu_event']) ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-location-dot w-5 h-5"></i>
                    <span><?php echo htmlspecialchars($row_event['lokasi_event']); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-users w-5 h-5"></i>
                    <span><?php echo htmlspecialchars($sisa_kuota); ?> / <?php echo htmlspecialchars($total_kuota); ?> Kuota Tersisa</span>
                </div>
            </div>

            <?php if ($row_event['statusbayar'] === 'no'): ?>
                <p class="text-yellow-400 text-sm italic">*untuk pelajar SMA/SMK diwajibkan mengupload KTP/Kartu Pelajar</p>
            <?php endif; ?>

            <div>
                <h2 class="text-white text-2xl md:text-3xl font-semibold text-left mb-4">Deskripsi Acara</h2>
                <p class="text-white text-lg text-justify font-light">
                    <?php echo nl2br(htmlspecialchars($row_event['deskripsi_event'])); ?>
                </p>
            </div>

            <div class="w-full flex py-4">
                <?php
                // Logika pengecekan status tiket yang baru
                $user_has_ticket = false;
                $is_verified_ticket = false;

                // Cek apakah ada tiket untuk event ini yang dimiliki pengguna
                if (isset($events_with_tickets[$row_event['id_event']])) {
                    $user_has_ticket = true;
                    // Dapatkan status verifikasi langsung dari array
                    $is_verified_ticket = ($events_with_tickets[$row_event['id_event']] == 1);
                }

                if ($user_has_ticket) {
                    if ($is_verified_ticket) {
                        echo '<button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">Sudah Terdaftar</button>';
                    } else {
                        echo '<button id="openVerificationPendingBtn" type="button" class="bg-yellow-400 text-black font-semibold px-8 py-3 rounded-full text-lg transition-all">Menunggu Verifikasi</button>';
                    }
                } else {
                    // Logika tombol untuk pengguna yang belum terdaftar
                    if ($row_event['event_status'] == 0 && $sisa_kuota > 0) {
                        if ($user_id) {
                            echo '<button id="openRegistrationModalBtn" type="button" class="bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-full text-lg transition-all">Daftar</button>';
                        } else {
                            echo '<button id="openNotLoginModalBtn" type="button" class="bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-full text-lg transition-all">Daftar</button>';
                        }
                    } else if ($row_event['event_status'] == 2 || $sisa_kuota <= 0) {
                        echo '<button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">Kuota Penuh</button>';
                    } else if ($row_event['event_status'] == 1) {
                        echo '<button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">Telah Berakhir</button>';
                    } else if ($row_event['event_status'] == 4) {
                        echo '<button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">Segera Hadir</button>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>

    <?php require 'detailevent_modals.php'; ?>

    <script src="js/detailevent.js"></script>
    <script src="js/navbar.js"></script>

</body>
</html>