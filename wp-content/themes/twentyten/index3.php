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

get_header(); ?>
<div id="welcomeBanner"></div>
<div class="grid_8 alpha" id="brochure">
				<div class="images">
					<!-- <div><img src="images/slide.leader.jpg" /></div> -->

					<div><a href="?page_id=10&amp;category=1"><img src="wp-content/themes/twentyten/images/banners/beef.jpg" alt="slide.1" width="620" height="400"/>
					<h1>Our fine beefy range</h1></a></div>
					<div><a href="?page_id=10&amp;category=7"><img src="wp-content/themes/twentyten/images/banners/biltong.jpg" alt="slide.2" width="620" height="400"/>
					<h1>Our proudly African biltong range</h1></a></div>
					<div><a href="?page_id=10&amp;category=6"><img src="wp-content/themes/twentyten/images/banners/chicken.jpg" alt="slide.5" width="620" height="400"/>
					<h1>Our tender chicken range</h1></a></div>
					<div><a href="?page_id=10&amp;category=4"><img src="wp-content/themes/twentyten/images/banners/fish.jpg" alt="slide.6" width="620" height="400"/>
					<h1>Our succulent fish range</h1></a></div>
					<div><a href="?page_id=10&amp;category=5"><img src="wp-content/themes/twentyten/images/banners/game.jpg" alt="slide.3" width="620" height="400"/>
					<h1>Our exotic game range</h1></a></div>
					<div><a href="?page_id=10&amp;category=3"><img src="wp-content/themes/twentyten/images/banners/ham.jpg" alt="slide.4" width="620" height="400"/>
					<h1>Our delectable ham range</h1></a></div>
					<div><a href="?page_id=10&amp;category=2"><img src="wp-content/themes/twentyten/images/banners/lamb.jpg" alt="slide.7" width="620" height="400"/>
					<h1>Our juicy lamb range</h1></a></div>
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
					<a href="#"></a>
					<!-- <button onClick='$("div.tabs").tabs().play();'>Play</button> --> 
					<!-- <button onClick='$("div.tabs").tabs().stop();'>Stop</button> -->
				</div>
			</div>
			
	<?php get_sidebar(); ?>

		<div id="container">
        	<a href="http://blog.thebutchershop.co.za" target="_blank">
            	<div id="bloglink"></div>
            </a>
			<!--<div id="content" role="main">-->

			<?php
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 //get_template_part( 'loop', 'index' );
			?>
			<!--</div>--><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
