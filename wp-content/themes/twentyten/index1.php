<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header();?>

<div class="grid_8 alpha" id="brochure">
				<div class="images">
					<!-- <div><img src="images/slide.leader.jpg" /></div> -->

					<div><a href="menu/"><img src="wp-content/themes/twentyten/images/banners/slide.1.jpg" alt="slide.1" width="620" height="400"/>
					<h1>Home of the superlative</h1></a></div>
					<div><a href="picks-pick/"><img src="wp-content/themes/twentyten/images/banners/slide.2.jpg" alt="slide.2" width="620" height="400"/>
					<h1>Liquid assets</h1></a></div>
					<div><a href="reviews/"><img src="wp-content/themes/twentyten/images/banners/slide.5.jpg" alt="slide.5" width="620" height="400"/>
					<h1>Raising the steaks</h1></a></div>
					<div><a href="butchery/"><img src="wp-content/themes/twentyten/images/banners/slide.6.jpg" alt="slide.6" width="620" height="400"/>

					<h1>Well-hung and aged to perfection</h1></a></div>
					<div><a href="vintage-cellar/"><img src="wp-content/themes/twentyten/images/banners/slide.3.jpg" alt="slide.3" width="620" height="400"/>
					<h1>The best cellar list</h1></a></div>
					<div><a href="butchery/"><img src="wp-content/themes/twentyten/images/banners/slide.4.jpg" alt="slide.4" width="620" height="400"/>
					<h1>A cut above</h1></a></div>
				</div>
				<div class="controls">

					<a class="backward">prev</a>
					<a class="forward">next</a>
				</div>
				<div class="tabs">
					<a href="#"></a>
					<a href="#"></a>
					<a href="#"></a>
					<a href="#"></a>

					<a href="#"></a>
					<a href="#"></a>
					<!-- <button onClick='$("div.tabs").tabs().play();'>Play</button> --> 
					<!-- <button onClick='$("div.tabs").tabs().stop();'>Stop</button> -->
				</div>
			</div>
			
	<?php get_sidebar(); ?>

		<div id="container">
			<div id="content" role="main">

			<?php
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'index' );
			?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
