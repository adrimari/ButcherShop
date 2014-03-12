<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
<div class="sidebar">
    <div class="middle" <?php echo (is_front_page()) ? 'style="min-height:438px;margin-top:-93px;"' : 'style="min-height:438px;margin-top: 150px;"'; ?>">
<?php //if(wpsc_product_count() < 1):?>
    	<div class="bar"></div>
<?php //endif; ?>
        <div class="category_container">
        	<!--<div class="title">CATEGORIES</div>-->
        	<div class="items">
<?php wpsc_start_category_query(array('category_group'=>get_option('wpsc_default_category'), 'show_thumbnails'=> get_option('show_category_thumbnails'))); ?>
				<a href="<?php wpsc_print_category_url(); ?>" class="sidebar"><?php wpsc_print_category_name();?></a>
				<br /><br />
<?php wpsc_end_category_query(); ?>
        	</div>
        </div>
    </div>
    <div class="middle"><div class="bar"></div></div>
    <div class="bottom"></div>
</div>



