<?php
  require_once("../controller/config.php");
  require_once("../controller/configdb.php");
  require_once("../controller/perawatController.php");

  global $conn;

  if ( is_session_started() === FALSE ) session_start();

  if (!isset($_SESSION['login'])) {
    header("location:../login.php");
    exit();
  }
  $aksi = "";
  $id = 0;
  $nama = "";
  $nik = "";
  $jk = "L";
  $nohp = "";
  $alamat = "";
  $isdokter = 0;


  if (isset($_REQUEST['aksi'])) {
    $aksi = $_REQUEST['aksi'];
  }

  if (isset($_REQUEST['x_id'])) {
    $id = $_REQUEST['x_id'];
    $id = real_escape($conn, intval($id));
  }
  if (isset($_REQUEST['namaperawat'])) {
    $nama = $_REQUEST['namaperawat'];
    $nama = real_escape($conn, $nama);
  }
  if (isset($_REQUEST['alamat'])) {
    $alamat = $_REQUEST['alamat'];
    $alamat = real_escape($conn, $alamat);
  }
  if (isset($_REQUEST['isdokter'])) {
    $isdokter = $_REQUEST['isdokter'];
    $isdokter = real_escape($conn, intval($isdokter));
  }


  switch ($aksi) {
    case 'lihat':
        get_detail_perawat($id);
      break;
    case 'update':
        edit_perawat($id, $nama, $alamat, $isdokter);
      break;
    case 'hapus':
      $idarr = array($_REQUEST['x_id']);
      $inQuery = implode(',', $idarr);
      del_perawat($inQuery);
      break;
    case 'simpan':
        simpan_perawat($nama, $alamat, $isdokter);
      break;
    default:
      get_perawat_all();
      break;
  }

 ?>
