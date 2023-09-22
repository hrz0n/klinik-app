var qty={};
var orderId = 12;
var iditem =0 ;
var urlserver ;
var add = true;
var idPasien =0 ;

var otomatis = true;
$(document).ready(function() {
  // var idhargarow = 1;
  var idrow=1;
  var idhargarow = $('#emp_details').find('tr').length;
  if (idhargarow == 0) {
    idhargarow = 1;
  }
  add = true;
  otomatis = true;
  idPasien = 0;
  iditem = $('#daftaritem').val();
  urlserver = "api/transaksiApi.php";
  getKategori();
  getDokter();
  $('#simpanTransaksi').attr('disabled', 'disabled');
  $('#delete_all').attr('disabled', 'disabled');
  $('#cetaktransaksi').attr('disabled', 'disabled');
  $('#tambahitem').attr('disabled', false);

  $('#nofaktur').text(noFaktur());
  $('input[type=radio][name="jk"][value=L]').prop('checked', true);
  $('input[type=radio][name="tr"][value=otomatis]').prop('checked', true);
  hideTrManual();

  $('#resetFaktur').click(function () {
    $('#nofaktur').text(noFaktur());
  });
  $('#resetPasien').click(function () {
    resetPasien();
    getDokter();
    idPasien = 0;
  });

  $('#datanamapasien').autocomplete({
    dataType:'json',
    paramName: 's',
    type: 'POST',
    serviceUrl: urlserver+"?aksi=pasien",
    onSelect: function (suggestion) {
      add = false;
      var namaP = suggestion.value;

      re = /\[(.*)\]/i;
      idPasien = namaP.match(re)[1];
      idPasien = parseInt(idPasien);

      $('#idpasien').val(idPasien);

      getBiodataPasien(idPasien);
    }
  });

  $('#simpanTransaksi').click(function () {
    var nofaktur = $('#nofaktur').text();
    var namaPs = $('#datanamapasien').val();
    if (namaPs == "" || iditem == 0) {
      toastr.error("Daftar Item dan Nama Pasien tidak boleh Kosong!", 'Error');
    } else {
      if (add) {
        // insert user baru juga
        insertPasien();
        // simpanData2();
      } else {
        simpanData2(0);
        // getdetailTransaksi(nofaktur);


      }


    }

  });


  $('#cetaktransaksi').click(function(){
    var nofaktur = $('#nofaktur').text();
    var redirectWindow = window.open('transaksiex.php?no='+nofaktur, '_blank');
   redirectWindow.location;
 });

 $('#hapustransaksi').click(function(){
   var idtr = $('#nofaktur').text();
   $.ajax({
    type: "POST",
    url: urlserver + "?aksi=hapustransaksi",
    cache:false,
    data: 'x_id_tr='+idtr,
    success: function(data) {
      toastr.success('Data Berhasil dihapus','Sukses');
      $('#konfirmasiTransaksi').modal('hide');

      resetPasien();
      getDokter();
      idPasien = 0;
      $('#nofaktur').text(noFaktur());
      $('#simpanTransaksi').attr('disabled', 'disabled');
      $('#delete_all').attr('disabled', 'disabled');
      $('#cetaktransaksi').attr('disabled', 'disabled');

    },
    error: function (jqXHR, textStatus, errorThrown) {
      toastr.error('Ouppsss... Gagal menghapus data!','Error');
    }
   });
});

  $('#ctr').click(function(){
  var no = $('[name=x_id_faktur]').val();
  var redirectWindow = window.open('transaksiex.php?no='+no, '_blank');
   redirectWindow.location;
 });


  $('#tambahitem').click(function () {

    var idharga=$(this).closest("tr").find("td:nth-child(1)").text();
    var product=$(this).closest("tr").find("td:nth-child(2)").text();
    var price=$(this).closest("tr").find("td:nth-child(3)").text();

    if (otomatis) {
      iditem = $('#daftaritem').val();
      if (iditem > 0) {
        var total = 0;

        $.ajax({
          type:'POST',
          url:urlserver+"?aksi=itemdetail&x_id_item="+iditem,
          dataType: "json",
          data: //JSON.stringify(order),
          {
          },
          contentType: "application/json; charset=utf-8",
          success:function(data) {


            $('#simpanTransaksi').attr('disabled', false);
            $('#delete_all').attr('disabled', false);


            if (qty.hasOwnProperty(idharga)) {
              qty[idharga]++;
            } else {
              qty[idharga] = 1;
            }
              value = data;
              var qtyItm = 0;
              qtyItm=  $("#qty"+value.idHarga);
              if (qtyItm.length) {

                var x= parseInt(qtyItm.text());
                var datax = parseInt(x + 1);
                var y = parseFloat($("#qty"+value.idHarga).text());
                qtyItm.html(datax);
                $("#price"+value.idHarga).html("Rp. "+format_uang(parseFloat(value.harga) * datax));
                $(".hargaTotal"+value.idHarga).val(parseFloat(value.harga) * datax);

              } else {

                idrow = $('#emp_details').find('tr').length;
                // if (idrow == 0) {
                //   idrow =1;
                // }
                var newid = idrow++;

                var row=$(

                  "<tr id='"+newid+"'><td> <input id='idharga"+newid+"' class='idharga"+newid+"' type='hidden' value='"+value.idHarga+"'><input id='hargaSatuan"+newid+"' class=\"hargaSatuan"+value.idHarga+"\" type='hidden' value='"+value.harga+"'> <input id='hargaTotal"+newid+"' class=\"hargaTotal"+value.idHarga+"\" type='hidden' value='"+value.harga+"'> <input type='checkbox' class='case'/></td>"+"<td id=\"idpelayanan"+newid+"\" class='pl-2'>"+data.namaPelayanan+"</td>"+
                  "<td class=\"qty"+newid+" text-center\" id=\"qty"+value.idHarga+"\">1</td>"+
                  "<td style='padding-left:10px' id=\"price"+value.idHarga+"\" class='count-me'>Rp. "+format_uang(value.harga)+"</td>"+
                  "</tr>");
                $("#order").append(row).removeClass("hidden");
              }

              var tds = document.getElementById('order').getElementsByTagName('td');
              var sum = 0;
              for(var i = 0; i < tds.length; i ++) {
                  if(tds[i].className == 'count-me') {
                    var str = tds[i].innerHTML;
                    var nstr = str.replace("Rp. ", "");
                    var t = nstr.replace(/\./g, "");

                      sum += isNaN(t) ? 0 : parseInt(t);
                  }
              }
              var totalBayar = format_uang(sum);
              document.getElementById('total').innerHTML = "<b>Rp. " + totalBayar + "</b>";

            },
          fail:function() {
              alert('problem')
          }
        });

      } else {
        toastr.error("Silahkan pilih Daftar Item terlebih dahulu!", 'Error');
      }


    } else {
      var manualitem = $('#daftarpelayanan').val();
      var manualharga = $('#hargapelayanan').val();
      var qtymanual = $('#qtymanual').val();
      if (manualitem == "" || manualharga == 0 || qtymanual == 0) {
        toastr.error("Daftar Item dan Harga Tidak boleh kosong!", 'Error');
      } else {
        var tharga = manualharga * qtymanual;
        var total = 0;
        idrow = $('#emp_details').find('tr').length;
        var newid = idrow++;
        //
        var row=$(

          "<tr id='"+newid+"'><td> <input id='idharga"+newid+"' class='idharga"+newid+"' type='hidden' value='"+manualharga+"'><input id='hargaSatuan"+newid+"' class=\"hargaSatuan"+newid+"\" type='hidden' value='"+manualharga+"'> <input id='hargaTotal"+newid+"' class=\"hargaTotal"+newid+"\" type='hidden' value='"+tharga+"'> <input type='checkbox' class='case'/></td>"+"<td id=\"idpelayanan"+newid+"\" class='pl-2'>"+manualitem+"</td>"+
          "<td class=\"qty"+newid+" text-center\" id=\"qty"+newid+"\">"+qtymanual+"</td>"+
          "<td style='padding-left:10px' id=\"price"+newid+"\" class='count-me'>Rp. "+format_uang(tharga)+"</td>"+
          "</tr>");
        $("#order").append(row).removeClass("hidden");

        $('#simpanTransaksi').attr('disabled', false);
        $('#delete_all').attr('disabled', false);
      }


      var tds = document.getElementById('order').getElementsByTagName('td');
      var sum = 0;
      for(var i = 0; i < tds.length; i ++) {
          if(tds[i].className == 'count-me') {
            var str = tds[i].innerHTML;
            var nstr = str.replace("Rp. ", "");
            var t = nstr.replace(/\./g, "");

              sum += isNaN(t) ? 0 : parseInt(t);
          }
      }
      var totalBayar = format_uang(sum);
      document.getElementById('total').innerHTML = "<b>Rp. " + totalBayar + "</b>";




    }





  });


  $(document).on('change', 'table tbody input:checkbox', function () {
    if ($(this).is(':checked')) {
        $(this).closest('tr').addClass('text-primary');
    }
    else {
        $(this).closest('tr').removeClass('text-primary');
    }
    var id = [];
    $(':checkbox:checked').each(function (i) {
        id[i] = $(this).val();
    });
  });

  $(document).on('click', 'thead input:checkbox', function () {
      var c = this.checked;
      $('tbody input:checkbox').prop('checked', c);
      $('tbody input:checkbox').trigger('change');

  });

  $(document).on('click', '.delete_all', function () {
      var hapus = $('#emp_details input:checked').parents("tr").remove();
      var tds = document.getElementById('order').getElementsByTagName('td');

      var sum = 0;

      // idhargarow = 0;
      // console.log(rowCount);
      for(var i = 0; i < tds.length; i ++) {
          if(tds[i].className == 'count-me') {
              sum += isNaN(tds[i].innerHTML) ? 0 : parseInt(tds[i].innerHTML);
          }
      }
      var totalBayar = format_uang(sum);
      document.getElementById('total').innerHTML = "<b>Rp. " + totalBayar + "</b>";
  });


  $('#trKategori').change(function(e) {
    // e.preventDefault();
    idkategori = $('#trKategori').val();
    getJenis(idkategori);

    // console.log(idkategori);
    getItem(0);
  });

  $('#trjenis').change(function(e) {
    // e.preventDefault();
    idjenis = $('#trjenis').val();
    getItem(idjenis);
  });

  $('#daftaritem').change(function(e) {
    // e.preventDefault();
    iditem = $('#daftaritem').val();
  });



});


