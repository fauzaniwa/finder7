<!-- INI DEZAIN STYLEZ SBELUMNYA, TAKUT ADA BUG WKWK -ZHAR -->

<!-- Jadwal -->
<section id="jadwal" class="bg-neutral-950 text-gray-100 min-h-screen">
    <main class="container mx-auto px-6 py-20 pt-32">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-16">
            Jadwal Acara
        </h1>

        <?php
        // Mengelompokkan semua event berdasarkan tanggal dari $events_data
        $grouped_by_date = [];
        foreach ($events_data as $event) {
            $tanggal = $event['jadwal_event']; // Menggunakan 'jadwal_event' sebagai kunci
            $grouped_by_date[$tanggal][] = $event;
        }

        // Ambil hanya 3 tanggal pertama dari array yang sudah dikelompokkan
        $limited_grouped_by_date = array_slice($grouped_by_date, 0, 3, true);

        // Cek apakah ada event yang ditemukan setelah pengelompokan dan pembatasan
        $events_found = !empty($limited_grouped_by_date);
        ?>

        <?php if ($events_found): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-12 md:gap-x-8">

            <?php
                // Logika untuk menambahkan border di antara kolom
                $card_count = 0;
                // Gunakan array yang sudah dibatasi untuk menghitung total kartu
                $total_cards = count($limited_grouped_by_date);
                // Lakukan perulangan pada array tanggal yang sudah dibatasi
                foreach ($limited_grouped_by_date as $tanggal => $events):
                    $card_count++;
                    // Menambahkan border kanan hanya jika bukan kartu terakhir di tampilan desktop
                    $border_class = ($card_count < $total_cards && $total_cards > 1) ? 'lg:border-r lg:border-neutral-800' : '';
                    ?>
            <div class="flex flex-col space-y-8 px-4 <?php echo $border_class; ?>">
                <div class="flex items-center space-x-4">
                    <span class="text-6xl md:text-7xl font-bold"><?php echo date('d', strtotime($tanggal)); ?></span>
                    <div class="flex flex-col">
                        <span class="text-2xl md:text-3xl"><?php echo date('F', strtotime($tanggal)); ?></span>
                        <span
                            class="text-2xl md:text-3xl text-neutral-400"><?php echo date('Y', strtotime($tanggal)); ?></span>
                    </div>
                </div>

                <div class="flex flex-col space-y-12">
                    <?php
                            // Ambil hanya 3 event pertama untuk tanggal ini
                            $limited_events = array_slice($events, 0, 3);
                            // Lakukan perulangan pada array event yang sudah dibatasi
                            foreach ($limited_events as $event):
                                ?>
                    <div>
                        <h3 class="text-lg font-bold"><?php echo htmlspecialchars($event['judul_event']); ?></h3>
                        <?php if (!empty($event['speakers'])): ?>
                        <p class="text-base text-neutral-400 mt-1">
                            Speakers:
                            <?php
                                            $speaker_names = array_map(function ($speaker) {
                                                $instansi = !empty($speaker['instansi']) ? " ({$speaker['instansi']})" : "";
                                                return htmlspecialchars($speaker['nama_speaker'] . $instansi);
                                            }, $event['speakers']);
                                            echo implode(', ', $speaker_names);
                                            ?>
                        </p>
                        <?php endif; ?>

                        <div class="flex justify-between items-center mt-1 pr-3">
                            <span class="text-neutral-400 text-base">Waktu:
                                <?php echo htmlspecialchars($event['waktu_event']); ?></span>
                            <span class="font-semibold text-base">Kuota:
                                <?php echo htmlspecialchars($event['kuota']); ?></span>
                        </div>

                        <div class="flex space-x-4 mt-6">
                            <?php
                                        $slug_event = htmlspecialchars($event['slug'] ?? 'default-slug');

                                        // --- LOGIKA PERBAIKAN DI SINI ---
                                        $user_has_ticket = false;
                                        $is_verified_ticket = false;

                                        // Cek apakah user sudah mendaftar dan dapatkan status verifikasinya
                                        if (isset($events_with_tickets[$event['id_event']])) {
                                            $user_has_ticket = true;
                                            $is_verified_ticket = ($events_with_tickets[$event['id_event']] == 1);
                                        }
                                        ?>
                            <a href="detailevent.php?slug=<?php echo $slug_event; ?>"
                                class="border border-neutral-600 rounded-xl px-5 py-2 text-base hover:bg-white hover:text-black transition-colors duration-300">Detail
                                Kegiatan</a>

                            <?php if ($user_has_ticket): ?>
                            <?php if ($is_verified_ticket): ?>
                            <span
                                class="border border-emerald-800 bg-emerald-950 text-emerald-400 rounded-xl px-5 py-2 text-base">Kamu
                                Memiliki Tiket</span>
                            <?php else: ?>
                            <span
                                class="border border-yellow-800 bg-yellow-950 text-yellow-400 rounded-xl px-5 py-2 text-base">Menunggu
                                Verifikasi</span>
                            <?php endif; ?>
                            <?php else: ?>
                            <?php
                                            $sisa_kuota = isset($event['sisa_kuota']) ? $event['sisa_kuota'] : 0;
                                            ?>
                            <?php if ($event['event_status'] == 0): ?>
                            <?php if ($sisa_kuota > 0): ?>
                            <a href="detailevent.php?slug=<?php echo $slug_event; ?>"
                                class="border border-neutral-700 rounded-xl px-5 py-2 hover:border-emerald-800 hover:bg-emerald-950 hover:text-emerald-400 transition-colors duration-300">Daftar</a>
                            <?php else: ?>
                            <button
                                class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-base cursor-not-allowed"
                                disabled>Kuota Penuh</button>
                            <?php endif; ?>
                            <?php elseif ($event['event_status'] == 1): ?>
                            <button
                                class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-base cursor-not-allowed"
                                disabled>Telah Berakhir</button>
                            <?php elseif ($event['event_status'] == 2): ?>
                            <button
                                class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-base cursor-not-allowed"
                                disabled>Kuota Penuh</button>
                            <?php elseif ($event['event_status'] == 4): ?>
                            <button
                                class="border border-neutral-700 text-neutral-500 rounded-xl px-5 py-2 text-base cursor-not-allowed"
                                disabled>Segera Hadir</button>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-20">
            <a href="event.php"
                class="inline-block rounded-2xl font-semibold text-center tracking-wide transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black whitespace-nowrap px-9 py-3 md:px-16 text-base md:text-lg bg-emerald-400 text-emerald-950 hover:bg-emerald-500 focus:ring-emerald-400">
                Lihat Semua
            </a>
        </div>

        <?php else: ?>
        <p class="text-center text-neutral-400 text-xl">Saat ini belum ada jadwal acara yang tersedia.</p>
        <?php endif; ?>

    </main>
</section>