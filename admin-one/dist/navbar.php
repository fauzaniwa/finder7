<?php
session_start();

// Periksa apakah session admin_name ada dan tidak kosong
if (!isset($_SESSION['admin_name'])) {
    // Redirect ke halaman login.php jika session tidak ditemukan
    header("Location: login.php");
    exit(); // Pastikan untuk keluar setelah melakukan redirect
}

// Jika session admin_name ditemukan, ambil nilainya
$admin_name = $_SESSION['admin_name'];
?>
<nav id="navbar-main" class="navbar is-fixed-top">
    <div class="navbar-brand">
      <a class="navbar-item mobile-aside-button">
        <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
      </a>
      <div class="navbar-item">
        <div class="control"><input placeholder="Search everywhere..." class="input"></div>
      </div>
    </div>
    <div class="navbar-brand is-right">
      <a class="navbar-item --jb-navbar-menu-toggle" data-target="navbar-menu">
        <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
      </a>
    </div>
    <div class="navbar-menu" id="navbar-menu">
      <div class="navbar-end">
        <div class="navbar-item dropdown has-divider has-user-avatar">
          <a class="navbar-link">
            <div class="user-avatar">
              <img src="https://avatars.dicebear.com/v2/initials/john-doe.svg" alt="John Doe" class="rounded-full">
            </div>
            <div class="is-user-name"><span><?php echo $_SESSION['admin_name']; ?></span></div>
            <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
          </a>
          <div class="navbar-dropdown">
            <a class="navbar-item" href="settingadmin.php">
              <span class="icon"><i class="mdi mdi-settings"></i></span>
              <span>Settings</span>
            </a>
            <a class="navbar-item">
              <span class="icon"><i class="mdi mdi-email"></i></span>
              <span>Messages</span>
            </a>
            <hr class="navbar-divider">
            <a href="logout.php" class="navbar-item">
                <span class="icon"><i class="mdi mdi-logout"></i></span>
                <span>Log Out</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
  
  <aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
      <div>
        Admin <b class="font-black">Finder</b>
      </div>
    </div>
    <div class="menu is-menu-main">
      <p class="menu-label">General</p>
      <ul class="menu-list">
        <li class="active">
          <a href="index.php">
            <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
            <span class="menu-item-label">Dashboard</span>
          </a>
        </li>
      </ul>
      <p class="menu-label">Data</p>
      <ul class="menu-list">
        <li class="--set-active-tables-html">
          <a href="datauser.php">
            <span class="icon"><i class="mdi mdi-account-circle"></i></span>
            <span class="menu-item-label">Data User</span>
          </a>
        </li>
        <li class="--set-active-forms-html">
          <a href="dataevent.php">
            <span class="icon"><i class="mdi mdi-square-edit-outline"></i></span>
            <span class="menu-item-label">Data Event</span>
          </a>
        </li>
        <li class="--set-active-profile-html">
          <a href="dataabsen.php">
            <span class="icon"><i class="mdi mdi-table"></i></span>
            <span class="menu-item-label">Data Absen Seminar & Workshop</span>
          </a>
        </li>
        <li class="--set-active-profile-html">
          <a href="dataabsenpameran.php">
            <span class="icon"><i class="mdi mdi-table"></i></span>
            <span class="menu-item-label">Data Absen Pameran</span>
          </a>
        </li>
        <li class="--set-active-profile-html">
          <a href="datagallery.php">
            <span class="icon"><i class="mdi mdi-image"></i></span>
            <span class="menu-item-label">Data Gallery</span>
          </a>
        </li>
        <li class="--set-active-profile-html">
          <a href="datapameran.php">
            <span class="icon"><i class="mdi mdi-folder"></i></span>
            <span class="menu-item-label">Data Pameran</span>
          </a>
        </li>
        <li class="--set-active-profile-html">
          <a href="speakers.php">
            <span class="icon"><i class="mdi mdi-folder"></i></span>
            <span class="menu-item-label">Data Speaker</span>
          </a>
        </li>
        <li class="--set-active-profile-html">
          <a href="lomba.php">
            <span class="icon"><i class="mdi mdi-folder"></i></span>
            <span class="menu-item-label">Data Peserta Lomba</span>
          </a>
        </li>
        <li class="--set-active-profile-html">
          <a href="dataqna.php">
            <span class="icon"><i class="mdi mdi-folder"></i></span>
            <span class="menu-item-label">Q and A</span>
          </a>
        </li>
      </ul>
      <p class="menu-label">Feature</p>
      <ul class="menu-list">
        <li>
          <a href="scanabsen.php" class="has-icon">
            <span class="icon"><i class="mdi mdi-credit-card-outline"></i></span>
            <span class="menu-item-label">Scan Absen Seminar & Workshop</span>
          </a>
        </li>
        <li>
          <a href="scanabsenpameran.php" class="has-icon">
            <span class="icon"><i class="mdi mdi-credit-card-outline"></i></span>
            <span class="menu-item-label">Scan Absen Pameran</span>
          </a>
        </li>
        <li>
          <a href="broadcasts.php" class="has-icon">
            <span class="icon"><i class="mdi mdi-square-edit-outline"></i></span>
            <span class="menu-item-label">Broadcast</span>
          </a>
        </li>
        <li>
          <a href="information.php" class="has-icon">
            <span class="icon"><i class="mdi mdi-information"></i></span>
            <span class="menu-item-label">Info</span>
          </a>
        </li>
        <li>
          <a href="https://wa.me/6282385541846?text=Halo!%20Terjadi%20error%20pada%20website%20Finder." class="has-icon" target="_blank">
            <span class="icon"><i class="mdi mdi-whatsapp"></i></span>
            <span class="menu-item-label">Report Error</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>