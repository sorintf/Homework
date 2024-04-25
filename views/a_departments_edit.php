
<?php include_once('views/_head.php') ?>

<?php include_once('views/_header.php') ?>

	<div class="section hero">
		<div class="container">
			<h1>Modifică departament: <?php echo $baseFunctions->rep['dept']->name; ?></h1>

			<?php include_once 'views/_messages.php' ?>

			<div class="card card-form">
				<div class="card-body">
					<form action="" method="post" class="needs-validation" novalidate>

						<div class="form-input">
							<label for="name">Nume</label>
							<input type="text" class="form-control <?php echo isset($baseFunctions->rep['errors']['name'])?$baseFunctions->rep['errors']['name']:''; ?>" id="name" name="name" value="<?php echo $baseFunctions->rep['dept']->name; ?>" placeholder="Nume" maxlength="100" required>
							<div class="invalid-feedback">
								Please fill in the name (max. 100 chars)
							</div>
						</div>

						<div class="form-input">
							<label for="id_parent">Alege departamentul parinte</label>
							<select name="id_parent" id="id_parent" class="form-control sel2">
								<?php if ($baseFunctions->rep['departments_list']): ?>
									<?php foreach ($baseFunctions->rep['departments_list'] as $department): ?>
										<?php if ($department['ID']==$baseFunctions->rep['dept']->ID) continue; ?>
										<option value=""></option>
										<option value="<?php echo $department['ID']; ?>" <?php echo ($department['ID']==$baseFunctions->rep['dept']->id_parent)?'selected':''; ?>><?php echo $department['name']; ?></option>
									<?php endforeach ?>
								<?php endif ?>
							</select>
						</div>

						<div class="form-input">
							<label for="status">Status</label>
							<select name="status" id="status" class="form-control sel2 noclear">
								<option value="Public" <?php echo ($baseFunctions->rep['dept']->features & 1)?'selected':''; ?>>Public</option>
								<option value="Privat" <?php echo (($baseFunctions->rep['dept']->features & 1)==0)?'selected':''; ?>>Privat</option>
							</select>
						</div>

						<div class="form-input submit">
							<input type="hidden" name="id_department" value="<?php echo $baseFunctions->rep['dept']->ID; ?>">
							<input type="submit" name="editDepartment" class="btn btn-dark" value="Modifică">
						</div>
					</form>
				</div>
			</div>


		</div>
	</div>


<?php include_once('views/_footer.php') ?>