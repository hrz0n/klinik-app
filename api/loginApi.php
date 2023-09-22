<?php
  require_once('../controller/config.php');
  require_once('../controller/configdb.php');
  require_once('../controller/loginController.php');
  global $conn;

  $username = "";
  $aksi = "";
  $password = "";

  if (isset($_REQUEST['aksi'])) {
    $aksi = $_REQUEST['aksi'];
  }

  if (isset($_POST['uname'])) {
    $username = real_escape($conn, $_POST['uname']);
  }
  if (isset($_POST['pwd'])) {
    $password = real_escape($conn, $_POST['pwd']);
  }

  switch ($aksi) {
    case 'login' :
      doLogin($username, $password);
      break;
  }
