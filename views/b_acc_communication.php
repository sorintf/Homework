
<?php include_once('views/_head.php') ?>

<?php include_once('views/_header.php') ?>

	<div class="section">
		<div class="container">
			<h1 class="section-title">Setări comunicare</h1>

			<?php include_once('views/_messages.php'); ?>

			<div class="row">
				<?php include_once('views/_b_acc_nav.php'); ?>

				<div class="acc-main col-lg-10">
					<div class="card card-form">
						<p class="h3">Modifică datele de abonare la NewsLetter</p>
						
						<form action="" method="post" class="needs-validation" novalidate>

							<div class="form-input">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="da" id="acc_nl" name="acc_nl" <?php echo ($baseFunctions->acc_nl=='da')?'checked="checked"':''; ?>>
									<label class="form-check-label" for="acc_nl">
										Sign up to our awesome newsletter
									</label>
								</div>
							</div>

							<div class="form-input submit">
								<input type="hidden" name="source" value="<?php echo $baseFunctions->view; ?>">
								<input type="submit" name="acc_communications_change" class="btn btn-primary w-100" value="Modifică">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php include_once('views/_footer.php') ?>