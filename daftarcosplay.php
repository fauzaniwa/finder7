<?php
include '../admin-one/dist/koneksi.php';
session_start();
?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        work: ['Work Sans'],
                    },
                    animation: {
                        'spin-slow': 'spin 4s linear infinite',
                        'loop-scroll': 'loop-scroll 10s linear infinite',
                    },
                    keyframes: {
                        'loop-scroll': {
                            from: { transform: 'translateX(0)' },
                            to: { transform: 'translateX(-100%)' },
                        },
                    },
                },
            },
        };
    </script>
    
    <style type="text/tailwindcss">
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }


        .navbar-scrolled {
        box-shadow: 2px 2px 30px #000000;
      }
      .ext-scrolled {
        color: black;
      }
      .navbar {
        transition: all 0.5s;
      }
      .scroller {
        max-width: 600px;
      }

      .scroller__inner {
        padding-block: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 3rem;
      }

      .scroller[data-animated='true'] {
        overflow: hidden;
        -webkit-mask: linear-gradient(90deg, transparent, white 20%, white 80%, transparent);
        mask: linear-gradient(90deg, transparent, white 20%, white 80%, transparent);
      }

      .scroller[data-animated='true'] .scroller__inner {
        width: max-content;
        flex-wrap: nowrap;
        animation: scroll var(--_animation-duration, 40s) var(--_animation-direction, forwards) linear infinite;
      }

      .scroller[data-direction='right'] {
        --_animation-direction: reverse;
      }

      .scroller[data-direction='left'] {
        --_animation-direction: forwards;
      }

      .scroller[data-speed='fast'] {
        --_animation-duration: 20s;
      }

      .scroller[data-speed='slow'] {
        --_animation-duration: 60s;
      }

      @keyframes scroll {
        to {
          transform: translate(calc(-50% - 0.5rem));
        }
      }

      /* for testing purposed to ensure the animation lined up correctly */
      .test {
        background: red !important;
      }
      .modal {
  display: none;
}

.modal.show {
  display: flex;
}

.hidden {
  display: none;
}


    </style>
    <title>Finder - Lomba Ilustrasi</title>
    <link rel="icon" href="../img/FinderLogo.svg" type="image/x-icon" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    </head>

<body class="bg-black pt-40">
    <?php require '_navbar.php'; ?>
    <div class="flex flex-col justify-center items-center pb-20">
        <div id="successModal"
            class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50 hidden">
            <div class="bg-emerald-400 text-black rounded-xl p-8 max-w-sm text-center shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Karya berhasil dikirim!</h2>
                <p>Terima kasih atas partisipasi Anda.</p>
                <button id="closeSuccessModal"
                    class="mt-6 bg-black text-emerald-400 px-6 py-2 rounded-xl font-semibold hover:bg-neutral-900">Tutup</button>
            </div>
        </div>


        <div class="pb-10">
            <h1 class="font-bold text-3xl text-white">Daftar Lomba Cosplay</h1>
        </div>

        <br>
        <div class="flex justify-center items-center py-12 px-5 bg-neutral-900 rounded-xl w-10/12 md:w-6/12">

            <form id="form" action="process_wacom.php" method="post" class="flex flex-col gap-5 w-full" enctype="multipart/form-data">

                <div id="infoModal" class="modal fixed inset-0 bg-black bg-opacity-60 justify-center items-center z-50"
                    style="display:none;">
                    <div
                        class="bg-neutral-900 rounded-xl px-8 py-6 w-11/12 md:w-1/2 text-center text-white shadow-lg relative">
                        <h2 class="text-xl md:text-2xl font-bold mb-4 text-emerald-400">
                            Apakah data yang Anda isi sudah benar?
                        </h2>
                        <p class="text-sm md:text-base text-neutral-300">
                            Pastikan semua informasi sudah sesuai sebelum dikirim.
                        </p>
                        <div class="mt-6 flex justify-center gap-4">
                            <button type="button" id="confirmSubmitBtn"
                                class="bg-emerald-400 hover:bg-emerald-600 text-black px-6 py-2 rounded-xl shadow">
                                Ya, Kirim
                            </button>
                            <button type="button" id="cancelSubmitBtn"
                                class="bg-neutral-700 hover:bg-neutral-600 text-white px-6 py-2 rounded-xl shadow">
                                Kembali
                            </button>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="lomba_cosplay" id="lomba_cosplay" value="cosplay">

                <div class="flex justify-center">
                    <input type="text" id="nama" name="Nama_Lengkap" placeholder="Nama Lengkap" required
                        class="rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                </div>

                <div class="flex justify-center">
                    <input type="text" id="karakter" name="karakter" placeholder="Nama Karakter (contoh: Monkey D. Luffy - One Piece)" required
                        class="rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                </div>

                <div class="flex gap-5 justify-center">
                    <input type="email" id="email" name="email" placeholder="Email" required
                        class="rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                </div>
                
                <div class="flex gap-5 justify-center">
                    <input type="text" id="nomor-telepon" name="nomor-telepon" placeholder="Nomor Telepon" required
                        class="rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                    <input type="text" id="social-media" name="social-media" placeholder="Social Media" required
                        class="rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                </div>

                <div class="flex items-start gap-3 text-white text-sm">
                    <input type="checkbox" id="persetujuan" name="persetujuan" required
                        class="accent-emerald-400 w-5 h-5 mt-1 rounded border border-neutral-600 focus:ring-emerald-500 focus:ring-2 transition">
                    <label for="persetujuan">
                        Saya menyetujui <a href="lombacosplay.php#ketentuan"
                            class="text-emerald-400 hover:text-emerald-600 underline">syarat dan ketentuan</a>.
                    </label>
                </div>

                <div class="flex justify-center">
                    <button type="button" id="btnShow"
                        class="bg-emerald-400 hover:bg-emerald-600 text-black px-20 py-2 rounded-xl shadow">
                        Submit
                    </button>
                </div>

            </form>
        </div>
    </div>
    <script>
        // Skrip untuk modal sukses
        document.addEventListener("DOMContentLoaded", () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                const successModal = document.getElementById('successModal');
                successModal.classList.remove('hidden');
                successModal.classList.add('flex');
            }
        });
        document.getElementById('closeSuccessModal').addEventListener('click', () => {
            const successModal = document.getElementById('successModal');
            successModal.classList.add('hidden');
            successModal.classList.remove('flex');
        });

        // Skrip untuk modal konfirmasi
        const form = document.getElementById('form');
        const btnShow = document.getElementById('btnShow');
        const infoModal = document.getElementById('infoModal');
        const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
        const cancelSubmitBtn = document.getElementById('cancelSubmitBtn');

        btnShow.addEventListener('click', () => {
            if (form.checkValidity()) {
                infoModal.style.display = 'flex';
            } else {
                form.reportValidity();
            }
        });

        confirmSubmitBtn.addEventListener('click', () => {
            infoModal.style.display = 'none';
            form.submit();
        });

        cancelSubmitBtn.addEventListener('click', () => {
            infoModal.style.display = 'none';
        });

    </script>
    <script src="https://unpkg.com/kursor"></script>
    <script>
        new kursor({
            type: 4,
            removeDefaultCursor: true,
            color: '#ffffff',
        });
    </script>

</body>

</html>