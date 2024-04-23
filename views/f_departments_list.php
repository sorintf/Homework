
<?php include_once('views/_head.php') ?>

<?php include_once('views/_header.php') ?>

	<div class="section hero">
		<div class="container">
			<h1>Vizualizare</h1>

			<div class="form-check form-switch">
				<input class="form-check-input" type="checkbox" role="switch" id="switchingBranches" value="1">
				<label class="form-check-label" for="switchingBranches">Collapse/Show entire child branch</label>
			</div>

			<div class="tree-wrap">
				<?php echo $baseFunctions->rep['tree_view']; ?>
			</div>
		</div>
	</div>


<?php include_once('views/_footer.php') ?>