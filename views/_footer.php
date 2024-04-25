
	<footer id="footer">
		<div class="container">
			<div class="copy">&copy;Homework <?php echo date("Y"); ?></div>
		</div>
	</footer>

	<script src="<?php echo BASE_URL; ?>/js/jquery-3.7.1.min.js"></script>
	<script src="<?php echo BASE_URL; ?>/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo BASE_URL; ?>/js/custom.js"></script>

	<?php if ($baseFunctions->pageSel2): ?>
		<script src="<?php echo BASE_URL; ?>/js/select2.min.js"></script>
		<script src="<?php echo BASE_URL; ?>/js/select-custom.js?v=<?php echo $baseFunctions->version; ?>"></script>
	<?php endif ?>

	<script>
		(() => {
			'use strict'

			// Fetch all the forms we want to apply custom Bootstrap validation styles to
			const forms = document.querySelectorAll('.needs-validation')

			// Loop over them and prevent submission
			Array.from(forms).forEach(form => {
				form.addEventListener('submit', event => {
					if (!form.checkValidity()) {
						event.preventDefault()
						event.stopPropagation()
					}

					form.classList.add('was-validated');

        				
					if (form.checkValidity() !== false && form.classList.contains('ajaxForm')) {

						event.preventDefault();
						event.stopPropagation();

						if (form.id === 'deleteDeptForm') {
							$(this) = form;
							console.log('deleteDeptForm', form);
							/*var id_dept = $("#ajxusr").val();
							var ajxpwd = $("#ajxpwd").val();

							$.ajax({
								type:'post',
								url:'<?php echo BASE_URL; ?>/ajax.php',
								data:{
									ajxlogin: true,
									ajxusr:ajxusr,
									ajxpwd:ajxpwd
								},
								success:function(response) {
									console.log('response', response);
									var responseObj = JSON.parse(response);
									console.log('responseObj', responseObj);
									if(responseObj.success){
										window.location.reload();
									}else{
										var alert_div = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' + responseObj.msg + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
										$("#ajaxLoginUserFormMsg").html(alert_div);
									}
								}
							});*/
						}
					}
				}, false)
			})
		})()
	</script>

    <script>
        $(document).ready(function() {
            $(".tree-toggler").on('click', function(){
            	var typeOfSwitching = $("#switchingBranches").prop('checked');
            	// console.log('typeOfSwitching', typeOfSwitching);
            	if (typeOfSwitching){
            		if ($(this).hasClass('noshow')) {
            			// console.log('should removeClass noshow from children');
            			$(this).parent().find('.tree-toggler').removeClass('noshow');
            		} else {
            			// console.log('should addClass noshow to children');
            			$(this).parent().find('.tree-toggler').addClass('noshow');
            		}
            	}else{
            		$(this).toggleClass('noshow');
            	}

            });
        });
    </script>

</body>
</html>