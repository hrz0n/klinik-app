<?php
  require_once("../controller/config.php");
  require_once("../controller/configdb.php");
  require_once("../controller/pasienController.php");

  global $conn;

  if ( is_session_started() === FALSE ) session_start();

  if (!isset($_SESSION['login'])) {
    header("location:../login.php");
    exit();
  }
  $aksi = "";
  $id = 0;
  $nama = "";
  $nrm = "";
  $jk = "L";
  $nohp = "";
  $alamat = "";
  $namaOrtu = "";


  if (isset($_REQUEST['aksi'])) {
    $aksi = $_REQUEST['aksi'];
  }

  if (isset($_REQUEST['x_id'])) {
    $id = $_REQUEST['x_id'];
    $id = real_escape($conn, intval($id));
  }
  if (isset($_REQUEST['namaortu'])) {
    $namaOrtu = $_REQUEST['namaortu'];
    $namaOrtu = real_escape($conn, $namaOrtu);
  }
  if (isset($_REQUEST['namapasien'])) {
    $nama = $_REQUEST['namapasien'];
    $nama = real_escape($conn, $nama);
  }
  if (isset($_REQUEST['nrm'])) {
    $nrm = $_REQUEST['nrm'];
    $nrm = real_escape($conn, $nrm);
  }
  if (isset($_REQUEST['jk'])) {
    $jk = $_REQUEST['jk'];
    $jk = real_escape($conn, $jk);
  }
  if (isset($_REQUEST['nohp'])) {
    $nohp = $_REQUEST['nohp'];
    $nohp = real_escape($conn, $nohp);
  }
  if (isset($_REQUEST['alamat'])) {
    $alamat = $_REQUEST['alamat'];
    $alamat = real_escape($conn, $alamat);
  }

  switch ($aksi) {
    case 'lihat':
      get_detail_pasien($id);
      break;
    case 'update':
        edit_pasien($id, $nama, $nrm, $namaOrtu,$jk, $nohp, $alamat);
      break;
    case 'hapus':
      $idarr = array($_REQUEST['x_id']);
      $inQuery = implode(',', $idarr);
      del_pasien($inQuery);
      break;
    case 'simpan':
        simpan_pasien($nama, $nrm, $namaOrtu,$jk, $nohp, $alamat);
      break;
    default:
      get_pasien_all();
      break;
  }

 ?>
