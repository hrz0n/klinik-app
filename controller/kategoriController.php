<?php

function get_kategori_all(){
  require_once('config.php');
  require( 'ssp.class.b0x.php' );
  $table = 'tbl_kategori';
  $primaryKey = 'idKategori';
  $columns = array(
      array( 'db' => 'idKategori', 'dt' => 0 ),
      array( 'db' => 'namaKategori', 'dt' => 1 ),
      array( 'db' => 'created_at', 'dt' => 2),
      array( 'db' => 'updated_at',  'dt' => 3),
  );

  $sql_details = array(
      'user' => USER_DB,
      'pass' => PASSWORD_DB,
      'db'   => DATABASE,
      'host' => HOSTNAME,
      'port' => PORT_DB
  );

  echo json_encode(
      SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null,null)
  );

}
function get_detail_kategori($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $data2 = [];
  $error = 0;
  $pesan = 0;
  $id = intval($id);
  $id = real_escape($conn, $id);
  $sql = 'SELECT * FROM tbl_kategori as tm';
  $sql .=' WHERE tm.idKategori='.$id.'';
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
function edit_kategori($xid, $nama){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";
  $updateAt = date('Y-m-d H:i:s');
  $sql = "UPDATE tbl_kategori SET namaKategori = '$nama', updated_at='$updateAt'";
  $sql .=' WHERE idKategori='.$xid;
    if ($result = query($conn, $sql)) {
      if (affected_rows($conn) > 0) {
        $error = 0;
        $pesan = "Data berhasil disimpan!";
      } else {
        $error = 1;
        $pesan = "Data gagal as disimpan!";
      }
    }else{
      $error = 1;
      $pesan = "Data gagal disimpan!!";
    }
  $output = [
    'error' => $error,
    'pesan' => $pesan
  ];
  echo json_encode($output, JSON_PRETTY_PRINT);

}
function simpan_kategori($namaKat){
    require_once('config.php');
    require_once('configdb.php');
    global $conn;
    $createdAt = date('Y-m-d H:i:s');
    $updateddAt = date('Y-m-d H:i:s');
    $error = 0;
    $pesan = "";
    $sql = "INSERT INTO tbl_kategori (namaKategori,created_at, updated_at) VALUE(
              '$namaKat','$createdAt','$updateddAt'
            )";
    if ($namaKat == "") {
      $error = 1;
      $pesan = "Nama Kategori tidak boleh kosong!";
    } else {
      if ($result = query($conn, $sql)) {
        if (affected_rows($conn) > 0) {
          $error = 0;
          $pesan = "Data berhasil disimpan!";
        } else {
          $error = 1;
          $pesan = "Data gagal disimpan! Kemungkinan duplikat data!";
        }
      }else{
        $error = 1;
        $pesan = "Data gagal disimpan!";
      }
    }

    $output = [
      'error' => $error,
      'pesan' => $pesan
    ];
    echo json_encode($output, JSON_PRETTY_PRINT);

}
function del_kategori($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";

  $sql ="DELETE FROM tbl_kategori WHERE idKategori in ($id)";
  if ($result = query($conn, $sql)) {
    if (affected_rows($conn) > 0) {
      $error = 0;
      $pesan = "Data berhasil dihapus!";
    }
  }else{
    $error = 1;
    $pesan = "Data gagal dihapus!";
  }

  $output = [
    'error' => $error,
    'pesan' => $pesan
  ];
  echo json_encode($output, JSON_PRETTY_PRINT);

}

function daftarKategori(){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;

  $data = [];
  $updateAt = date('Y-m-d H:i:s');
  $sql = "SELECT * FROM tbl_kategori";
  $sql .=' ORDER by idKategori ASC';
    if ($result = query($conn, $sql)) {
      if (rows($result) > 0) {
        while ($r = fetch_assoc($result)) {
          $data[] = $r;
        }
      }
    }

    return $data;
}
