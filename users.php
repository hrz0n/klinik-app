  <?php
  require_once('controller/config.php');
  require_once('controller/configdb.php');

  global $conn;

  if (!isset($_SESSION['login'])) {
    header("location:login.php");
    exit();
  }
  $level = $_SESSION['tipeuser'];
  $title = ' Daftar User';

  require('header.php');
  include('menu.php');



  ?>
  <main class="main">
     <div style="padding:6px" class="">
       <div class="animated fadeIn">
         <div class="row">
           <div class="col-lg-12">
             <div class="card">
               <div class="card-header">
                 <b><i class="icon-people"></i> <?php echo $title ?></b>
               </div>
               <div class="card-body">

                 <div class="d-flex flex-wrap justify-content-between mb-1">
                    <div class="col-md-6">
                      <button type="button" id="refreshData" class="float-left btn-icon btn-pill btn btn-info refreshData"><i class="icon icon-refresh"></i> Refresh
                      </button>

                      <button onclick="add_new()" type="button" class="float-left ml-1 btn-icon btn-pill btn btn-success "><i class="icon icon-paper-plane"></i> Tambah Data
                      </button>

                      <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-danger " name="hapusall" id="hapusall"><i class="fa fa-trash"></i> Hapus Semua
                      </button>
                    </div>
                      <div class="col-md-4">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="fa fa-search fa-w-16 "></i>
                            </div>
                          </div>
                          <input placeholder="Cari data users ..." type="text" class="form-control global_filter" id="global_filter">
                        </div>
                      </div>
                  </div>

                 <form class="mt-0" id="form_table" action="" method="post" >
                 <table style="width:100%" id="tabelAuthor" class="table table-bordered table-responsive table-sm  table-hover">
                   <thead class="bg-light" >
                     <tr>
                       <th style="padding-left:15px;max-width:10px"> <input name="select_all" value="1" id="users-select-all" type="checkbox" /></th>
                       <th class="pl-2" style="width:1050px">Username</th>
                       <th class="pl-2" style="width:500px">Level</th>
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

                 <div class="form-group row">
                   <label for="username" class="col-sm-3 col-form-label">Username</label>
                   <div class="col-sm-9">
                     <input id="username" placeholder="Username" class="rounded-0 form-control" type="text" name="username" value="">
                     <span class="help-block"></span>
                   </div>
                 </div>

                 <div class="form-group row password" style="margin-top:-12px">
                   <label for="password" class="col-sm-3 col-form-label">Password</label>
                   <div class="col-sm-9">
                     <input id = "password" placeholder="Password" class="rounded-0 form-control" type="password" name="password" value="">
                     <span class="help-block"></span>
                   </div>
                 </div>

                 <div style="margin-top:-12px;" class="form-group row">
                     <label for="akses" class="col-sm-3 col-form-label">Akses</label>
                     <div class="col-sm-9">
                       <select data-placeholder="Pilih Akses" class="rounded-0 form-control akses" id="akses" name="akses">
                         <option value="0">Admin</option>';
                         <option value="1">Petugas</option>';
                       </select>
                       <span class="help-block"></span>
                     </div>
                   </div>

                 <div style="margin-top:-12px;" class="form-group row">
                   <label for="status" class="col-sm-3 col-form-label">Status</label>
                   <div class="col-sm-9">
                     <select data-placeholder="Pilih Akses" class="rounded-0 form-control status" id="status" name="status">
                       <option value="AKTIF">AKTIF</option>';
                       <option value="TIDAK AKTIF">TIDAK</option>';
                     </select>
                     <span class="help-block"></span>
                   </div>
                 </div>


               </div>
             </form>
           </div>
           <div class="modal-footer bg-light">
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-warning " data-dismiss="modal"><i class="nav-icon icon-logout"></i> Selesai</button>
             <button id="simpanData" name="btnSave" type="button" class="float-left ml-1 btn-icon btn-pill btn btn-primary"><b><i class="nav-icon icon-share-alt"></i> Simpan Data</b></button>
           </div>
         </div>
       </div>
     </div>

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
             <input type="hidden" id="x_id_hapus" name="x_id_hapus" value="">
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
             <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fa fa-trash"></i> Hapus Data Jadwal</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <input type="hidden" id="x_id_hapus_all" name="x_id_hapus_all" value="">
             <div class="pesanmodal"></div>
           </div>
           <div class="modal-footer bg-light">
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-secondary" data-dismiss="modal"><i class="icon icon-logout"></i> Batal</button>
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-danger " id="delete-confirm-button"><i class="fa fa-trash"></i>Konfirmasi & Hapus Data</button>
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
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-warning " data-dismiss="modal"><i class="fa fa-trash"></i> OK, Mengerti
             </button>
           </div>
         </div>
       </div>
     </div>

     <div class="modal fade docs-example-modal-lg" id="modal_form_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
       <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
           <div class="modal-header bg-danger">
             <h5 class="modal-title" id="modal-title">Title</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body form">
             <form enctype="multipart/form-data" action="#" id="export_excel" class="form-horizontal">
               <div class="form-body">
                 <div class="form-group row">
                   <div class="col-sm-12">
                     <input accept=".xls,.xlsx,.csv" placeholder="Pilih File" class="rounded-0 form-control" type="file" id="excel_file" name="excel_file" value="">
                   </div>
                   <div class="mt-2 col-sm-9">
                     <p class="text-muted well well-sm no-shadow">
                        Silahkan upload Biodata Siswa dari file Microsoft Excel dengan format
                      .xls, .xlsx, .csv. Download contoh template Biodata
                      <a href="files/Biodata.xlsx">Biodata.xlsx</a> atau
                      <a href="files/Biodata.xls">Biodata.xls</a> atau
                      <a href="files/Biodata.csv">Biodata.csv</a></p>
                   </div>
                 </div>
               </div>
             </form>
           </div>
           <div class="modal-footer bg-light">
             <button type="button" class="rounded-0 btn btn-warning" data-dismiss="modal"><i class="nav-icon icon-logout"></i> Selesai</button>
             <button id="export" name="export" type="button" class="rounded-0 btn btn-danger"><b><i class="nav-icon icon-share-alt"></i> Upload Data</b></button>
           </div>
         </div>
       </div>
     </div>
   </main>
   <script type="text/javascript" src="js/users.js"></script>
<?php
  require('footer.php');
 ?>
