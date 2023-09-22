<?php

if ( is_session_started() === FALSE ) session_start();

require_once 'config.php';
$conn = mysqli_connect(HOSTNAME,USER_DB,PASSWORD_DB,DATABASE,PORT_DB);

date_default_timezone_set(getZonaWaktu());

if (mysqli_connect_errno()) {
   die("Ouppsss .... Database Connection Failed!");
}

function closeConnection($conn){
	return mysqli_close($conn);
}

function getZonaWaktu(){
    global $conn;
    $sql = 'SELECT value_config FROM tbl_config WHERE nama_config="timeZone"';
    if ($r = query($conn,$sql)) {
        while ($m = fetch_array($r)) {
        $timeZone = $m['value_config'];
        }
        return $timeZone;
    }
}

function real_escape($conn, $data) {
  $data = stripslashes($data);
	return mysqli_real_escape_string($conn, $data);
}

function affected_rows($conn) {
	return mysqli_affected_rows($conn);
}

function insert_id($conn, $tbl= '', $col= ''){
    if ($r = mysqli_query($conn,'SELECT LAST_INSERT_ID() FROM '.$tbl.'')) {
        if ($m = mysqli_fetch_row($r)) {
            return $m[0];
        }
    }
    return 0;
}

function query($conn, $str){
	return mysqli_query($conn, $str);
}

function fetch_array($result) {
  return mysqli_fetch_array($result);
}
function fetch_assoc($result) {
  return mysqli_fetch_assoc($result);
}

function rows($result){
  return mysqli_num_rows($result);
}

function count_rows($dbtable, $where = '') {
  $numofrows = 0;
  global $conn;
  $sql = 'SELECT COUNT(*) AS numrows FROM '.$dbtable.' '.$where.'';
  if ($r = query($conn,$sql)) {
      if ($m = fetch_array($r)) {
          $numofrows = $m['numrows'];
      }
  } else {
      // error
  }
  return($numofrows);
}

function getConfig($param){
    global $conn;
    $param = real_escape($conn, $param);
    $data = "";
    $sql = 'SELECT value_config FROM tbl_config WHERE nama_config="'.$param.'"';
    if ($r = query($conn, $sql)) {
        while ($m = fetch_array($r)) {
          $data = $m['value_config'];
        }
    }
    return $data;
}

function getTokenTele($kelas){
    global $conn;
    $kelas = real_escape($conn, $kelas);
    $sql = 'SELECT tokenTele FROM kelas WHERE kelas="'.$kelas.'" LIMIT 1';
    if ($r = query($conn, $sql)) {
        while ($m = fetch_array($r)) {
          $data = $m['tokenTele'];
        }
        return $data;
    }
}

function getHari(){
  date_default_timezone_set(getConfig('timeZone'));
	$hari = date("D");
	switch($hari){
		case 'Sun':
			$hari_ini = "Minggu";
		break;

		case 'Mon':
			$hari_ini = "Senin";
		break;

		case 'Tue':
			$hari_ini = "Selasa";
		break;

		case 'Wed':
			$hari_ini = "Rabu";
		break;

		case 'Thu':
			$hari_ini = "Kamis";
		break;

		case 'Fri':
			$hari_ini = "Jum\'at";
		break;

		case 'Sat':
			$hari_ini = "Sabtu";
		break;

		default:
			$hari_ini = "Tidak di ketahui";
		break;
	}

	return $hari_ini;

}

function daftarhari(){
  $hari = [
    'Senin',
    'Selasa',
    'Rabu',
    'Kamis',
    'Jum\'at',
    'Sabtu',
    'Minggu'
  ];
  return $hari;
}

function acakUsername($strength=16){
  $input = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
  $input_length = strlen($input);
  $random_string = '';
  for($i = 0; $i < $strength; $i++) {
    $random_character = $input[mt_rand(0, $input_length - 1)];
  }
  return $random_string;
}

function ePassword($d){
    $x=base64_encode($d);
    $y = base64_encode($x);
    $r0 = '$';
    $r3 = '*';
    $r1 = ['a','i','u','e','o','=','0','W','U','A','I','E','T'];
    $r2 = [':','(','@','#','&','?','^','|',']','[','{','}','.'];
    $z = str_replace($r1,$r2,$y);
    return $r0.$z.$r3;
  }

  function dPassword($d){
    $r1 = ['a','i','u','e','o','=','0','W','U','A','I','E','T'];
    $r2 = [':','(','@','#','&','?','^','|',']','[','{','}','.'];
    $d = str_replace($r2,$r1,$d);
    $x = base64_decode($d);
    $y = base64_decode($x);
    return $y;
  }

  function is_session_started(){
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
  }

  function hakAkses($param){
    // 1 admin -- hal admin
    // 2 keuangan -- hal admin
    // 3 kaprodi -- hal admin
    // 4 dosen -- hal dosen
    // 5 mhs -- hal mhs
    // 6 cmhs -- hal mhs
    // 10 bank -- hal bank

    // BELUM CLEAR
    if ( is_session_started() === FALSE ) session_start();
    switch ($param) {
      case 'admin':
        if ($_SESSION['login']) {
          if ($_SESSION['level'] > 0) {
            return true;
          }
        }
        header('Location:login.php');
        break;
      case 'tu':
        if ($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
          return true;
        } else {
          header('Location:../index.php');
          exit;
        }
      case 'kaprod':
        if ($_SESSION['level'] == 1 || $_SESSION['level'] == 3) {
          return true;
        } else {
          header('Location:../index.php');
          exit;
        }

        break;

      default:
        // code...
        break;
    }
    // code...
  }

  function cekUnik($tblName, $primary, $field, $unikNEW, $id = 0){
    // user, username, admin
    require_once('config.php');
    require_once('configdb.php');
    global $conn;

    $unikOLD = "";
    if ($id > 0) {
      // update
      $sqla = 'SELECT * FROM '.$tblName.' WHERE '.$primary.' ='.$id.' ';
      if ($a = query($conn, $sqla)) {
        if (rows($a) > 0) {
          $n = fetch_assoc($a);
          $unikOLD = $n[$field]; // admin
          if ($unikOLD == $unikNEW) {
            return 1;
          }
        }
      }
      return 0;

    } else {
      // add
      $sql = 'SELECT * FROM '.$tblName.' WHERE '.$field.' = "'.$unikNEW.'"';
      if ($r = query($conn, $sql)) {
        if (rows($r) > 0) {
          return true;
        }
      }
      return false;
    }

  }




?>
