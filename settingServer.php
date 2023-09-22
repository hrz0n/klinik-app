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

  $error = "";

  $nSekolah = "";
  $aSekolah = "";
  $thnAjaran = "";
  $semester = "";
  $tm = "";
  $tempat = "";
  $petugas = "";
  $nohp = "";
  $notelp = "";



  $title = "Manage Server";
  require("header.php");
  require("menu.php");



  if(isset($_POST['submit'])){


    if (isset($_POST['namaSekolah'])) {
      $nSekolah = $_POST['namaSekolah'];
    }
    if (isset($_POST['alamatSekolah'])) {
      $aSekolah = $_POST['alamatSekolah'];
    }
    if (isset($_POST['timeZone'])) {
      $tm = $_POST['timeZone'];
    }

    if (isset($_POST['notelp'])) {
      $notelp = $_POST['notelp'];
    }
    if (isset($_POST['nohp'])) {
      $nohp = $_POST['nohp'];
    }
    if (isset($_POST['namapetugas'])) {
      $petugas = $_POST['namapetugas'];
    }
    if (isset($_POST['tempat'])) {
      $tempat = $_POST['tempat'];
    }


    $targetDir = "img/";
    $allowTypes = array('jpg','png','jpeg','gif');
    $images_arr = array();
    $ttdAdmin_arr = array();
    $fileName = "";

    if (isset($_FILES["images"] ) && !empty( $_FILES["images"]["name"] ) ) {
      foreach($_FILES['images']['name'] as $key => $val){
          $image_name = $_FILES['images']['name'][$key];
          $tmp_name   = $_FILES['images']['tmp_name'][$key];
          $size       = $_FILES['images']['size'][$key];
          $type       = $_FILES['images']['type'][$key];
          $error      = $_FILES['images']['error'][$key];

          // File upload path
          $fileName = basename($_FILES['images']['name'][$key]);
          $targetFilePath = $targetDir . $fileName; // img/upload/0915c467-2f1a-4f6d-809c-50f9d3f32af5.jpg

          // Check whether file type is valid
          $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
          if(in_array($fileType, $allowTypes)){
              // Store images on the server
              if(move_uploaded_file($_FILES['images']['tmp_name'][$key],$targetFilePath)){
                  $images_arr[] = $targetFilePath;
                  $sql = "UPDATE tbl_config SET value_config='$fileName' WHERE nama_config='logo_config'";
                  if ($r = query($conn, $sql)) {
                    $logoSekolah = "img/".getConfig('logo_config');
                  }
              }
          }


      }
    }




    $sql = 'UPDATE tbl_config SET value_config = "'.$nSekolah.'" where nama_config = "namaApp"';
    if ($r = query($conn, $sql)) {}
    $sql = 'UPDATE tbl_config SET value_config = "'.$aSekolah.'" where nama_config = "alamat_config"';
    if ($r = query($conn, $sql)) {}
    $sql = 'UPDATE tbl_config SET value_config = "'.$tm.'" where nama_config = "timeZone"';
    if ($r = query($conn, $sql)) {}

      $sql = 'UPDATE tbl_config SET value_config = "'.$notelp.'" where nama_config = "telpApp"';
      if ($r = query($conn, $sql)) {}

      $sql = 'UPDATE tbl_config SET value_config = "'.$nohp.'" where nama_config = "hpApp"';
      if ($r = query($conn, $sql)) {}

      $sql = 'UPDATE tbl_config SET value_config = "'.$petugas.'" where nama_config = "namattd"';
      if ($r = query($conn, $sql)) {}

      $sql = 'UPDATE tbl_config SET value_config = "'.$tempat.'" where nama_config = "tmptCetak"';
      if ($r = query($conn, $sql)) {$error = "sukses";}




  }


  $namaSekolah = getConfig('namaApp');
  $notelp = getConfig('telpApp');
  $nohp = getConfig('hpApp');
  $alamatSekolah = getConfig('alamat_config');
  $petugas = getConfig('namattd');
  $tempat = getConfig('tmptCetak');
  $timeZone = getConfig('timeZone');
  $logoSekolah = "img/".getConfig('logo_config');
