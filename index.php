
  <?php
  require_once('controller/config.php');
  require_once('controller/configdb.php');
  // require_once('controller/homeController.php');
  global $conn;

  if (!isset($_SESSION['login'])) {
    header("location:login.php");
    exit();
  }
  $level = $_SESSION['tipeuser'];
  $title = 'Home';
  require('header.php');
  include('menu.php');

  ?>
      <main class="main">
        <div class="mt-3 container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="mb-2 col-md-12">
                <h5>Selamat datang di MIS-K (Medical Information System) - Klinik</h5>
                <p>Sistim Informasi pelayanan Klinik berbasis Web dan Android</p>
              </div>
              <div class="col-md-12">
                <b>Fitur-fitur Aplikasi :</b>
                <ol>
                  <li>Daftar Transaksi Penjualan</li>
                  <li>Tambah Transaksi Penjualan</li>
                  <li>Cetak Transaksi Penjualan per Pasien</li>
                  <li>Daftar kategori Pelayanan</li>
                  <li>Daftar Jenis Pelayanan</li>
                  <li>Daftar Harga Pelayanan</li>
                  <li>Daftar Biodata pasien</li>
                  <li>Daftar Data Perawat/Dokter</li>
                  <li>Daftar Pengguna</li>
                  <li>Pengaturan Aplikai</li>
                  <li>Cetak Rekap Transaksi</li>
                </ol>
              </div>
            </div>

          </div>
        </div>
      </main>

<?php
  require('footer.php');
 ?>
