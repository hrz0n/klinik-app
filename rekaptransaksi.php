<?php
// error_reporting(0);
session_start();
if (!isset($_SESSION['login'])) {
  header("location:login.php");
  exit();
}
  require_once('controller/config.php');
  require_once('controller/configdb.php');
  global $conn;

  $title = "Rekap Semua Data Transaksi";
  require("header.php");
  require("menu.php");

  $tglNow = date('Y-m-d');
  // $tglNow = date('d-m-y', strtotime($tglNow));
  $error = 0;
  $drTanggal = "";
  $spTanggal = "";

  if (isset($_POST['cetakData'])) {

    if (isset($_POST['daritgl'])) {
      $drTanggal = real_escape($conn,$_POST['daritgl']);
    }

    if (isset($_POST['sampaitgl'])) {
      $spTanggal = real_escape($conn,$_POST['sampaitgl']);
    }

    if ($drTanggal == "" || $spTanggal == "") {
      $error = 1;
    } else {
      echo '<script type="text/javascript" language="Javascript">window.open("rekaptransaksiex.php?daritgl='.$drTanggal.'&sampaitgl='.$spTanggal.'", "_blank");</script>';
    }
  }

?>
  <main class="main">
    <div style="padding:6px" class="">
      <div class="animated fadeIn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <b><i class="cil-print"></i> <?php echo $title; ?></b>
              </div>
              <div class="card-body">


                <form class="mt-0" action="" method="post">

                  <?php if ($error) { ?>
                  <p class="mb-0 mt-1">
                    <div id="error" class="mb-0 alert-sm alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>Ouppsss!! </strong> Silahkan filter tanggal transaksi terlebih dahulu.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  </p>
                  <?php } ?>

                  <div class="mb-1 mt-1 position-relative row form-group">

                  <label for="siswa" class="col-sm-1 col-form-label">Tanggal</label>
                    <div class="input-group col-sm-8">
                      <div class="input-group-append">
                        <i class="btn bg-light fa fa-calendar"></i>
                      </div>
                        <input id="daritgl" name="daritgl" type="date" value="<?= $tglNow; ?>" class="form-control btn-outline-2x">
                        <div class="input-group-append">
                          <span class="btn disabled btn-outline-default">s/d</span>
                          <i class="btn bg-light fa fa-calendar"></i>
                        </div>
                        <input id="sampaitgl" name="sampaitgl" type="date" value="<?= $tglNow; ?>" class=" form-control btn-outline-2x">
                    </div>
                  </div>
                  <div class="form-group row mt-2">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-3">
                      <button class="btn  btn-pill btn-block btn-outline-light btn-outline-2x text-dark" type="submit" name="cetakData"><i class="cil-print"></i><b> Cetak Data</b></button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
          <!-- /.col-->
        </div>

      </div>
    </div>

    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body"><center><img src="img/loading.gif" alt=""></center>
          <p class="text-muted"><center>Processing data, silahkan tunggu sebentar ....</center></p>
        </div>
        </div>
      </div>
    </div>



    <div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id=""><i class="fa fa-warning"></i> Warning!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Maaf, silahkan pilih data yang akan dihapus terlebih dahulu!
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-warning rounded-0 col-md-6" data-dismiss="modal"><i class="nav-icon icon-reload"></i> OK, Mengerti </button>
          </div>
        </div>
      </div>
    </div>
  </main>
<script type="text/javascript" src="js/telegram.js"></script>
<?php
  require("footer.php");
 ?>
