<!DOCTYPE html>
<html lang="en">
<html lang="en" class="scroll-smooth">

</html>

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
    </style>
  <!-- Title Web & Icon -->
  <title>Login</title>
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
  <div
        class="w-2/3 h-3/4 blur-3xl absolute z-0 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
    </div>
  <section id="login" class="w-full h-screen flex lg:flex-row flex-col-reverse sm:p-8 sm:pt-24">
    <div class="order-first lg:order-last relative z-10 lg:w-1/2 w-full h-1/2 md:h-2/3 lg:h-full text-start text-white space-y-4">
    <form action="systemdata.php" method="POST" class="overflow-y-auto hide-scrollbar max-h-full items-center h-full w-full bg-white rounded-3xl rounded-b-none sm:rounded-b-3xl">
      <div class="flex flex-col justify-start sm:justify-center items-center w-full min-h-full bg-white rounded-xl gap-6 px-10 py-10 md:px-24 md:py-24">
        <h1 class="text-2xl md:text-3xl text-neutral-600 font-semibold">Login</h1>
        <hr>
        <hr>
        <div class="relative gap-2 w-full">
          <div class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-7">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="30px" height="30px" viewBox="0 0 24 24">
              <path
                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
            </svg>
          </div>
          <input type="email" name="email"
            class="w-full h-14 rounded-2xl pl-16 font-work font-medium text-sm sm:text-base bg-neutral-200"
            placeholder="Masukkan Email Anda" required>
        </div>

        <div class="gap-2 w-full relative">
          <div class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-8">
            <svg fill="#000000" width="30px" height="30px" viewBox="0 0 36 36" version="1.1"
              preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink">
              <path class="clr-i-solid clr-i-solid-path-1"
                d="M26,15V10.72a8.2,8.2,0,0,0-8-8.36,8.2,8.2,0,0,0-8,8.36V15H7V32a2,2,0,0,0,2,2H27a2,2,0,0,0,2-2V15ZM19,25.23V28H17V25.14a2.4,2.4,0,1,1,2,.09ZM24,15H12V10.72a6.2,6.2,0,0,1,6-6.36,6.2,6.2,0,0,1,6,6.36Z">
              </path>
              <rect x="0" y="0" width="36" height="36" fill-opacity="0" />
            </svg>
          </div>
          <input type="password" name="password" id="passwordInput"
            class="w-full h-14 rounded-2xl pl-16 font-work font-medium text-sm sm:text-base bg-neutral-200"
            required placeholder="Masukkan Password Anda">
          <span id="togglePassword"
            class="absolute inset-y-0 right-0 flex items-center justify-center pr-6 cursor-pointer text-base text-emerald-700">
            <svg id="eyeIcon" fill="#000000" width="30px" height="30px" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M21.92,11.6C19.9,6.91,16.1,4,12,4S4.1,6.91,2.08,11.6a1,1,0,0,0,0,.8C4.1,17.09,7.9,20,12,20s7.9-2.91,9.92-7.6A1,1,0,0,0,21.92,11.6ZM12,18full.17,0-6.17-2.29-7.9-6C5.83,8.29,8.83,6,12,6s6.17,2.29,7.9,6C18.17,15.71,15.17,18,12,18ZM12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z" />
            </svg>
          </span>
        </div>


        <div class="flex w-full items-end justify-end text-end">
          <a href="forgotpassword.php" class="text-emerald-700 hover:text-emerald-500 font-medium italic">Lupa password?
          </a>
        </div>

        <button type="submit" name="login"
          class="text-base w-full lg:text-xl text-black px-6 h-14 bg-neutral-300 rounded-2xl font-work hover:bg-neutral-500 duration-150 hover:drop-shadow-md">
          Login
        </button>
        <hr>
        <hr>

        <p class="text-black">Belum memiliki akun? Register <span><a href="register.php"
              class="font-bold text-emerald-700 hover:text-emerald-500">di sini.</a></span></p>

      </div>
    </form>
    </div>
    <div class="order-last lg:order-first lg:w-1/2 md:h-1/3 w-full h-1/2 lg:h-full mt-20 lg:mt-0 flex justify-center items-center ">
      <img src="./img/hero/char.png" alt="Mindspace Characters" class="max-w-full h-fit animate-pulse" />
    </div>
  </section>

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

</html>