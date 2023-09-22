<?php

function get_pasien_all(){
  require_once('config.php');
  require( 'ssp.class.b0x.php' );
  $table = 'tbl_pasien';
  $primaryKey = 'idPasien';
  $columns = array(
      array( 'db' => 'idPasien', 'dt' => 0 ),
      array( 'db' => 'namaPasien', 'dt' => 1 ),
      array( 'db' => 'nrmPasien', 'dt' => 2 ),
      array( 'db' => 'jenisKelamin', 'dt' => 3),
      array( 'db' => 'noHp', 'dt' => 4),
      array( 'db' => 'namaOrtu', 'dt' => 5),
      array( 'db' => 'alamatPasien', 'dt' => 6),
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
function get_detail_pasien($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $data2 = [];
  $error = 0;
  $pesan = 0;
  $id = intval($id);
  $id = real_escape($conn, $id);
  $sql = 'SELECT * FROM tbl_pasien as tm';
  $sql .=' WHERE tm.idPasien='.$id.'';
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
function edit_pasien($xid, $nama, $nrm,$namaOrtu, $jk, $nohp, $alamat){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";
  $updateAt = date('Y-m-d H:i:s');
  $sql = "UPDATE tbl_pasien SET
          namaPasien = '$nama',
          nrmPasien = '$nrm',
          namaOrtu = '$namaOrtu',
          jenisKelamin = '$jk',
          noHp = '$nohp',
          alamatPasien = '$alamat',
          updated_at='$updateAt'";
  $sql .=' WHERE idPasien='.$xid;
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
function simpan_pasien($nama, $nrm,$namaOrtu, $jk, $nohp, $alamat){
    require_once('config.php');
    require_once('configdb.php');
    global $conn;
    $createdAt = date('Y-m-d H:i:s');
    $updateddAt = date('Y-m-d H:i:s');
    $error = 0;
    $pesan = "";
    $sql = "INSERT INTO tbl_pasien (namaPasien,nrmPasien,namaOrtu,jenisKelamin, noHp,alamatPasien,created_at, updated_at) VALUE(
              '$nama','$nrm', '$namaOrtu', '$jk', '$nohp', '$alamat', '$createdAt', '$updateddAt'
            )";
    if ($nama == "") {
      $error = 1;
      $pesan = "Nama Pasien tidak boleh kosong!";
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
function del_pasien($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";

  $sql ="DELETE FROM tbl_pasien WHERE idPasien in ($id)";
  if ($result = query($conn, $sql)) {

    $sqld = "DELETE FROM tbl_perawatan WHERE idPerawatanIdPasien in($id)";
    if ($r = query($conn, $sqld)) {
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