function getKategori() {
  $.ajax({
      type: "POST",
      dataType: "json",
      url: urlserver+"?aksi=kat",
      data: '',
      success: function(data){
        var katOpt = "";
          for (i = 0; i< data.data.length;i++) {
              var id = data.data[i]['idKategori'];
              var val = data.data[i]['namaKategori'];
              katOpt += "<option value=''></option>";
              katOpt += "<option value='"+id+"'>"+val+"</option>";
              idkategori = id;
          }
        $('#trKategori').append(katOpt);
      }
  });
}

function getJenis(idkat) {
  $.ajax({
      type: "POST",
      dataType: "json",
      url: urlserver+"?aksi=jenis",
      data: '&x_id='+idkat,
      success: function(data){
        var jeOpt = "";
        jeOpt += "<option value=''>Pilih Jenis Layanan</option>";
          for (i=0; i< data.data.length;i++) {
              var id = data.data[i]['idJenisLayanan'];
              var val = data.data[i]['namaJenisLayanan'];
              jeOpt += "<option value='"+id+"'>"+val+"</option>";
          }
        $('#trjenis').html(jeOpt);
      }
  });
}
function getItem(idjenis) {
  $.ajax({
      type: "POST",
      dataType: "json",
      url: urlserver+"?aksi=item",
      data: '&x_id_jenis='+idjenis,
      success: function(data){
        var jeOpt = "";
        jeOpt += "<option value=''>Pilih Item</option>";
          for (i=0; i< data.data.length;i++) {
              var id = data.data[i]['idHarga'];
              var val = data.data[i]['namaPelayanan'];
              jeOpt += "<option value='"+id+"'>"+val+"</option>";
          }
        $('#daftaritem').html(jeOpt);
      }
  });
}


