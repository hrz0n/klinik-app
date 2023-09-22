<?php
function get_all_users()
{
  require_once('config.php');
  require_once('configdb.php');
  require('ssp.class.b0x.php');
  global $conn;
  $table = 'user';
  $primaryKey = 'id';
  $columns = array(
    array('db' => 'id', 'dt' => 0),
    array('db' => 'username', 'dt' => 1),
    array('db' => 'akses',  'dt' => 2),
    array('db' => 'status',  'dt' => 3),
    array('db' => 'password',  'dt' => 4)
  );

  $sql_details = array(
    'user' => USER_DB,
    'pass' => PASSWORD_DB,
    'db'   => DATABASE,
    'host' => HOSTNAME,
    'port' => PORT_DB
  );

  echo json_encode(
    SSP::complex($_POST, $sql_details, $table, $primaryKey, $columns, null, null)
  );
}

function simpan_data($perangkatTanding, $username, $password, $akses, $status){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $createdAt = date('Y-m-d H:i:s');
  $updateddAt = date('Y-m-d H:i:s');
  $error = 0;
  $pesan = "";
  $password = ePassword($password);
  $sql = "INSERT INTO user (id, username, password, akses, created_at, updated_at, status)VALUE(
                              '$perangkatTanding','$username','$password','$akses','$createdAt','$updateddAt','$status')";
  if ($username == "" || $password == "") {
    $error = 1;
    $pesan = "Username atau Password tidak boleh kosong!";
  } else {

    if (cekUnik('user', 'id', 'username', $username)) {
      // data sudah ada
      $error = 1;
      $pesan = "Username sudah ada di Database!";
    } else {
      if ($result = query($conn, $sql)) {
        if (affected_rows($conn) > 0) {
          $error = 0;
          $pesan = "Data berhasil disimpan!";
        } else {
          $error = 1;
          $pesan = "Data gagal disimpan! Kemungkinan duplikat data!";
        }
      } else {
        $error = 1;
        $pesan = "Data gagal disimpan!";
      }
    }
  }

  $output = [
    'error' => $error,
    'pesan' => $pesan
  ];
  echo json_encode($output, JSON_PRETTY_PRINT);
}

function get_detail($id)
{
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  global $table;
  global $unik;
  $data = [];
  $data2 = [];
  $error = 0;
  $pesan = 0;
  $id = intval($id);
  $id = real_escape($conn, $id);
  $sql = "SELECT * FROM user ";
  $sql .= " WHERE id = " . $id;
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      while ($rows = fetch_assoc($r)) {
        $data[]  = $rows;
      }
      $pesan = "Berhasil menampilkan data!";
      $error = 0;
    } else {
      $pesan = "Data masih Kosong!";
      $error = 1;
    }
  } else {
    $pesan = "Error saat mengambil data!";
    $error = 1;
  }

  $output = [
    'error' => $error,
    'pesan' => $pesan,
    'data' => $data
  ];

  echo json_encode($output, JSON_PRETTY_PRINT);
}

function update_user($id, $username, $password, $akses, $status, $perangkatTanding)
{
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";
  $update_at = date('Y-m-d H:i:s');

  if ($akses == 0) {
    $perangkatTanding = 0;
  }

  if ($password == "") {
    $sql = "UPDATE user SET username = '$username', akses = '$akses', status = '$status', updated_at = '$update_at'";

  } else {
    $password = ePassword($password);
    $sql = "UPDATE user SET  username = '$username', password = '$password', akses = '$akses', status = '$status', updated_at = '$update_at'";
    
  }
  $sql .= ' WHERE id = ' . $id;


  // $tblName, $primary, $field, $unikNEW, $id = 0
  if (!cekUnik('user', 'username=\''.real_escape($conn, $username).'\'', 'id', $id)) {
      $error = 1;
      $pesan = "Username tidak boleh sama!";
  } else {
    if ($result = query($conn, $sql)) {
      if (affected_rows($conn) > 0) {
        $error = 0;
        $pesan = "Data berhasil disimpan!";
      } else {
        $error = 1;
        $pesan = "Data gagal as disimpan!";
      }
    } else {
      $error = 1;
      $pesan = "Data gagal disimpan!!";
    }
  }

  $output = [
    'error' => $error,
    'pesan' => $pesan
  ];
  echo json_encode($output, JSON_PRETTY_PRINT);
}


function del_data($id)
{
  require_once('config.php');
  require_once('configdb.php');
  global $conn;

  $error = 0;
  $pesan = "";
  $sql = "";
  $sql .= "DELETE FROM user WHERE id in ('$id')";

  if ($result = query($conn, $sql)) {

    if (affected_rows($conn) > 0) {
      $error = 0;
      $pesan = "Data berhasil dihapus!";
    } else {
      $error = 1;
      $pesan = "Data Gagal Dihapus";
    }
  }
  $output = [
    'error' => $error,
    'pesan' => $pesan
  ];

  echo json_encode($output, JSON_PRETTY_PRINT);
}

function get_user(){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];

  $sql = "SELECT * FROM `user`";
  $sql .= " ORDER BY `user`.`id` ASC";

  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      while ($rows = fetch_assoc($r)) {
        $data[] = $rows;
      }
    }
  }
  return $data;
}
