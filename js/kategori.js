var save_method;
var table;
var urlserver;

$(document).ready(function() {

    urlserver = "api/kategoriApi.php";
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
                "defaultContent": "<div class='mb-0 mt-0 btn-group btn-group-sm' role='group'> <a class='tblEdit btn btn-sm btn-warning' title='Edit Data' href='javascript:void(0)'><i class='icon icon-note' aria-hidden='true'></i></a> <a class='tblHapus btn btn-sm btn-danger' title='Hapus Data' href='javascript:void(0)'><i class='cil-trash' aria-hidden='true'></i></a></div>"
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


    //simpan
    $('#simpanData').click(function() {
        var url;
        if (save_method == 'add') {
            url = "api/kategoriApi.php?aksi=simpan";
        } else {
            url = "api/kategoriApi.php?aksi=update";
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

    $('#tabelAuthor tbody').on('click', '.tblEdit', function() {
        save_method = 'edit';
        var id = table.row($(this).parents('tr')).data();
        var rowHarga ="";
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $.ajax({
            url: urlserver + "?aksi=lihat&x_id=" + id[0],
            type: "POST",
            dataType: "JSON",
            success: function(data) {
              $('[name="x_id"]').val(data['data'][0].idKategori);
              $('[name="namakategori"]').val(data['data'][0].namaKategori);
              $('#modal_form').modal('show');
              $('.modal-title').html('<i class="icon icon-note"></i>	Edit Data <b>' + data['data'][0].namaKategori + '</b>');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    });


  	$('#hapusall').click(function () {
      $('#form')[0].reset();
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
      $('#form')[0].reset();
      $('[name="x_id"]').val(id[0]);
      $('#confirmDelete2').modal('show');
      $('.modal-title').html('<i class="cil-trash"></i> Hapus Data');
    });

    $('#modal_form').on('hidden.bs.modal', function() {
        $('#modal_form form')[0].reset();

    });

    $('#modal_form').on('shown.bs.modal', function() {
        $('#namakategori').focus();
    });


    $('#delete-confirm-button2').click(function() {
        var idRow = $('input:hidden[name=x_id]').val();
        $.ajax({
            type: "POST",
            url: urlserver + "?aksi=hapus",
            cache: true,
            data: 'x_id=' +idRow,
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
    						.attr('name', 'x_id[]')
    						.val(rowId)
    		 );
    	});
    	ID = rows_selected.join(",");
     $.ajax({
    	 type: "POST",
    	 url: urlserver + "?aksi=hapus",
    	 cache:false,
       dataType: 'JSON',
    	 data: 'x_id='+ID,
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


});




function add_new() {
    save_method = 'add';
    $('#modal_form').modal('show');
    $('.modal-title').html('<i class="icon icon-plus"></i> Tambah Data Kategori Layanan');
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
