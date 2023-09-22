
var save_method;
var table;
var urlserver;

$(document).ready(function(){
	$('#uname').focus();
	urlserver = "api/loginApi.php";
	save_method = 'add';

 $('#login').click(function(){
	 doLogin();
 });

 	$('#uname').keydown(function (e){
		var pass = $('#pwd').val();
    if(e.keyCode == 13){
			if (pass =="") {
				$('#pwd').focus();
			} else {
				doLogin();
			}

    }
	});

	$('#pwd').keydown(function (e){
		var uname = $('#uname').val();
		if(e.keyCode == 13){
			if (uname =="") {
				$('#uname').focus();
			} else {
				doLogin();
			}

		}
	});


});

function doLogin() {
	var url = urlserver+"?aksi=login";
	 $.ajax({
				url : url,
				type: "POST",
				data: $('#frmLogin').serialize(),
				dataType: 'json',
				success: function(data) {
					if (data.error == 1) {
						toastr.error(data.pesan,'Error');
					} else {
						// redirek ke home
						redirectToHome();
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
						toastr.error('Error, Tidak Terhubung ke server','Error');

				}
	});
}
function redirectToHome() {
	$.ajax({
     url: "index.php",
     }).done(function(data) {
        window.location.reload();
  });
}
