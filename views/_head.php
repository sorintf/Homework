
<!DOCTYPE html>
<html lang="ro">
<head>
	<meta charset="UTF-8">

    <!-- handle the viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title><?php echo $baseFunctions->page_title; ?></title>
    <meta name="description" content="<?php echo $baseFunctions->page_description; ?>">

    <!-- never cache patterns -->
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <!-- favicon -->
    <!-- <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL; ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo BASE_URL; ?>/favicon-16x16.png"> -->


    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo BASE_URL; ?>/images/favicon/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo BASE_URL; ?>/images/favicon/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo BASE_URL; ?>/images/favicon/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo BASE_URL; ?>/images/favicon/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?php echo BASE_URL; ?>/images/favicon/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo BASE_URL; ?>/images/favicon/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php echo BASE_URL; ?>/images/favicon/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo BASE_URL; ?>/images/favicon/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/favicon/favicon-196x196.png" sizes="196x196" />
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/favicon/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/favicon/favicon-128.png" sizes="128x128" />




    <link rel="manifest" href="<?php echo BASE_URL; ?>/site.webmanifest">
    <link rel="mask-icon" href="<?php echo BASE_URL; ?>/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#f4690a">

    <meta property="fb:app_id" content="" />
    <meta property="og:url" content="<?php echo $baseFunctions->page_url; ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?php echo $baseFunctions->page_title; ?>" />
    <meta property="og:description" content='<?php echo $baseFunctions->page_description; ?>' />
    <meta property="og:image" content="<?php echo $baseFunctions->page_image; ?>" />

	<link href="<?php echo BASE_URL; ?>/css/bootstrap.min.css" rel="stylesheet">

	<?php if ($baseFunctions->pageSel2): ?>
		<link href="<?php echo BASE_URL; ?>/css/select2.min.css" rel="stylesheet">
	<?php endif ?>

	<link href="<?php echo BASE_URL; ?>/css/main.css?v=<?php echo $baseFunctions->version; ?>" rel="stylesheet">
</head>

<body>