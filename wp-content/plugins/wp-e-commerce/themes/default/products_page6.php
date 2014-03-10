<?php
//print_r($_GET);
global $wpsc_query, $wpdb, $wpsc_cart;
if (isset($_GET['page_id']) && !isset($_GET['category'])) {
	$url = home_url( '/' )."?page_id=10";
	if (!is_numeric($_GET['page_id']))
		echo "<script>document.location.href='".$url."'</script>";
} else if (isset($_GET['page_id']) && isset($_GET['category'])) {
	$url = home_url( '/' )."?page_id=10";
	if (!is_numeric($_GET['page_id']))
		echo "<script>document.location.href='".$url."'</script>";
	if (!is_numeric($_GET['category'])) {
		echo "<script>document.location.href='".$url."'</script>";
	}
}
?>
<?php $cat = ""; ?>
<div id='products_page_container' class="wrap wpsc_container">
<?php //echo get_site_url(); ?>
<?php if(wpsc_has_breadcrumbs()) : ?>
		<div class='breadcrumb'>
			<a href='<?php echo get_option('product_list_url'); ?>'><?php echo get_option('blogname'); ?></a> &raquo;
			<?php while (wpsc_have_breadcrumbs()) : wpsc_the_breadcrumb(); ?>
				<?php if(wpsc_breadcrumb_url()) :?> 	   
					<a href='<?php echo wpsc_breadcrumb_url(); ?>'><?php echo wpsc_breadcrumb_name(); ?></a> &raquo;
				<?php else: ?> 
					<?php echo wpsc_breadcrumb_name(); ?>
				<?php endif; ?> 
			<?php endwhile; ?>
		</div><!--.breadcrumb-->
	<?php endif; ?>
	
	<?php do_action('wpsc_top_of_products_page'); // Plugin hook for adding things to the top of the products page, like the live search ?>
	<?php if(wpsc_display_categories()): ?>
	  <?php if(get_option('wpsc_category_grid_view') == 1) :?>
            <div class='wpsc_categories wpsc_category_grid' id="grid_item">
				<?php wpsc_start_category_query(array('category_group'=> get_option('wpsc_default_category'), 'show_thumbnails'=> 1)); ?>
				<div id="category">
    	        	<div class="cat_header">
    	        		<a href="<?php wpsc_print_category_url();?>" ><?php echo wpsc_print_category_name();?></a>
    	        	</div><!--#cat_header-->
					<a href="<?php wpsc_print_category_url();?>" class="wpsc_category_grid_item" title='<?php wpsc_print_category_name();?>'>
						<?php wpsc_print_category_image(); ?>
					</a>
						<div id="view_prod"><a href="<?php wpsc_print_category_url();?>" title='<?php wpsc_print_category_name();?>'>view products</a><div class="arrow2"></div></div>
                    
				</div><!--#category-->
            	<?php wpsc_print_subcategory("", ""); ?>
				<?php wpsc_end_category_query(); ?>
				<div class='clear_category_group'></div><!--.clear_category_group-->
			</div><!--.wpsc_categories wpsc_category_grid-->
	  <?php else:?>
			<ul class='wpsc_categories'>
				<?php wpsc_start_category_query(array('category_group'=>get_option('wpsc_default_category'), 'show_thumbnails'=> get_option('show_category_thumbnails'))); ?>
					<li>
						<?php wpsc_print_category_image(32, 32); ?>
						<a href="<?php wpsc_print_category_url();?>" class="wpsc_category_link"><?php wpsc_print_category_name();?></a>
						<?php if(get_option('wpsc_category_description')) :?>
							<?php wpsc_print_category_description("<div class='wpsc_subcategory'>", "</div>"); ?>				
						<?php endif;?>
						<?php wpsc_print_subcategory("<ul>", "</ul>"); ?>
					</li>
				<?php wpsc_end_category_query(); ?>
			</ul>
		<?php endif; ?>
	<?php endif; ?>

	<?php if(wpsc_display_products()): ?>
		<?php if(wpsc_is_in_category()) : ?>
			<div class='wpsc_category_details'>
				<?php if(false): //get_option('show_category_thumbnails') && wpsc_category_image()) : ?>
					<img src='<?php echo wpsc_category_image(); ?>' alt='<?php echo wpsc_category_name(); ?>' title='<?php echo wpsc_category_name(); ?>' />
				<?php endif; ?>
				
				<?php if(get_option('wpsc_category_description') &&  wpsc_category_description()) : ?>
					<?php echo wpsc_category_description(); ?>
				<?php endif; ?>
			</div><!--#wpsc_category_details-->
		<?php endif; ?>
		
		<!-- Start Pagination -->
		<?php if ( ( get_option( 'use_pagination' ) == 1 && ( get_option( 'wpsc_page_number_position' ) == 1 || get_option( 'wpsc_page_number_position' ) == 3 ) ) ) : ?>
			<div class="wpsc_page_numbers">
				<?php if ( wpsc_has_pages() ) : ?>
					<?php echo wpsc_first_products_link( '&laquo; First', true ); ?> <?php echo wpsc_previous_products_link( '&laquo; Previous', true ); ?> <?php echo wpsc_pagination( 10 ); ?> <?php echo wpsc_next_products_link( 'Next &raquo;', true ); ?> <?php echo wpsc_last_products_link( 'Last &raquo;', true ); ?>
				<?php endif; ?>
			</div><!--.wpsc_page_numbers-->
		<?php endif; ?>
        </div>
		<!-- End Pagination -->
        
