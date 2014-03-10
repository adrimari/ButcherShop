<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
	</div><!-- #main -->

	<div class="new_footer">
		<div id="colophon"></div>
		<div id="left">
        	<div class="title">CATEGORIES:</div>
        	
<?php	$categories = $wpdb->get_results("SELECT * FROM `wp_wpsc_product_categories`");
//print_r($categories);
		$cat_count = count($categories);?>
<?php //wpsc_start_category_query(array('category_group'=>get_option('wpsc_default_category'), 'show_thumbnails'=> get_option('show_category_thumbnails'))); $j = 1;
	for ($j=0;$j<$cat_count;$j++) {
?>
					<div class="items"><a href="<?php echo home_url('/')."?page_id=10&category=".$categories[$j]->id;?>"><?php echo $categories[$j]->name;?></a><?php echo ($j < ($cat_count - 1)) ? "|" : ""; ?></div>
<?php }
	  //$j++;
	  //echo $cat_count;
	  //wpsc_end_category_query(); ?>
        	
        </div>
        <div id="right">
        	Contact us on +27 11 784 8676 / +27 11 784 8667
        </div>
	</div>
</div><!-- #wrapper -->
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
