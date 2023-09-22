<?php
  require_once("../controller/config.php");
  require_once("../controller/configdb.php");

  global $conn;
  $nSekolah = "";
  $aSekolah = "";
  $thnAjaran = "";
  $semester = "";
  $tm = "";
  $tempat = "";
  $petugas = "";
  $nohp = "";
  $notelp = "";


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
    if (isset($_POST['petugas'])) {
      $petugas = $_POST['petugas'];
    }
    if (isset($_POST['tempat'])) {
      $tempat = $_POST['tempat'];
    }


    $targetDir = "../img/";
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
              }
          }

          $sql = "UPDATE tbl_config SET value_config='$fileName' WHERE nama_config='logo_config'";
          if ($r = query($conn, $sql)) {}
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
      if ($r = query($conn, $sql)) {}




  }

 ?>
