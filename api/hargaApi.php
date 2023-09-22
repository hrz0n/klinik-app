<?php
  require_once("../controller/config.php");
  require_once("../controller/configdb.php");
  require_once("../controller/hargaController.php");

  global $conn;

  if ( is_session_started() === FALSE ) session_start();

  if (!isset($_SESSION['login'])) {
    header("location:../login.php");
    exit();
  }
  $aksi = "";
  $id = 0;
  $nama = "";
  $idkategori = 0;
  $idHargaIdJenis = 0;
  $harga = 0;


  if (isset($_REQUEST['aksi'])) {
    $aksi = $_REQUEST['aksi'];
  }

  if (isset($_REQUEST['x_id'])) {
    $id = $_REQUEST['x_id'];
    $id = real_escape($conn, intval($id));
  }

  if (isset($_REQUEST['namapelayanan'])) {
    $nama = $_REQUEST['namapelayanan'];
    $nama = real_escape($conn, $nama);
  }
  if (isset($_REQUEST['jenislayanan'])) {
    $idHargaIdJenis = $_REQUEST['jenislayanan'];
    $idHargaIdJenis = real_escape($conn, intval($idHargaIdJenis));
  }

  if (isset($_REQUEST['harga'])) {
    $harga = $_REQUEST['harga'];
    $harga = real_escape($conn, $harga);
  }

  switch ($aksi) {
    case 'lihat':
      get_detail_harga($id);
      break;
    case 'update':
      edit_harga($id, $nama, $idHargaIdJenis, $harga);
      break;
    case 'hapus':
      $idarr = array($_REQUEST['x_id']);
      $inQuery = implode(',', $idarr);
      del_harga($inQuery);
      break;
    case 'simpan':
      simpan_harga(
        $nama, $idHargaIdJenis, $harga
      );
      break;
    default:
      get_harga_all();
      break;
  }

 ?>