<?php if(wpsc_category_transition()) :?>
	<div style="clear: both"></div>
	<div id='wpsc_category_boundary'>
		<h4 style="float:left;width:200px;"><?php $cat = wpsc_current_category_name();
				echo $cat; ?></h4>
		<div id="prod_count"><?php echo wpsc_product_count(); ?> product(s)</div>
	</div><!--#wpsc_category_boundary-->
	<div style="clear: both"></div>
<?php endif; ?>

<?php if(wpsc_product_count() > 0):?>
<div class="outer_product_container" >
<?php while (wpsc_have_products()) :  wpsc_the_product(); ?>
	<div class="inner_product_container">
		<div class="middle">
			<div class="prod_left_col">
				<div class="product_bg">
					<div class="prod_title">
						<a href="javascript:build_GET('<?php echo wpsc_the_product_permalink(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_vargrp_id(); ?>')" onclick="" id="anchor"><?php echo wpsc_the_product_title(); ?></a>
						<?php echo wpsc_edit_the_product_link(); ?>
					</div><!--.prod_title-->
					<?php if(get_option('show_thumbnails')) :?>
					<div class="prod_img_container">
                    	<div class="prod_img">
						<?php if(wpsc_the_product_thumbnail()) :?>
							<div class="gallery">
								<a class="gallery" href="<?php echo wpsc_the_product_image(); ?>" title="<?php echo wpsc_the_product_title(); ?>">
									<img id="product_image_<?php echo wpsc_the_product_id(); ?>" alt="<?php echo wpsc_the_product_title(); ?>" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo wpsc_the_product_thumbnail(); ?>" width="200px"/>
								</a>
							</div>
						<?php else: ?>
							<div class="item_no_image"></div><!--.item_no_image-->
						<?php endif; ?>
						</div><!--.prod_img-->
					</div><!--.prod_img_container-->
					<?php endif; ?>
					<div class="prod_bottom">	
						<div class="unit_price">
							<div class="wpsc_product_price">
							<?php if(wpsc_product_is_donation()) : ?>
								<label for='donation_price_<?php echo wpsc_the_product_id(); ?>'><?php echo __('Donation', 'wpsc'); ?>:</label>
								<input type='text' id='donation_price_<?php echo wpsc_the_product_id(); ?>' name='donation_price' value='<?php echo $wpsc_query->product['price']; ?>' size='6' />
							<?php else : ?>
								<?php if(wpsc_product_on_special()) : ?>
									<span class='oldprice'><?php echo wpsc_product_normal_price(get_option('wpsc_hide_decimals')); ?> per kg</span><br />
								<?php endif; ?>
								<span id="product_price_<?php echo wpsc_the_product_id(); ?>" class="pricedisplay2">R<?php echo $wpsc_query->product['price']; // substr(wpsc_the_product_price(get_option('wpsc_hide_decimals')),2); ?> per <?php echo $wpsc_query->product['weight_unit']; ?></span>
							<?php endif; ?>
							</div><!--.wpsc_product_price-->
						</div><!--.unit_price-->
						<div class="arrow"></div>
					</div><!--.prod_bottom-->
				</div><!--.product_bg-->
			</div><!--.prod_left_col-->
            
			<div class="prod_right_col">
<?php 
	if(wpsc_product_external_link(wpsc_the_product_id()) != '') {
		$action =  wpsc_product_external_link(wpsc_the_product_id()); 
	} else {
		$action =  htmlentities(wpsc_this_page_url(),ENT_QUOTES); 
	} 
	if (isset($action))
		$action = str_replace("amp;","",$action);

