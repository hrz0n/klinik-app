<?php
  require_once("../controller/config.php");
  require_once("../controller/configdb.php");
  require_once("../controller/jenisController.php");

  global $conn;

  if ( is_session_started() === FALSE ) session_start();

  if (!isset($_SESSION['login'])) {
    header("location:../login.php");
    exit();
  }
  $aksi = "";
  $id = 0;
  $namaJenis = "";
  $idkategori = 0;


  if (isset($_REQUEST['aksi'])) {
    $aksi = $_REQUEST['aksi'];
  }

  if (isset($_REQUEST['x_id'])) {
    $id = $_REQUEST['x_id'];
    $id = real_escape($conn, intval($id));
  }

  if (isset($_REQUEST['namakategori'])) {
    $idkategori = $_REQUEST['namakategori'];
    $idkategori = real_escape($conn, intval($idkategori));
  }

  if (isset($_REQUEST['namajenislayanan'])) {
    $namaJenis = $_REQUEST['namajenislayanan'];
    $namaJenis = real_escape($conn, $namaJenis);
  }

  switch ($aksi) {
    case 'lihat':
      get_detail_jenis($id);
      break;
    case 'update':
      edit_jenis($id, $idkategori, $namaJenis);
      break;
    case 'hapus':
      $idarr = array($_REQUEST['x_id']);
      $inQuery = implode(',', $idarr);
      del_jenis($inQuery);
      break;
    case 'simpan':
      simpan_jenis(
        $idkategori, $namaJenis
      );
      break;
    default:
      get_jenis_all();
      break;
  }

 ?>
