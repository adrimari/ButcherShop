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
        	
<?php	$categories = $wpdb->get_results("SELECT * FROM `wp_wpsc_product_categories` ORDER BY `name` ASC");
//print_r($categories);
		$cat_count = count($categories);?>
<?php //wpsc_start_category_query(array('category_group'=>get_option('wpsc_default_category'), 'show_thumbnails'=> get_option('show_category_thumbnails'))); $j = 1;
	for ($j=0;$j<$cat_count;$j++) {
	
		$cache_url=home_url('/');
		//if(is_ssl()) {
			$cache_url = str_replace("http://", "https://", $cache_url);
		//}
?>
					<div class="items"><a href="<?php echo $cache_url."?page_id=10&category=".$categories[$j]->id;?>"><?php echo $categories[$j]->name;?></a><?php echo ($j < ($cat_count - 1)) ? "|" : ""; ?></div>
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


<!--<script type="text/javascript" src="wp-content/themes/twentyten/js/jquery.js"></script>-->
<script type="text/javascript" src="wp-content/themes/twentyten/js/jquery.tools-1.1.2-min.js"></script>
<script type="text/javascript" src="wp-content/themes/twentyten/js/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="wp-content/themes/twentyten/js/jquery.localscroll-min.js"></script>
<script type="text/javascript" src="wp-content/plugins/wp-e-commerce/js/custom-form-elements.js"></script>
<script type="text/javascript" src="wp-content/themes/twentyten/js/jquery.dimensions.js"></script>
<script type="text/javascript" src="wp-content/themes/twentyten/js/jquery.ie-select-width.min.js"></script>
<script type="text/javascript" src="wp-content/themes/twentyten/js/jquery.lightbox-0.5.js"></script>
<script type="text/javascript" src="wp-content/themes/twentyten/js/jquery.popup.js"></script>


<?php 	if (is_home() || is_front_page()) { ?>
<script language="Javascript">
$(document).ready(function() {
	$("div.tabs").tabs(".images > div", {effect: 'fade',fadeOutSpeed: "slow", rotate: true}).slideshow({autoplay: true, interval: 4000, clickable:false});
	/* $("div.tabs").tabs(".images > div", {effect: 'fade',fadeOutSpeed: "slow", rotate: true}).slideshow(); */
	$.localScroll({hash:true});
	$.localScroll.hash();
});   
</script>
<?php } else { ?>
<script type="text/javascript" >
$(function() {
       $('.gallery a').lightBox();
    });
</script>

<?php } ?>
        <script> 
			$(function() {
			    // Set the width via the plugin.
    			$('select').ieSelectWidth
    			({
					containerClassName : 'select-container',
					overlayClassName : 'select-overlay'
    			});
			});
        </script> 

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