function format_uang(uang) {
  var	reverse = uang.toString().split('').reverse().join(''),
  ribuan 	= reverse.match(/\d{1,3}/g);
  ribuan	= ribuan.join('.').split('').reverse().join('');
  return ribuan;
}

function noFaktur(){
    var currentTime = new Date();
    var day = ("0" + currentTime.getDate()).slice(-2);
    var month = ("0" + (currentTime.getMonth() + 1)).slice(-2);
    var year = currentTime.getFullYear();
    var time= currentTime.getHours()+currentTime.getMinutes() + currentTime.getSeconds();
    var str1 ="KDM";
    var str2 =str1+year+month+day+time;
    return str2;
}
function resetPasien() {
  add = true;
  $('[name="datanamapasien"]').val("");
  $('[name="nik"]').val("");
  $('[name="namaortu"]').val("");
  $('[name="nohp"]').val("");
  $('[name="alamat"]').val("");
  $('input[type=radio][name="jk"][value=L]').prop('checked', true);
  $('[name="datanamapasien"]').focus();
}

function getDokter() {
  $.ajax({
      type: "POST",
      dataType: "json",
      url: urlserver+"?aksi=dokter",
      data: '',
      success: function(data){
        var jeOpt = "";
        jeOpt += "<option value=''></option>";
          for (i=0; i< data.length;i++) {
              var id = data[i]['idPerawat'];
              var val = data[i]['namaPerawat'];
              jeOpt += "<option value='"+val+"'>"+val+"</option>";
          }
        $('#dokter').html(jeOpt);
      }
  });
}

