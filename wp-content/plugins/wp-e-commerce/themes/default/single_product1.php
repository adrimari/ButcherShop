<?php

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

$id = (string)$_GET['product_id'];
$units_grams = "units_grams" . $id;
$units_kilograms = "units_kilograms" . $id;
$units_pounds = "units_pounds" . $id;
$units_ounces = "units_ounces" . $id;
//print_r($wpsc_cart->get_cart_item_by_key($_GET['key']));
//echo wpsc_cart_item_quantity();
//echo $wpsc_cart->get_cart_item(wpsc_the_product_id())->quantity;

//print_r($wpsc_cart);
/*
 * Most functions called in this page can be found in the wpsc_query.php file
 */
?>
<div id='products_page_container' class="wrap wpsc_container">

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
		</div><!-- .breadcrumb -->
	<?php endif; ?>
	<?php do_action('wpsc_top_of_products_page'); // Plugin hook for adding things to the top of the products page, like the live search ?>


	<?php if(wpsc_display_products()): ?>
		<!-- Start Pagination -->
		<?php if ( ( get_option( 'use_pagination' ) == 1 && ( get_option( 'wpsc_page_number_position' ) == 1 || get_option( 'wpsc_page_number_position' ) == 3 ) ) ) : ?>
			<div class="wpsc_page_numbers">
				<?php if ( wpsc_has_pages() ) : ?>
					Pages: <?php echo wpsc_first_products_link( '&laquo; First', true ); ?> <?php echo wpsc_previous_products_link( '&laquo; Previous', true ); ?> <?php echo wpsc_pagination( 10 ); ?> <?php echo wpsc_next_products_link( 'Next &raquo;', true ); ?> <?php echo wpsc_last_products_link( 'Last &raquo;', true ); ?>
				<?php endif; ?>
			</div><!-- .wpsc_page_numbers -->
		<?php endif; ?>		
		<!-- End Pagination -->
		
	<div style="clear: both"></div>
	<div id='wpsc_category_boundary'>
		<h4><?php echo wpsc_current_category_name_by_id($_GET['category']); ?></h4>
	</div><!-- #wpsc_category_boundary -->
	<div style="clear: both"></div>
<div class="outer_product_container">
<?php while (wpsc_have_products()) :  wpsc_the_product(); ?>
	<div class="inner_product_container">
		<div class="middle">
			<div class="prod_left_col">
				<div class="product_bg">
					<div class="prod_title">
						<?php echo wpsc_the_product_title(); ?>
						<?php echo wpsc_edit_the_product_link(); ?>
					</div><!-- .prod_title -->
					<?php if(get_option('show_thumbnails')) :?>
					<div class="prod_img_container">
                    	<div class="prod_img">
						<?php if(wpsc_the_product_thumbnail()) :?>
							<div class="gallery">
								<a href="<?php echo wpsc_the_product_image(); ?>" title="<?php echo wpsc_the_product_title(); ?>">
									<img id="product_image_<?php echo wpsc_the_product_id(); ?>" alt="<?php echo wpsc_the_product_title(); ?>" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo wpsc_the_product_thumbnail(); ?>"/>
								</a>
							</div><!-- .gallery -->
						<?php else: ?>
							<div class="item_no_image"></div><!-- .item_no_image -->
						<?php endif; ?>
						</div><!-- .prod_img -->
					</div><!-- .prod_img_container -->
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
									<span id="product_price_<?php echo wpsc_the_product_id(); ?>" class="pricedisplay2">R<?php echo $wpsc_query->product['price']; //substr(wpsc_the_product_price(get_option('wpsc_hide_decimals')),2); ?> per <?php echo $wpsc_query->product['weight_unit']; ?></span>
							<?php endif; ?>
							</div><!-- .wpsc_product_price -->
						</div><!-- .unit_price -->
						<div class="arrow"></div><!-- .arrow -->
					</div><!-- .prod_bottom -->
				</div><!-- .product_bg -->
			</div><!-- .prod_left_col -->
			<div class="prod_right_col">
			<?php
				do_action('wpsc_product_before_description', wpsc_the_product_id(), $wpsc_query->product);
				do_action('wpsc_product_addons', wpsc_the_product_id());
			?>
				<div class='wpsc_description'><?php echo wpsc_the_product_description(); ?></div><!-- .wpsc_description -->

