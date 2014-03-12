<!DOCTYPE html>
<html> 
<head>

	<title>Steakout | Shop</title>
	
	<!-- css resets -->
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.14.1/build/cssreset/cssreset-min.css">
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.14.1/build/cssfonts/cssfonts-min.css">
	<!-- custom css -->
	<link rel="stylesheet" href="<?php echo (get_template_directory_uri()); ?>/includes/css/layout.css"  type="text/css"/>
	<link rel="stylesheet" href="<?php echo (get_template_directory_uri()); ?>/includes/javascript/rhino/css/rhino.css"  type="text/css"/>
	<script type="<?php echo (get_template_directory_uri()); ?>/includes/javascript/jQuery.js"></script>
		<<script type="text/javascript" src="<?php echo (get_template_directory_uri()); ?>/includes/javascript/rhino/js/mousewheel.js"></script>
		<script type="text/javascript" src="<?php echo (get_template_directory_uri()); ?>/includes/javascript/rhino/js/rhinoslider.js"></script>
		<script type="text/javascript" src="<?php echo (get_template_directory_uri()); ?>/includes/javascript/slider.js"></script>

</head>
<body id="home">
	<div>
		<div class="wrapper">
			<div>
				<header>
					<section class="social">
						<div class="inner">
							<article class="icons">
								<a href="#"><img src="<?php echo (get_template_directory_uri()); ?>/includes/images/social-links-all.png" id="phone" alt="Call Us"></a>
								<a href="#"><img src="<?php echo (get_template_directory_uri()); ?>/includes/images/social-links-all.png" id="mail"  alt="Mail Us"></a>
								<a href="#"><img src="<?php echo (get_template_directory_uri()); ?>/includes/images/social-links-all.png" id="man"  alt=""></a>
								<a href="#"><img src="<?php echo (get_template_directory_uri()); ?>/includes/images/social-links-all.png" id="like"  alt="Like us on Facebook"></a>
								<a href="#"><img src="<?php echo (get_template_directory_uri()); ?>/includes/images/social-links-all.png" id="rss"  alt="Subscribe to RSS feed"></a>
							</article>
							<article class="logo">
								<img src="<?php echo (get_template_directory_uri()); ?>/includes/images/steakout-logo.png" alt="steak out logo"/>
							</article>
							<!--INCLUDE) THIS BEFORE PUBLISH <a class="menuFont" href="./mobi/index.html">View on Mobile Device</a> -->
						</div>

					</section>
					<div class="menu-container">
					<nav class="inner">
						<?php wp_nav_menu(array('depth'=>1)); ?>
					</nav></div>
					<div class="clear"></div>
				</header>
			</div>

			<div class="clear"></div>
			<div>