function getBiodataPasien(id) {
  $.ajax({
      type: "POST",
      dataType: "json",
      url: urlserver+"?aksi=biodataPasien&x_id="+id,
      data: '',
      success: function(data){
        data = data[0];
        var jk = data.jenisKelamin;
        $('input[type=radio][name="jk"][value='+jk+']').prop('checked', true);

        $('[name="x_id"]').val(data.idPasien);
        $('[name="datanamapasien"]').val(data.namaPasien);
        $('[name="nik"]').val(data.nrmPasien);
        $('[name="namaortu"]').val(data.namaOrtu);
        $('[name="nohp"]').val(data.noHp);
        $('[name="alamat"]').val(data.alamatPasien);
      }
  });
}


function simpanData2(idPas) {
  var nofaktur = $('#nofaktur').text();
  var lastRowId = $('#emp_details tr:last').attr("id"); /*finds id of the last row inside table*/
  var idPerawatanIdDokter = new Array();
  var idPerawatanIdPasien = new Array();
  var namaPelayanan = new Array();
  var hargaSatuan = new Array();
  var qty = new Array();
  var hargaTotal = new Array();
  var noFaktur = new Array();
  var tglMasuk = new Array();
  var tglKeluar = new Array();
  var keterangan = new Array();
  var created_at = new Array();
  var updated_at = new Array();


  for ( var i = 0; i <= lastRowId; i++) {
    if (idPas == 0) {
      idPerawatanIdPasien.push($('#idpasien').val());
    } else {
      idPerawatanIdPasien.push(idPas);
    }

    idPerawatanIdDokter.push($('#dokter').val());
    noFaktur.push($('#nofaktur').text());
    namaPelayanan.push($("#idpelayanan"+i).text());
    hargaSatuan.push($("#hargaSatuan"+i).val());
    hargaTotal.push($("#hargaTotal"+i).val());
    qty.push($(".qty"+i).text());
  }

  var sendidPerawatanIdDokter = JSON.stringify(idPerawatanIdDokter);
  var sendidPerawatanIdPasien = JSON.stringify(idPerawatanIdPasien);
  var sendnoFaktur = JSON.stringify(noFaktur);
  var sendPelayanan = JSON.stringify(namaPelayanan);
  var sendHargaSatuan = JSON.stringify(hargaSatuan);
  var sendHargaTotal = JSON.stringify(hargaTotal);
  var sendqty = JSON.stringify(qty);

  $.ajax({
    url: urlserver+"?aksi=simpantransaksi",
    type: "POST",
    dataType: "JSON",
    data: {idPerawatanIdDokter : sendidPerawatanIdDokter , idPerawatanIdPasien : sendidPerawatanIdPasien, noFaktur:sendnoFaktur, namaPelayanan:sendPelayanan, hargaSatuan:sendHargaSatuan, qty:sendqty, hargaTotal:sendHargaTotal},
    beforeSend:function(){
      $('#simpanTransaksi').attr('disabled', 'disabled');
    },
    success: function(data){
      $('#simpanTransaksi').attr('disabled', false);
      if (data.error == 1) {
          toastr.error(data.pesan, 'Error');
      } else {
          $('#simpanTransaksi').attr('disabled', 'disabled');
          $('#cetaktransaksi').attr('disabled', false);
          $('#delete_all').attr('disabled', 'disabled');
          $('#tambahitem').attr('disabled', 'disabled');
          toastr.success(data.pesan, 'Sukses');
          getdetailTransaksi(nofaktur);
      }
    }
  });

}
function insertPasien() {
  var namapasien = $('#datanamapasien').val();
  var nik = $('#nik').val();
  var namaortu = $('#namaortu').val();
  var jk = $('input:radio[name=jk]:checked').val();

  var hp = $('[name="nohp"]').val();
  var alamat = $('[name="alamat"]').val();
  $.ajax({
    url: urlserver+"?aksi=simpanpasien",
    type: "POST",
    dataType: "JSON",
    data: {namapasien : namapasien , nrm : nik, namaortu : namaortu, jk:jk, hp:hp, alamat:alamat},
    beforeSend:function(){
      $('#add').attr('disabled', 'disabled');
    },
    success: function(data){
      console.log(data);
      $('#add').attr('disabled', false);
      if (data.error == 0) {
        if (data.data > 0) {
          simpanData2(data.data);

        } else {
          toastr.error("Gagal menyimpan data Transaksi", 'Sukses');
        }
      }

    }
  });

}

