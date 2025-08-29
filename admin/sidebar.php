<?php
// Pastikan variabel $admin_role sudah didefinisikan sebelum file ini di-include
// Jika belum, Anda bisa mengambilnya dari sesi di sini
if (!isset($admin_role)) {
    session_start();
    $admin_role = $_SESSION['role'] ?? 'guest';
}
?>

<aside id="sidebar" class="bg-dark-card w-64 min-h-screen p-6 shadow-lg fixed top-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:relative lg:block flex flex-col">
    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <span class="material-symbols-outlined text-primary-green text-3xl mr-3">
                menu
            </span>
            <span class="text-2xl font-semibold text-light-gray">Admin Panel</span>
        </div>
        <button id="close-sidebar-btn" class="text-white lg:hidden">
            <span class="material-symbols-outlined text-3xl">
                close
            </span>
        </button>
    </div>

    <div class="flex-grow overflow-y-auto pr-2">
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="flex items-center py-2 px-4 rounded-lg text-light-gray hover:bg-dark-gray transition-colors duration-200">
                        <span class="material-symbols-outlined text-2xl mr-3">
                            dashboard
                        </span>
                        Dashboard
                    </a>
                </li>
                <li class="my-4 h-px bg-gray-700"></li>
                
                <?php if ($admin_role === 'master'): ?>
                <li>
                    <a href="users.php" class="flex items-center py-2 px-4 rounded-lg text-light-gray hover:bg-dark-gray transition-colors duration-200">
                        <span class="material-symbols-outlined text-2xl mr-3">
                            group
                        </span>
                        Users
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($admin_role === 'master' || $admin_role === 'pameran'): ?>
                <li>
                    <button type="button" class="flex items-center justify-between w-full py-2 px-4 rounded-lg text-light-gray hover:bg-dark-gray transition-colors duration-200" onclick="toggleDropdown('pameran-menu')">
                        <span class="flex items-center">
                            <span class="material-symbols-outlined text-2xl mr-3">
                                palette
                            </span>
                            Pameran
                        </span>
                        <span class="material-symbols-outlined text-xl">
                            expand_more
                        </span>
                    </button>
                    <ul id="pameran-menu" class="dropdown-menu pl-8 mt-2 space-y-2 text-sm text-mid-gray">
                        <li><a href="#" class="block py-2 rounded-lg hover:bg-dark-gray transition-colors duration-200">Data Karya</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if ($admin_role === 'master' || $admin_role === 'seminar'): ?>
                <li>
                    <button type="button" class="flex items-center justify-between w-full py-2 px-4 rounded-lg text-light-gray hover:bg-dark-gray transition-colors duration-200" onclick="toggleDropdown('seminar-menu')">
                        <span class="flex items-center">
                            <span class="material-symbols-outlined text-2xl mr-3">
                                school
                            </span>
                            Seminar
                        </span>
                        <span class="material-symbols-outlined text-xl">
                            expand_more
                        </span>
                    </button>
                    <ul id="seminar-menu" class="dropdown-menu pl-8 mt-2 space-y-2 text-sm text-mid-gray">
                        <li><a href="speakers_list.php" class="block py-2 rounded-lg hover:bg-dark-gray transition-colors duration-200">Pemateri</a></li>
                        <li><a href="#" class="block py-2 rounded-lg hover:bg-dark-gray transition-colors duration-200">Jadwal Seminar</a></li>
                        <li><a href="#" class="block py-2 rounded-lg hover:bg-dark-gray transition-colors duration-200">Daftar Hadir</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if ($admin_role === 'master' || $admin_role === 'workshop'): ?>
                <li>
                    <button type="button" class="flex items-center justify-between w-full py-2 px-4 rounded-lg text-light-gray hover:bg-dark-gray transition-colors duration-200" onclick="toggleDropdown('workshop-menu')">
                        <span class="flex items-center">
                            <span class="material-symbols-outlined text-2xl mr-3">
                                engineering
                            </span>
                            Workshop
                        </span>
                        <span class="material-symbols-outlined text-xl">
                            expand_more
                        </span>
                    </button>
                    <ul id="workshop-menu" class="dropdown-menu pl-8 mt-2 space-y-2 text-sm text-mid-gray">
                        <li><a href="speakers_list.php" class="block py-2 rounded-lg hover:bg-dark-gray transition-colors duration-200">Pemateri</a></li>
                        <li><a href="#" class="block py-2 rounded-lg hover:bg-dark-gray transition-colors duration-200">Jadwal Workshop</a></li>
                        <li><a href="#" class="block py-2 rounded-lg hover:bg-dark-gray transition-colors duration-200">Daftar Hadir</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if ($admin_role === 'master' || $admin_role === 'lomba'): ?>
                <li>
                    <button type="button" class="flex items-center justify-between w-full py-2 px-4 rounded-lg text-light-gray hover:bg-dark-gray transition-colors duration-200" onclick="toggleDropdown('lomba-menu')">
                        <span class="flex items-center">
                            <span class="material-symbols-outlined text-2xl mr-3">
                                trophy
                            </span>
                            Lomba
                        </span>
                        <span class="material-symbols-outlined text-xl">
                            expand_more
                        </span>
                    </button>
                    <ul id="lomba-menu" class="dropdown-menu pl-8 mt-2 space-y-2 text-sm text-mid-gray">
                        <li><a href="#" class="block py-2 rounded-lg hover:bg-dark-gray transition-colors duration-200">Peserta Lomba</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                
                <li class="my-4 h-px bg-gray-700"></li>

                <li>
                    <a href="register.php" class="flex items-center py-2 px-4 rounded-lg text-light-gray hover:bg-dark-gray transition-colors duration-200">
                        <span class="material-symbols-outlined text-2xl mr-3">
                            admin_panel_settings
                        </span>
                        Admin
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center py-2 px-4 rounded-lg text-light-gray hover:bg-dark-gray transition-colors duration-200">
                        <span class="material-symbols-outlined text-2xl mr-3">
                            receipt_long
                        </span>
                        Log Admin
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="mt-auto pt-6">
        <a href="logout.php" class="w-full flex items-center justify-center py-3 px-4 rounded-lg bg-red-error hover:bg-opacity-80 transition-colors duration-200">
            <span class="material-symbols-outlined text-2xl mr-2">
                logout
            </span>
            Logout
        </a>
    </div>
</aside>