<?php //****************************** ?>
						
				<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') {
					$action =  wpsc_product_external_link(wpsc_the_product_id()); 
				} else {
					$action =  htmlentities(wpsc_this_page_url(),ENT_QUOTES); 
				} ?>

				<?php $query = $wpdb->get_row("SELECT * FROM `wp_wpsc_product_list` WHERE `id` = ".wpsc_the_product_id()); ?>
				<?php if ($query->custom_units == 1) { ?>
	               	<div style="width:50%; float: left;">
                    	<div style="width: 100%; height: 20px; float: left">
							<input type="radio"  id="weight<?php echo wpsc_the_product_id(); ?>" name="custom" 
                        		onchange="enable_custom('<?php echo wpsc_the_product_id(); ?>','<?php echo wpsc_vargrp_form_id(); ?>')" 
								<?php echo (isset($_GET['custom']) && $_GET['custom'] == 1) ? "checked='checked'" : ($wpsc_cart->get_cart_item_by_key($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])->custom_units == 1) ? "checked='checked'" : ""; echo ($wpsc_cart->get_cart_item_by_key($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])->custom_units == 0) ? 'disabled="disabled"' : ""; ?>
                    			onclick="enable_custom('<?php echo wpsc_the_product_id(); ?>','<?php echo wpsc_vargrp_form_id(); ?><?php echo wpsc_vargrp_id(); ?>')" />
							<label for="weight<?php echo wpsc_the_product_id(); ?>" <?php echo ($wpsc_cart->get_cart_item_by_key($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])->custom_units == 0) ? "onclick='enable_custom('".wpsc_the_product_id()."','".wpsc_vargrp_form_id().wpsc_vargrp_id()."')" : ""; ?> >PER WEIGHT</label><br />
						</div>
               			<div style="width: 100%; height: 20px; float: left">
                   			<input type="radio"  id="items<?php echo wpsc_the_product_id(); ?>" name="custom" 
                        		onchange="enable_item('<?php echo wpsc_the_product_id(); ?>','<?php echo wpsc_vargrp_form_id(); ?>')" 
								<?php echo (isset($_GET['custom']) && $_GET['custom'] == 0) ? "checked='checked'" : ($wpsc_cart->get_cart_item_by_key($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])->custom_units == 0) ? "checked='checked'" : ""; echo ($wpsc_cart->get_cart_item_by_key($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])->custom_units == 1) ? 'disabled="disabled"' : (!wpsc_have_variation_groups()) ? 'disabled="disabled"' : ""; ?>
                    			onclick="enable_item('<?php echo wpsc_the_product_id(); ?>','<?php echo wpsc_vargrp_form_id(); ?>')" />
							<label for="items<?php echo wpsc_the_product_id(); ?>"  <?php echo ($wpsc_cart->get_cart_item_by_key($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])->custom_units == 0) ? "onclick='enable_item('".wpsc_the_product_id()."','".wpsc_vargrp_form_id()."')'" : ""; ?> >PRE-PACKAGED</label>
						</div>
                    </div>
                    <div style="float: right; width: 100px; height: 20px;">
                    <?php if (!isset($_GET['edit']) && isset($_GET['key'])) : ?>
                    	<div class="edit_prod" onclick="enable_edit('<?php echo wpsc_the_product_id(); ?>')">[Edit this product...]</div><!-- .edit_prod -->	
					<?php endif; ?>
                    <?php if (isset($_GET['key'])) : ?>
                    	<div class="add_prod"><a href="<?php echo get_site_url()."/?page_id=".$_GET['page_id']."&category=".$_GET['category']."&product_id=".$_GET['product_id']."&custom=".$_GET['custom']; ?>" title="Add new Product">[Add a new item here...]</a></div><!-- .add_prod -->
                    <?php endif; ?>
                    </div>
					<div id="custom<?php echo wpsc_the_product_id(); ?>" class="custom" style="display:<?php echo ($_GET['custom'] == '1') ? "block" : ($wpsc_cart->get_cart_item_by_key($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])->custom_units == 1) ? "block" : "none"; ?>; width: 164px;">
						<div class="prod_form">
                        <form class='product_form'  enctype="multipart/form-data" action="<?php echo str_replace("amp;","",$action); ?>" method="post" name="custom_product_<?php echo wpsc_the_product_id(); ?>" id="custom_product_<?php echo wpsc_the_product_id(); ?>">
                    	<?php do_action('wpsc_product_addon_after_descr', wpsc_the_product_id()); ?>
                    	<?php if ($query->custom_units == 1) { ?>
							<div class="product_form">
							<table>
						<?php if($query->units_grams) { ?>
								<tr>
									<td style="width:100px;padding-left:10px;padding-bottom:2px;">Grams:</td>
									<td width="20px">
										<?php $price = $query->price; //substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
										<input size='3' maxlength='3' type='text' class='wpsc_select_variation' 
                                        	name='units_grams<?php echo wpsc_the_product_id(); ?>' 
                                            id='units_grams<?php echo wpsc_the_product_id(); ?>' 
                                            onblur="check_vals('grams',<?php echo $price; ?>, 'units_grams<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_grams; ?>', '<?php echo $query->max_grams; ?>')" 
                                            onkeyup="checknumber('grams',<?php echo $price; ?>, 'units_grams<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_grams; ?>', '<?php echo $query->max_grams; ?>')" 
                                            value="<?php if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) echo (int)$wpsc_cart->get_cart_item_by_key($_GET['key'])->units_grams; 
												else if (isset($_GET[$units_grams])) echo $_GET[$units_grams]; ?>"
                                            <?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?> />
                                	</td>
									<td>
										<div id='total_grams<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' style="margin-left:5px;" >
										<?php if($wpsc_cart->get_cart_item_by_key($_GET['key'])) {
											echo "R".(number_format($wpsc_cart->get_cart_item_by_key($_GET['key'])->units_grams * $price / 1000,2));
											} else if (isset($_GET['units_grams'.wpsc_the_product_id()])) {
											echo "R" . number_format($_GET['units_grams'.wpsc_the_product_id()] * $price / 1000, 2);
											//in_cart(wpsc_the_product_id())) {
											//echo "R".(number_format(($wpsc_cart->get_cart_item(wpsc_the_product_id())->units_grams * $price / 1000),2));
											} else {
											echo "R0.00";
											} ?>
										</div>
									</td>
								</tr>
						<?php }
							if($query->units_kilograms) { ?>
                                <tr>
                                    <td style="width:100px;padding-left:10px;padding-bottom:2px;">Kilograms:</td>
                                    <td width="20px">
                                        <?php $price = $query->price; //substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
                                        <input size='3' maxlength='3' type='text' class='wpsc_select_variation'
                                        	name="units_kilograms<?php echo wpsc_the_product_id(); ?>" 
                                            id="units_kilograms<?php echo wpsc_the_product_id(); ?>" 
                                            onblur="check_vals('kilograms',<?php echo $price; ?>, 'units_kilograms<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_kilograms; ?>', '<?php echo $query->max_kilograms; ?>')" 
                                            onkeyup="checknumber('kilograms',<?php echo $price; ?>, 'units_kilograms<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_kilograms; ?>', '<?php echo $query->max_kilograms; ?>')" 
                                            value="<?php if (isset($_GET[$units_kilograms])) echo $_GET[$units_kilograms]; 
												else if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) echo (int)$wpsc_cart->get_cart_item_by_key($_GET['key'])->units_kilograms; ?>"
                                            <?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?> />
                                    </td>
                                    <td>
                                        <div id='total_kilograms<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' style="margin-left:5px;" >
                                        <?php if(isset($_GET['units_kilograms'.wpsc_the_product_id()])) {
                                            echo "R".(number_format($_GET['units_kilograms'.wpsc_the_product_id()] * $price,2));
                                            } else if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) {
											echo "R" . number_format($wpsc_cart->get_cart_item_by_key($_GET['key'])->units_kilograms * $price,2);
                                            } else {
                                            echo "R0.00";
                                            } ?>
                                        </div>
                                    </td>
                                </tr>
						<?php }
							if($query->units_ounces) { ?>
                                <tr>
                                    <td style="width:100px;padding-left:10px;padding-bottom:2px;">Ounces:</td>
                                    <td width="20px">
                                        <?php $price = $query->price; //substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
                                        <input size='3' maxlength='3' type='text' class='wpsc_select_variation' 
                                        	name="units_ounces<?php echo wpsc_the_product_id(); ?>" 
                                            id="units_ounces<?php echo wpsc_the_product_id(); ?>" 
                                            onblur="check_vals('ounces',<?php echo $price; ?>, 'units_ounces<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_ounces; ?>', '<?php echo $query->max_ounces; ?>')" 
                                            onkeyup="checknumber('ounces',<?php echo $price; ?>, 'units_ounces<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_ounces; ?>', '<?php echo $query->max_ounces; ?>')" 
                                            value="<?php if (isset($_GET[$units_ounces])) echo $_GET[$units_ounces]; 
												else if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) echo (int)$wpsc_cart->get_cart_item_by_key($_GET['key'])->units_ounces; ?>"
                                            <?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?> />
                                    </td>
                                    <td>
                                        <div id='total_ounces<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' style="margin-left:5px;" >
                                        <?php if(isset($_GET["units_ounces".wpsc_the_product_id().""])) {
                                            echo "R".(number_format($_GET["units_ounces".wpsc_the_product_id().""] * $price,2));
                                            } else if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) {
											echo "R" . number_format($wpsc_cart->get_cart_item_by_key($_GET['key'])->units_ounces * $price,2);
                                            } else {
                                            echo "R0.00";
                                            } ?>
                                        </div>
                                    </td>
                                </tr>
						<?php }
							if($query->units_pounds) { ?>
                                <tr>
                                    <td style="width:100px;padding-left:10px;padding-bottom:2px;">Pounds:</td>
                                    <td width="20px">
                                        <?php $price = $query->price; //substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
                                        <input size='3' maxlength='3' type='text' class='wpsc_select_variation' 
                                        	name="units_pounds<?php echo wpsc_the_product_id(); ?>" 
                                            id="units_pounds<?php echo wpsc_the_product_id(); ?>" 
                                            onblur="check_vals('pounds',<?php echo $price; ?>, 'units_pounds<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_pounds; ?>', '<?php echo $query->max_pounds; ?>')" 
                                            onkeyup="checknumber('pounds',<?php echo $price; ?>, 'units_pounds<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_pounds; ?>', '<?php echo $query->max_pounds; ?>')" 
                                            value="<?php if (isset($_GET[$units_pounds])) echo $_GET[$units_pounds]; 
												else if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) echo (int)$wpsc_cart->get_cart_item_by_key($_GET['key'])->units_pounds; ?>"
                                            <?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?> />
                                    </td>
                                    <td>
                                        <div id='total_pounds<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' style="margin-left:5px;" >
                                        <?php if(isset($_GET['units_pounds'.wpsc_the_product_id()])) {
                                            echo "R".(number_format($_GET['units_pounds'.wpsc_the_product_id()] * $price,2));
                                            } else if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) {
											echo "R" . number_format($wpsc_cart->get_cart_item_by_key($_GET['key'])->units_pounds * $price,2);
                                            } else {
                                            echo "R0.00";
                                            } ?>
                                        </div>
                                    </td>
                                </tr>                               
              	<?php } ?>
							<input type='hidden' name='total_price<?php echo wpsc_the_product_id(); ?>' id='total_price<?php echo wpsc_the_product_id(); ?>' value='<?php if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) echo $wpsc_cart->get_cart_item_by_key($_GET['key'])->total_price; else if (isset($_GET['total_price'.wpsc_the_product_id()])) echo $_GET['total_price'.wpsc_the_product_id()]; else echo "0"; ?>' />
							</table>
							<!--</div>-->
						<?php } ?>
					
							<div class="totals_container_custom">
								<div class="left">
									<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
									<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
    								<?php if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
									<div class="wpsc_buy_button">
									<?php if(wpsc_product_has_stock()) : ?>
										<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
											<?php $action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
											<input class="wpsc_buy_button" type='button' value='<?php echo __('Buy Now', 'wpsc'); ?>' onclick='gotoexternallink("<?php echo $action; ?>")'>
										<?php elseif (!isset($_GET['key'])) : ?>
											<input type="hidden" name="weight" value="add" />
											<input type="submit" value="" name="Buy" class="buy_button" id="custom_product_<?php echo wpsc_the_product_id(); ?>_submit_button" disabled="disabled" />
										<?php else : ?>
											<input type="hidden" name="weight" value="update" />
											<input type="hidden" name="key" value="<?php echo $_GET['key']; ?>" />
											<input type="submit" value="" class="update_button" name="submit" id="update<?php echo wpsc_the_product_id(); ?>"
                                            	<?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?> />
										<?php endif; ?>
										<div class='wpsc_loading_animation'>
											<img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" class="loadingimage"/>
											<?php echo __('Updating cart...', 'wpsc'); ?>
										</div><!-- .wpsc_loading_animation -->
									<?php else : ?>
										<p class='soldout'><?php echo __('This product has sold out.', 'wpsc'); ?></p>
									<?php endif ; ?>
                                    </div><!-- .wpsc_buy_button -->
								</div><!-- .left -->
                                <div style="clear: right;"></div>
    							<div class="right">
									<div class="subtotal">
        								<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'>Subtotal:</label></div><!-- #left -->
    									<div class="subtotal_val" id="subtotal<?php echo wpsc_the_product_id(); ?>">
										<?php if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) {
											echo "R" . number_format($wpsc_cart->get_cart_item_by_key($_GET['key'])->unit_price, 2); /* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
										} else if (isset($_GET['subtot'.wpsc_the_product_id()]) && $_GET['subtot'.wpsc_the_product_id()] != "R0.00") {
											echo $_GET['subtot'.wpsc_the_product_id()];
										} else {
											echo "R0.00";
										} ?>
                                        </div><!-- #subtotal_val -->
        							</div><!-- .subtotal -->
        							<div style="clear: right"></div>
								<!-- THE QUANTITY OPTION MUST BE ENABLED FROM ADMIN SETTINGS -->
								<?php //if(wpsc_has_multi_adding()): ?>
									<?php //if(wpsc_have_variation_groups()) { ?>
									<div class="quantity">
										<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'><?php echo __('Quantity', 'wpsc'); ?>:</label></div><!-- #left -->
										<div id="right">
											<input type="text" id='custom_wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>' 
                                            	name="custom_wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" size="2" maxlength="2" 
                                                value="<?php if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) echo $wpsc_cart->get_cart_item_by_key($_GET['key'])->quantity; 
													else if (isset($_GET['custom_wpsc_quantity_update'.wpsc_the_product_id()])) echo $_GET['custom_wpsc_quantity_update'.wpsc_the_product_id()]; 
													else echo "0"; ?>" 
                                                onblur="update_qty(<?php echo wpsc_the_product_id(); ?>)" 
                                                onkeyup="update_qty(<?php echo wpsc_the_product_id(); ?>)"
                                                <?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?> />
											<input type="hidden" name="key" value="<?php echo $_GET['key']; ?>"/>
											<input type="hidden" name="wpsc_update_quantity" value="true"/>
										</div><!-- #right -->
									</div><!-- .quantity -->
									<div style="clear: right"></div>
								<?php //} endif ;?>
									<div class="total">
        								<div id="total_text"><label>Total:</label></div><!-- #total_text -->
            							<div id="total<?php echo wpsc_the_product_id(); ?>" class="total_val">
										<?php if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
											echo "<script>updatetotal(".wpsc_the_product_id().")</script>";
										} else if (isset($_GET['total'.wpsc_the_product_id()]) && $_GET['total'.wpsc_the_product_id()] != "R0.00") {
											echo $_GET['total'.wpsc_the_product_id()];
										} else {
											echo "R0.00";
										} ?>
            							</div><!-- #total -->
        							</div><!-- .total -->
								</div><!-- .right -->
							</div><!-- .totals_container_custom -->
						</div><!-- .product_form -->
						</form>
						</div><!-- .prod_form -->
	<?php endif ; ?>
					</div><!-- .custom -->
					<div id="item<?php echo wpsc_the_product_id(); ?>" 
                    	style="display:<?php echo ($_GET['custom'] == '0') ? "block" : ($wpsc_cart->get_cart_item_by_key($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])->custom_units == 0) ? "block" : "none"; ?>;width: 164px;">
						<div class="prod_form">
<?php	
/*			if (isset($_GET['key'])) 
				$variations = subval_sort($wpsc_cart->get_cart_item_by_key($_GET['key'])->variation_data,'variation_id');
			else if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) 
				$variations = subval_sort($wpsc_cart->get_cart_item(wpsc_the_product_id())->variation_data,'variation_id');
			else $variations = null;*/
if (isset($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])) { //get_cart_item(wpsc_the_product_id())) {
			if (isset($_GET['key']))
				$variations = subval_sort($wpsc_cart->get_cart_item_by_key($_GET['key'])->variation_data,'variation_id');  
			else
				$variations = subval_sort($wpsc_cart->get_cart_item(wpsc_the_product_id())->variation_data,'variation_id');  
			//print_r($variations);
		}
		else 
		$variations = null;
		//$variations = $wpsc_cart->get_cart_item_by_key($_GET['key'])->variation_data;
		//print_r($variations);