function getdetailTransaksi(nofaktur) {
  $.ajax({
    url: urlserver+"?aksi=detailtransaksi&nomorfaktur="+nofaktur,
    type: "POST",
    dataType: "JSON",
    data: '',
    beforeSend:function(){
      $('#add').attr('disabled', 'disabled');
    },
    success: function(data){
      var rowHarga ="";
      if (data.error == 1) {
          toastr.error(data.pesan, 'Error');
      } else {
        $('#add').attr('disabled', false);

        var d = new Date(data['data'][0].created_at);
        var e = formatDate(d);
        $('[name="x_id_faktur"]').val(data['data'][0].noFaktur);
        $('[name="nofaktur"]').text(data['data'][0].noFaktur);
        $('[name="namapasien"]').text(data['data'][0].namaPasien);
        $('[name="tgl"]').text(e);
        $('[name="alamat"]').text(data['data'][0].nrmPasien);

				var no = 0;

        var totalPembayaran = 0;
        var qty = 1;

        $.each(data['data'], function( key, value ) {
            var hargatotal = 0;
            var harga = 0;
						no = no + 1;
            harga = parseInt(value['harga']);
            qty = parseInt(value['qty']);
            hargatotal = parseInt(value['hargaTotal']);

						rowHarga += "<tr><td class='pl-2'>"+no+".</td>";
            rowHarga +="<td class='pl-2'> " + value['namaItem'] + " </td>";
            rowHarga +="<td class='pl-2'> Rp. "+ format_uang(value['hargaSatuan']) +" </td>";
            rowHarga +="<td class='pl-2 text-center'> "+ value['qty'] +" </td>";
            rowHarga +="<td class='pl-2'> Rp. "+ format_uang(hargatotal) +" </td></tr>";
            totalPembayaran += hargatotal;
					});
          rowHarga += "<tr><td colspan='4' class='text-right'><b> TOTAL PEMBAYARAN </b></td><td class='pl-2'> <b>Rp. "+format_uang(totalPembayaran)+"</b></td></tr>";

        $('#datatransaksi').html(rowHarga);


        $('#konfirmasiTransaksi').modal('show');
      }

    }
  });

}

function formatDate(date) {
  return ("0" + (date.getDate() + 1)).slice(-2) + "/" + ("0" + date.getMonth()).slice(-2) + "/" + date.getFullYear();
}


function hideTrManual() {
  $('input[type=radio][name="tr"][value=otomatis]').prop('checked', true);

  $("#daftarpelayanan").val("");
  $("#hargapelayanan").val("");

  $("#manualitem").addClass("hidden");
  $("#manualharga").addClass("hidden");
  $("#manualqty").addClass("hidden");
  // $("#manualharga").addClass("hidden");

  $("#otomatiskategori").removeClass("hidden");
  $("#otomatisjl").removeClass("hidden");
  $("#otomatisdi").removeClass("hidden");
  otomatis = true;
}

function hideTrOtomatis() {
  $('input[type=radio][name="tr"][value=manual]').prop('checked', true);
  $("#manualitem").removeClass("hidden");
  $("#manualharga").removeClass("hidden");
  $("#manualqty").removeClass("hidden");
  // $("#manualharga").removeClass("hidden");


  $("#otomatiskategori").addClass("hidden");
  $("#otomatisjl").addClass("hidden");
  $("#otomatisdi").addClass("hidden");
  $("#daftarpelayanan").focus();


  otomatis = false;
}
