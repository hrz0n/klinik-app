<?php

function get_harga_all(){
  require_once('config.php');
  require( 'ssp.class.b0x.php' );
  $table = 'tbl_harga';
  $primaryKey = 'tbl_harga.idHarga';
  $columns = array(
      array( 'db' => 'tbl_harga.idHarga', 'dt' => 0 ),
      array( 'db' => 'tbl_harga.namaPelayanan', 'dt' => 1 ),
      array( 'db' => 'tbl_jenis_layanan.namaJenisLayanan',  'dt' => 2 ),
      array( 'db' => 'tbl_harga.harga',  'dt' => 3 ),
  );

  $sql_details = array(
      'user' => USER_DB,
      'pass' => PASSWORD_DB,
      'db'   => DATABASE,
      'host' => HOSTNAME,
      'port' => PORT_DB
  );

  $join = "
          INNER JOIN `tbl_jenis_layanan` on `tbl_jenis_layanan`.`idJenisLayanan` = `tbl_harga`.`idHargaIdJenis`
          ";

  echo json_encode(
      SSP::complexJoin( $_POST, $sql_details, $table, $primaryKey, $columns, null,null, $join)
  );

}
function get_detail_harga($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $data2 = [];
  $error = 0;
  $pesan = 0;
  $id = intval($id);
  $id = real_escape($conn, $id);
  $sql = 'SELECT * FROM tbl_harga as tm';
  $sql .=' WHERE tm.idHarga='.$id.'';
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
function edit_harga($xid, $nama, $idHargaIdJenis, $harga){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";
  $updateAt = date('Y-m-d H:i:s');
  $sql = "UPDATE tbl_harga SET namaPelayanan = '$nama', idHargaIdJenis='$idHargaIdJenis', harga = '$harga', updated_at='$updateAt'";
  $sql .=' WHERE idHarga='.$xid;
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
function simpan_harga($nama, $idHargaIdJenis, $harga){
    require_once('config.php');
    require_once('configdb.php');
    global $conn;
    $createdAt = date('Y-m-d H:i:s');
    $updateddAt = date('Y-m-d H:i:s');
    $error = 0;
    $pesan = "";
    $sql = "INSERT INTO tbl_harga (namaPelayanan, idHargaIdJenis, harga, created_at, updated_at) VALUE(
              '$nama', '$idHargaIdJenis', '$harga','$createdAt','$updateddAt'
            )";
    if ($nama == "" || $harga == 0 || $idHargaIdJenis == 0) {
      $error = 1;
      $pesan = "Nama Jenis kategori Layanan dan harga tidak boleh kosong!";
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
function del_harga($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";

  $sql ="DELETE FROM tbl_harga WHERE idHarga in ($id)";
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
