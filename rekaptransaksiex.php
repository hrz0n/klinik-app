<?php
// error_reporting(0);

require_once('controller/config.php');
require_once('controller/configdb.php');
require('fpdf/fpdf.php');
if ( is_session_started() === FALSE ) session_start();

if (!isset($_SESSION['login'])) {
  header("location:login.php");
  exit();
}

  global $conn;
  $data = [];
  $judulLap = "REKAP TRANSAKSI PEMBAYARAN";
  $saring = 0;


  $mulaiTgl = "";
  $sampaiTgl = "";
  if (isset($_REQUEST['daritgl'])) {
    $mulaiTgl = real_escape($conn,$_REQUEST['daritgl']);
    $mulaiTgl = $mulaiTgl . " 00:00:00";
  }
  if (isset($_REQUEST['sampaitgl'])) {
    $sampaiTgl = real_escape($conn,$_REQUEST['sampaitgl']);
    $sampaiTgl = $sampaiTgl . " 23:59:59";
  }

  $d_kunjunganTgl = date('d/m/Y', strtotime($mulaiTgl));
  $s_kunjunganTgl = date('d/m/Y', strtotime($sampaiTgl));

  $logoLap = "img/".getConfig('logo_config');
  if (getConfig('logo_config') == "") {
    $logoLap = "img/nophoto.jpg";
  }


  $sql = '
  SELECT tp.noFaktur,
        tps.namaPasien,
        tps.nrmPasien,
        tps.jenisKelamin,
        tp.created_at,
        tp.idPerawatanIdDokter,
        SUM(tp.hargaTotal) as harga
        FROM tbl_perawatan AS tp
    INNER JOIN tbl_pasien AS tps ON tps.idPasien=tp.idPerawatanIdPasien';

    $sql .= " WHERE tp.created_at >= '$mulaiTgl' AND tp.created_at <= '$sampaiTgl'";
    $sql .= " GROUP BY tp.noFaktur order by tp.created_at DESC";

    $nourut =0;
    if ($r = query($conn, $sql)) {
      if (rows($r) > 0) {
        while ($rows = fetch_assoc($r)) {
          $data[] = $rows;
        }
      }
    }




class PDF extends FPDF{

}

$pdf = new PDF('P','cm','A4');
 $pdf->AliasNbPages();
 $pdf->AddPage();
 $pdf->SetTextColor(0,0,0);
 $pdf->SetTitle("Rekap Transaksi Penjualan");
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
  $pdf->Ln(.3);
  $pdf->Cell(19,.7,'REKAP TRANSAKSI PEMBAYARAN',10,10,'C');
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(19,.2,'Tanggal  ' .$d_kunjunganTgl. ' s/d '. $s_kunjunganTgl,10,10,'C');
  $pdf->Ln(.7);


 $pdf->SetFillColor(200,220,255);
 $pdf->Ln(0);
 $pdf->SetFont('Arial','B','8');
 $pdf->Cell(1,.7,'NO.','LTRB',0,'C',1);
 $pdf->Cell(3,.7,'NOMOR FAKTUR','TBR',0,'L',1);
 $pdf->Cell(5.2,.7,'NAMA PASIEN','TBR',0,'L',1);
 $pdf->Cell(3,.7,'N R M','TBR',0,'L',1);
 $pdf->Cell(.7,.7,'JK','TBR',0,'C',1);
 $pdf->Cell(3.2,.7,'NAMA DOKTER','TBR',0,'L',1);
 $pdf->Cell(2.7,.7,'TOTAL BAYAR','TBR',1,'L',1);
 $pdf->SetFont('Arial','','8');

 $total = 0;
 $dokter = "-";
 $nik = "-";
 $totaltr = 0;

  $tglCetak = date('d F Y');

 foreach ($data as $key => $rows) {
   $nourut = $nourut+1;
   if ($rows['idPerawatanIdDokter'] != "") {
     $dokter = $rows['idPerawatanIdDokter'];
   }

   if ($rows['nrmPasien'] != "") {
     $nik = $rows['nrmPasien'];
   }
   $tgltransaksi = date('d/m/Y', strtotime($rows['created_at']));
   $pdf->Cell(1,.7,$nourut,'LRB',0,'C');
   $pdf->Cell(3,.7,$rows['noFaktur'],'BR',0,'L');
   $pdf->Cell(5.2,.7,$rows['namaPasien'],'BR',0,'L');
   $pdf->Cell(3,.7,$nik,'BR',0,'L');
   $pdf->Cell(.7,.7,$rows['jenisKelamin'],'BR',0,'C');
   $pdf->Cell(3.2,.7,$dokter,'BR',0,'L');
   $pdf->Cell(2.7,.7,"Rp. " .number_format($rows['harga']),'BR',1,'L');
   $total = $nourut;
   $totaltr = $totaltr + intval($rows['harga']);
 }
    $pdf->SetFont('Arial','B','8');
   $pdf->Cell(1,.7,'','LTB',0,'L');
   $pdf->Cell(2.5,.7,'JUMLAH DATA :','BT',0,'L');
   $pdf->Cell(9.4,.7,number_format($total).' PASIEN','BT',0,'L');
   $pdf->Cell(3.2,.7,' TOTAL TRANSAKSI','BT',0,'L');
   $pdf->Cell(2.7,.7,"Rp. ".number_format($totaltr),'BRT',1,'L');

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
