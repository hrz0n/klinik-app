  <?php
  require_once('controller/config.php');
  require_once('controller/configdb.php');
  require_once('controller/jenisController.php');

  global $conn;

  if (!isset($_SESSION['login'])) {
    header("location:login.php");
    exit();
  }
  $daftarJenis = daftarJenis();

  $level = $_SESSION['tipeuser'];
  $title = ' Daftar Harga Pelayanan';

  require('header.php');
  include('menu.php');

  ?>
  <main class="main">
     <div style="padding:6px" class="">
       <div class="animated fadeIn">
         <div class="row">
           <div class="col-lg-12">
             <div class="card">
               <div class="card-header text-primary">
                 <b><i class="cil-dollar"></i> <?= $title ?></b>
               </div>
               <div class="card-body">
                 <div class="d-flex flex-wrap justify-content-between mb-1">
                    <div class="col-md-6">
                      <button type="button" id="refreshData" class="float-left btn-icon btn-pill btn btn-success refreshData"><i class="cil-sync"></i> Refresh</button>
                      <button type="button" onclick="add_new()" id="adddata"  class="ml-1 float-left btn-icon btn-pill btn btn-warning"><i class="icon icon-paper-plane"></i> Tambah Item Layanan</button>
                      <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-danger " name="hapusall" id="hapusall"><i class=" cil-trash"></i> Hapus Semua</button>
                    </div>
                      <div class="col-md-4">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text bg-light">
                              <i class="cil-search "></i>
                            </div>
                          </div>
                          <input placeholder="Cari Nama, Jenis, dan harga Layanan ..." type="text" class="btn-outline-2x form-control global_filter" id="global_filter">
                        </div>
                      </div>
                  </div>

                 <form class="mt-0" id="form_table" action="" method="post" >
                   <input type="hidden" value="" name="x_id_tr_hapus" />
                 <table style="width:100%" id="tabelAuthor" class="table table-bordered table-responsive table-sm  table-hover">
                   <thead class="bg-light" >
                     <tr><th style="padding-left:15px;max-width:10px"> <input name="select_all" value="1" id="altet-select-all" type="checkbox" /></th>
                       <th class="pl-2" style="width:1000px">Nama Pelayanan</th>
                       <th class="pl-2" style="width:500px">Jenis Pelayanan</th>
                       <th class="pl-2" style="width:300px">Harga</th>
                       <th class="text-center" style="width:100px" >Aksi</th>
                     </tr>
                   </thead>
                   <tbody>
                   </tbody>
                 </table>
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
     <!-- Modal -->
     <div class="modal fade" id="confirmDelete2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
       <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
           <div class="modal-header bg-danger">
             <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             Apakah anda yakin untuk menghapus data ini?
           </div>
           <div class="modal-footer bg-light">
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-secondary" data-dismiss="modal"><i class="icon icon-logout"></i> Batal</button>
             <button type="button" id="delete-confirm-button2" class="float-left ml-1 btn-icon btn-pill btn btn-danger "><i class="fa fa-trash"></i> Konfirmasi & Hapus Data</button>
           </div>
         </div>
       </div>
     </div>

     <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
       <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
           <div class="modal-header bg-danger">
             <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fa fa-trash"></i> Hapus Data</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <div class="pesanmodal"></div>
           </div>
           <div class="modal-footer bg-light">
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-secondary" data-dismiss="modal"><i class="icon icon-logout"></i> Batal</button>
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-danger " id="delete-confirm-button"><i class="cil-trash"></i>Konfirmasi & Hapus Data</button>
           </div>
         </div>
       </div>
     </div>

     <div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
       <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
           <div class="modal-header bg-warning">
             <h5 class="modal-title text-dark"  id=""><i class="fa fa-warning"></i> Warning!</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <div class="pesanmodal"></div>
           </div>
           <div class="modal-footer bg-light">
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-warning " data-dismiss="modal"><i class="cil-sync"></i> OK, Mengerti
             </button>
           </div>
         </div>
       </div>
     </div>

     <!-- Modal -->
   <div class="modal fade docs-example-modal-lg" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
     <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
         <div class="modal-header bg-primary">
           <h5 class="modal-title" id="modal-title">Title</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body form">

           <form action="#" id="form" class="form-horizontal">
             <input type="hidden" value="" name="x_id" />
             <div class="form-body">
               <div class="form-group form-group-sm row">
                 <label for="namapelayanan" class="col-sm-3 col-form-label">Nama Layanan</label>
                 <div class="col-sm-9">
                   <input required placeholder="Nama Pelayanan" id="namapelayanan" class="btn-outline-2x rounded-0 form-control" type="text" name="namapelayanan" value="">
                   <span class="help-block"></span>
                 </div>
               </div>

               <div class="form-group form-group-sm row" style="margin-top:-12px;" >
                 <label for="jenislayanan" class="col-sm-3 col-form-label">Jenis Layanan</label>
                 <div class="col-sm-9">
                   <select data-placeholder="Pilih kategori" class="form-control" name="jenislayanan" id="jenislayanan" required>
                     <option value=""></option>
                     <?php
                      foreach ($daftarJenis as $key => $value) {
                        echo "<option value=".$value['idJenisLayanan']."> ".$value['namaJenisLayanan']."</option>";
                      }

                      ?>
                   </select>
                 </div>
               </div>
               <div class="form-group form-group-sm row" style="margin-top:-12px;">
                 <label for="harga" class="col-sm-3 col-form-label">Harga</label>
                 <div class="col-sm-9">
                   <input required placeholder="Harga, Ex: 500000" id="harga" class="btn-outline-2x rounded-0 form-control" type="number" name="harga" value="">
                   <span class="help-block"></span>
                 </div>
               </div>

             </div>
           </form>
         </div>
         <div class="modal-footer bg-light">
           <button type="button" class="rounded-0 btn btn-warning" data-dismiss="modal"><i class="nav-icon icon-logout"></i> Selesai</button>
           <button id="simpanData" name="simpanData" type="button" class="rounded-0 btn btn-primary"><b><i class="nav-icon icon-share-alt"></i> Simpan Data</b></button>
         </div>
       </div>
     </div>
   </div>

   </main>
   <script type="text/javascript" src="js/harga.js"></script>
<?php
  require('footer.php');
 ?>
