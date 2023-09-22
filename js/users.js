var save_method;
var table;
var urlserver;

$(document).ready(function() {

    urlserver = "api/usersApi.php";
    save_method = 'add';
    //munculkan semua table
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
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //// 1 admin, 2 kontingen
            if (aData[2] == "0") {
                table.cell(nRow, 2).data('Admin');
            } else if (aData[2] == "1") {
                table.cell(nRow, 2).data('Petugas');
            }
        },
        "columnDefs": [{
                "targets": -1,
                "data": null,
                'className': 'text-center',
                "defaultContent": "<div class='mb-0 mt-0 btn-group btn-group-sm' role='group'> <a class='tblEdit btn btn-sm btn-warning' title='Edit' href='javascript:void(0)'><i class='icon icon-note' aria-hidden='true'></i></a> <a class='tblHapus btn btn-sm btn-danger' title='Hapus' href='javascript:void(0)'><i class='fa fa-trash' aria-hidden='true'></i></a></div>"
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

    //edit
    $('#tabelAuthor tbody').on('click', '.tblEdit', function() {
        var id = table.row($(this).parents('tr')).data();
        save_method = 'update';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $.ajax({
            url: urlserver + "?aksi=lihat&x_id=" + id[0],
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                // $('.password').hide();
                $('[name="x_id"]').val(data['data'][0].id);
                $('[name="username"]').val(data['data'][0].username);
                $('[name="password"]').val("");
                $("#akses").val(data['data'][0].akses);
                $('#akses').find('option[value="' + data['data'][0].akses + '"]').prop('selected', true).change();

                $("#status").val(data['data'][0].status);
                $('#status').find('option[value="' + data['data'][0].status + '"]').prop('selected', true).change();
                $('#modal_form').modal('show');
                $('.modal-title').html('<i class="icon-people"></i>	Edit Data <b>' + data['data'][0].username + '</b>');
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
			$('.modal-title').html('<i class="fa fa-trash"></i> Hapus Data User');
		} else {
			$('.pesanmodal').text('Maaf, silahkan pilih Data yang akan dihapus terlebih dahulu!');
			$('#warningModal').modal('show');
			$('.modal-title').html('<i class="fa fa-exclamation-triangle"></i> Perhatian!');
		}
	});




    //hapus
    $('#tabelAuthor tbody').on('click', '.tblHapus', function() {
        var id = table.row($(this).parents('tr')).data();
        $('#form')[0].reset();
        $('[name="x_id_hapus"]').val(id[0]);
        $('#confirmDelete2').modal('show');
        $('.modal-title').html('<i class="fa fa-trash"></i> Hapus Data User');
    });

    $('#modal_form').on('hidden.bs.modal', function() {
        $('#modal_form form')[0].reset();

    });

		$('#akses').change(function() {
			var perangkat = $('#akses').val();
				if (perangkat == 1 ){
					$( ".perangkatTanding" ).show();
				}else{
					$( ".perangkatTanding" ).hide();
				}
    });


    $('#modal_form').on('shown.bs.modal', function() {
        $('#username').focus();
    });


    $('#delete-confirm-button2').click(function() {
        var idRow = $('input:hidden[name=x_id_hapus]').val();
        hapusRow(idRow);
    });
});

$('#delete-confirm-button').click(function(){
var ID;
	var rows_selected = table.column(0).checkboxes.selected();
	$.each(rows_selected, function(index, rowId){
		 $(form).append(
				 $('<input>')
						.attr('type', 'hidden')
						.attr('name', 'x_id_hapus_all[]')
						.val(rowId)
		 );
	});
	ID = rows_selected.join(",");

 $.ajax({
	 type: "POST",
	 url: urlserver + "?aksi=hapus",
	 cache:false,
	 data: 'x_id='+ID,
	 success: function(data) {
		 toastr.success('Data Berhasil dihapus','Sukses');
		 $('#confirmDelete').modal('hide');
		 $('#tabelAuthor').DataTable().ajax.reload();

	 },
	 error: function (jqXHR, textStatus, errorThrown) {
		 toastr.error('Ouppsss... Gagal menghapus data!','Error');
	 }
 });
});



function hapusRow(id) {
    $.ajax({
        type: "POST",
        url: urlserver + "?aksi=hapus",
        cache: true,
        data: 'x_id=' + id,
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
}

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
