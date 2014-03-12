<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta name="robots" content="index,follow">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<base href="<?php echo bloginfo('siteurl'); ?>/"><!--[if lte IE 6]></base><![endif]-->
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="wp-content/themes/twentyten/css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="wp-content/themes/twentyten/css/text.css" />
<link rel="stylesheet" type="text/css" media="all" href="wp-content/themes/twentyten/css/960.css" />
<link rel="stylesheet" type="text/css" media="all" href="wp-content/themes/twentyten/css/custom.css" />
<link rel="stylesheet" type="text/css" media="all" href="wp-content/themes/twentyten/css/tabs.css" />
<link rel="stylesheet" type="text/css" media="print" href="wp-content/themes/twentyten/css/print_transaction.css" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" type="text/css" href="wp-content/themes/twentyten/css/jquery.lightbox-0.5.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../style-projects-jquery.css" /> 
<link rel="stylesheet" type="text/css" href="wp-content/themes/twentyten/css/ieselectwidth.css" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
  
<?php 	if (is_home() || is_front_page()) { ?>

<?php } else { ?>

   
  <link rel="stylesheet" type="text/css" href="wp-content/themes/twentyten/css/jquery.lightbox-0.5.css" media="screen" />

   
<style type="text/css">
	.gallery {
	}
	.gallery img {
		border: 1px solid #fff;		
	}
	.gallery a:hover img {
		border: 1px solid #fff;
		color: #fff;
	}
	.gallery a:hover { color: #fff; }
</style>
<?php } ?>
</head>






<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
   	<div id="discs"></div>
	<div id="logo_print">
    	<img src="<?php echo home_url( '/' ); ?>wp-content/themes/twentyten/images/logo.png" />
    </div>
	<div id="header">
		<div id="masthead">
			<div id="branding" role="banner">
				<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<div id="site-title">
					<span>
						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" name="top"><?php bloginfo( 'name' ); ?></a>
					</span>
				</div>
                <div class="menu_container">
                	<div class="dots">
                	<div class="left">
                    	<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
                   			<div id="home"></div>
                        </a>
                        <a href="<?php echo home_url( '/' ); ?>?page_id=10" title="Products Page">
                        	<div id="products"></div>
                        </a>
                    </div>
                    </div>
                    <div class="right">
                        <div id="transaction">
                        	<a href="<?php echo home_url( '/' ); ?>?page_id=12" title="Transaction Results"></a>
                        </div>
                    	<div id="checkout">
                        	<a href="<?php echo home_url( '/' ); ?>?page_id=11" title="Checkout"></a>
                    	</div>
                        <div id="sidebartop" style="float:right;background: url('<?php echo home_url( '/' ); ?>wp-content/themes/twentyten/images/sidebar_top.png') no-repeat;width: 300px;height: 82px;">
                        	<div class="sidebar">
	<div class="top">
		<div id="delivery">
			<div id="delivery_amt">Orders over R500 to Gauteng</div>
		</div>
	</div>
                        </div>
                    </div>
                </div>
				<div id="site-description"><?php bloginfo( 'description' ); ?></div>

				<?php
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() &&
							has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
					else : ?>
						<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
					<?php endif; ?>
			</div><!-- #branding -->
			<div id="access" role="navigation">
			  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
				<?php // wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
			</div><!-- #access -->

		</div><!-- #masthead -->
	</div><!-- #header -->
</div>	
<div id="main">
