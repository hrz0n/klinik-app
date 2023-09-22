<?php

function get_perawat_all(){
  require_once('config.php');
  require( 'ssp.class.b0x.php' );
  $table = 'tbl_perawat';
  $primaryKey = 'idPerawat';
  $columns = array(
      array( 'db' => 'idPerawat', 'dt' => 0 ),
      array( 'db' => 'namaPerawat', 'dt' => 1 ),
      array( 'db' => 'isDokter', 'dt' => 2 ),
      array( 'db' => 'alamat', 'dt' => 3),
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
function get_detail_perawat($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $data2 = [];
  $error = 0;
  $pesan = 0;
  $id = intval($id);
  $id = real_escape($conn, $id);
  $sql = 'SELECT * FROM tbl_perawat as tm';
  $sql .=' WHERE tm.idPerawat='.$id.'';
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
function edit_perawat($xid, $nama, $alamat, $isdokter){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";
  $updateAt = date('Y-m-d H:i:s');
  $sql = "UPDATE tbl_perawat SET
          namaPerawat = '$nama',
          alamat = '$alamat',
          isDokter = '$isdokter', updated_at='$updateAt'";
  $sql .=' WHERE idPerawat='.$xid.'';

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
function simpan_perawat($nama, $alamat, $isdokter){
    require_once('config.php');
    require_once('configdb.php');
    global $conn;
    $createdAt = date('Y-m-d H:i:s');
    $updateddAt = date('Y-m-d H:i:s');
    $error = 0;
    $pesan = "";
    $sql = "INSERT INTO tbl_perawat (namaPerawat,alamat,isDokter,created_at, updated_at) VALUE(
              '$nama','$alamat', '$isdokter', '$createdAt', '$updateddAt'
            )";
    if ($nama == "") {
      $error = 1;
      $pesan = "Nama Dokter tidak boleh kosong!";
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
function del_perawat($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";

  $sql ="DELETE FROM tbl_perawat WHERE idPerawat in ($id)";
  if ($result = query($conn, $sql)) {
    $error = 0;
    $pesan = "Data berhasil dihapus!";
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