?>
						<form class='product_form'  enctype="multipart/form-data" action="<?php echo str_replace("amp;","",$action); ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_<?php echo wpsc_the_product_id(); ?>">
						<?php do_action('wpsc_product_addon_after_descr', wpsc_the_product_id()); ?>
<?php /** the variation group HTML and loop */?>
							<div class="wpsc_variation_forms">
							<?php $i = 0; ?>
							<?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
								<div class="variation_name"><label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label></div><!-- .variation_name -->
<?php /** the variation HTML and loop */?>
								<div class="variation_select">
									<select class='wpsc_select_variation' name="variation[<?php echo wpsc_vargrp_id(); ?>]" 
                                    	id="<?php echo wpsc_vargrp_form_id(); ?>" 
                                        onchange="test('<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_vargrp_form_id(); ?>')"
                                        <?php echo (isset($_GET['key']) && !isset($_GET['edit'])) ? 'disabled="disabled"': ''; ?> >
										<option value="" <?php echo wpsc_the_variation_out_of_stock(); ?> ></option>
										<?php while (wpsc_have_variations()) : wpsc_the_variation(); ?>
											<option value="<?php echo wpsc_the_variation_id(); ?>" 
												<?php echo wpsc_the_variation_out_of_stock(); ?> 
												<?php if (isset($_GET['variation_select_'.wpsc_the_product_id().'_'.wpsc_vargrp_id()]) && 
																$_GET['variation_select_'.wpsc_the_product_id().'_'.wpsc_vargrp_id()] == wpsc_the_variation_id()) 
																	echo 'selected="selected"'; 
													else if ($variations[$i]['id'] == wpsc_the_variation_id()) echo 'selected="selected"'; 
													else echo ""; ?> ><?php echo wpsc_the_variation_name(); ?></option>
										<?php //echo 'variation_select_'.wpsc_the_product_id().'_'.wpsc_the_variation_id();
											endwhile; 
											$i++; ?>
									</select>
                            	</div><!-- .variation_select -->
                            	<div style="clear: both"></div>
							<?php endwhile; ?>
							</div><!-- .wpsc_variation_forms -->
