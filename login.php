<?php
  error_reporting(0);
  session_start();
  require_once('controller/config.php');
  require_once('controller/configdb.php');
  require_once('controller/loginController.php');

  $namaSekolah = getConfig('namaApp');
  $logoSekolah = "img/".getConfig('logo_config');
  $alamat = getConfig('alamat_config');
  $error = false;
  $title = "Login";

  if (isset($_SESSION['login']) && isset($_SESSION['userid']) > 0 ) {
    header('Location:index.php');
    exit();
  }

 ?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="MiS-A Educational Information System">
    <meta name="author" content="Harison">
    <meta name="keyword" content="Aplikasi Klinik berbasis Web dan Android">
    <title><?php echo $title. " - " .$namaSekolah; ?></title>
    <link rel="icon" type="image/ico" href="./img/flyexam.ico" sizes="any" />
    <link href="vendors/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    <link href="css/toastr.min.css" rel="stylesheet"/>
  </head>
  <div class="loader"></div>
  <body class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card-group">
            <div class="card p-4">
              <form id="frmLogin" action="" method="post">
                <div class="card-body">
                  <h3 id="logo" style="color:#333"><strong>M.i.S</strong></h3>
                  <p class="mt-0 mb-1 text-muted">Silahkan Log-in untuk melanjutkan!</p>
                  <?php if ($error) { ?>
                    <p class="mb-0">
                      <div id="error" class="mb-0 alert-sm alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Ouppsss!! </strong> Username atau Password yang Anda masukkan salah.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    </p>
                  <?php } ?>
                  <div class="input-group mb-1">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-user"></i>
                      </span>
                    </div>
                    <input id="uname" name="uname" class="form-control" type="text" placeholder="Username">
                  </div>
                  <div class="input-group mb-4">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-lock"></i>
                      </span>
                    </div>
                    <input id="pwd" name="pwd" class="form-control" type="password" placeholder="Password">
                  </div>
                  <div class="row mt-0">
                    <div class="col-6">
                      <button id="login" name="login" class="btn btn-block btn-pill btn-outline-2x btn-primary px-4" type="button">Log-in</button>
                    </div>
                    <div class="col-6 text-right">
                      <button class="btn btn-link px-0 mb-0" type="button">Lupa password?</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div style="background-image:url('img/bg-profile-new.png');background-repeat: no-repeat;background-size: cover;background-position: center;background-color: #cccccc;" class="card text-white  py-5 d-md-down-none" style="width:44%">
              <div class="card-body text-center">
                <div>
                  <img src="<?php echo $logoSekolah; ?>" width="90px" height="80px;" alt="">
                  <h4 class="mt-0"><strong><?php echo $namaSekolah ?></strong></h4>
                  <p><?php echo $alamat ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        <div class="col-md-12 mt-3 text-center">
          <a class="text-muted" id="cp" href="index.php">MiS (Medical Information System)</a>
          <span id="smk3">&copy; <?php echo date('Y'); ?> Delta Microtech.</span>
        </div>
    </div>

    <script src="vendors/jquery/js/jquery.min.js"></script>
    <script src="vendors/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <script type="text/javascript" src="js/load.js"></script>
    <script type="text/javascript" src="js/select2.full.min.js"></script>
    <script type="text/javascript" src="js/jquery.multi-select.js"></script>
    <script type="text/javascript" src="js/quickSearch.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
  </body>
</html>
