<?php
// error_reporting(0);

require_once('controller/config.php');
require_once('controller/configdb.php');
require_once('controller/transaksiController.php');
require('fpdf/fpdf.php');
if ( is_session_started() === FALSE ) session_start();

if (!isset($_SESSION['login'])) {
  header("location:login.php");
  exit();
}

$logoLap = "img/".getConfig('logo_config');
if (getConfig('logo_config') == "") {
  $logoLap = "img/nophoto.jpg";
}


  global $conn;

  $data = [];
  $nomorF = "";
  if (isset($_REQUEST['no'])) {
    $nomorF = real_escape($conn, $_REQUEST['no']);
  }
  $datatransaksi = cetak_transaksi($nomorF);

  $idPerawatan="";
  $idPerawatanIdDokter="";
  $idPerawatanIdPasien="";
  $idPerawatanIdHarga="";
  $noFaktur="";

  $created_at="";
  $idPasien="";
  $namaPasien="";
  $nrmPasien="";
  $jenisKelamin="";
  $noHp="";
  $alamatPasien="";
  $idHarga="";
  $namaPelayanan="";
  $idHargaIdJenis="";
  $harga="";
  $namaOrtu = "";


  $jk = "Laki-Laki";
  if (count($datatransaksi) > 0) {

    $idPerawatan= $datatransaksi[0]['idPerawatan'];
    $idPerawatanIdDokter= $datatransaksi[0]['idPerawatanIdDokter'];
    $idPerawatanIdPasien= $datatransaksi[0]['idPerawatanIdPasien'];
    $noFaktur= $datatransaksi[0]['noFaktur'];
    $created_at= $datatransaksi[0]['created_at'];
    $idPasien= $datatransaksi[0]['idPerawatanIdPasien'];
    $namaPasien= $datatransaksi[0]['namaPasien'];
    $nrmPasien= $datatransaksi[0]['nrmPasien'];
    $jenisKelamin= $datatransaksi[0]['jenisKelamin'];
    $noHp= $datatransaksi[0]['noHp'];
    $alamatPasien= $datatransaksi[0]['alamatPasien'];
    $namaOrtu= $datatransaksi[0]['namaOrtu'];
    if ($jenisKelamin == "P") {
      $jk = "Perempuan";
    }
  }

  $tglFaktur = date('d F Y', strtotime($created_at));
  $tglCetak = date('d F Y');


class PDF extends FPDF{

}