$query = $wpdb->get_row("SELECT * FROM `wp_wpsc_product_list` WHERE `id` = ".wpsc_the_product_id());
if ($query->custom_units == 1) { ?>
			<div style="width: 100%; height: 20px">
				<input type="radio" id="weight<?php echo wpsc_the_product_id(); ?>" name="custom" 
                	onchange="enable_custom('<?php echo wpsc_the_product_id(); ?>','<?php echo wpsc_vargrp_form_id(); ?><?php echo wpsc_vargrp_id(); ?>')" 
					<?php //echo ($wpsc_cart->get_cart_item(wpsc_the_product_id()) && $wpsc_cart->get_cart_item(wpsc_the_product_id())->custom_units == 1) ? "checked='checked'" : ""; ?> 
                	value="weight"
                    onclick="enable_custom('<?php echo wpsc_the_product_id(); ?>','<?php echo wpsc_vargrp_form_id(); ?><?php echo wpsc_vargrp_id(); ?>')" />
				<label for="weight<?php echo wpsc_the_product_id(); ?>" onclick="enable_custom('<?php echo wpsc_the_product_id(); ?>','<?php echo wpsc_vargrp_form_id(); ?><?php echo wpsc_vargrp_id(); ?>')">PER WEIGHT</label><br />
			</div>
            <div style="width: 100%; height: 20px">
            	<input type="radio"  id="items<?php echo wpsc_the_product_id(); ?>" name="custom" 
                	onchange="enable_item('<?php echo wpsc_the_product_id(); ?>','<?php echo wpsc_vargrp_form_id(); ?>')" 
					<?php //echo ($wpsc_cart->get_cart_item(wpsc_the_product_id()) && $wpsc_cart->get_cart_item(wpsc_the_product_id())->custom_units == 0) ? "checked='checked'" : ""; ?> 
                	value="item"
                    onclick="enable_item('<?php echo wpsc_the_product_id(); ?>','<?php echo wpsc_vargrp_form_id(); ?>')"
                    <?php echo (!wpsc_have_variation_groups()) ? 'disabled="disabled"' : ""; ?> />
				<label for="items<?php echo wpsc_the_product_id(); ?>" <?php if (wpsc_have_variation_groups())  ?> onclick="enable_item('<?php echo wpsc_the_product_id()?>','<?php echo wpsc_vargrp_form_id() ?>')" >PRE-PACKAGED</label>
			</div>	
					
			<div id="custom<?php echo wpsc_the_product_id(); ?>" 
            	style="display:<?php echo ($_GET['custom'] == '1') ? "block" : "none"; //($wpsc_cart->get_cart_item(wpsc_the_product_id()) && $wpsc_cart->get_cart_item(wpsc_the_product_id())->custom_units == 1) ? "block" : "none"; ?>;  
                width: 164px;" class="custom">
				<div class="prod_form">
				<form class='product_form'  enctype="multipart/form-data" action="<?php echo $action; ?>" method="post" name="custom_product_<?php echo wpsc_the_product_id(); ?>" id="custom_product_<?php echo wpsc_the_product_id(); ?>">
                 	<input type="hidden" name="custom_product_<?php echo wpsc_the_product_id(); ?>_single" id="custom_product_<?php echo wpsc_the_product_id(); ?>_single" value="false" />
<?php 
	if ($query->custom_units == 1) { ?>
					<div class="product_form">
					<table>
	<?php 
		if($query->units_grams) { ?>
						<tr>
							<td style="width:100px;padding-left:10px;padding-bottom:2px;height:15px;">Grams:</td>
							<td width="20px" height="15px">
								<?php $price = $query->price; // substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
								<input size='3' maxlength='3' type='text' class='wpsc_select_variation' 
                                	name='units_grams<?php echo wpsc_the_product_id(); ?>' 
                                	id='units_grams<?php echo wpsc_the_product_id(); ?>'
                                    onblur="rounding('units_grams<?php echo wpsc_the_product_id(); ?>', 'grams', '<?php echo $price; ?>', '<?php echo wpsc_the_product_id(); ?>','<?php echo $query->min_grams; ?>', '<?php echo $query->max_grams; ?>')"
                                    onkeydown="clear1('<?php echo wpsc_the_product_id(); ?>','units_kilograms<?php echo wpsc_the_product_id(); ?>','total_kilograms<?php echo wpsc_the_product_id(); ?>')" 
                                    onkeyup="checknumber('grams',<?php echo $price; ?>, 'units_grams<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_grams; ?>', '<?php echo $query->max_grams; ?>')" 
									<?php 
										//if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) 
											//echo "value='".$wpsc_cart->get_cart_item(wpsc_the_product_id())->units_grams."'"; ?> />	
							</td>
							<td height="15px">
								<div id='total_grams<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' style="margin-left:5px;" >
								<?php if(isset($_GET['units_grams'.wpsc_the_product_id()])) {
									echo "R".(number_format($_GET['units_grams'.wpsc_the_product_id()] * $price / 1000,2));
									//} else if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
									//echo "R".(number_format(($wpsc_cart->get_cart_item(wpsc_the_product_id())->units_grams * $price / 1000),2));
									} else {
									echo "R0.00";
									} ?>
								</div><!--#total_grams-->
							</td>
						</tr>
	<?php } 
		if($query->units_kilograms) { ?>
						<tr>
                         	<td style="width:100px;padding-left:10px;height:15px">Kilograms:</td>
                            <td width="20px" height="15px">
                            	<?php $price = $query->price; //substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
                                <input size='3' maxlength='3' type='text' class='wpsc_select_variation' 
                                name="units_kilograms<?php echo wpsc_the_product_id(); ?>" 
                                id="units_kilograms<?php echo wpsc_the_product_id(); ?>" 
                                onblur="check_vals('kilograms',<?php echo $price; ?>, 'units_kilograms<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_kilograms; ?>', '<?php echo $query->max_kilograms; ?>')" 
                                onkeydown="clear1('<?php echo wpsc_the_product_id(); ?>','units_grams<?php echo wpsc_the_product_id(); ?>','total_grams<?php echo wpsc_the_product_id(); ?>')"
                                onkeyup="checknumber('kilograms',<?php echo $price; ?>, 'units_kilograms<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_kilograms; ?>', '<?php echo $query->max_kilograms; ?>')" 
								<?php 
									//if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) 
										//echo "value='".$wpsc_cart->get_cart_item(wpsc_the_product_id())->units_kilograms."'"; ?> />
                            </td>
                            <td height="15px">
                            	<div id='total_kilograms<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' style="margin-left:5px;" >
                                <?php if(isset($_GET['units_kilograms'.wpsc_the_product_id()])) {
                                	echo "R".(number_format($_GET['units_kilograms'.wpsc_the_product_id()] * $price,2));
                                     //} else if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
                                     //echo "R".(number_format(($wpsc_cart->get_cart_item(wpsc_the_product_id())->units_kilograms * $price),2));
                                     } else {
                                     echo "R0.00";
                                     } ?>
								</div><!--#total_kilograms-->
							</td>
						</tr>
	<?php }
		if($query->units_ounces) { ?>
						<tr>
							<td style="width:100px;padding-left:10px;">Ounces:</td>
							<td width="20px">
								<?php $price = $query->price; //substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
								<input size='3' maxlength='3' type='text' class='wpsc_select_variation' 
                                name="units_ounces<?php echo wpsc_the_product_id(); ?>" 
                                id="units_ounces<?php echo wpsc_the_product_id(); ?>" 
                                onblur="check_vals('ounces',<?php echo $price; ?>, 'units_ounces<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_ounces; ?>', '<?php echo $query->max_ounces; ?>')" 
                                onkeyup="checknumber('ounces',<?php echo $price; ?>, 'units_ounces<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_ounces; ?>', '<?php echo $query->max_ounces; ?>')" 
								<?php 
									//if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) 
										//echo "value='".$wpsc_cart->get_cart_item(wpsc_the_product_id())->units_ounces."'"; ?> />
							</td>
							<td>
								<div id='total_ounces<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' style="margin-left:5px;" >
								<?php if(isset($_GET["units_ounces".wpsc_the_product_id().""])) {
									echo "R".(number_format($_GET['units_ounces'.wpsc_the_product_id()] * $price,2));
									//} else if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
									//echo "R".(number_format(($wpsc_cart->get_cart_item(wpsc_the_product_id())->units_ounces * $price),2));
									} else {
									echo "R0.00";
									} ?>
								</div><!--#total_ounces-->
							</td>
						</tr>
	<?php }
		if($query->units_pounds) { ?>
						<tr>
							<td style="width:100px;padding-left:10px;">Pounds:</td>
							<td width="20px">
								<?php $price = $query->price; //substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
								<input size='3' maxlength='3' type='text' class='wpsc_select_variation' 
                                name="units_pounds<?php echo wpsc_the_product_id(); ?>" 
                                id="units_pounds<?php echo wpsc_the_product_id(); ?>" 
                                onblur="check_vals('pounds',<?php echo $price; ?>, 'units_pounds<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_pounds; ?>', '<?php echo $query->max_pounds; ?>')" 
                                onkeyup="checknumber('pounds',<?php echo $price; ?>, 'units_pounds<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_pounds; ?>', '<?php echo $query->max_pounds; ?>')" 
								<?php 
									//if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) 
										//echo "value='".$wpsc_cart->get_cart_item(wpsc_the_product_id())->units_pounds."'"; ?> />
							</td>
							<td>
								<div id='total_pounds<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' style="margin-left:5px;" >
								<?php if(isset($_GET["units_pounds".wpsc_the_product_id().""])) {
									echo "R".(number_format($_GET["units_pounds".wpsc_the_product_id().""] * $price,2));
									//} else if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
									//echo "R".(number_format(($wpsc_cart->get_cart_item(wpsc_the_product_id())->units_pounds * $price),2));
									} else {
									echo "R0.00";
									} ?>
								</div><!--#total_pounds-->
							</td>
						</tr>
	<?php } ?>
						<input type='hidden' name='total_price<?php echo wpsc_the_product_id(); ?>' id='total_price<?php echo wpsc_the_product_id(); ?>' value='<?php echo (isset($_POST['key']) && $wpsc_cart->get_cart_item_by_key($_POST['key'])) ? $wpsc_cart->get_cart_item_by_key($_POST['key'])->total_price : "0"; ?>' />
					</table>
					</div>
<?php } ?>
					
					<div class="totals_container_custom">
						<div class="left">
							<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
							<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
<?php 
if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
							<div class="wpsc_buy_button">
<?php 
	if(wpsc_product_has_stock()) : 
		if(wpsc_product_external_link(wpsc_the_product_id()) != '') : 
			$action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
								<input class="wpsc_buy_button" type='button' value='<?php echo __('Buy Now', 'wpsc'); ?>' onclick='gotoexternallink("<?php echo $action; ?>")'>
<?php 
		else : //(!$wpsc_cart->get_cart_item(wpsc_the_product_id())) : ?>
								<input type="hidden" value="add" name="weight" />
								<input type="submit" value="" name="Buy" class="buy_button" id="custom_product_<?php echo wpsc_the_product_id(); ?>_submit_button" />
<?php 
		//else : ?>
<!--        
								<input type="hidden" value="weight" name="weight" />
								<input type="hidden" name="key" value="<?php //echo wpsc_the_cart_item_key(); ?>" />
								<input type="submit" value="" class="update_button" name="submit" />
-->                           
<?php 
		endif; ?>
								<div class='wpsc_loading_animation'>
									<img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" class="loadingimage"/>
									<?php echo __('Updating cart...', 'wpsc'); ?>
								</div><!--.wpsc_loading_animation-->
<?php 
	else : ?>
								<p class='soldout'><?php echo __('This product has sold out.', 'wpsc'); ?></p>
<?php 
	endif ; ?>
							</div><!--.wpsc_buy_button-->
						</div><!--.left-->
    					<div class="right">
							<div class="subtotal">
        						<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'>Subtotal:</label></div><!--#left-->
    							<div class="subtotal_val" id="subtotal<?php echo wpsc_the_product_id(); ?>">
<?php //if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
	//echo "R" . number_format($wpsc_cart->get_cart_item(wpsc_the_product_id())->unit_price, 2); 
	/* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
//} else {
	echo "R0.00";
//} ?>
								</div><!--.subtotal_val #subtotal-->
        					</div><!--.subtotal-->
        					<div style="clear: right"></div>
<?php 
//if(wpsc_has_multi_adding()): 
	//if(wpsc_have_variation_groups()) { ?>
							<div class="quantity">
								<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'><?php echo __('Quantity', 'wpsc'); ?>:</label></div><!--#left-->
								<div id="right">
									<input type="text" id='custom_wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>' 
                                    	name="custom_wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" size="2" maxlength="2" 
                                        value="1" <?php //echo ($wpsc_cart->get_cart_item(wpsc_the_product_id())) ? $wpsc_cart->get_cart_item(wpsc_the_product_id())->quantity : "0"; ?> 
                                        onblur="update_qty(<?php echo wpsc_the_product_id(); ?>)" 
                                        onkeyup="update_qty(<?php echo wpsc_the_product_id(); ?>)"/>
									<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
									<input type="hidden" name="wpsc_update_quantity" value="true"/>
								</div><!--#right-->
							</div><!--.quantity-->
							<div style="clear: right"></div>
<?php 
	//}
//endif ;?>
							<div class="total">
								<div id="total_text"><label>Total:</label></div><!--#total_text-->
								<div id="total<?php echo wpsc_the_product_id(); ?>" class="total_val">
<?php 
/*if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
	echo "<script>updatetotal(".wpsc_the_product_id().")</script>";
} else {*/
	echo "R0.00";
//} ?>
								</div><!--#total-->
							</div><!--.total-->
						</div><!--.right-->
					</div><!--.totals_container_custom-->
				</form>
				</div><!--.prod_form-->
<?php 
endif ; ?>
			</div><!--#custom-->
			<div id="item<?php echo wpsc_the_product_id(); ?>" 
            	style="display:<?php echo ($_GET['custom'] == '0') ? "block;" : "none;"; ?>">
<?php 
/*if ($wpsc_cart->get_cart_item(wpsc_the_product_id()))
	$variations = subval_sort($wpsc_cart->get_cart_item(wpsc_the_product_id())->variation_data,'variation_id');  
else 
	$variations = null;*/ ?>
				<div class="prod_form">
				<form class='product_form'  enctype="multipart/form-data" action="<?php echo $action; ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_<?php echo wpsc_the_product_id(); ?>">
                 	<input type="hidden" name="product_<?php echo wpsc_the_product_id(); ?>_single" id="product_<?php echo wpsc_the_product_id(); ?>_single" value="false" />
<?php /** the variation group HTML and loop */?>
					<div class="wpsc_variation_forms_item">
<?php 
//$i = 0;
while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
						<div class="variation_name"><label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label></div><!--.variation_name-->
						<div class="variation_select">
							<select class='wpsc_select_variation' name="variation<?php echo wpsc_vargrp_id(); ?>" 
                            	id="<?php echo wpsc_vargrp_form_id(); ?>" 
                                onchange="test('<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_vargrp_form_id(); ?>')">
								<option value="" <?php echo wpsc_the_variation_out_of_stock(); ?> ></option>
<?php 
	while (wpsc_have_variations()) : wpsc_the_variation(); ?>
								<option value="<?php echo wpsc_the_variation_id(); ?>" <?php echo wpsc_the_variation_out_of_stock(); ?>
									<?php /*if (isset($_GET['variation_select_'.wpsc_the_product_id().'_'.wpsc_vargrp_id()])) echo 'selected="selected"'; 
										else if ($variations[$i]['id'] == wpsc_the_variation_id()) echo 'selected="selected"'; 
										else echo "";*/ ?> ><?php echo wpsc_the_variation_name(); ?></option>
<?php
	endwhile; 
	//$i++; ?>
							</select>
                        </div><!--.variation_select-->
                        <div style="clear: both"></div>
<?php
endwhile; ?>
					</div><!--.wpsc_variation_forms_item-->
					<div class="totals_container">
						<div class="right">
							<div class="subtotal">
								<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'>Subtotal:</label></div><!--#left-->
								<div class="subtotal_val" id="subtot<?php echo wpsc_the_product_id(); ?>">
<?php 
/*if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
	echo "R" . number_format($wpsc_cart->get_cart_item(wpsc_the_product_id())->unit_price, 2); 
	// "<script>updatetotal(".wpsc_the_product_id().")</script>";
} else {*/
	echo "R0.00";
//} ?>
								</div><!--.subtotal_val-->
							</div><!--.subtotal-->
							<div style="clear: right"></div>
<?php 
if(wpsc_has_multi_adding()): 
	if(wpsc_have_variation_groups()) { ?>
							<div class="quantity">
								<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'><?php echo __('Quantity', 'wpsc'); ?>:</label></div><!--#left-->
								<div id="right">
									<input type="text" id='wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>' 
                                    	name="wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" size="2" maxlength="2" 
                                        value="1" 
                                        onblur="update_qty(<?php echo wpsc_the_product_id(); ?>)" 
                                        onkeyup="update_qty(<?php echo wpsc_the_product_id(); ?>)"/>
									<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
									<input type="hidden" name="wpsc_update_quantity" value="true"/>
								</div><!--#right-->
							</div><!--.quantity-->
							<div style="clear: right"></div>
<?php } endif ;?>
							<div class="total">
								<input type='hidden' name='total_price_var<?php echo wpsc_the_product_id(); ?>' id='total_price_var<?php echo wpsc_the_product_id(); ?>' value='<?php echo (isset($_POST['key']) && $wpsc_cart->get_cart_item_by_key($_POST['key'])) ? $wpsc_cart->get_cart_item_by_key($_POST['key'])->total_price : "0"; ?>' />
        						<div id="total_text"><label>Total:</label></div><!--#total_text-->
            					<div id="tot<?php echo wpsc_the_product_id(); ?>" class="total_val">

<?php 
//if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
	//echo "R" . number_format($wpsc_cart->get_cart_item(wpsc_the_product_id())->total_price, 2);
	/*echo "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
//} else {
	echo "R0.00";
//} ?>
								</div><!--.total_val-->
							</div><!--.total-->
						</div><!--.right-->
						<div class="left">
							<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
							<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
<?php 
if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
							<div class="wpsc_buy_button">
<?php 
	if(wpsc_product_has_stock()) :
		if(wpsc_product_external_link(wpsc_the_product_id()) != '') :
			$action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
								<input class="wpsc_buy_button" type='button' value='<?php echo __('Buy Now', 'wpsc'); ?>' onclick='gotoexternallink("<?php echo $action; ?>")'>
<?php 
		else : //if (!$wpsc_cart->get_cart_item(wpsc_the_product_id())) : ?>
								<input type="hidden" value="add" name="prepacked" />
								<input type="submit" value="" name="Buy" class="buy_button" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button" />
<?php 
		//else : ?>
<!--        
								<input type="hidden" value="weight" name="weight" />
								<input type="hidden" name="key" value="<?php //echo wpsc_the_cart_item_key(); ?>" />
								<input type="submit" value="" class="update_button" name="submit" />
-->                                
<?php 
		endif; ?>
								<div class='wpsc_loading_animation'>
									<img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" class="loadingimage"/>
									<?php echo __('Updating cart...', 'wpsc'); ?>
								</div><!--.wpsc_loading_animation-->
<?php 
	else : ?>
								<p class='soldout'><?php echo __('This product has sold out.', 'wpsc'); ?></p>
<?php 
	endif ; ?>
							</div><!--.wpsc_buy_button-->
						</div><!--.left-->
					</div><!--.totals_container-->
				</form>
				</div><!--.prod_form-->
<?php 
endif ; ?>
			</div><!--#item-->
<?php 
} else { ?>				
            <div class="prod_form">
<?php 
/*if ($wpsc_cart->get_cart_item(wpsc_the_product_id()))
	$variations = subval_sort($wpsc_cart->get_cart_item(wpsc_the_product_id())->variation_data,'variation_id');  
else 
	$variations = null;*/
?>
				<form class='product_form'  enctype="multipart/form-data" action="<?php echo $action; ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_<?php echo wpsc_the_product_id(); ?>">
                 	<input type="hidden" name="product_<?php echo wpsc_the_product_id(); ?>_single" id="product_<?php echo wpsc_the_product_id(); ?>_single" value="false" />
					<div class="wpsc_variation_forms" style="height: 110px;">
<?php //$i = 0; 
while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
						<div class="variation_name"><label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label></div><!--.variation_name-->
						<div class="variation_select">
							<select class='wpsc_select_variation' name="variation[<?php echo wpsc_vargrp_id(); ?>]" 
                            	id="<?php echo wpsc_vargrp_form_id(); ?>" 
                                onchange="test('<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_vargrp_form_id(); ?>')">
								<option value="" <?php echo wpsc_the_variation_out_of_stock(); ?> ></option>
<?php 
	while (wpsc_have_variations()) : wpsc_the_variation(); ?>
								<option value="<?php echo wpsc_the_variation_id(); ?>" <?php echo wpsc_the_variation_out_of_stock(); ?> 
									<?php /*if (isset($_GET['variation_select_'.wpsc_the_product_id().'_'.wpsc_vargrp_id()])) echo 'selected="selected"'; 
										else if ($variations[$i]['id'] == wpsc_the_variation_id()) echo 'selected="selected"'; 
										else echo "";*/ ?> ><?php echo wpsc_the_variation_name(); ?></option>
<?php 
	endwhile; 
	//$i++;?>
							</select>
						</div><!--.variation_select-->
						<div style="clear: both"></div>
<?php 
endwhile; ?>
					</div><!--.wpsc_variation_forms-->
					<div class="totals_container">
						<div class="right">
							<div class="subtotal">
								<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'>Subtotal:</label></div><!--#left-->
								<div class="subtotal_val" id="subtot<?php echo wpsc_the_product_id(); ?>">
<?php 
//if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
	//echo "R" . number_format($wpsc_cart->get_cart_item(wpsc_the_product_id())->unit_price, 2); 
	/* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
//} else {
	echo "R0.00";
//} ?>
								</div><!--.subtotal_val-->
							</div><!--.subtotal-->
							<div style="clear: right"></div>
<?php 
if(wpsc_has_multi_adding()):
	if(wpsc_have_variation_groups()) { ?>
							<div class="quantity">
								<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'><?php echo __('Quantity', 'wpsc'); ?>:</label></div><!--#left-->
								<div id="right">
									<input type="text" id="wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" name="wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" size="2" maxlength="2" 
                                    value="1" <?php //echo ($wpsc_cart->get_cart_item(wpsc_the_product_id())) ? $wpsc_cart->get_cart_item(wpsc_the_product_id())->quantity : "0"; ?> 
                                    onkeyup="update_qty(<?php echo wpsc_the_product_id(); ?>)" 
                                    onblur="update_qty(<?php echo wpsc_the_product_id(); ?>)"/>
									<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
									<input type="hidden" name="wpsc_update_quantity" value="true"/>
								</div><!--#right-->
							</div><!--.quantity-->
							<div style="clear: right"></div>
<?php 
	}
endif ;?>
							<div class="total">
								<input type='hidden' name='total_price_var<?php echo wpsc_the_product_id(); ?>' id='total_price_var<?php echo wpsc_the_product_id(); ?>' value='<?php echo (isset($_POST['key']) && $wpsc_cart->get_cart_item_by_key($_POST['key'])) ? $wpsc_cart->get_cart_item_by_key($_POST['key'])->total_price : "0"; ?>' />
       							<div id="total_text"><label>Total:</label></div><!--#total_text-->
           						<div id="total<?php echo wpsc_the_product_id(); ?>" class="total_val">
<?php 
//if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
	//echo "R" . number_format($wpsc_cart->get_cart_item(wpsc_the_product_id())->total_price, 2); 
	/* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
//} else {
	echo "R0.00";
//} ?>                                        
								</div><!--.total_val-->
        					</div><!--.total-->
						</div><!--.right-->
						<div class="left">
							<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
							<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
<?php 
if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
							<div class="wpsc_buy_button">
<?php 
	if(wpsc_product_has_stock()) :
		if(wpsc_product_external_link(wpsc_the_product_id()) != '') :
			$action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
								<input class="wpsc_buy_button" type='button' value='<?php echo __('Buy Now', 'wpsc'); ?>' onclick='gotoexternallink("<?php echo $action; ?>")'>
<?php
		else : //if (!$wpsc_cart->get_cart_item(wpsc_the_product_id())) : ?>
								<input type="hidden" value="add" name="prepacked" />
								<input type="submit" value="" name="Buy" class="buy_button" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button" />
<?php 
		//else : ?>
<!--        
								<input type="hidden" value="weight" name="weight" />
								<input type="hidden" name="key" value="<?php //echo wpsc_the_cart_item_key(); ?>" />
								<input type="submit" value="" class="update_button" name="submit" />
-->
<?php 
		endif; ?>
								<div class='wpsc_loading_animation'>
									<img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" class="loadingimage"/>
									<?php echo __('Updating cart...', 'wpsc'); ?>
								</div><!--.wpsc_loading_animation-->
<?php
	else : ?>
								<p class='soldout'><?php echo __('This product has sold out.', 'wpsc'); ?></p>
<?php 
	endif ; ?>
							</div><!--.wpsc_buy_button-->
						</div><!--.left-->
					</div><!--.totals_container-->
				</form>
			</div><!--.prod_form-->
<?php 
endif ;
} ?>
<?php 
if((get_option('hide_addtocart_button') == 0) && (get_option('addtocart_or_buynow')=='1')) : 
	echo wpsc_buy_now_button(wpsc_the_product_id());
endif ; ?>
<?php echo wpsc_product_rater(); ?>
<?php
if(function_exists('gold_shpcrt_display_gallery')) :					
	echo gold_shpcrt_display_gallery(wpsc_the_product_id(), true);
endif; ?>
		</div><!--.middle-->
	</div><!--.inner_product_container-->
</div><!--.outer_product_container-->

<?php endwhile; ?>
<?php endif; ?>
<?php /** end the product loop here */?>
<?php //****************************** ?>			
<!--</div>	-->	
<?php //endwhile; ?>

<?php if(wpsc_product_count() > 0):?>
<div class="productdisplayend"></div>

<?php else :?>
<div class="shopcart_empty">
	<div class="title"><h4>
<?php $cat = $wpdb->get_var("SELECT `name` FROM `wp_wpsc_product_categories` WHERE id = ".$_GET['category']);
				echo $cat; ?>
	</h4></div>
	<div class="no_items">No products</div>
	<div class="redtext">No products are available in this category at this moment.</div>
    <div class="blacktext"><div id="greenlinkbig"><a href="<?php echo home_url( '/' )."?page_id=10"; ?>">Click here</a>&nbsp;to view our selection of meats...</div></div>
<?php endif ; ?>
<?php
if(function_exists('fancy_notifications')) {
echo fancy_notifications();
}
?>

<?php endif; ?>
</div><!--#products_page_container-->
<!-- Start Pagination -->
<?php 
if (isset($_GET['category'])) {
if ( ( get_option( 'use_pagination' ) == 1 && ( get_option( 'wpsc_page_number_position' ) == 2 || get_option( 'wpsc_page_number_position' ) == 3 ) ) ) : ?>
	<div class="wpsc_page_numbers">
<?php 
	if ( wpsc_has_pages() ) : ?>
		<?php echo wpsc_first_products_link( '&laquo; First', true ); ?> <?php echo wpsc_previous_products_link( '&laquo; Previous', true ); ?> <?php echo wpsc_pagination( 10 ); ?> <?php echo wpsc_next_products_link( 'Next &raquo;', true ); ?> <?php echo wpsc_last_products_link( 'Last &raquo;', true ); ?>
<?php 
	endif; ?>
	</div><!--.wpsc_page_numbers-->
<?php 
endif; 
}
?>
<!-- End Pagination -->
<div style="clear: both;"></div>
<?php if (isset($_GET['category'])) { ?>
<div class="top_link" ><a href="#top" id="cart_table">[back to top]</a></div>
<?php } ?>