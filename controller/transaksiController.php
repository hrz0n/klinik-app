<?php
function get_all_transaksi(){
  error_reporting(0);
  require_once('config.php');
  require_once('configdb.php');
  require( 'ssp.class.b0x.group.php' );
  $table = 'tbl_perawatan';
  $primaryKey = 'noFaktur';
  $columns = array(
      array( 'db' => '`tp`.`noFaktur`',       'dt' => 0, 'field' => 'noFaktur' ),
      array( 'db' => '`tp`.`noFaktur`', 'dt' => 1, 'field' => 'noFaktur' ),
      array( 'db' => '`tps`.`namaPasien`',  'dt' => 2 , 'field' => 'namaPasien' ),
      array( 'db' => '`tps`.`nrmPasien`',   'dt' => 3 , 'field' => 'nrmPasien' ),
      // array( 'db' => '`tp`.`created_at`',   'dt' => 4 , 'field' => 'created_at' ),
      array( 'db' => '`tp`.`created_at`',   'dt' => 4 , 'field' => 'created_at' , 'formatter' => function( $d, $row ) {
            if (!$d =="") {
              $tgl = date('d F Y', strtotime($d));
              $link = $tgl;
            } else {
                $link = ' - ';
            }
            return $link;
            })
  );

  $joinQuery = " FROM `tbl_perawatan` AS `tp` INNER JOIN `tbl_pasien` AS `tps` ON (`tp`.`idPerawatanIdPasien` = `tps`.`idPasien`)";
  $groupBy = "`tp`.`noFaktur`";

  $sql_details = array(
      'user' => USER_DB,
      'pass' => PASSWORD_DB,
      'db'   => DATABASE,
      'host' => HOSTNAME,
      'port' => PORT_DB
  );


  echo json_encode(
       SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, null, $groupBy)
     );
}

function simpan_data_transaksi(
        $idPerawatanIdDokter,
        $idPerawatanIdPasien,
        $namaItem,
        $hargaSatuan,
        $qty,
        $hargaTotal,
        $noFaktur,
        $tglMasuk,
        $tglKeluar,
        $keterangan,
        $created_at,
        $updated_at){
    require_once('config.php');
    require_once('configdb.php');
    global $conn;
    $data = [];
    $error = 0;
    $pesan = "";

    for ($i = 0; $i < count($hargaSatuan); $i++) {
      if(($idPerawatanIdPasien[0] != "")){
        $sql = "INSERT INTO tbl_perawatan (
          idPerawatanIdDokter,
          idPerawatanIdPasien,
          namaItem,
          hargaSatuan,
          qty,
          hargaTotal,
          noFaktur,
          tglMasuk,
          tglKeluar,
          keterangan,
          created_at,
          updated_at)
                VALUES(
                  '$idPerawatanIdDokter[$i]',
                  '$idPerawatanIdPasien[$i]',
                  '$namaItem[$i]',
                  '$hargaSatuan[$i]',
                  '$qty[$i]',
                  '$hargaTotal[$i]',
                  '$noFaktur[$i]',
                  '$tglMasuk',
                  '$tglKeluar',
                  '$keterangan',
                  '$created_at',
                  '$updated_at'
                )";

        if ($result = query($conn, $sql)) {
          if (affected_rows($conn) > 0) {
            $error = 0;
            $pesan = "Data berhasil disimpan!";
          }
        }else{
          $error = 1;
          $pesan = "Data gagal disimpan!";
        }
      } else {
        $error = 1;
        $pesan = "Pasien tidak boleh kosong!";
      }
  }


    $output = [
      'error' => $error,
      'pesan' => $pesan
    ];
    echo json_encode($output, JSON_PRETTY_PRINT);
}

function simpan_data_pasien(
  $namaPasien,
  $nikPasien,
  $namaOrtu,
  $jenisKelamin,
  $noHp,
  $alamatPasien,
  $created_at,
  $updated_at){
    require_once('config.php');
    require_once('configdb.php');
    global $conn;
    $data = 0;
    $error = 0;
    $pesan = "";

    $sql = "INSERT INTO tbl_pasien (
      namaPasien,
      nrmPasien,
      namaOrtu,
      jenisKelamin,
      noHp,
      alamatPasien,
      created_at,
      updated_at)
            VALUES(
              '$namaPasien',
              '$nikPasien',
              '$namaOrtu',
              '$jenisKelamin',
              '$noHp',
              '$alamatPasien',
              '$created_at',
              '$updated_at'
            )";
    if($namaPasien == ""){
      $error = 1;
      $pesan = "Nama Tidak boleh kosong!";
      $data = 0;
    } else {
      if ($result = query($conn, $sql)) {
        $data = mysqli_insert_id($conn);
        if (affected_rows($conn) > 0) {
          $error = 0;
          $pesan = "Data berhasil disimpan!";
        }
      }else{
        $error = 1;
        $pesan = "Data gagal disimpan!";
        $data = 0;
      }
    }
    $output = [
      'error' => $error,
      'pesan' => $pesan,
      'data' => $data
    ];
    echo json_encode($output, JSON_PRETTY_PRINT);
}

