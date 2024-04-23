
$(document).ready(function() {

	$(".pass-toggle").on("click", function(){
		$(this).toggleClass('pass-show');
		var input = $(this).parent().children(".password");
		var attr = input.attr('type');
		if (attr=="password") {
			input.attr('type', 'text');
		}else{
			input.attr('type', 'password');
		}
	});
});