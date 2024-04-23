
<?php include_once('views/_head.php') ?>

<?php include_once('views/_header.php') ?>

	<div class="section login">
		<div class="container">
			<h1 class="section-title">Login</h1>

			<?php include_once('views/_messages.php'); ?>

			<div class="card card-form bg-transparent">
                <form class="needs-validation mb-4" id="loginUserForm" action="" method="post" novalidate>

					<div class="form-input">
						<div class="form-floating">
							<input type="email" class="form-control" id="login-username" name="login-username" value="" placeholder="E-mail" required>
							<label for="login-username">E-mail</label>
							<div class="invalid-feedback">
								Please fill in your email address.
							</div>
						</div>
					</div>

					<div class="form-input">
						<div class="form-floating">
							<input type="password" class="form-control password" id="login-password" name="login-password" value="" placeholder="Parola" required>
							<label for="login-password">Parola</label>
							<span class="pass-toggle"></span>
							<div class="invalid-feedback">
								Please fill in a password.
							</div>
						</div>
					</div>

                    <div class="text-end mb-3">
                        <a href="<?php echo $baseFunctions->buildUrl(array('view'=>"b_acc_password_request_reset")); ?>" class="">Ai uitat parola?</a>
                    </div>

					<div class="form-input submit">
						<input type="submit" name="loginUser" class="btn btn-dark w-100" value="Intră în cont">
					</div>
                </form>
			</div>
		</div>
	</div>

<?php include_once('views/_footer.php') ?>