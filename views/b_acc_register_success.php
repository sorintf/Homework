
<?php include_once('views/_head.php') ?>

<?php include_once('views/_header.php') ?>

	<div class="section login">
		<div class="container">
			<h1 class="section-title">Register</h1>

			<?php include_once('views/_messages.php'); ?>

			<div class="card card-form">
				<div class="card-text">
					<p>Un email pentru confirmare adresei de email a fost trimis la: "<?php echo $baseFunctions->rep['user']->email_user; ?>". Urmareste indicatiile din mesaj pentru a finaliza crearea contului.</p>
					<p>Daca nu ai primit emailul (nici in junk/spam) apasa <a href="<?php echo $baseFunctions->buildUrl(array('view'=>'b_acc_recover')); ?>">aici</a> pentru a-l retrimite</p>
				</div>
			</div>
		</div>
	</div>

<?php include_once('views/_footer.php') ?>