<?php /** the variation group HTML and loop ends here */?>
<!---->

							<div class="totals_container">
						    	<div class="right">
									<div class="subtotal">
        								<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'>Subtotal:</label></div><!-- #left -->
   										<div class="subtotal_val" id="subtot<?php echo wpsc_the_product_id(); ?>">
										<?php if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) {
											echo "R" . number_format($wpsc_cart->get_cart_item_by_key($_GET['key'])->unit_price, 2); /* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
										//} else if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
											/*echo "R" . number_format($wpsc_cart->get_cart_item(wpsc_the_product_id())->unit_price, 2);*/ /* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
										} else if (isset($_GET['subtot'.wpsc_the_product_id()])) {
											echo $_GET['subtot'.wpsc_the_product_id()]; /* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
										} else {
											echo "R0.00";
										} ?>
                                        </div><!-- .subtotal_val -->
        							</div><!-- .subtotal -->
        							<div style="clear: right"></div>
<!-- THE QUANTITY OPTION MUST BE ENABLED FROM ADMIN SETTINGS -->
<?php if(wpsc_has_multi_adding()): ?>
	<?php if(wpsc_have_variation_groups()) { ?>
									<div class="quantity">
										<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'><?php echo __('Quantity', 'wpsc'); ?>:</label></div><!-- #left -->
										<div id="right">
											<input type="text" id='wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>' 
                                            	name="wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" size="2" maxlength="2" 
                                                value="<?php if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) echo $wpsc_cart->get_cart_item_by_key($_GET['key'])->quantity; 
														else if (isset($_GET['wpsc_quantity_update'.wpsc_the_product_id()])) echo $_GET['wpsc_quantity_update'.wpsc_the_product_id()]; 
														else echo "0"; ?>" 
                                                onblur="update_qty(<?php echo wpsc_the_product_id(); ?>)" 
                                                onkeyup="update_qty(<?php echo wpsc_the_product_id(); ?>)"
                                                <?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?>/>
											<input type="hidden" name="key" value="<?php echo $_GET['key']; ?>"/>
											<input type="hidden" name="wpsc_update_quantity" value="true"/>
										</div><!-- #right -->
									</div><!-- .quantity -->
									<div style="clear: right"></div>
<?php } endif ;?>
									<div class="total">
        								<div id="total_text"><label>Total:</label></div><!-- #total_text -->
            							<div id="tot<?php echo wpsc_the_product_id(); ?>" class="total_val">
										<?php if ($wpsc_cart->get_cart_item(wpsc_the_product_id())) {
											echo "R" . number_format($wpsc_cart->get_cart_item_by_key($_GET['key'])->total_price, 2);
											/*echo "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
										} else if (isset($_GET['tot'.wpsc_the_product_id()])) {
											echo $_GET['tot'.wpsc_the_product_id()]; /* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
										} else {
											echo "R0.00";
										} ?>
                                        </div><!-- #tot -->
        							</div><!-- .total -->
								</div><!-- .right -->
								<div class="left">
									<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
									<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
    								<?php if((get_option('hide_addtocart_button') == 0) && (get_option('addtocart_or_buynow') !='1')) : ?>
									<div class="wpsc_buy_button">
									<?php if(wpsc_product_has_stock()) : ?>
										<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
											<?php $action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
											<input class="wpsc_buy_button" type='button' value='<?php echo __('Buy Now', 'wpsc'); ?>' onclick='gotoexternallink("<?php echo $action; ?>")'>
										<?php elseif (!isset($_GET['key'])) : ?>
											<input type="hidden" value="add" name="prepacked" />
											<input type="submit" value="" name="Buy" class="buy_button" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button" disabled="disabled" />
										<?php else : ?>
											<input type="hidden" value="update" name="prepacked" />
											<input type="hidden" name="key" value="<?php echo $_GET['key']; ?>" />
											<input type="submit" value="" class="update_button" name="submit" id="update<?php echo wpsc_the_product_id(); ?>"
                                            	<?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?> />
										<?php endif; ?>
										<div class='wpsc_loading_animation'>
											<img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" class="loadingimage"/>
											<?php echo __('Updating cart...', 'wpsc'); ?>
										</div><!-- .wpsc_loading_animation -->
									<?php else : ?>
										<p class='soldout'><?php echo __('This product has sold out.', 'wpsc'); ?></p>
									<?php endif ; ?>
<!-- END OF QUANTITY OPTION -->
									</div><!-- .wpsc_buy_button -->
								</div><!-- .left -->
                        	</div><!-- .totals_container -->

<!---->
						</form>
                        </div><!-- .prod_form -->
<?php endif ; ?>
                 	</div><!-- #item -->
							<?php } else { ?>
                        <?php if (isset($_GET['key'])) { ?>
                        	<div style="width: 50%; height: 40px; float: left;"></div>
                        <?php } ?>
						<?php if (!isset($_GET['edit']) && isset($_GET['key'])) : ?>
                        	<div class="edit_prod" onclick="enable_edit('<?php echo wpsc_the_product_id(); ?>')">[Edit this product...]</div>
                        <?php endif; ?>	
                    	<?php //if (isset($_GET['key'])) : ?>
                    		<div class="add_prod" <?php if (!isset($_GET['key'])) echo 'style="display:none;"'; ?>><a href="<?php echo get_site_url()."/?page_id=".$_GET['page_id']."&category=".$_GET['category']."&product_id=".$_GET['product_id']; ?>" title="Add new Product">[Add a new item here...]</a></div>
                    	<?php //endif; ?>
						<div class="prod_form">
<?php	/**/
		if (isset($_GET['key']) && $wpsc_cart->get_cart_item_by_key($_GET['key'])) { //get_cart_item(wpsc_the_product_id())) {
			if (isset($_GET['key']))
				$variations = subval_sort($wpsc_cart->get_cart_item_by_key($_GET['key'])->variation_data,'variation_id');  
			else
				$variations = subval_sort($wpsc_cart->get_cart_item(wpsc_the_product_id())->variation_data,'variation_id');  
			//print_r($variations);
		}
		else 
		$variations = null;
		
		//$variations = $wpsc_cart->get_cart_item_by_key($_GET['key'])->variation_data;
		//print_r($variations);
?>
						<form class='product_form'  enctype="multipart/form-data" action="<?php echo str_replace("amp;","",$action); ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_<?php echo wpsc_the_product_id(); ?>">
						<?php do_action('wpsc_product_addon_after_descr', wpsc_the_product_id()); ?>                           
<?php /** the variation group HTML and loop */?>
							<div class="wpsc_variation_forms" style="height: <?php echo (isset($_GET['key'])) ? "70" : "110"; ?>px;">
							<?php $i = 0; ?>
							<?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
								<div class="variation_name"><label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label></div>
<?php /** the variation HTML and loop */?>
								<div class="variation_select">
								<select class='wpsc_select_variation' name="variation[<?php echo wpsc_vargrp_id(); ?>]" 
                                	id="<?php echo wpsc_vargrp_form_id(); ?>" 
                                    onchange="test('<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_vargrp_form_id(); ?>')" 
									<?php echo (isset($_GET['key']) && !isset($_GET['edit'])) ? 'disabled="disabled"': ''; ?> >
									<option value="" <?php echo wpsc_the_variation_out_of_stock(); ?> ></option>
									<?php //$j = 0;
										while (wpsc_have_variations()) : wpsc_the_variation(); ?>
										<option value="<?php echo wpsc_the_variation_id(); ?>" 
											<?php echo wpsc_the_variation_out_of_stock(); ?> 
											<?php if (isset($_GET['variation_select_'.wpsc_the_product_id().'_'.wpsc_vargrp_id()]) && 
															$_GET['variation_select_'.wpsc_the_product_id().'_'.wpsc_vargrp_id()] == wpsc_the_variation_id()) 
															echo 'selected="selected"'; 
												  else if ($variations[$i]['id'] == wpsc_the_variation_id()) echo 'selected="selected"'; else echo ""; ?> >
												  <?php echo wpsc_the_variation_name(); ?>
												  <?php echo $wpsc_cart->get_cart_item_by_key($_GET['key'])->variation_values[$i]['id'];?>
										</option>
									<?php //echo $wpsc_cart->get_cart_item_by_key($_GET['key'])->variation_values[$_GET['key']]['id'];//echo 'variation_select_'.wpsc_the_product_id().'_'.wpsc_vargrp_id();
										//$j++;
										//echo $wpsc_cart->get_cart_item_by_key($_GET['key'])->variation_values[$j]['id'];
										endwhile; 
										$i++;?>
								</select>
                                </div>
                                <div style="clear: both"></div>
							<?php endwhile; ?>
							</div>
<?php /** the variation group HTML and loop ends here */?>
							<div class="totals_container">
    							<div class="right">
									<div class="subtotal">
        								<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'>Subtotal:</label></div>
   										<div class="subtotal_val" id="subtot<?php echo wpsc_the_product_id(); ?>">
										<?php if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) {
											echo "R" . number_format($wpsc_cart->get_cart_item_by_key($_GET['key'])->unit_price, 2); /* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
										} else if (isset($_GET['subtot'.$id]) && $_GET['subtot'.$id] != "R0.00") {
											echo $_GET['subtot'.$id];
										} else {
											echo "R0.00";
										} ?>
                                        </div>
        							</div>
        							<div style="clear: right"></div>
<!-- THE QUANTITY OPTION MUST BE ENABLED FROM ADMIN SETTINGS -->
<?php if(wpsc_has_multi_adding()): ?>
	<?php if(wpsc_have_variation_groups()) { ?>
									<div class="quantity">
										<div id="left"><label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'><?php echo __('Quantity', 'wpsc'); ?>:</label></div>
										<div id="right">
											<input type="text" id="wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" 
                                            	name="wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" size="2" maxlength="2" 
                                                value="<?php if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) echo $wpsc_cart->get_cart_item_by_key($_GET['key'])->quantity; 
														else if (isset($_GET['wpsc_quantity_update'.wpsc_the_product_id()])) echo $_GET['wpsc_quantity_update'.wpsc_the_product_id()]; 
														else echo "0"; ?>" 
                                                onkeyup="update_qty(<?php echo wpsc_the_product_id(); ?>)" 
                                                onblur="update_qty(<?php echo wpsc_the_product_id(); ?>)"
                                                <?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?>/>
											<input type="hidden" name="key" value="<?php echo $_GET['key']; ?>"/>
											<input type="hidden" name="wpsc_update_quantity" value="true"/>
										</div>
									</div>
									<div style="clear: right"></div>
<?php } endif ;?>
									<div class="total">
        								<div id="total_text"><label>Total:</label></div>
            							<div id="total<?php echo wpsc_the_product_id(); ?>" class="total_val">
										<?php if ($wpsc_cart->get_cart_item_by_key($_GET['key'])) {
											echo "R" . number_format($wpsc_cart->get_cart_item_by_key($_GET['key'])->total_price, 2); /* "<script>updatetotal(".wpsc_the_product_id().")</script>";*/
										} else if (isset($_GET['total'.$id]) && $_GET['total'.$id] != "R0.00") {
											echo $_GET['total'.$id];
										} else {
											echo "R0.00";
										} ?>                                        
                                        </div>
        							</div>
								</div>

								<div class="left">
									<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
									<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
    								<?php if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
									<div class="wpsc_buy_button">
									<?php if(wpsc_product_has_stock()) : ?>
										<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
											<?php $action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
											<input class="wpsc_buy_button" type='button' value='<?php echo __('Buy Now', 'wpsc'); ?>' onclick='gotoexternallink("<?php echo $action; ?>")'>
										<?php elseif (!isset($_GET['key'])) : ?>
											<input type="hidden" value="add" name="prepacked" />
											<input type="submit" value="" name="Buy" class="buy_button" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button" disabled="disabled" onsubmit="testing('<?php echo wpsc_the_product_id(); ?>')" />
										<?php else : ?>
											<input type="hidden" value="update" name="prepacked" />
											<input type="hidden" name="key" value="<?php echo $_GET['key']; ?>" />
											<input type="submit" value="" class="update_button" name="submit" id="update<?php echo wpsc_the_product_id(); ?>"
                                            	<?php if (isset($_GET['key']) && !isset($_GET['edit'])) echo 'disabled="disabled"'; ?> />
										<?php endif; ?>
										<div class='wpsc_loading_animation'>
											<img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" class="loadingimage"/>
											<?php echo __('Updating cart...', 'wpsc'); ?>
										</div>
									<?php else : ?>
										<p class='soldout'><?php echo __('This product has sold out.', 'wpsc'); ?></p>
									<?php endif ; ?>
                                    </div>
