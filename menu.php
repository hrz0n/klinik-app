<?php
// session_start();
if (isset($_SESSION['login'])) {
  $level = $_SESSION['tipeuser'];
  echo '
  <div class="app-body">
    <div class="sidebar">
      <nav class="sidebar-nav">
        <ul class="nav">
          <li class="nav-item">
          <a class="nav-link" href="index.php" title="Beranda">
             <i class="nav-icon icon-home"></i> Beranda
          </a>
        </li>
        <li class="nav-item nav-dropdown">
          <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon  cil-factory "></i> Transaksi </a>
          <ul class="nav-dropdown-items">
          <li class="nav-item">
            <a class="nav-link" href="transaksi.php" title="Transaksi">
               <i class="nav-icon icon icon-paper-plane"></i> Transaksi Baru
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-toggle" href="daftartransaksi.php">
            <i class="nav-icon  cil-short-text"></i> Daftar Transaksi</a>
          </li>
          </ul>
        </li>

        ';

  if ($level >= 0) {
    echo '
    <li class="nav-title">MASTER DATA</li>

    <li class="nav-item nav-dropdown">
      <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon  cil-bank "></i> Pelayanan </a>
      <ul class="nav-dropdown-items">
      <li class="nav-item">
        <a class="nav-link nav-toggle" href="kategori.php"><i class="nav-icon  cil-diamond "></i> Kategori Pelayanan</a>
      </li>
      <li class="nav-item">
        <a class="nav-link nav-toggle" href="jenis.php"><i class="nav-icon  cil-education "></i> Jenis Pelayanan</a>
      </li>
        <li class="nav-item">
          <a class="nav-link" href="harga.php">
            <i class="nav-icon  cil-dollar "></i> Daftar Harga</a>
        </li>


      </ul>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="daftarpasien.php">
        <i class="nav-icon  cil-contact "></i> Data Pasien</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="daftarperawat.php">
        <i class="nav-icon  cil-user-female"></i> Data Perawat</a>
    </li>


    ';
  }

    echo '<li class="nav-item nav-dropdown">
      <a class="nav-link nav-dropdown-toggle" href="#">
         <i class="nav-icon icon-printer"></i>  Laporan</a>
      <ul class="nav-dropdown-items"> ';
    if ($level >= 0) {
    echo '
            <li class="nav-item">
              <a class="nav-link" href="rekaptransaksi.php">
                <i class="nav-icon  cil-square "></i> Rekap Transaksi</a>
            </li>
        ';
    }
    echo '</ul>
    </li>';
    if ($level == 0) {
    echo '<li class="nav-title">PENGATURAN APP</li>
    <li class="nav-item nav-dropdown">
      <a class="nav-link nav-dropdown-toggle" href="#">
         <i class="nav-icon icon-settings"></i>  Pengaturan</a>
      <ul class="nav-dropdown-items">
        <li class="nav-item">
          <a class="nav-link" href="settingServer.php">
            <i class="nav-icon icon-info "></i> Server</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="users.php">
            <i class="nav-icon icon-people"></i> Users</a>
        </li>
      </ul>
    </li>';
  }
echo '
        </ul>
      </nav>
      <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
  ';

}

 ?>
