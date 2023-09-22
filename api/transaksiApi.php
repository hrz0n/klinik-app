<?php
  require_once('../controller/config.php');
  require_once('../controller/configdb.php');
  require_once('../controller/transaksiController.php');
  global $conn;

  $username = "";
  $password = "";
  $akses = "";
  $created_at = date('Y-m-d H:i:s');
  $updated_at = date('Y-m-d H:i:s');
  $status = "AKTIF";
  $aksi = "";

  $perangkatTanding = 0;
  $idkat = 0;
  $idjenis = 0;
  $iditem = 0;
  $idPerawatanIdDokter = 0;
  $idPerawatanIdPasien = 0;
  $idPerawatanIdHarga = 0;
  $noFaktur = "";
  $tglMasuk = "";
  $tglKeluar = "";
  $keterangan = "";
  $namaPasien ="";
  $nikPasien ="";
  $jenisKelamin ="L";
  $noHp ="";
  $alamatPasien ="";
  $nomorF = "";
  $qty = 1;
  $idtr = "";
  $namaortu = "";

  $namaItem ="";
  $hargaSatuan = 0;
  $hargaTotal = 0;


  if (isset($_REQUEST['aksi'])) {
    $aksi = $_REQUEST['aksi'];

    if (isset($_REQUEST['namaPelayanan'])) {
      $namaItem = json_decode($_REQUEST['namaPelayanan']);
    }
    if (isset($_REQUEST['hargaSatuan'])) {
      $hargaSatuan = json_decode($_REQUEST['hargaSatuan']);
    }
    if (isset($_REQUEST['hargaTotal'])) {
      $hargaTotal = json_decode($_REQUEST['hargaTotal']);
    }

    if (isset($_REQUEST['x_id'])) {
      $idkat = real_escape($conn, intval($_REQUEST['x_id']));
    }
    if (isset($_REQUEST['x_id_jenis'])) {
      $idjenis = real_escape($conn, intval($_REQUEST['x_id_jenis']));
    }
    if (isset($_REQUEST['x_id_tr'])) {
      $idtr = real_escape($conn, $_REQUEST['x_id_tr']);
    }
    if (isset($_REQUEST['x_id_item'])) {
      $iditem = real_escape($conn, intval($_REQUEST['x_id_item']));
    }
    if (isset($_REQUEST['idPerawatanIdDokter'])) {
      $idPerawatanIdDokter = json_decode($_REQUEST['idPerawatanIdDokter']);
    }
    if (isset($_REQUEST['idPerawatanIdPasien'])) {
      $idPerawatanIdPasien = json_decode($_REQUEST['idPerawatanIdPasien']);
    }
    if (isset($_REQUEST['idPerawatanIdHarga'])) {
      $idPerawatanIdHarga = json_decode($_REQUEST['idPerawatanIdHarga']);
    }
    if (isset($_REQUEST['qty'])) {
      $qty = json_decode($_REQUEST['qty']);
    }
    if (isset($_REQUEST['noFaktur'])) {
      $noFaktur = json_decode($_REQUEST['noFaktur']);
    }
    if (isset($_REQUEST['tglMasuk'])) {
      $tglMasuk = json_decode($_REQUEST['tglMasuk']);
    }
    if (isset($_REQUEST['tglKeluar'])) {
      $tglKeluar = json_decode($_REQUEST['tglKeluar']);
    }
    if (isset($_REQUEST['keterangan'])) {
      $keterangan = json_decode($_REQUEST['keterangan']);
    }
    if (isset($_REQUEST['created_at'])) {
      $created_at = json_decode($_REQUEST['created_at']);
    }
    if (isset($_REQUEST['updated_at'])) {
      $updated_at = json_decode($_REQUEST['updated_at']);
    }

    if (isset($_REQUEST['namapasien'])) {
      $namaPasien = real_escape($conn,$_REQUEST['namapasien']);
    }
    if (isset($_REQUEST['nrm'])) {
      $nikPasien = real_escape($conn,$_REQUEST['nrm']);
    }
    if (isset($_REQUEST['namaortu'])) {
      $namaortu = real_escape($conn,$_REQUEST['namaortu']);
    }
    if (isset($_REQUEST['jk'])) {
      $jenisKelamin = real_escape($conn,$_REQUEST['jk']);
    }
    if (isset($_REQUEST['hp'])) {
      $noHp = real_escape($conn,$_REQUEST['hp']);
    }
    if (isset($_REQUEST['alamat'])) {
      $alamatPasien = real_escape($conn,$_REQUEST['alamat']);
    }
    if (isset($_REQUEST['nomorfaktur'])) {
      $nomorF = real_escape($conn,$_REQUEST['nomorfaktur']);
    }


  }

  switch ($aksi) {
    case 'kat' :
      get_transaksi_kategori();
    break;
    case 'jenis' :
      get_transaksi_jenis($idkat);
    break;
    case 'item' :
      get_transaksi_item($idjenis);
    break;
    case 'itemdetail' :
      get_transaksi_item_detail($iditem);
    break;
    case 'dokter' :
      get_transaksi_dokter();
    break;
    case 'pasien' :
      get_transaksi_pasien();
    break;
    case 'biodataPasien' :
      get_transaksi_biodataPasien($idkat);
    break;
    case 'simpantransaksi' :
      simpan_data_transaksi(
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
        $updated_at
      );
    break;

    case 'simpanpasien' :
      simpan_data_pasien(
        $namaPasien,
        $nikPasien,
        $namaortu,
        $jenisKelamin,
        $noHp,
        $alamatPasien,
        $created_at,
        $updated_at
      );
    break;

    case 'detailtransaksi' :
      get_detail_transaksi($nomorF);
    break;

    case 'hapustransaksi' :
      $id = array($_REQUEST['x_id_tr']);
      $inQuery = implode(',', $id);
      hapus_transaksi($inQuery);
    break;
    default:
      get_all_transaksi();
      break;
  }