function get_detail_transaksi($nomorF){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;

  $data = [];
  $error = 0;
  $pesan = 0;

  $sql = "SELECT * FROM tbl_perawatan as tp
           INNER JOIN tbl_pasien as tps ON tp.idPerawatanIdPasien = tps.idPasien";
  $sql .=' WHERE tp.noFaktur="'.$nomorF.'"';
  $sql .=' ORDER BY tp.namaItem ASC';
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
function get_transaksi_kategori(){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $error = 1;
  $pesan ="";
  $sql = 'SELECT idKategori, namaKategori FROM tbl_kategori';
  $sql .= ' ORDER BY namaKategori ASC';
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      while ($rows = fetch_assoc($r)) {
        $data[] = $rows;
      }
      $error = 0;
      $pesan = "Sukses menampilkan data";

    } else {
      $error = 1;
      $pesan = "Data Masih Kosong";
    }
  } else {
    $error = 1;
    $pesan = "Data Gagal Dihapus";
  }
  $output = [
    'error' => $error,
    'pesan' => $pesan,
    'data' => $data
  ];
  echo json_encode($output, JSON_PRETTY_PRINT);
}
function get_transaksi_jenis($idkat){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $error = 1;
  $pesan ="";

  $sql = 'SELECT idJenisLayanan, namaJenisLayanan FROM tbl_jenis_layanan';
  $sql .= ' WHERE idJenisIdKategori='.$idkat.'';
  $sql .= ' ORDER BY namaJenisLayanan ASC';
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      while ($rows = fetch_assoc($r)) {
        $data[] = $rows;
      }
      $error = 0;
      $pesan = "Sukses menampilkan data";

    } else {
      $error = 1;
      $pesan = "Data Masih Kosong";
    }
  } else {
    $error = 1;
    $pesan = "Data Gagal ditampilkan";
  }
  $output = [
    'error' => $error,
    'pesan' => $pesan,
    'data' => $data
  ];
  echo json_encode($output, JSON_PRETTY_PRINT);
}
function get_transaksi_item($idjenis){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $error = 1;
  $pesan ="";

  $sql = 'SELECT th.idHarga, th.namaPelayanan, th.harga FROM tbl_harga as th
          INNER JOIN tbl_jenis_layanan as tjl ON tjl.idJenisLayanan = th.idHargaIdJenis';
  $sql .= ' WHERE tjl.idJenisLayanan='.$idjenis.'';
  $sql .= ' ORDER BY th.namaPelayanan ASC';
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      while ($rows = fetch_assoc($r)) {
        $data[] = $rows;
      }
      $error = 0;
      $pesan = "Sukses menampilkan data";

    } else {
      $error = 1;
      $pesan = "Data Masih Kosong";
    }
  } else {
    $error = 1;
    $pesan = "Data jenis Gagal ditampilkan";
  }
  $output = [
    'error' => $error,
    'pesan' => $pesan,
    'data' => $data
  ];
  echo json_encode($output, JSON_PRETTY_PRINT);
}
function get_transaksi_item_detail($iditem){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $error = 1;
  $pesan ="";

  $sql = 'SELECT th.idHarga, th.namaPelayanan, th.harga FROM tbl_harga as th';
  $sql .= ' WHERE th.idHarga='.$iditem.'';
  $sql .= ' ORDER BY th.namaPelayanan ASC';
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      $rows = fetch_assoc($r);
      $data = $rows;
      // while ($rows = fetch_assoc($r)) {
      //   $data[] = $rows;
      // }
      $error = 0;
      $pesan = "Sukses menampilkan data";

    } else {
      $error = 1;
      $pesan = "Data Masih Kosong";
    }
  } else {
    $error = 1;
    $pesan = "Data jenis Gagal ditampilkan";
  }
  $output = [
    'error' => $error,
    'pesan' => $pesan,
    'data' => $data
  ];
  echo json_encode($data, JSON_PRETTY_PRINT);
}
function get_transaksi_dokter(){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $error = 1;
  $pesan ="";

  $sql = 'SELECT th.idPerawat, th.namaPerawat FROM tbl_perawat as th';
  $sql .= ' ORDER BY th.namaPerawat ASC';
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      while ($rows = fetch_assoc($r)) {
        $data[] = $rows;
      }
      $error = 0;
      $pesan = "Sukses menampilkan data";

    } else {
      $error = 1;
      $pesan = "Data Masih Kosong";
    }
  } else {
    $error = 1;
    $pesan = "Data jenis Gagal ditampilkan";
  }
  $output = [
    'error' => $error,
    'pesan' => $pesan,
    'data' => $data
  ];
  echo json_encode($data, JSON_PRETTY_PRINT);
}
function get_transaksi_pasien(){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $sql = 'SELECT idPasien, namaPasien FROM tbl_pasien';
  $sql .= ' ORDER BY namaPasien ASC';
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      while ($rows = fetch_assoc($r)) {
        $data[] ="[".$rows['idPasien']. "] ".$rows['namaPasien'];
      }
    }
  }
  $output = [
    "suggestions" => $data
  ];

  echo json_encode($output, JSON_PRETTY_PRINT);
}
function get_transaksi_biodataPasien($id){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $data = [];
  $sql = 'SELECT * FROM tbl_pasien';
  $sql .= ' WHERE idPasien='.$id.'';
  $sql .= ' ORDER BY namaPasien ASC';
  if ($r = query($conn, $sql)) {
    if (rows($r) > 0) {
      $rows = fetch_assoc($r);
      $data[] =$rows;
    }
  }
  echo json_encode($data, JSON_PRETTY_PRINT);
}
function cetak_transaksi($nomorF){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;

  $data = [];
  $error = 0;
  $pesan = 0;

  $sql = "SELECT * FROM tbl_perawatan as tp
           INNER JOIN tbl_pasien as tps ON tp.idPerawatanIdPasien = tps.idPasien";
  $sql .=' WHERE tp.noFaktur="'.$nomorF.'"';
  $sql .=' ORDER BY tp.namaItem ASC';
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

  return $data;
}
function hapus_transaksi($nomorF){
  require_once('config.php');
  require_once('configdb.php');
  global $conn;
  $error = 0;
  $pesan = "";


  $sql = "DELETE FROM tbl_perawatan WHERE noFaktur in ('$nomorF')";
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



 ?>
