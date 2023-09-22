<?php

function doLogin($uname, $pwd){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;

  $data = [];
  $error = 0;
  $pesan = "";
  $pwd = ePassword($pwd);

  $sql = 'SELECT * FROM user WHERE username = "'.$uname.'" AND status = "AKTIF" LIMIT 1';
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      $rows = fetch_assoc($r);
      if ($pwd === $rows['password']) {
        $_SESSION['login'] = true;
        $_SESSION['userid'] = $rows['id'];
        $_SESSION['tipeuser'] = $rows['akses'];
        $_SESSION['username'] = $rows['username'];
        $error = 0;
        $pesan = "Login berhasil";

      } else {
        $error = 1;
        $pesan = "Pssword Salah";
      }
    } else {
      $error = 1;
      $pesan = "Username Salah";
    }
  } else {
    $error = 1;
    $pesan = "Oupppssss.... Tidak konek ke database";
  }

  $output = [
          'error' => $error,
          'pesan' => $pesan,
          'data' => $data
  ];

   echo json_encode($output, JSON_PRETTY_PRINT);
}

function doLogout(){
  session_start();
  $helper = array_keys($_SESSION);
  foreach ($helper as $key){
      unset($_SESSION[$key]);
  }
  session_destroy();
  header('location:login.php');
}
//
// function dLevel(){
//   $data = "";
//   if (isset($_SESSION['login'])) {
//     $tipeUser = $_SESSION['tipeuser'];
//     if ($tipeUser == 0){
//       // guru
//       $data = "guru";
//     } elseif ($tipeUser == 1) {
//       // piket
//       $data = "wkelas";
//     } elseif ($tipeUser == 2) {
//       // walikelas
//       $data = "piket";
//     } elseif ($tipeUser == 3) {
//       // waka
//       $data = "waka";
//     } elseif ($tipeUser == 4) {
//       // kepsek
//       $data = "kepsek";
//     } elseif ($tipeUser == 5) {
//       // kepsek
//       $data = "admin";
//     }
//
//   }
//   return $data;
// }

function getDataLog($str){
  require_once('configdb.php');
  global $conn;
  $sql = 'SELECT * FROM user WHERE username ="'.$uname.'" LIMIT 1';
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      $data = fetch_array($r);
      if (password_verify($pwd, $data['password_hash'])) {
        $_SESSION['login'] = true;
        $_SESSION['userid'] = $data['id'];
        $_SESSION['tipeuser'] = $data['tipeuser'];
        $_SESSION['username'] = $data['username'];
        header("Location:index.php");
        return true;
      }
    }
  }
  return false;
}

 ?>
