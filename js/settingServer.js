
var save_method;
var table;
var urlserver;

$(document).ready(function(){

	urlserver = "api/serverSetting.php";
	save_method = 'add';
	toastr.options = {
	 "closeButton": true,
	 "debug": false,
	 "newestOnTop": true,
	 "progressBar": false,
	 "positionClass": "toast-top-right",
	 "preventDuplicates": true,
	 "showDuration": "300",
	 "hideDuration": "1000",
	 "timeOut": "5000",
	 "extendedTimeOut": "1000",
	 "showEasing": "swing",
	 "hideEasing": "linear",
	 "showMethod": "fadeIn",
	 "hideMethod": "fadeOut"
	}




 $('#refreshData').click(function(){
	 $('#tabelAuthor').DataTable().ajax.reload();
 });

 $('#hapusall').click(function(){
	 $('#form')[0].reset();
 		rows_selected = table.column(0).checkboxes.selected().count();
	 if (rows_selected > 0) {
		$('#confirmDelete').modal('show');
		$('.modal-title').html('<i class="fa fa-trash"></i> Hapus Jadwal Dosen');
		}else{
		 $('#warningModal').modal('show');
		 $('.modal-title').html('<i class="fa fa-exclamation-triangle"></i> Perhatian!!!');
		}
	});

});

function settingServer() {

	$.ajax({
		url : urlserver,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			$('#namaaplikasi').val(data['data'][5].value_config);
			$('#alamat').val(data['data'][6].value_config);
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
				alert('Error get data from ajax');
		}
	});
}

function reload_setting() {
	$.ajax({
     url: "settingserver.php",
     }).done(function(data) {
        window.location.reload();
  });
}
