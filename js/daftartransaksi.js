var save_method;
var table;
var urlserver;

$(document).ready(function() {

    urlserver = "api/transaksiApi.php";
    save_method = 'add';
    table = $('#tabelAuthor').DataTable({
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "bDestroy": true,
        "olReorder": true,
        "ordering": true,
        "selected": true,
        'searching': true,
        "info": false,
        "ajax": {
            url: urlserver,
            type: 'POST'
        },
        "dom": 'tp',
        "pageLength": 15,
        "columnDefs": [{
                "targets": -1,
                "data": null,
                'className': 'text-center',
                "defaultContent": "<div class='mb-0 mt-0 btn-group btn-group-sm' role='group'> <a class='tblLihat btn btn-sm btn-warning' title='Lihat Detail' href='javascript:void(0)'><i class='cil-external-link' aria-hidden='true'></i></a> <a class='tblCetak btn btn-sm btn-success' title='Cetak Data' href='javascript:void(0)'><i class='cil-print' aria-hidden='true'></i></a> <a class='tblHapus btn btn-sm btn-danger' title='Hapus Data' href='javascript:void(0)'><i class='cil-trash' aria-hidden='true'></i></a></div>"
            },
            {
                'className': 'text-center',
                'targets': 0,
                "orderable": false,
                'searchable': false,
                'checkboxes': {
                    'selectRow': false,
                }
            },
            {
                "targets": -1,
                "orderable": false
            },
        ],
        'select': {
            'style': 'multi',
            'selector': 'td:first-child'
        },
        'order': [
            [1, 'asc']
        ]
    });
    //refres table
    $('#refreshData').click(function() {
        $('#tabelAuthor').DataTable().ajax.reload();
    });

    $('input.global_filter').on('keyup click', function() {
        filterGlobal();
    });

    $('input.column_filter').on('keyup click', function() {
        filterColumn($(this).parents('tr').attr('data-column'));
    });


    $('#ctr').click(function(){
      var no = $('[name=x_id_faktur]').val();
      var redirectWindow = window.open('transaksiex.php?no='+no, '_blank');
       redirectWindow.location;
     });

    //simpan
    $('#simpanData').click(function() {
        var url;
        if (save_method == 'add') {
            url = "api/usersApi.php?aksi=simpan";
        } else {
            url = "api/usersApi.php?aksi=update";
        }



        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data) {
                if (data.error == 1) {
                    toastr.error(data.pesan, 'Error');
                } else {
                    toastr.success(data.pesan, 'Sukses');
                    $('#modal_form').modal('hide');
                    $('#tabelAuthor').DataTable().ajax.reload();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error('Data User gagal disimpan', 'Warning');
            }
        });
    });

    $('#tabelAuthor tbody').on('click', '.tblCetak', function() {
        var id = table.row($(this).parents('tr')).data();
        var redirectWindow = window.open('transaksiex.php?no='+id[0], '_blank');
        redirectWindow.location;
    });

    $('#tabelAuthor tbody').on('click', '.tblLihat', function() {
        var id = table.row($(this).parents('tr')).data();
        var rowHarga ="";
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $.ajax({
            url: urlserver + "?aksi=detailtransaksi&nomorfaktur=" + id[0],
            type: "POST",
            dataType: "JSON",
            success: function(data) {

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
                      var hargatotal = 0;
                      var harga = 0;
                      $.each(data['data'], function( key, value ) {
              						no = no + 1;
                          hargatotal = parseInt(value['hargaTotal']);
              						rowHarga += "<tr><td class='pl-2'>"+no+".</td>";
                          rowHarga +="<td class='pl-2'> " + value['namaItem'] + " </td>";
                          rowHarga +="</td><td class='pl-2'> Rp. "+ format_uang(value['hargaSatuan']) +" </td>";
                          rowHarga +="<td class='pl-2 text-center'> "+ value['qty'] +" </td>";
                          rowHarga +="</td><td class='pl-2'> Rp. "+ format_uang(hargatotal) +" </td></tr>";
                          totalPembayaran += hargatotal;
              					});
                        rowHarga += "<tr><td colspan='4' class='text-right'><b> TOTAL PEMBAYARAN </b></td><td > <b>Rp. "+format_uang(totalPembayaran)+"</b></td></tr>";

                      $('#datatransaksi').html(rowHarga);

                      $('.modal-title').html('<i class="cil-short-text"></i> Detail Transaksi No. <b>'+(data['data'][0].noFaktur)+'</b>');
                      $('#konfirmasiTransaksi').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    });


	$('#hapusall').click(function () {
		rows_selected = table.column(0).checkboxes.selected().count();
		if (rows_selected > 0) {
      rows_selected = 0;
			$('.pesanmodal').text('Apakah anda yakin untuk menghapus semua data yang dipilih?');
			$('#confirmDelete').modal('show');
			$('.modal-title').html('<i class="cil-trash"></i> Hapus Data Transaksi');
		} else {
			$('.pesanmodal').text('Maaf, silahkan pilih Data yang akan dihapus terlebih dahulu!');
			$('#warningModal').modal('show');
			$('.modal-title').html('<i class="fa fa-exclamation-triangle text-white"></i> <b class="text-white">Perhatian!</b>');
		}
	});




    //hapus
    $('#tabelAuthor tbody').on('click', '.tblHapus', function() {
      var id = table.row($(this).parents('tr')).data();
        $('[name="x_id_tr_hapus"]').val(id[0]);
        $('#confirmDelete2').modal('show');
        $('.modal-title').html('<i class="cli-trash"></i> Hapus Data');
    });

    $('#modal_form').on('hidden.bs.modal', function() {
        $('#modal_form form')[0].reset();

    });

    $('#modal_form').on('shown.bs.modal', function() {
        $('#username').focus();
    });


    $('#delete-confirm-button2').click(function() {
        var idRow = $('input:hidden[name=x_id_tr_hapus]').val();
        $.ajax({
            type: "POST",
            url: urlserver + "?aksi=hapustransaksi",
            cache: true,
            data: 'x_id_tr=' +idRow,
            dataType: "JSON",
            success: function(data) {
                if (data.error == 0) {
                    toastr.success(data.pesan, 'Sukses');
                } else {
                    toastr.error(data.pesan, 'Error');
                }
                $('#confirmDelete2').modal('hide');
                $('#tabelAuthor').DataTable().ajax.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error('Ouppsss... Gagal menghapus data!', 'Error');
            }
        });
    });

    $('#delete-confirm-button').click(function(){
      var ID;
    	var rows_selected = table.column(0).checkboxes.selected();
    	$.each(rows_selected, function(index, rowId){
    		 $(form_table).append(
    				 $('<input>')
    						.attr('type', 'hidden')
    						.attr('name', 'x_id_tr_hapus[]')
    						.val(rowId)
    		 );
    	});
    	ID = rows_selected.join("','");
     $.ajax({
    	 type: "POST",
    	 url: urlserver + "?aksi=hapustransaksi",
    	 cache:false,
       dataType: 'JSON',
    	 data: 'x_id_tr='+ID,
    	 success: function(data) {
         if (data.error == 0) {
             toastr.success(data.pesan, 'Sukses');
         } else {
             toastr.error(data.pesan, 'Error');
         }
         $('#confirmDelete').modal('hide');
         $('#tabelAuthor').DataTable().ajax.reload();


    	 },
    	 error: function (jqXHR, textStatus, errorThrown) {
    		 toastr.error('Ouppsss... Gagal menghapus data!','Error');
    	 }
     });
    });

    $('#hapustransaksi').click(function(){
      var idtr = $('#nofaktur').text();
      $.ajax({
       type: "POST",
       url: urlserver + "?aksi=hapustransaksi",
       cache:false,
       data: 'x_id_tr='+idtr,
       success: function(data) {

         if (data.error == 0) {
             toastr.success(data.pesan, 'Sukses');
         } else {
             toastr.error(data.pesan, 'Error');
         }
         $('#konfirmasiTransaksi').modal('hide');
         $('#tabelAuthor').DataTable().ajax.reload();

       },
       error: function (jqXHR, textStatus, errorThrown) {
         toastr.error('Ouppsss... Gagal menghapus data!','Error');
       }
      });
   });


});




function add_new() {
    save_method = 'add';
    $('#modal_form').trigger('reset');

    $("#status").val(0);
    $('#status').find('option[value="0"]').prop('selected', true).change();

    $("#perangkatTanding").val("");
    $('#perangkatTanding').find('option[value=""]').prop('selected', true).change();



    $("#akses").val(0);
    $('#akses').find('option[value="0"]').prop('selected', true).change();



    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
		$( ".perangkatTanding" ).hide();
    $('.modal-title').html('<i class="icon icon-plus"></i> Tambah Data User');
}





function redirectToHome() {
    $.ajax({
        url: "index.php",
    }).done(function(data) {
        window.location.reload();
    });
}


function filterGlobal() {
    $('#tabelAuthor').DataTable().search(
        $('#global_filter').val(),
        $('#global_regex').prop('checked'),
        $('#global_smart').prop('checked')
    ).draw();
}

function filterColumn(i) {
    $('#tabelAuthor').DataTable().column(i).search(
        $('#col' + i + '_filter').val(),
        $('#col' + i + '_regex').prop('checked'),
        $('#col' + i + '_smart').prop('checked')
    ).draw();
}

function formatDate(date) {
  return ("0" + (date.getDate() + 1)).slice(-2) + "/" + ("0" + date.getMonth()).slice(-2) + "/" + date.getFullYear();
}
function format_uang(uang) {
  var	reverse = uang.toString().split('').reverse().join(''),
  ribuan 	= reverse.match(/\d{1,3}/g);
  ribuan	= ribuan.join('.').split('').reverse().join('');
  return ribuan;
}