?>
  <main class="main">
    <div style="padding:6px" class="">
      <div class="animated fadeIn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <b><i class="icon icon-info"></i> <?= $title; ?></b>
              </div>
              <div class="card-body">

                <form  class="mt-0"  action="" method="post" enctype="multipart/form-data">

                  <?php if ($error== "error") { ?>
                  <p class="mb-0 mt-1">
                    <div id="error" class="mb-0 alert-sm alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>Ouppsss!! </strong> Gagal mengupdate data server!
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  </p>
                <?php } elseif ($error== "sukses") {
                  echo '<p class="mb-0 mt-1">
                    <div id="error" class="mb-0 alert-sm alert alert-success alert-dismissible fade show" role="alert">
                      <strong>Suksess!! </strong> Data server berhasil disimpan, Silahkan refresh halaman untuk melih seluruh perubahan.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  </p>';
                } ?>


                  <div class="form-group col-sm-12 row">
                  <div class="col-sm-2"></div>
                    <table border="0">
                      <tr>
                        <td><img alt="Logo <?php echo $namaSekolah; ?>" class="ml-3 rounded-1" src="<?= $logoSekolah; ?>" width="100px" height="100px"></td>
                        <td>
                          <h2 class="ml-3"><?php echo $namaSekolah; ?></h2>
                          <input accept=".jpg,.jpeg,.png,.gif" id="images" name="images[]" placeholder="Pilih Logo" class="btn-outline-2x ml-3 form-control rounded-0" type="file" value="">
                        </td>
                      </tr>
                    </table>
                  </div>


                  <div class="form-group row">
                    <label for="thnAjaran" class="col-sm-2 col-form-label">Nama Aplikasi</label>
                    <div class="col-sm-6">
                      <input placeholder="Nama Aplikasi" class="form-control rounded-0 btn-outline-2x" type="text" name="namaSekolah" value="<?= $namaSekolah; ?>">
                      <span class="help-block"></span>
                    </div>
                  </div>

                  <div style="margin-top:-12px" class="form-group row">
                    <label for="notelp" class="col-sm-2 col-form-label">No. Telp</label>
                    <div class="col-sm-6">
                      <input placeholder="No. Telp, Ex : (0371) 2382" class="form-control rounded-0 btn-outline-2x" type="text" name="notelp" value="<?= $notelp; ?>">
                      <span class="help-block"></span>
                    </div>
                  </div>

                  <div style="margin-top:-12px" class="form-group row">
                    <label for="nohp" class="col-sm-2 col-form-label">No. Hp</label>
                    <div class="col-sm-6">
                      <input placeholder="No. Hp, Ex : 0821 238 323 232" class="form-control rounded-0 btn-outline-2x" type="text" name="nohp" value="<?= $nohp; ?>">
                      <span class="help-block"></span>
                    </div>
                  </div>


                  <div style="margin-top:-12px" class="form-group row">
                    <label for="thnAjaran" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-6">
                      <textarea class="form-control rounded-0 btn-outline-2x" placeholder="Alamat Aplikasi"  name="alamatSekolah" rows="3" cols="80"><?php echo $alamatSekolah; ?></textarea>
                      <span class="help-block"></span>
                    </div>
                  </div>


                  <div style="margin-top:-12px" class="form-group row">
                    <label for="timeZone" class="col-sm-2 col-form-label">TimeZone</label>
                    <div class="col-sm-6">
                      <select class="form-control rounded-0" id="timeZone" name="timeZone">
                        <?php
                          $tm = ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura'];
                          foreach ($tm as $key => $value) {
                            $s = "";
                            if ($value == $timeZone) {
                              $s = " selected=selected ";
                            }
                            echo '<option'.$s.' value="'.$value.'">'.$value.'</option>';
                          }
                         ?>
                      </select>
                      <span class="help-block"></span>
                    </div>
                  </div>


                  <div style="margin-top:-12px" class="form-group row">
                    <label for="namapetugas" class="col-sm-2 col-form-label">Nama Penandatangan Laporan</label>
                    <div class="col-sm-6">
                      <input placeholder="Nama Petugas" class="form-control rounded-0 btn-outline-2x" type="text" name="namapetugas" value="<?= $petugas; ?>">
                      <span class="help-block"></span>
                    </div>
                  </div>

                  <div style="margin-top:-12px" class="form-group row">
                    <label for="tempat" class="col-sm-2 col-form-label">Tempat Cetak laporan</label>
                    <div class="col-sm-6">
                      <input placeholder="Nama Tempat, Ex : Lahat" class="form-control rounded-0 btn-outline-2x" type="text" name="tempat" value="<?= $tempat; ?>">
                      <span class="help-block"></span>
                    </div>
                  </div>

                  <hr>




                  <div class="form-group row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-3">
                      <button class="btn btn-primary rounded-0 btn-block" type="submit" name="submit"><i class="icon icon-shuffle"></i><b> Simpan Data</b></button>
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
<!-- <script type="text/javascript" src="js/settingServer.js"></script> -->
<?php
  require("footer.php");
 ?>
