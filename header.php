<?php
if (isset($_SESSION['login'])) {
  $level = $_SESSION['tipeuser'];
  $uname = $_SESSION['username'];

  require_once('controller/config.php');
  require_once('controller/configdb.php');
  require_once('controller/loginController.php');
  $namaApp = getConfig('namaApp');
  $logo = "img/".getConfig('logo_config');
  echo '
  <!DOCTYPE html>
  <html lang="id">
    <head>
      <base href="./">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <meta name="description" content="eIS-A Educational Information System">
      <meta name="author" content="Harison">
      <meta name="keyword" content="Aplikasi Absensi Online eIS-A Educational Information System">
      <title>'.$title.' - '.$namaApp.'</title>
      <!-- Icons-->
      <link rel="icon" type="image/ico" href="./img/flyexam.ico" sizes="any" />
      <link href="vendors/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
      <link href="css/all.min.css" rel="stylesheet">
      <link href="vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
      <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <link href="vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">

      <link href="vendors/pace-progress/css/pace.min.css" rel="stylesheet">
      <link href="css/toastr.min.css" rel="stylesheet"/>
      <script src="vendors/jquery/js/jquery.min.js"></script>
      <link rel="stylesheet" href="css/select2.min.css">
      <link rel="stylesheet" href="css/select2-bootstrap4.min.css">
      <link rel="stylesheet" href="css/datatables.min.css">
      <link rel="stylesheet" href="css/dataTables.checkboxes.css">
      <style>
        .autocomplete-suggestions { border: 2px solid #f2f2f2; background: #FFF; overflow: auto; }
        .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
        .autocomplete-selected { background: #F0F0F0; }
        .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
        .autocomplete-group { padding: 2px 5px; }
        .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
        .btn-outline-2x{border-width:2px}.btn-group .btn{font-size:0.8rem;font-weight:500}.btn-group .btn-outline-2x+.btn-outline-2x{margin-left:-2px;}
        .pagination {
          font-size: 10px;
        }
      </style>
    </head>
    <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show ">
    <div class="loader"></div>
  <header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="index.php" alt="Educational Information System" title="Educational Information System">
      <img src="img/'.getConfig('logo_config').'" width="50px">
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
      <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="nav navbar-nav d-md-down-none">
  <li class="nav-item px-0">
    <h5 style="padding-top:6px; padding-right:30px; font-weight: bold">
      <a id="namaSekolah" class="nav-link" href="index.php">
        '.$namaApp.'
      </a>
    </h5>
  </li>
  </ul>';
    echo '<ul class="nav navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><strong>'.$uname.'</strong>
          <img width="30px" height="30px" class="img-avatar" src="img/nouser.png" alt="admin@flyexam.id">
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="dropdown-header text-center">
            <strong>Akun</strong>
          </div>
          <a class="dropdown-item" href="logout.php">
            <i class="icon icon-logout"></i> Logout ('.$uname.')
          </a>';

          if ($level == 0) {
          echo '<div class="dropdown-header text-center">
            <strong>Pengaturan</strong>
          </div>
          <a class="dropdown-item" href="settingServer.php">
            <i class="icon icon-info"></i> Server</a>
            <a class="dropdown-item" href="users.php">
              <i class="icon-people"></i> Users</a>
        </div>';
        }
        echo '
      </li>
    </ul>
    <button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">
      <span class="navbar-toggler-icon"></span>
    </button>
    <button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">
      <span class="navbar-toggler-icon"></span>
    </button>
  </header>';
  }
