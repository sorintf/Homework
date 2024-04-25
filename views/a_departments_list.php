
<?php include_once('views/_head.php') ?>

<?php include_once('views/_header.php') ?>

	<div class="section hero">
		<div class="container">
			<h1>Administrare</h1>
			<a href="<?php echo $baseFunctions->buildUrl(array('view'=>"a_departments_add")); ?>" class="btn btn-dark">Adaugă departament</a>

			<?php include_once 'views/_messages.php' ?>


			<div class="table-wrap">
				<table class="table table-striped align-middle">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Departament</th>
							<th scope="col">Flag</th>
							<th scope="col">Public/Privat</th>
							<th scope="col">Sters</th>
							<th scope="col">Departament parinte</th>
							<th scope="col">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($baseFunctions->rep['departments_list'] as $dept): ?>
							<tr class="">
								<td><?php echo $dept['ID']; ?></td>
								<td><a href="<?php echo $baseFunctions->buildUrl(array('view'=>'a_departments_edit', 'id_department'=>$dept['ID'])); ?>" class=""><?php echo $dept['name']; ?></a></td>
								<td><?php echo $dept['features']; ?></td>
								<td><?php echo (($dept['features']&1) > 0)?'Public':'Privat'; ?></td>
								<td><?php echo (($dept['features']&2) > 0)?'Da':'Nu'; ?></td>
								<td><?php echo (!empty($dept['parent_name']))?$dept['parent_name']:'Top dept'; ?></td>
								<td class="w-auto">
									<form class="needs-validation d-inline" id="deleteDeptForm" action="" method="post" novalidate>
										<input type="hidden" name="id_department" value="<?php echo $dept['ID']; ?>">
										<button type="submit" name="deleteDepartment" class="btn btn-danger"><svg width="16" height="16" role="img" aria-label="Danger:"><use xlink:href="#icon-trash"/></svg></button>
									</form>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>


<?php include_once('views/_footer.php') ?>