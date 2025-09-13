<nav>
    <div class="w-full h-20 fixed bg-transparent top-0 flex z-50 justify-center">
        <nav class="fixed top-0 left-0 w-full z-50 bg-black/70 backdrop-blur-md">
            <div class="container mx-auto flex h-20 items-center justify-between px-4 lg:px-8">

                <div class="flex-shrink-0">
                    <a href="homepage.php" class="block w-40 md:w-44">
                        <img src="img/FINDER 7 LOGO/Finder 7 Logopack_Lockup White.png" alt="Logo Finder 7"
                            class="h-auto w-full" />
                    </a>
                </div>

                <ul class="hidden items-center gap-6 md:flex">
                    <li><a href="homepage.php#finderdesc"
                            class="text-base text-white transition-colors hover:text-gray-300">About</a></li>
                    <li><a href="homepage.php#pameran"
                            class="text-base text-white transition-colors hover:text-gray-300">Pameran</a></li>
                    <li><a href="homepage.php#jadwal"
                            class="text-base text-white transition-colors hover:text-gray-300">Jadwal</a></li>
                    <li><a href="portal-lomba.php"
                            class="text-base text-white transition-colors hover:text-gray-300">Lomba</a></li>
                </ul>

                <div class="hidden items-center gap-4 md:flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center focus:outline-none">
                            <img src="./img/iconakun.svg" alt="User Account" />
                        </button>
                        <div id="dropdownMenu"
                            class="absolute right-0 mt-2 hidden w-48 rounded-md border border-gray-300 bg-white py-1 shadow-lg">
                            <a href="account.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                    <?php else: ?>
                    <a href="login.php"
                        class="rounded-full border border-white px-6 py-2 text-base text-white transition-colors hover:bg-white/20">Login</a>
                    <a href="register.php"
                        class="rounded-full bg-[#0D0D0D] px-6 py-2 text-base text-white transition-colors hover:bg-white/20">Daftar</a>
                    <?php endif; ?>
                </div>

                <div class="md:hidden">
                    <button id="hamburgerBtn" class="text-white">
                        <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-3xl"></ion-icon>
                    </button>
                </div>

            </div>
        </nav>

        <div id="navMenu"
            class="nav-links hidden flex flex-col absolute top-full bg-[#0D0D0D] w-full shadow-2xl text-center">
            <div
                class="bg-[#0D0D0D] hover:bg-neutral-700 w-full p-2 transition duration-300 ease-in-out cursor-pointer">
                <a href="homepage.php#finderdesc"><button style="font-family: 'Work Sans'"
                        class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">About</button></a>
            </div>
            <div
                class="bg-[#0D0D0D] hover:bg-neutral-700 w-full p-2 transition duration-300 ease-in-out cursor-pointer">
                <a href="homepage.php#pameran"><button style="font-family: 'Work Sans'"
                        class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Pameran</button></a>
            </div>
            <div
                class="bg-[#0D0D0D] hover:bg-neutral-700 w-full p-2 transition duration-300 ease-in-out cursor-pointer">
                <a href="homepage.php#jadwal"><button style="font-family: 'Work Sans'"
                        class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Jadwal</button></a>
            </div>
            <div
                class="bg-[#0D0D0D] hover:bg-neutral-700 w-full p-2 transition duration-300 ease-in-out cursor-pointer">
                <a href="portal-lomba.php"><button style="font-family: 'Work Sans'"
                        class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Lomba</button></a>
            </div>

            <div class="flex flex-col items-center gap-2 p-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="account.php" style="font-family: 'Work Sans'"
                    class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full w-full">Profile</a>
                <a href="logout.php" style="font-family: 'Work Sans'"
                    class="bg-[#0D0D0D] hover:bg-white hover:bg-opacity-25 py-2 px-6 text-white rounded-full w-full">Logout</a>
                <?php else: ?>
                <a href="login.php" style="font-family: 'Work Sans'"
                    class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full w-full">Login</a>
                <a href="register.php" style="font-family: 'Work Sans'"
                    class="bg-[#0D0D0D] hover:bg-opacity-25 py-2 px-6 text-white rounded-full w-full">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
// Logika untuk Hamburger Menu
const btn = document.getElementById('hamburgerBtn');
const menu = document.getElementById('navMenu');
btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
});

// Logika untuk Dropdown Menu Profil
function toggleDropdown() {
    document.getElementById("dropdownMenu").classList.toggle("hidden");
}

// Tutup dropdown jika klik di luar
window.onclick = function(event) {
    if (!event.target.matches('img[alt=""]')) { // Sesuaikan selektor jika perlu
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (!openDropdown.classList.contains('hidden')) {
                openDropdown.classList.add('hidden');
            }
        }
    }
}
</script>