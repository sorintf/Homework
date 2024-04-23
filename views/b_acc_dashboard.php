<?php include_once('views/_head.php') ?>

<?php include_once('views/_header.php') ?>

	<div class="section bacc"> 
		<div class="container">
			<h1 class="page-title">Dashboard</h1>
			<p class="welcome-message">Bine ai revenit, <?php echo $baseFunctions->email_user; ?>!</p>
		</div>
	</div>

	<div class="section bg-pale">
		<div class="container">
			<?php include_once('views/_messages.php'); ?>
		</div>
	</div>

<?php include_once('views/_footer.php') ?>