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
     <div style="padding:6px; margin-top:-6px;" class="">
       <div class="animated fadeIn">
         <div class="row">
           <div class="col-lg-12">
             <div class="card">
               <div class="card-body">
  <div class="row" style="margin-top:-16px">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header bg-light text-primary"><strong>Data</strong> <small>Pelayanan</small> <button class="btn btn-outline-2x btn-pill btn-outline-gray btn-sm float-right text-primary" id="resetFaktur"> Reset No Faktur</button></div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group row">
                <label class="col-md-3 col-form-label">No. Faktur</label>
                <div class="col-md-9">
                  <h2 class="nofaktur text-primary" name="nofaktur" id="nofaktur">....</h2>
                </div>
              </div>

              <div class="form-group row" style="margin-top:-8px;">
                <label class="col-md-3 col-form-label" for="hf-password"></label>
                <div id="jjjj" class="col-md-9" style="margin-top:-6px;">
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input"  type="radio" value="otomatis" name="tr" id="otomatis" onclick="javascript:hideTrManual();">
                    <label onclick="javascript:hideTrManual();" class="form-check-label" for="tr">Transaksi Otomatis</label>
                  </div>
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input" type="radio" value="manual" name="tr" id="manual" onclick="javascript:hideTrOtomatis();">
                    <label onclick="javascript:hideTrOtomatis();" class="form-check-label"  for="tr">Transaksi Manual</label>
                  </div>
                </div>
              </div>

              <div id="otomatiskategori" class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">Kategori</label>
                <div class="col-md-9">
                  <select data-placeholder="Pilih Kategori Pelayanan" class="form-control btn-outline-2x" name="trKategori" id="trKategori">
                  </select>
                </div>
              </div>

              <div id="otomatisjl" class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">Jenis Layanan</label>
                <div class="col-md-9">
                  <select data-placeholder="Pilih Jenis Pelayanan" class="form-control btn-outline-2x" name="trjenis" id="trjenis">
                  </select>
                </div>
              </div>

              <div  id="otomatisdi" class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">Daftar Item</label>
                <div class="col-md-9">
                  <select data-placeholder="Pilih Daftar Item" class="form-control btn-outline-2x" name="daftaritem" id="daftaritem">
                  </select>
                </div>
              </div>

              <div id="manualitem" class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">Daftar Item</label>
                <div class="col-md-9">
                  <input class="form-control btn-outline-2x" type="text" name="daftarpelayanan" value="" placeholder="Isi Nama Item" id="daftarpelayanan">
                </div>
              </div>

              <div id="manualharga" class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">Harga (Rp.)</label>
                <div class="col-md-9">
                  <input class="form-control btn-outline-2x" type="number" name="hargapelayanan" value="" min="0" placeholder="Isi Harga, Ex: 25000" id="hargapelayanan">
                </div>
              </div>

              <div id="manualqty" class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">Qty</label>
                <div class="col-md-9">
                  <input class="form-control btn-outline-2x" type="number" name="qtymanual" value="1" min="1" placeholder="Qty Item" id="qtymanual">
                </div>
              </div>

              <div class="form-group row" style="margin-top:-2px;">
                <label class="col-md-3 col-form-label"></label>
                <div class="col-md-9">
                  <button type="button" class="float-left btn-primary btn-icon btn-block btn-pill btn  btn-outline-2x" name="tambahitem" id="tambahitem"><i class="cil-cloud-download "></i> Tambahkan Item
                  </button>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="card">
        <div class="card-header bg-light text-primary"><strong>Data </strong> <small>Pasien</small> <button class="btn btn-outline-2x btn-pill btn-outline-gray btn-sm float-right text-primary" id="resetPasien"> Reset Data Pasien</button></div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group row">
                <label class="col-md-3 col-form-label" for="datanamapasien">Nama Pasien</label>
                <div class="col-md-9">
                  <input id="datanamapasien" class="form-control btn-outline-2x" type="text" name="datanamapasien" placeholder="Nama Pasien">
                  <input id="idpasien" class="form-control btn-outline-2x" type="hidden" name="idpasien">
                </div>
              </div>
              <div class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">N R M</label>
                <div class="col-md-9">
                  <input id="nik" class="form-control btn-outline-2x" type="text" name="nik" placeholder="N R M">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label" for="hf-password" style="margin-top:-12px;">Jenis Kelamin</label>
                <div id="jenisk" class="col-md-9" style="margin-top:-6px;">
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input"  type="radio" value="L" name="jk">
                    <label class="form-check-label" for="jk">Laki-Laki</label>
                  </div>
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input" type="radio" value="P" name="jk">
                    <label class="form-check-label"  for="jk">Perempuan</label>
                  </div>
                </div>

              </div>
              <div class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">Nama Ortu</label>
                <div class="col-md-9">
                  <input id="namaortu" class="form-control btn-outline-2x" type="text" name="namaortu" placeholder="Nama Ayah/Ibu">
                </div>
              </div>
              <div class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">No. Hp</label>
                <div class="col-md-9">
                  <input id="nohp" class="form-control btn-outline-2x" type="text" name="nohp" placeholder="No. Hp">
                </div>
              </div>
              <div class="form-group row" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">Alamat</label>
                <div class="col-md-9">
                  <input id="alamat" class="form-control btn-outline-2x" type="text" name="alamat" placeholder="Alamat">
                </div>
              </div>
              <div class="form-group row mb-0" style="margin-top:-12px;">
                <label class="col-md-3 col-form-label" for="hf-password">Dokter</label>
                <div class="col-md-9">
                  <select id="dokter" data-placeholder="Nama Dokter yg merawat" class="form-control btn-outline-2x" name="dokter">
                  </select>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>

  </div>








                 <form class="mt-0" id="form_table" action="" method="post" >

                 <table style="width:100%" id="order" class="table table-bordered table-responsive table-sm  table-hover">
                   <thead class="bg-light" >
                     <tr>
                       <th>
                         <input class="case" name="select_all" value="1" id="users-select-all" type="checkbox" />
                       </th>
                       <th class="pl-2" style="width:1050px">Daftar Nama Pelayanan</th>
                       <th class="pl-2 text-center" style="width:100px">Qty</th>
                       <th class="pl-2" style="width:500px">Total Harga</th>
                     </tr>
                   </thead>
                   <tbody id="emp_details">

                   </tbody>
                   <tfoot>
                     <tr>
                       <td colspan="3" class="text-right" id="harga"><b>TOTAL PEMBAYARAN<b></td>
                       <td id="total" class="pl-2"><b>Rp.0</b></td>
                     </tr>
                   </tfoot>
                 </table>
                 <input type="hidden" name="namaPelayanan[]" value="">
                 <input type="hidden" name="qty[]" value="">
                 <input type="hidden" name="harga[]" value="">
                 </form>

                 <button type="button" class="delete_all float-left ml-1 btn-icon btn-pill btn btn-danger btn-outline-2x" name="delete_all" id="delete_all"><i class="fa fa-trash"></i> Hapus Terpilih
                 </button>

                 <button id="simpanTransaksi" name="simpanTransaksi" type="button" class="float-left ml-1 btn-icon btn-pill btn btn-warning "><i class="icon icon-paper-plane"></i> Simpan Data
                 </button>

                 <button name="cetaktransaksi" id="cetaktransaksi" type="button" class="float-left ml-1 btn-icon btn-pill btn btn-info "><i class="fa fa-print"></i> Cetak Transaksi
                 </button>

                 <a href="transaksi.php" class="float-right ml-1 btn-icon btn-pill btn btn-success "><i class="icon icon-paper-plane"></i> Transaksi Baru
                 </a>

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
             <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fa fa-trash"></i> Hapus Data</h5>
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
             <button type="button" class="float-left ml-1 btn-icon btn-pill btn btn-danger " id="delete-confirm-button"><i class="fa fa-trash"></i>Konfirmasi & Hapus</button>
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
   </main>

   <script type="text/javascript" src="js/testtransaksi.js"></script>
<?php
  require('footer.php');
 ?>