<!-- END OF QUANTITY OPTION -->
								</div>
							</div>
                            </form>
						</div>
<?php endif ; ?>
<?php } ?>
<?php if((get_option('hide_addtocart_button') == 0) && (get_option('addtocart_or_buynow')=='1')) : ?>
	<?php echo wpsc_buy_now_button(wpsc_the_product_id()); ?>
<?php endif ; ?>
						<?php echo wpsc_product_rater(); ?>
						<?php
						if(function_exists('gold_shpcrt_display_gallery')) :					
							echo gold_shpcrt_display_gallery(wpsc_the_product_id(), true);
						endif;
						?>
						
                	</div>
				</div>
			</div>
<?php endwhile; ?>
<?php /** end the product loop here */?>
<?php //****************************** ?>			
		
<?php //endwhile; ?>
</div>
   <div class="productdisplayend"></div>
		
		<?php if(wpsc_product_count() < 1):?>
			<p><?php  echo __('There are no products in this group.', 'wpsc'); ?></p>
		<?php endif ; ?>

	<?php

	if(function_exists('fancy_notifications')) {
		echo fancy_notifications();
	}
	?>
		
		
		<!-- Start Pagination -->
		<?php if ( ( get_option( 'use_pagination' ) == 1 && ( get_option( 'wpsc_page_number_position' ) == 2 || get_option( 'wpsc_page_number_position' ) == 3 ) ) ) : ?>
			<div class="wpsc_page_numbers">
				<?php if ( wpsc_has_pages() ) : ?>
					Pages: <?php echo wpsc_first_products_link( '&laquo; First', true ); ?> <?php echo wpsc_previous_products_link( '&laquo; Previous', true ); ?> <?php echo wpsc_pagination( 10 ); ?> <?php echo wpsc_next_products_link( 'Next &raquo;', true ); ?> <?php echo wpsc_last_products_link( 'Last &raquo;', true ); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<!-- End Pagination -->
		
		
	<?php endif; ?>
</div>
