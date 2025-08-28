<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- CDN Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
  <!--  Font -->

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
  <!-- ----------- -->

  <style type="text/tailwindcss">
    * {
        /* border: 1px solid red; */
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

      .scroll-smooth { /* the element was html.scroll-smooth.hydrated */
  height: 100vh;
}

body { /* the element was body */
  height: 100vh;
}

 /* Sembunyikan scrollbar */
  .hide-scrollbar::-webkit-scrollbar {
    display: none;
  }

  .hide-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;     /* Firefox */
  }
    </style>

  <!-- Title Web & Icon -->
  <title>Daftar Akun</title>
  <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
  <!-- Script Navbar Menu -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <!-- Script Cursor -->
  <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
  <!-- Script Cursor -->
</head>

<body class="bg-black">
  <?php
  require '_navbar.php';
  ?>
  <section id="register" class="w-full h-screen flex items-end sm:items-center justify-center md:justify-end sm:p-8 sm:pt-24 ">
    <form action="systemdata.php" method="POST" class="overflow-y-auto hide-scrollbar max-h-full items-center h-1/2 sm:h-full w-full sm:w-3/4 md:w-1/2 bg-white rounded-3xl rounded-b-none sm:rounded-b-3xl">
      <div class="flex flex-col justify-start sm:justify-center items-center w-full min-h-full bg-white rounded-xl gap-6  px-24 py-24">

        <h1 class="text-2xl md:text-3xl text-black font-semibold">Daftar Akun</h1>
        <hr>
        <div class="flex-col gap-2 w-full">
          <h1 class="text-base md:text-lg text-black font-semibold pb-1 italic font-work">Nama Lengkap</h1>
          <input type="text" name="nama" class="w-full h-14 rounded-full px-8 font-work font-medium  bg-neutral-200" required placeholder="Masukkan Nama Lengkap">
        </div>

        <div class="flex-col gap-2 w-full">
          <h1 class="text-base md:text-lg text-black font-semibold pb-1 italic font-work">Nomor Handphone</h1>
          <input type="number" name="no_hp" class="w-full h-14 rounded-full px-8 font-work font-medium  bg-neutral-200" required placeholder="Masukkan Nomor Handphone">
        </div>

        <div class="flex-col gap-2 w-full">
          <h1 class="text-base md:text-lg text-black font-semibold pb-1 italic font-work">Tanggal Lahir</h1>
          <input type="date" name="tgl_lahir" class="w-full h-14 rounded-full px-8 font-work font-medium  bg-neutral-200" required>
        </div>

        <div class="flex-col gap-2 w-full">
          <h1 class="text-base md:text-lg text-black font-semibold pb-1 italic font-work">Instansi</h1>
          <input type="text" name="instansi" class="w-full h-14 rounded-full px-8 font-work font-medium  bg-neutral-200" required placeholder="Masukkan Instansi">
        </div>

        <div class="flex-col gap-2 w-full">
          <h1 class="text-base md:text-lg text-black font-semibold pb-1 italic font-work">Email</h1>
          <input type="email" name="email" class="w-full h-14 rounded-full px-8 font-work font-medium  bg-neutral-200" required placeholder="Masukkan Email">
        </div>

        <div class="flex-col gap-2 w-full relative">
          <h1 class="text-base md:text-lg text-black font-semibold pb-1 italic font-work">Password</h1>
          <div class="relative">
            <input type="password" name="password" id="passwordInput"
              class="w-full h-14 rounded-full px-8 font-work font-medium  bg-neutral-200" required placeholder="Masukkan Password">
            <span id="togglePassword"
              class="absolute inset-y-0 right-0 flex items-center justify-center pr-6 cursor-pointer text-base text-emerald-700">
            <svg id="eyeIcon" fill="#000000" width="30px" height="30px" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M21.92,11.6C19.9,6.91,16.1,4,12,4S4.1,6.91,2.08,11.6a1,1,0,0,0,0,.8C4.1,17.09,7.9,20,12,20s7.9-2.91,9.92-7.6A1,1,0,0,0,21.92,11.6ZM12,18c-3.17,0-6.17-2.29-7.9-6C5.83,8.29,8.83,6,12,6s6.17,2.29,7.9,6C18.17,15.71,15.17,18,12,18ZM12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z" />
            </svg>
            </span>
          </div>
        </div>

        <div class="flex-col gap-2 w-full">
          <label class="flex items-center gap-2">
            <input type="checkbox" class="rounded-lg px-2 font-work font-medium bg-neutral-200" required >
            <span class="text-black font-work">Saya setuju dengan Kebijakan Privasi.</span>
          </label>
        </div>



        <button type="submit" name="register"
          class="text-base w-full lg:text-xl text-black px-6 py-4 bg-neutral-300 rounded-lg font-work hover:bg-neutral-500 duration-150 hover:drop-shadow-md">Daftar
          Sekarang</button>

        <p class="text-black">Sudah memiliki akun? Login <span><a href="login.php" class="font-bold text-emerald-700 hover:text-emerald-500">di
              sini.</a></span></p>

      </div>
    </form>
  </section>

  <!-- JavaScript untuk menampilkan pesan error -->
  <script>
    <?php if (isset($_SESSION['message'])): ?>
      alert("<?php echo $_SESSION['message']; ?>");
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
  </script>
</body>

<!-- Cursor CDN -->
<script src="https://unpkg.com/kursor"></script>
<script>
  new kursor({
    type: 4,
    removeDefaultCursor: true,
    color: '#ffffff',
  });
</script>
<!-- Cursor CDN -->

<script>
  const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("passwordInput");
    const eyeIcon = document.getElementById("eyeIcon");

    const eyeOpen = `<svg id="eyeIcon" fill="#000000" width="30px" height="30px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M21.92,11.6C19.9,6.91,16.1,4,12,4S4.1,6.91,2.08,11.6a1,1,0,0,0,0,.8C4.1,17.09,7.9,20,12,20s7.9-2.91,9.92-7.6A1,1,0,0,0,21.92,11.6ZM12,18c-3.17,0-6.17-2.29-7.9-6C5.83,8.29,8.83,6,12,6s6.17,2.29,7.9,6C18.17,15.71,15.17,18,12,18ZM12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z"/>
    </svg>` ;

    const eyeClosed = `
    <svg id="eyeIcon" width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M2 2L22 22" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M6.71277 6.7226C3.66479 8.79527 2 12 2 12C2 12 5.63636 19 12 19C14.0503 19 15.8174 18.2734 17.2711 17.2884M11 5.05822C11.3254 5.02013 11.6588 5 12 5C18.3636 5 22 12 22 12C22 12 21.3082 13.3317 20 14.8335" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M14 14.2362C13.4692 14.7112 12.7684 15.0001 12 15.0001C10.3431 15.0001 9 13.657 9 12.0001C9 11.1764 9.33193 10.4303 9.86932 9.88818" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>`;

    togglePassword.addEventListener("click", function () {
      const isPassword = passwordInput.getAttribute("type") === "password";
      passwordInput.setAttribute("type", isPassword ? "text" : "password");
      togglePassword.innerHTML = isPassword ? eyeClosed : eyeOpen;
    });
</script>

</html>