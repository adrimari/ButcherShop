<?php
/**
 * Custom functions and definitions
 *
 * @package Custom
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function custom_theme_setup() {
	
	/* Load the primary menu. */
	remove_action( 'omega_before_header', 'omega_get_primary_menu' );	
	add_action( 'omega_header', 'omega_get_primary_menu' );
	add_action( 'omega_header', 'custom_intro');
	add_filter( 'omega_site_description', 'custom_site_description' );

	add_theme_support( 'omega-footer-widgets', 4 );

	add_theme_support( 'color-palette', array( 'callback' => 'custom_register_colors' ) );

	/* Add support for a custom header image. */
	add_theme_support(
		'custom-header',
		array( 'header-text' => false,
			'flex-width'    => true,
			'uploads'       => true,
			'default-image' => get_stylesheet_directory_uri() . '/images/header.png' ) );


	/* Custom background. */
	add_theme_support( 
		'custom-background',
		array( 'default-color' => 'f5f5f5' )
	);

}

add_action( 'after_setup_theme', 'custom_theme_setup', 11 );

/* disable site description */

function custom_site_description($desc) {
	$desc = "";
	return $desc;
}

/**
 * Registers colors for the Color Palette extension.
 *
 * @since  0.1.0
 * @access public
 * @param  object  $color_palette
 * @return void
 */

function custom_register_colors( $color_palette ) {

	/* Add custom colors. */
	$color_palette->add_color(
		array( 'id' => 'accent', 'label' => __( 'Accent Color', 'custom' ), 'default' => 'f38635' )
	);

	/* Add rule sets for colors. */

	$color_palette->add_rule_set(
		'accent',
		array(
			'color'               => 'a:hover, .omega-nav-menu a:hover, .entry-title a:hover, a.more-link',
			'background-color'    => '.intro, .tagcloud a, button, input[type="button"], input[type="reset"], input[type="submit"]',
			'border-top-color'    => '.site-container',
			'border-left-color'   => 'pre'
		)
	);
}

/* display custom header image */

function custom_intro() {
	echo "<div class='intro'>";
	if(is_front_page()) {					
		if (get_header_image()) {
			echo '<img class="header-image" src="' . esc_url( get_header_image() ) . '" alt="' . get_bloginfo( 'description' ) . '" />';
		}
	} else {		
		// get title		
		$id = get_option('page_for_posts');
		if ( is_day() || is_month() || is_year() || is_tag() || is_category() || is_singular('post' ) || is_home() ) {
			$the_title = get_the_title($id);
		} else {
			$the_title = get_the_title(); 
		}
		
		if(is_home() || is_singular('post' ) ) {
			if ( has_post_thumbnail($id) ) {
				echo get_the_post_thumbnail( $id, 'full' );
			} elseif (get_header_image()) {
				echo '<img class="header-image" src="' . esc_url( get_header_image() ) . '" alt="' . $the_title . '" />';	
			}
		} elseif ( has_post_thumbnail() && is_singular('page' ) ) {	
				the_post_thumbnail();
		} elseif (get_header_image()) {
			echo '<img class="header-image" src="' . esc_url( get_header_image() ) . '" alt="' . $the_title . '" />';	
		}
	}       
	echo "</div>";	
}