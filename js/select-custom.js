
$(document).ready(function() {
	$('.sel2').select2({
		placeholder: "Alege o optiune", 
		allowClear: true
	});
	$('.sel2.noclear').select2({
		placeholder: "Alege o optiune", 
		allowClear: false
	});
});