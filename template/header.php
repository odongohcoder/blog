<!doctype html>
<html lang="nl">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="<?php echo $meta["META_TITLE"]; ?>">
		<meta name="theme-color" content="#ffffff">
		<meta name="description" content="<?php echo $meta["META_DESCRIPTION"]; ?>">
		<meta name="keywords" content="<?php echo $meta["META_KEYWORDS"]; ?>">
		<meta name="author" content="<?php echo $meta["AUTHOR"]; ?>">
		<meta name="content_origin"  content="<?php echo $meta["DATE"]; ?>">
		<meta name="robots" content="index, follow">
		<meta name="revisit-after" content="3 days">
		<meta property="og:title" content="<?php echo $meta["META_TITLE"]; ?>">
		<meta property="og:site_name" content="<?php echo $meta["META_TITLE"]; ?>">
		<meta property="og:url" content="<?php echo $meta["META_URL"]; ?>/">
		<meta property="og:description" content="<?php echo $meta["META_DESCRIPTION"]; ?>">
		<meta property="og:type" content="website">
		<meta property="og:locale" content="nl_NL" />
		<meta name="twitter:card" content="summary">
		<meta name="twitter:title" content="<?php echo $meta["META_TITLE"]; ?>">
		<meta name="twitter:description" content="<?php echo $meta["META_DESCRIPTION"]; ?>">

		<!-- link rel="manifest" href="manifest.json" -->
		<link rel="shortcut icon" type="image/x-icon" href="img/favicon/favicon.ico">
		<link rel="shortcut icon" type="image/gif" href="img/favicon/favicon.gif">
		<link rel="shortcut icon" type="image/png" href="img/favicon/favicon.png">
		<link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png" sizes="16x16" />
		<link rel="icon" type="image/png" href="img/favicon/favicon-32x32.png" sizes="32x32" />
		<link rel="apple-touch-icon" href="img/favicon/touch-icon-iphone.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-ipad.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/favicon/touch-icon-iphone-114.png">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/favicon/touch-icon-ipad-144.png">
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="img/favicon/touch-icon-ipad.png">
		<link rel="apple-touch-icon-precomposed" sizes="167x167" href="img/favicon/touch-icon-ipad-retina.png">
		<link rel="apple-touch-icon-precomposed" sizes="180x180" href="img/favicon/touch-icon-iphone-retina.png">
		<link rel="apple-touch-startup-image" href="img/splash/launch.png">
		<!-- iPhone X (1125px x 2436px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="img/splash/apple-launch-1125x2436.png">
		<!-- iPhone 8, 7, 6s, 6 (750px x 1334px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="img/splash/apple-launch-750x1334.png">
		<!-- iPhone 8 Plus, 7 Plus, 6s Plus, 6 Plus (1242px x 2208px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)" href="img/splash/apple-launch-1242x2208.png">
		<!-- iPhone 5 (640px x 1136px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" href="img/splash/apple-launch-640x1136.png">
		<!-- iPad Mini, Air (1536px x 2048px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" href="img/splash/apple-launch-1536x2048.png">
		<!-- iPad Pro 10.5" (1668px x 2224px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" href="img/splash/apple-launch-1668x2224.png">
		<!-- iPad Pro 12.9" (2048px x 2732px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" href="img/splash/apple-launch-2048x2732.png">

		<title><?php echo $meta["META_TITLE"]; ?></title>

		<link href="<?php echo $base;?>/css/normalize.css" rel="stylesheet">
		<link href="<?php echo $base;?>/css/style.css" rel="stylesheet">

	</head>

	<body>
		<input id="dark-mode" name="dark-mode" class="dark-mode-checkbox visually-hidden" type="checkbox">
		<div class="wrapper theme-container">
			<header id="header">
					<?php if (isset($_GET["article"])):?>
						<a href="index.php" class="return">
							<?php echo file_get_contents($base . '/img/icon/' . "arrow-left.svg"); ?>
						</a>
					<?php endif;?>
					<div class="logo">
						<a id="logo" href="<?php echo $base;?>">
							Sincerity
						</a>
					</div>
					<label class="dark-mode-label switch" for="dark-mode">
						<?php echo file_get_contents($base . '/img/icon/' . "sun.svg"); ?><?php echo file_get_contents($base . '/img/icon/' . "moon.svg"); ?>
					</label>
					<nav class="menu" id="main">
						<?php include 'menu.php'; ?>
					</nav>
			</header>
