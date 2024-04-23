
<?php include_once('views/_head.php') ?>

<?php include_once('views/_header.php') ?>

	<div class="section hero">
		<div class="container">
			<h1>Adaugă departament</h1>

			<?php include_once 'views/_messages.php' ?>

			<div class="card card-form">
				<div class="card-body">
					<form action="" method="post" class="needs-validation" novalidate>

						<div class="form-input">
							<label for="name">Nume</label>
							<input type="text" class="form-control <?php echo isset($baseFunctions->rep['errors']['name'])?$baseFunctions->rep['errors']['name']:''; ?>" id="name" name="name" value="<?php echo isset($baseFunctions->rep['name'])?$baseFunctions->rep['name']:''; ?>" placeholder="Nume" maxlength="100" required>
							<div class="invalid-feedback">
								Please fill in the name (max. 100 chars)
							</div>
						</div>

						<div class="form-input">
							<label for="name">Alege departamentul parinte</label>
							<select name="id_parent" id="id_parent" class="form-control sel2">
								<option value=""></option>
								<?php if ($baseFunctions->rep['departments_list']): ?>
									<?php foreach ($baseFunctions->rep['departments_list'] as $department): ?>
										<option value="<?php echo $department['ID']; ?>"><?php echo $department['name']; ?></option>
									<?php endforeach ?>
								<?php endif ?>
							</select>
						</div>

						<div class="form-input submit">
							<input type="submit" name="addDepartment" class="btn btn-dark" value="Adaugă">
						</div>
					</form>
				</div>
			</div>


		</div>
	</div>


<?php include_once('views/_footer.php') ?>