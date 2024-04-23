
<?php include_once('views/_head.php') ?>

<?php include_once('views/_header.php') ?>

	<div class="section login">
		<div class="container">
			<h1 class="section-title">Register</h1>

			<?php include_once('views/_messages.php'); ?>

			<div>
				<pre>
					<?php print_r($baseFunctions->rep['user']) ?>
				</pre>
			</div>

			<div class="card card-form">
				<div class="card-body">
					<form action="" method="post" class="needs-validation" novalidate>

						<div class="form-input">
							<div class="form-floating">
								<input type="email" class="form-control <?php echo isset($baseFunctions->rep['errors']['email'])?$baseFunctions->rep['errors']['email']:''; ?>" id="register-email" name="register-email" value="<?php echo isset($baseFunctions->rep['email'])?$baseFunctions->rep['email']:''; ?>" placeholder="E-mail" required>
								<label for="register-email">E-mail</label>
								<div class="invalid-feedback">
									Please fill in your email address.
								</div>
							</div>
						</div>

						<div class="form-input">
							<div class="form-floating">
								<input type="password" class="form-control <?php echo isset($baseFunctions->rep['errors']['password'])?$baseFunctions->rep['errors']['password']:''; ?>" id="register-password" name="register-password" value="" placeholder="Creează o parolă" required>
								<label for="register-password">Creează o parolă</label>
								<div class="invalid-feedback">
									Please fill in a password.
								</div>
							</div>
							<div class="additional-info">
								Parola trebuie să conțină minim 8 caractere
							</div>
						</div>

						<div class="form-input">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="da" id="register-acc_tc" name="register-acc_tc" required>
								<label class="form-check-label <?php echo isset($baseFunctions->rep['errors']['acc_tc'])?$baseFunctions->rep['errors']['acc_tc']:''; ?>" for="register-acc_tc">
									Agree to <a href="<?php echo $baseFunctions->buildUrl(array('view'=>"f_policy_tc")); ?>" class="label-link" target="_blank">terms and conditions</a>
								</label>
								<div class="invalid-feedback">
									You must agree before submitting.
								</div>
							</div>
						</div>

						<div class="form-input submit">
							<input type="submit" name="registerUser" class="btn btn-dark w-100" value="Creează cont">
						</div>
					</form>
				</div>

                
                <div class="card-footer">
                    Ai cont? <a href="<?php echo $baseFunctions->buildUrl(array('view'=>"b_acc_login")); ?>" class="">Autentifică-te.</a>
                </div>
			</div>
		</div>
	</div>

<?php include_once('views/_footer.php') ?>