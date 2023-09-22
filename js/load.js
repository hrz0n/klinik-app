$(document).ready(function(){
	$(".loader").fadeOut("slow");

		toastr.options = {
		 "closeButton": true,
		 "debug": false,
		 "newestOnTop": true,
		 "progressBar": true,
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


	$('select').select2({
    theme: 'bootstrap4',
		selectOnClose: false,
		escapeMarkup: function (m) { return m; }
	});
});
