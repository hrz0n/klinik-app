<?php
  require_once('../controller/config.php');
  require_once('../controller/configdb.php');
  require_once('../controller/usersController.php');
  global $conn;

  $username = "";
  $password = "";
  $akses = "";
  $created_at = date('Y-m-d H:i:s');
  $updated_at = date('Y-m-d H:i:s');
  $status = "AKTIF";
  $aksi = "";
  $perangkatTanding = 0;
  $id = 0;

  if (isset($_REQUEST['aksi'])) {
    $aksi = $_REQUEST['aksi'];

    if (isset($_REQUEST['x_id'])) {
      $id = $_REQUEST['x_id'];
      $id = intval($id);
    }
    if (isset($_POST['username'])) {
      $username = real_escape($conn, $_POST['username']);
    }
    if (isset($_POST['perangkatTanding'])) {
      $perangkatTanding = real_escape($conn, $_POST['perangkatTanding']);

    }
    if (isset($_POST['password'])) {
      $password = real_escape($conn, $_POST['password']);
    }
    if (isset($_POST['akses'])) {
      $akses = real_escape($conn, $_POST['akses']);
    }
    if (isset($_POST['status'])) {
      $status = $_POST['status'];
    }

  }

  switch ($aksi) {
    case 'login' :
      doLogin($username, $password);
    break;

    case 'simpan' :
      echo simpan_data(
        0,
        $username,
        $password,
        $akses,
        $status
      );
    break;
    case 'lihat':
      get_detail($id);
    break;
    case 'update':
      update_user($id, $username, $password, $akses, $status,0);
    break;
    case 'hapus' :
      $id = array($_REQUEST['x_id']);
      $inQuery = implode(',', $id);
      del_data($inQuery);

    break;
    default:
      get_all_users();
      break;
  }