$pdf = new PDF('P','cm','A4');
 $pdf->AliasNbPages();
 $pdf->AddPage();
 $pdf->SetTextColor(0,0,0);
 $pdf->SetTitle("Rekap Nota Penjualan");
 $pdf->SetAuthor("b0x");
 $pdf->SetCreator("b0x");
 $pdf->SetKeywords("b0x");
 $pdf->SetSubject("b0x");
 $pdf->SetFont('Arial','','10');
  $pdf->Image($logoLap,1,1,1.7,1.7);
  $pdf->SetFont('Arial','B',11);
  $pdf->Cell(2.3,1,'',0,0);
  $pdf->Cell(16,1,getConfig('namaApp'),0,10,1);
  $pdf->SetFont('Arial','','10');
  $pdf->Cell(16,.1,getConfig('alamat_config'),10,10,'L');
  $pdf->Cell(16,.8,'Telp : '.getConfig('telpApp').' - No Hp: '.getConfig('hpApp'),10,10,'L');
  $pdf->Ln(.1);
  $pdf->Cell(19,.1,'','LR',10,10,'L');
  $pdf->SetFont('Arial','B',11);
  $pdf->Ln(.1);
  $pdf->Cell(19,.7,'NOTA TRANSAKSI PEMBAYARAN',10,10,'C');
  $pdf->Cell(19,.3,'NO. FAKTUR : ' .$noFaktur,10,10,'C');
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(19,.7,'Tanggal  ' .$tglFaktur,10,10,'C');
  $pdf->Ln(.2);


   // biodata transaksi
   $pdf->SetFont('Arial','B',10);
   $pdf->Cell(13.5,.2,'','',1,'',0);
   $pdf->Cell(.8,.5,'','',0,'',0);
   $pdf->Cell(4,.1,'Biodata Pasien','',1,'',0);
   $pdf->SetFont('Arial','',10);
   $pdf->Cell(13.5,.2,'','',1,'',0);
   $pdf->Cell(.8,.5,'','',0,'',0);
   $pdf->Cell(4,.5,'Nama Lengkap','',0,'',0);
   $pdf->Cell(.3,.5,':','',0,'',0);
   $pdf->Cell(8.4,.5,$namaPasien,'',1,'',0);

   $pdf->Cell(.8,.5,'','',0,'',0);
   $pdf->Cell(4,.5,'N R M','',0,'',0);
   $pdf->Cell(.3,.5,':','',0,'',0);
   $pdf->Cell(8.4,.5,$nrmPasien,'',1,'',0);

   $pdf->Cell(.8,.5,'','',0,'',0);
   $pdf->Cell(4,.5,'No. Hp','',0,'',0);
   $pdf->Cell(.3,.5,':','',0,'',0);
   $pdf->Cell(8.4,.5,$noHp,'',1,'',0);

   $pdf->Cell(.8,.5,'','',0,'',0);
   $pdf->Cell(4,.5,'Nama Ortu','',0,'',0);
   $pdf->Cell(.3,.5,':','',0,'',0);
   $pdf->Cell(8.4,.5,$namaOrtu,'',1,'',0);

  $pdf->Cell(.8,.5,'','',0,'',0);
  $pdf->Cell(4,.5,'Jenis kelamin ','',0,'',0);
  $pdf->Cell(.3,.5,':','',0,'',0);
  $pdf->Cell(8.4,.5,$jk,'',1,'',0);

   $pdf->Cell(.8,.5,'','',0,'',0);
   $pdf->Cell(4,.5,'Alamat','',0,'',0);
   $pdf->Cell(.3,.5,':','',0,'',0);
   $pdf->Cell(8.4,.5,$alamatPasien,'',1,'',0);

   if ($idPerawatanIdDokter !="") {
     $pdf->Cell(.8,.5,'','',0,'',0);
     $pdf->Cell(4,.5,'Dokter yg Merawat','',0,'',0);
     $pdf->Cell(.3,.5,':','',0,'',0);
     $pdf->Cell(8.4,.5,$idPerawatanIdDokter,'',1,'',0);
   }

  $pdf->Ln(.3);
  $pdf->SetFont('Arial','B','11');
  $pdf->SetFillColor(200,220,255);

  $pdf->Cell(1.2,.8,' NO.','LTB',0,1,1);
  $pdf->Cell(10,.8,' Keterangan Pembayaran','TB',0,1,1);
    $pdf->Cell(2.8,.8,' Harga Satuan','TB',0,1,1);
  $pdf->Cell(1.3,.8,' QTY','TB',0,1,1);
  $pdf->Cell(3.7,.8,' Total Harga','TBR',1,1,'R');
  $pdf->SetFillColor(0,0,0);

  $no = 0;
  $d = 0;
  $pdf->Cell(19,.1,'','LR',1,'',0);
  $totalHarga = 0;
  $qty =1;
foreach ($datatransaksi as $key => $value) {
  $no = $no + 1;
  $qty = $value['qty'];
  $totalHarga = intval($value['hargaTotal']);

  $pdf->SetFont('Arial','',10);
  $pdf->Cell(1.2,.5,$no,'L',0,'C',0);
  $pdf->Cell(10,.5,$value['namaItem'],'',0,'L',0);
  $pdf->Cell(2.8,.5,'Rp. '.number_format($value['hargaSatuan']),'',0,'L',0);
  $pdf->Cell(1.3,.5,$qty,'',0,'C',0);
  $pdf->Cell(3.7,.5,'Rp. '.number_format($totalHarga),'R',1,'L',0);
  $d += $totalHarga;
}
 $pdf->Cell(19,.1,'','LRB',0,'R',0);
 $pdf->SetFont('Arial','B','11');
 $pdf->Ln(.5);
 $pdf->Cell(10,.6,'','',0,'C',0);
 $pdf->Cell(4.7,.6,'TOTAL PEMBAYARAN :','B',0,'L',0);
 $pdf->Cell(4.2,.6,"Rp. ".number_format($d),'B',1,'L',0);

$pdf->Ln(1);

$pdf->SetFont('Arial','','10');
$pdf->Cell(10,.6,'','',0,'L',0);
$pdf->Cell(8,.6,getConfig('tmptCetak') .", ". $tglCetak,'',1,'C',0);
$pdf->Cell(10,.6,'','',0,'L',0);
$pdf->Cell(8,.3,'Petugas','',1,'C',0);
$pdf->Ln(1);
$pdf->Cell(10,.6,'','',0,'L',0);
$pdf->Cell(8,.6,strtoupper($_SESSION['username']),'',1,'C',0);



 $pdf->Output();
?>
