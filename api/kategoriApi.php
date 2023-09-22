<?php
  require_once("../controller/config.php");
  require_once("../controller/configdb.php");
  require_once("../controller/kategoriController.php");

  global $conn;

  if ( is_session_started() === FALSE ) session_start();

  if (!isset($_SESSION['login'])) {
    header("location:../login.php");
    exit();
  }
  $aksi = "";
  $id = 0;
  $namakategori = "";


  if (isset($_REQUEST['aksi'])) {
    $aksi = $_REQUEST['aksi'];
  }

  if (isset($_REQUEST['x_id'])) {
    $id = $_REQUEST['x_id'];
    $id = real_escape($conn, intval($id));
  }
  if (isset($_REQUEST['namakategori'])) {
    $namakategori = $_REQUEST['namakategori'];
    $namakategori = real_escape($conn, $namakategori);
  }

  switch ($aksi) {
    case 'lihat':
      get_detail_kategori($id);
      break;
    case 'update':
      edit_kategori($id, $namakategori);
      break;
    case 'hapus':
      $idarr = array($_REQUEST['x_id']);
      $inQuery = implode(',', $idarr);
      del_kategori($inQuery);
      break;
    case 'simpan':
      simpan_kategori(
        $namakategori
      );
      break;
    default:
      get_kategori_all();
      break;
  }

 ?>
