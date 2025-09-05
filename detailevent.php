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
                    <img src="./img/event/<?php echo htmlspecialchars($row_event['thumbnail_event']); ?>" class="object-cover w-full h-full rounded-2xl">
                    <img src="./img/dekorasi/atas.png" class="absolute -top-4 -left-4 z-10 opacity-35" alt="Dekorasi Kiri">
                    <img src="./img/dekorasi/bawah.png" class="absolute -bottom-4 -right-4 z-10 opacity-35" alt="Dekorasi Kanan">
                </div>
            </div>

            <div class="order-last lg:order-first w-full lg:w-1/2 space-y-5">
                <p class="text-sm text-white/70">Homepage / Jadwal / <span class="text-yellow-400 italic">Detail Acara</span></p>

                <?php if ($row_event['statusbayar'] === 'no'): ?>
                    <span class="inline-block border border-green-400 text-green-400 text-sm font-semibold px-4 py-1 rounded-full">FREE ENTRY</span>
                <?php else: ?>
                    <div class="flex items-center gap-2 bg-gray-800/50 text-white font-semibold px-4 py-2 rounded-full w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8.433 7.418c.158-.103.346-.196.567-.267v1.698a2.5 2.5 0 00-1.134 0v-1.43zM9.5 0a9.5 9.5 0 100 19 9.5 9.5 0 000-19zM.5 9.5a9 9 0 1118 0 9 9 0 01-18 0z" />
                            <path d="M9 7.083A2.502 2.502 0 007.5 9.5v.001c0 .895.46 1.708 1.134 2.166a2.5 2.5 0 002.732 0C12.04 11.21 12.5 10.396 12.5 9.501V9.5A2.5 2.5 0 009 7.083z" />
                        </svg>
                        <span>Rp <?php echo number_format($row_event['tiket_event'], 0, ',', '.'); ?>,-</span>
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
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-11 8h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                        </svg>
                        <span><?php echo htmlspecialchars(date('d F Y', strtotime($row_event['jadwal_event']))); ?> | <?php echo htmlspecialchars($row_event['waktu_event']) ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 12.414a4 4 0 1 0-1.414 1.414l4.243 4.243a1 1 0 0 0 1.414-1.414z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                        </svg>
                        <span><?php echo htmlspecialchars($row_event['lokasi_event']); ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292m15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21v-1.5a6 6 0 00-6-6M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><?php echo htmlspecialchars($sisa_kuota); ?> Kuota Tersisa</span>
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
                        $is_registered = false;
                        $is_verified = false;
                        if ($user_id) {
                            foreach ($events_with_tickets as $ticket) {
                                if ($ticket['id_event'] == $row_event['id_event']) {
                                    $is_registered = true;
                                    if ($ticket['is_verified'] == 1) {
                                        $is_verified = true;
                                    }
                                    break;
                                }
                            }
                        }

                        if ($is_registered) {
                            if ($is_verified) {
                                echo '<button disabled class="bg-[#202020] text-white px-8 py-3 rounded-full text-lg cursor-not-allowed">Sudah Terdaftar</button>';
                            } else {
                                echo '<button id="openVerificationPendingBtn" type="button" class="bg-yellow-400 text-black font-semibold px-8 py-3 rounded-full text-lg transition-all">Menunggu Verifikasi</button>';
                            }
                        } else if ($row_event['event_status'] == 0 && $sisa_kuota > 0) {
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