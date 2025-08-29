<nav>
    <div class="w-full h-24 fixed bg-transparent top-0 backrop-blur-md flex z-50 justify-center">
        <div
            class="flex w-full h-full bg-[#000000]  navbar mx-auto my-auto py-2 pl-4 md:px-8 gap-3 justify-between items-center bg-opacity-70">
            <div class="flex items-center gap-4 w-[220px]">
                <a href="homepage.php" class="md:h-2/3 my-auto"><img src="img/Finder 7 Logopack_Lockup Full White small.png" alt=""
                        class="w-[66%] h-[66%] md:w-full md:h-full" /></a>
            </div>

            <!-- Nav Asli -->

            <div class="hidden md:flex gap-6 justify-center">
                <a href="homepage.php#finderdesc" style="font-family: 'Work Sans'" class="flex"><button
                        class="text-sm lg:text-xl text-white txt1">About</button></a>
                <!-- <a href="homepage.php#pameran" style="font-family: 'Work Sans'" class="flex"><button
                        class="text-sm lg:text-xl text-white txt2">Pameran</button></a> -->
                <a href="homepage.php#jadwal" style="font-family: 'Work Sans'" class="flex"><button
                        class="text-sm lg:text-xl text-white txt">Jadwal</button></a>
                <a href="submission.php" style="font-family: 'Work Sans'" class="flex"><button
                        class="text-sm lg:text-xl text-white txt">Lomba</button></a>
                <a href="./wacom/" style="font-family: 'Work Sans'" class="flex"><button
                        class="text-sm lg:text-xl text-white txt">Lomba (New)</button></a>
            </div>

            <!-- Tombol Login -->
             
            <div class="md:flex items-center hidden gap-4 justify-end opacity-0">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Jika sudah login, tampilkan tombol akun dengan dropdown menu -->
                    <div class="relative inline-block text-left">
                        <button onclick="toggleDropdown()" class="flex items-center focus:outline-none">
                            <img src="./img/iconakun.svg" alt="" />
                        </button>
                        <div id="dropdownMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg py-1 mx-auto">
                            <a href="account.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Jika belum login, tampilkan tombol login dan daftar -->
                    <a href="login.php" style="font-family: 'Work Sans'"
                        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Login</a>
                    <a href="register.php" style="font-family: 'Work Sans'"
                        class="bg-[#0D0D0D] hover:bg-white hover:bg-opacity-25 py-2 px-6 text-white rounded-full md:text-lg">Daftar</a>
                <?php endif; ?>
            </div>

            <!-- Tombol Menu -->

            <button id="hamburgerBtn" class="bg-transparent aspect-square md:hidden text-center text-white px-4">
                <ion-icon onclick="onToggleMenu(this)" name="menu" class="txt text-3xl cursor-pointer p-0"></ion-icon>
            </button>
        </div>

        <!-- Nav Menu -->
        <div
            id="navMenu" class="nav-links hidden flex flex-col absolute top-full bg-[#0D0D0D] w-full shadow-2xl text-center">
            <div class="bg-[#0D0D0D] hover:bg-neutral-700 w-full p-2 transition duration-300 ease-in-out cursor-pointer">
                <a href="homepage.php#finderdesc"><button style="font-family: 'Work Sans'"
                class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">About</button></a>
            </div>
            <!-- <div class="bg-[#0D0D0D] hover:bg-neutral-700 w-full p-2 transition duration-300 ease-in-out cursor-pointer">
                <a href="homepage.php#pameran"><button style="font-family: 'Work Sans'"
                class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Pameran</button></a>
            </div> -->
            <div class="bg-[#0D0D0D] hover:bg-neutral-700 w-full p-2 transition duration-300 ease-in-out cursor-pointer">
                <a href="homepage.php#jadwal"><button style="font-family: 'Work Sans'"
                class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Jadwal</button></a>
            </div>
            <div class="bg-[#0D0D0D] hover:bg-neutral-700 w-full p-2 transition duration-300 ease-in-out cursor-pointer">
                <a href="submission.php"><button style="font-family: 'Work Sans'"
                class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Lomba</button></a>
            </div>
            <div class="bg-[#0D0D0D] hover:bg-neutral-700 w-full p-2 transition duration-300 ease-in-out cursor-pointer">
                <a href="./wacom/"><button style="font-family: 'Work Sans'"
                class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Lomba (New)</button></a>
            </div>

            <!-- -------- -->
            <div class="hidden flex flex-row items-center gap-6 justify-start mt-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="account.php"><img src="./img/iconakun.svg" alt="" /></a>
                    <a href="logout.php" style="font-family: 'Work Sans'"
                        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Logout</a>
                <?php else: ?>
                    <a href="login.php" style="font-family: 'Work Sans'"
                        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Login</a>
                    <a href="register.php" style="font-family: 'Work Sans'"
                        class="bg-[#0D0D0D] hover:bg-opacity-25 py-2 px-6 text-white rounded-full md:text-lg">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
        <!-- Akhir Navmenu -->
    </div>
</nav>
<!-- Navbar -->

 <script>
    const btn = document.getElementById('hamburgerBtn');
    const menu = document.getElementById('navMenu');
    btn.addEventListener('click', () => menu.classList.toggle('hidden'));
  </script>
  
</nav>