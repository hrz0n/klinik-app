<?php

function get_jenis_all(){
  require_once('config.php');
  require( 'ssp.class.b0x.php' );
  $table = 'tbl_jenis_layanan';
  $primaryKey = 'tbl_jenis_layanan.idJenisLayanan';
  $columns = array(
      array( 'db' => 'tbl_jenis_layanan.idJenisLayanan', 'dt' => 0 ),
      array( 'db' => 'tbl_jenis_layanan.namaJenisLayanan', 'dt' => 1 ),
      array( 'db' => 'tbl_kategori.namaKategori',  'dt' => 2 ),
      array( 'db' => 'tbl_jenis_layanan.created_at',   'dt' => 3 ),
      array( 'db' => 'tbl_jenis_layanan.updated_at',     'dt' => 4 )
  );

  $sql_details = array(
      'user' => USER_DB,
      'pass' => PASSWORD_DB,
      'db'   => DATABASE,
      'host' => HOSTNAME,
      'port' => PORT_DB
  );

  $join = "
          INNER JOIN `tbl_kategori` on `tbl_kategori`.`idKategori` = `tbl_jenis_layanan`.`idJenisIdKategori`
          ";

  echo json_encode(
      SSP::complexJoin( $_POST, $sql_details, $table, $primaryKey, $columns, null,null, $join)
  );

}
function get_detail_jenis($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $data2 = [];
  $error = 0;
  $pesan = 0;
  $id = intval($id);
  $id = real_escape($conn, $id);
  $sql = 'SELECT * FROM tbl_jenis_layanan as tm';
  $sql .=' WHERE tm.idJenisLayanan='.$id.'';
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
function edit_jenis($xid, $idkategori, $nama){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";
  $updateAt = date('Y-m-d H:i:s');
  $sql = "UPDATE tbl_jenis_layanan SET idJenisIdKategori = '$idkategori', namaJenisLayanan = '$nama', updated_at='$updateAt'";
  $sql .=' WHERE idJenisLayanan='.$xid;
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
function simpan_jenis($idkat, $namaKat){
    require_once('config.php');
    require_once('configdb.php');
    global $conn;
    $createdAt = date('Y-m-d H:i:s');
    $updateddAt = date('Y-m-d H:i:s');
    $error = 0;
    $pesan = "";
    $sql = "INSERT INTO tbl_jenis_layanan (idJenisIdKategori, namaJenisLayanan, created_at, updated_at) VALUE(
              '$idkat', '$namaKat','$createdAt','$updateddAt'
            )";
    if ($namaKat == "" || $idkat == 0) {
      $error = 1;
      $pesan = "Nama Jenis dan kategori Layanan tidak boleh kosong!";
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
function del_jenis($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";

  $sql ="DELETE FROM tbl_jenis_layanan WHERE idJenisLayanan in ($id)";
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

function daftarJenis(){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;

  $data = [];
  $updateAt = date('Y-m-d H:i:s');
  $sql = "SELECT * FROM tbl_jenis_layanan";
  $sql .=' ORDER by namaJenisLayanan ASC';
    if ($result = query($conn, $sql)) {
      if (rows($result) > 0) {
        while ($r = fetch_assoc($result)) {
          $data[] = $r;
        }
      }
    }

    return $data;
}
