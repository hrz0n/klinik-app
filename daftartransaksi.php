  <?php
  require_once('controller/config.php');
  require_once('controller/configdb.php');

  global $conn;

  if (!isset($_SESSION['login'])) {
    header("location:login.php");
    exit();
  }
  $level = $_SESSION['tipeuser'];
  $title = ' Daftar Transaksi';

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
                 <b><i class="cil-short-text"></i> <?php echo $title ?></b>
               </div>
               <div class="card-body">
                 <div class="d-flex flex-wrap justify-content-between mb-1">
                    <div class="col-md-6">
                      <button type="button" id="refreshData" class="float-left btn-icon btn-pill btn btn-success refreshData"><i class="cil-sync"></i> Refresh</button>
                      <a href="transaksi.php"  class="ml-1 float-left btn-icon btn-pill btn btn-warning refreshData"><i class="icon icon-paper-plane"></i> Tambah Transaksi</a>
                      <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-danger " name="hapusall" id="hapusall"><i class=" cil-trash"></i> Hapus Semua</button>
                    </div>
                      <div class="col-md-4">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text bg-light">
                              <i class="cil-search "></i>
                            </div>
                          </div>
                          <input placeholder="Cari No. Faktur, Nama, NRM ..." type="text" class="btn-outline-2x form-control global_filter" id="global_filter">
                        </div>
                      </div>
                  </div>

                 <form class="mt-0" id="form_table" action="" method="post" >
                   <input type="hidden" value="" name="x_id_tr_hapus" />
                 <table style="width:100%" id="tabelAuthor" class="table table-bordered table-responsive table-sm  table-hover">
                   <thead class="bg-light" >
                     <tr><th style="padding-left:15px;max-width:10px"> <input name="select_all" value="1" id="altet-select-all" type="checkbox" /></th>
                       <th class="pl-2" style="width:310px">No Faktur</th>
                       <th class="pl-2" style="width:310px">Nama Pasien</th>
                       <th class="pl-2" style="width:310px">N R M</th>
                       <th class="pl-2" style="width:310px">Tgl Transaksi</th>
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

     <div class="modal fade docs-example-modal-lg" id="konfirmasiTransaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
         <div class="modal-content">
           <div class="modal-header bg-primary">
             <h5 class="modal-title" id="exampleModalCenterTitle"><i class="cil-fullscreen-exit"></i> Konfirmasi dan Cetak Nota Transaksi</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <input type="hidden" id="x_id_faktur" name="x_id_faktur" value="">
             <h3><?= getConfig('namaApp') ?></h3>
             <p style="margin-top:-10px"><?= getConfig('alamat_config') ?></p>
             <hr style="margin-top:-10px">

             <div class="form-group row">
               <label for="namaMsdm" class="col-sm-2 col-form-label">Nomor Faktur</label>
               <div class="col-sm-9">
                 <h4 class="text-primary"><span id='nofaktur' name='nofaktur'><b>KDM2020041458</b></span></h4>
               </div>
             </div>
             <div class="form-group row" style="margin-top:-18px">
               <label class="col-sm-2 col-form-label" style="margin-top:-8px">Tanggal</label>
               <div class="col-sm-9">
                 <span id='tgl' name='tgl'></span>
               </div>
             </div>
             <div class="form-group row" style="margin-top:-18px">
               <label class="col-sm-2 col-form-label" style="margin-top:-8px">Nama Pasien</label>
               <div class="col-sm-9">
                 <span id='namapasien' name='namapasien'></span>
               </div>
             </div>
             <div class="form-group row" style="margin-top:-18px">
               <label class="col-sm-2 col-form-label" style="margin-top:-8px">N R M</label>
               <div class="col-sm-9">
                 <span id='alamat' name='alamat'></span>
               </div>
             </div>

             <div style="margin-top:-12px;" class="form-group row">

               <div class="container table-responsive">
                 <table style="width:100%" id="nota" class="table table-bordered  table-sm  table-hover">
                   <thead class="bg-light" >
                   <tr >
                     <th style="max-width:20px;">No</th>
                     <th>Nama Pelayanan</th>
                     <th>Harga Satuan</th>
                     <th class="text-center">QTY</th>
                     <th>Total Harga</th>
                   </tr>
                 </thead>
                   <tbody id="datatransaksi">

                   </tbody>
                 </table>
               </div>



             </div>

           </div>
           <div class="modal-footer bg-light">
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-primary" id="ctr"><i class="cil-print"></i> Cetak Transaksi</button>

             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-danger " id="hapustransaksi"><i class="cil-trash"></i> Hapus Transaksi</button>

             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-secondary" data-dismiss="modal"><i class="icon icon-logout"></i> Selesai</button>
           </div>
         </div>
       </div>
     </div>

   </main>
   <script type="text/javascript" src="js/daftartransaksi.js"></script>
<?php
  require('footer.php');
 ?>
