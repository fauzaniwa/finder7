<div id="registrationModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4 hidden">
    <?php if ($row_event['statusbayar'] === 'no'): // Modal FREE ?>
        <div class="bg-white rounded-2xl p-8 max-w-lg w-full relative overflow-y-auto max-h-[95vh]">
            <button type="button" id="closeRegistrationModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
            <p class="text-sm text-gray-500 mb-1">Kamu akan mendaftar di seminar/workshop:</p>
            <h2 class="text-2xl font-bold mb-6"><?php echo htmlspecialchars($row_event['judul_event']); ?></h2>
            <form id="freeRegistrationForm" method="post" action="detailevent_logic.php?slug=<?php echo htmlspecialchars($slug_target); ?>" enctype="multipart/form-data">
                <input type="hidden" name="id_event" value="<?php echo htmlspecialchars($row_event['id_event']); ?>">
                <input type="hidden" name="form_type" value="free">
                <div class="mb-4">
                    <label for="nama_lengkap_free" class="block text-sm font-medium text-gray-700">
                        <i class="fa-solid fa-user text-gray-400 mr-2"></i>Nama Lengkap
                    </label>
                    <input type="text" id="nama_lengkap_free" name="nama_lengkap[]"
                        class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                        required>
                </div>
                <div class="mb-6">
                    <label for="email_free" class="block text-sm font-medium text-gray-700">
                        <i class="fa-solid fa-envelope text-gray-400 mr-2"></i>Email
                    </label>
                    <input type="email" id="email_free" name="email[]"
                        class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                        required>
                </div>
                <div class="mb-6">
                    <label for="no_whatsapp_free" class="block text-sm font-medium text-gray-700">
                        <i class="fa-solid fa-mobile-screen-button text-gray-400 mr-2"></i>Nomor Whatsapp
                    </label>
                    <input type="tel" id="no_whatsapp_free" name="no_whatsapp[]"
                        class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                        required>
                </div>
                <button type="submit" class="w-full bg-[#00E091] hover:bg-[#00c77e] text-black font-bold py-3 rounded-lg text-lg">Kirim</button>
            </form>
        </div>
    <?php else: // Modal PAID ?>
        <div class="bg-white rounded-2xl p-8 max-w-3xl w-full relative overflow-y-auto max-h-[95vh]">
            <button type="button" id="closeRegistrationModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
            <p class="text-sm text-gray-500 mb-1">Kamu akan mendaftar di seminar/workshop:</p>
            <h2 class="text-2xl font-bold mb-6"><?php echo htmlspecialchars($row_event['judul_event']); ?></h2>
            <form id="paidRegistrationForm" method="post" action="detailevent_logic.php?slug=<?php echo htmlspecialchars($slug_target); ?>" enctype="multipart/form-data">
                <input type="hidden" name="id_event" value="<?php echo htmlspecialchars($row_event['id_event']); ?>">
                <input type="hidden" name="form_type" value="paid">
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="w-full md:w-1/2 space-y-4">
                        <div id="user-form-container" class="space-y-4">
                            <div class="user-form-group border-b pb-4">
                                <h5 class="font-bold text-left text-gray-700">Data Peserta 1</h5>
                                <label class="block text-sm font-medium text-gray-700 mt-2"><i class="fa-solid fa-user text-gray-400 mr-2"></i>Nama Lengkap</label>
                                <input type="text" name="nama_lengkap[]" placeholder="Nama Lengkap" class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                                
                                <label class="block text-sm font-medium text-gray-700 mt-2"><i class="fa-solid fa-envelope text-gray-400 mr-2"></i>Email</label>
                                <input type="email" name="email[]" placeholder="Email" class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                                
                                <label class="block text-sm font-medium text-gray-700 mt-2"><i class="fa-solid fa-mobile-screen-button text-gray-400 mr-2"></i>Nomor Telepon</label>
                                <input type="tel" name="no_whatsapp[]" placeholder="Nomor Telepon" class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                            </div>
                        </div>
                        
                        <button type="button" id="addUserBtn" class="mt-2 text-sm text-[#00E091] hover:underline">Tambah Peserta Lain</button>
                    
                        <div id="discount-info" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-4 hidden" role="alert">
                            <p class="font-bold">🎉 Promo Grup!</p>
                            <p class="text-sm">Jika kamu mendaftar dengan total 3 orang, akan mendapatkan potongan harga menjadi total <span id="total-harga-promo"></span></p>
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fa-solid fa-image text-gray-400 mr-2"></i>Unggah Bukti Pembayaran
                            </label>
                            <label for="bukti_pembayaran" class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 text-gray-500 rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:bg-gray-200">
                                <span id="file-name-paid">qris-bukti-payment</span>
                                <span class="bg-[#00E091] text-black font-semibold px-4 py-1 rounded-md">Upload File</span>
                            </label>
                            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="hidden" accept=".jpg, .jpeg, .png" required>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG (MAX. 2MB).</p>
                            <div class="mt-2 text-center">
                                <img id="image-preview" src="#" alt="Pratinjau Bukti Pembayaran" class="hidden w-48 h-auto mx-auto border rounded-md shadow-md">
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-1/2">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg">QRIS PAY</h3>
                            <span id="total-price-display" class="bg-gray-800 text-white px-4 py-2 rounded-full font-semibold">
                                <i class="fa-solid fa-dollar-sign text-[#00E091] mr-1"></i>Rp. <?php echo number_format($row_event['tiket_event'], 0, ',', '.'); ?>,-
                            </span>
                        </div>
                        <div class="bg-gray-200 w-full aspect-square rounded-lg flex items-center justify-center">
                            <img src="./img/qris.png" alt="QRIS Code" class="object-contain max-w-full max-h-full p-4">
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full mt-6 bg-[#00E091] hover:bg-[#00c77e] text-black font-bold py-3 rounded-lg text-lg">Kirim</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<div id="notLoginModal" class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center relative">
        <button type="button" id="closeNotLoginModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
        <h2 class="text-2xl font-bold mb-4">Oops! Kamu belum punya akun nih.</h2>
        <p class="text-gray-700 mb-6">Ayo daftarkan akunmu untuk mendaftar di seminar/workshop ini!</p>
        <a href="register.php" class="inline-block bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-full text-lg transition-all">Buat Akun</a>
    </div>
</div>

<div id="confirmCloseModal" class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center relative">
        <button type="button" id="cancelCloseModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
        <h2 class="text-2xl font-bold mb-4">Kesempatan ini mungkin nggak datang dua kali!</h2>
        <p class="text-gray-700 mb-6">Kamu yakin ingin menutup pop up pendaftaran ini?</p>
        <button type="button" id="confirmCloseModalBtn" class="inline-block bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-2xl text-lg transition-all">Tutup</button>
    </div>
</div>

<div id="thankYouModal" class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center relative">
        <h2 class="text-2xl font-bold mb-4">Terima Kasih!</h2>
        <p class="text-gray-700 mb-6">Tunggu 1x24 jam dan cek berkala halaman account page pada bagian Tiket. Hubungi CP admin dibawah jika dalam batas waktu tersebut tiket belum didapatkan atau terdapat bug sistem. CP : 085155471153</p>
        <a href="account.php" class="inline-block bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-2xl text-lg transition-all">Tutup</a>
    </div>
</div>

<div id="verificationPendingModal" class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center relative">
        <button type="button" id="closeVerificationPendingModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
        <h2 class="text-2xl font-bold mb-4">Pendaftaran Berhasil!</h2>
        <p class="text-gray-700 mb-6">Tunggu 1x24 Jam dan cek berkala halaman account page pada bagian Tiket. Hubungi CP admin dibawah jika dalam batas waktu tersebut tiket belum didapatkan atau terdapat bug sistem. CP : 085155471153</p>
        <button type="button" id="okVerificationPendingModalBtn" class="inline-block bg-[#00E091] hover:bg-[#00c77e] text-black font-semibold px-8 py-3 rounded-2xl text-lg transition-all">Tutup</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openNotLoginModalBtn = document.getElementById('openNotLoginModalBtn');
        const notLoginModal = document.getElementById('notLoginModal');
        const closeNotLoginModalBtn = document.getElementById('closeNotLoginModalBtn');

        const openRegistrationModalBtn = document.getElementById('openRegistrationModalBtn');
        const registrationModal = document.getElementById('registrationModal');
        const closeRegistrationModalBtn = document.getElementById('closeRegistrationModalBtn');
        const confirmCloseModal = document.getElementById('confirmCloseModal');
        const confirmCloseModalBtn = document.getElementById('confirmCloseModalBtn');
        const cancelCloseModalBtn = document.getElementById('cancelCloseModalBtn');
        const thankYouModal = document.getElementById('thankYouModal');

        const fileInputPaid = document.getElementById('bukti_pembayaran');
        const fileNameSpanPaid = document.getElementById('file-name-paid');
        const imagePreview = document.getElementById('image-preview');

        // --- Fungsi pembuka Modal Belum Login ---
        if (openNotLoginModalBtn) {
            openNotLoginModalBtn.addEventListener('click', () => {
                notLoginModal.classList.remove('hidden');
            });
        }
        if (closeNotLoginModalBtn) {
            closeNotLoginModalBtn.addEventListener('click', () => {
                notLoginModal.classList.add('hidden');
            });
        }
        if (notLoginModal) {
            notLoginModal.addEventListener('click', (event) => {
                if (event.target === notLoginModal) {
                    notLoginModal.classList.add('hidden');
                }
            });
        }

        // --- Fungsi pembuka Modal Pendaftaran ---
        if (openRegistrationModalBtn) {
            openRegistrationModalBtn.addEventListener('click', () => {
                registrationModal.classList.remove('hidden');
            });
        }

        // --- Konfirmasi Tutup Modal Pendaftaran ---
        if (closeRegistrationModalBtn) {
            closeRegistrationModalBtn.addEventListener('click', () => {
                confirmCloseModal.classList.remove('hidden');
            });
        }
        if (confirmCloseModalBtn) {
            confirmCloseModalBtn.addEventListener('click', () => {
                confirmCloseModal.classList.add('hidden');
                registrationModal.classList.add('hidden');
            });
        }
        if (cancelCloseModalBtn) {
            cancelCloseModalBtn.addEventListener('click', () => {
                confirmCloseModal.classList.add('hidden');
            });
        }
        if (confirmCloseModal) {
            confirmCloseModal.addEventListener('click', (event) => {
                if (event.target === confirmCloseModal) {
                    confirmCloseModal.classList.add('hidden');
                }
            });
        }

        // --- Thank You Modal ---
        if (thankYouModal) {
            const thankYouCloseBtn = thankYouModal.querySelector('a'); 
            if (thankYouCloseBtn) {
                thankYouCloseBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    thankYouModal.classList.add('hidden');
                    window.location.href = 'account.php';
                });
            }
            thankYouModal.addEventListener('click', (event) => {
                if (event.target === thankYouModal) {
                    thankYouModal.classList.add('hidden');
                    window.location.href = 'account.php';
                }
            });
        }
        
        // --- Logic Tampilan Nama File Upload dan Pratinjau Gambar ---
        if (fileInputPaid && fileNameSpanPaid && imagePreview) {
            fileInputPaid.addEventListener('change', () => {
                if (fileInputPaid.files.length > 0) {
                    fileNameSpanPaid.textContent = fileInputPaid.files[0].name;
                    const file = fileInputPaid.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                } else {
                    fileNameSpanPaid.textContent = 'qris-bukti-payment';
                    imagePreview.src = '#';
                    imagePreview.classList.add('hidden');
                }
            });
        }
    });

    // --- Logika Formulir Dinamis untuk Peserta Berbayar ---
    const addUserBtn = document.getElementById('addUserBtn');
    const userFormContainer = document.getElementById('user-form-container');
    const discountInfo = document.getElementById('discount-info');
    const totalPriceDisplay = document.getElementById('total-price-display');
    const totalHargaPromoSpan = document.getElementById('total-harga-promo');
    let userCount = 1;
    
    // Asumsi harga tiket dari PHP
    const tiketEventPrice = <?php echo json_encode($row_event['tiket_event']); ?>;

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }
    
    function updateTotalPrice() {
        let totalHarga = userCount * tiketEventPrice;
        if (userCount === 3) {
            if (tiketEventPrice === 20000) {
                totalHarga = 50000;
                totalHargaPromoSpan.textContent = formatRupiah(totalHarga);
                discountInfo.classList.remove('hidden');
            } else if (tiketEventPrice === 35000) {
                totalHarga = 90000;
                totalHargaPromoSpan.textContent = formatRupiah(totalHarga);
                discountInfo.classList.remove('hidden');
            } else {
                discountInfo.classList.add('hidden');
            }
        } else {
            discountInfo.classList.add('hidden');
        }
        totalPriceDisplay.innerHTML = `<i class="fa-solid fa-dollar-sign text-[#00E091] mr-1"></i>${formatRupiah(totalHarga)}`;
    }

    if (addUserBtn) {
        addUserBtn.addEventListener('click', () => {
            if (userCount < 3) {
                userCount++;
                const newUserForm = document.createElement('div');
                newUserForm.classList.add('user-form-group', 'border-b', 'pb-4', 'pt-4');
                newUserForm.innerHTML = `
                    <h5 class="font-bold text-left text-gray-700">Data Peserta ${userCount}</h5>
                    <label class="block text-sm font-medium text-gray-700 mt-2"><i class="fa-solid fa-user text-gray-400 mr-2"></i>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap[]" placeholder="Nama Lengkap" class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                    
                    <label class="block text-sm font-medium text-gray-700 mt-2"><i class="fa-solid fa-envelope text-gray-400 mr-2"></i>Email</label>
                    <input type="email" name="email[]" placeholder="Email" class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                    
                    <label class="block text-sm font-medium text-gray-700 mt-2"><i class="fa-solid fa-mobile-screen-button text-gray-400 mr-2"></i>Nomor Telepon</label>
                    <input type="tel" name="no_whatsapp[]" placeholder="Nomor Telepon" class="mt-1 block w-full px-3 py-2 bg-gray-100 border-gray-300 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                    
                    <button type="button" class="remove-user-btn text-red-500 hover:underline mt-2 text-sm">Hapus</button>
                `;
                userFormContainer.appendChild(newUserForm);

                if (userCount === 3) {
                    addUserBtn.classList.add('hidden');
                }
                updateTotalPrice();
            }
        });
    }

    userFormContainer.addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-user-btn')) {
            const formGroup = event.target.closest('.user-form-group');
            formGroup.remove();
            userCount--;
            document.querySelectorAll('.user-form-group').forEach((group, index) => {
                group.querySelector('h5').textContent = `Data Peserta ${index + 1}`;
                const labels = group.querySelectorAll('label');
                labels[0].textContent = `Nama Lengkap`;
                labels[1].textContent = `Email`;
                labels[2].textContent = `Nomor Telepon`;
            });
            if (userCount < 3) {
                addUserBtn.classList.remove('hidden');
            }
            updateTotalPrice();
        }
    });

    // Logic untuk modal verifikasi
    document.addEventListener('DOMContentLoaded', function() {
        const verificationPendingModal = document.getElementById('verificationPendingModal');
        const closeVerificationPendingModalBtn = document.getElementById('closeVerificationPendingModalBtn');
        const okVerificationPendingModalBtn = document.getElementById('okVerificationPendingModalBtn');
    
        if (closeVerificationPendingModalBtn) {
            closeVerificationPendingModalBtn.addEventListener('click', () => {
                verificationPendingModal.classList.add('hidden');
            });
        }
    
        if (okVerificationPendingModalBtn) {
            okVerificationPendingModalBtn.addEventListener('click', () => {
                verificationPendingModal.classList.add('hidden');
            });
        }
    
        // Cek jika variabel PHP untuk menampilkan modal diset
        const showVerificationModal = <?php echo json_encode($show_verification_modal); ?>;
        if (showVerificationModal) {
            verificationPendingModal.classList.remove('hidden');
        }
    });

    updateTotalPrice(); // Initial price update on page load
</script>