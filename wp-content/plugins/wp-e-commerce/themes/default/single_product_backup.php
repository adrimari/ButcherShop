<?php
/*
function get_variation_name($id) {
	global $wpdb;
	$query = $wpdb->get_results("SELECT `val`.`name` FROM `".WPSC_TABLE_VARIATION_VALUES."` AS `val` LEFT JOIN `".WPSC_TABLE_PRODUCT_VARIATIONS."` AS `var` ON `val`.`variation_id` = `var`.`id` WHERE `var`.`name` = '{$id}'",ARRAY_A) ;
	if ($query[0]['name'] == 'custom') {
		return true;
	}
	return false;
}
 */
global $wpsc_query, $wpdb, $wpsc_cart;
//print_r($wpsc_cart->get_cart_item(wpsc_the_product_id())->variation_data[0]['variation_id']);
//print_r($wpsc_cart);
$image_width = get_option('single_view_image_width');
$image_height = get_option('single_view_image_height');
echo $_GET['group'];
//$stuff = $wpsc_cart->get_cart_item($_GET['product_id']);
/*if($_GET)
	print_r($_GET);
if($_GET["variation[".wpsc_vargrp_id()."]_grams"])
	echo "<script>alert('ok')</script>";
else
	echo "<script>alert('not ok')</script>";*/
?>
<div id='products_page_container' class="wrap wpsc_container">
	
	<?php if(wpsc_has_breadcrumbs()) :?>
		<div class='breadcrumb'>
			<a href='<?php echo get_option('product_list_url'); ?>'><?php echo get_option('blogname'); ?></a> &raquo;
			<?php while (wpsc_have_breadcrumbs()) : wpsc_the_breadcrumb(); ?>
				<?php if(wpsc_breadcrumb_url()) :?> 	   
					<a href='<?php echo wpsc_breadcrumb_url(); ?>'><?php echo wpsc_breadcrumb_name(); ?></a> &raquo;
				<?php else: ?> 
					<?php echo wpsc_breadcrumb_name(); ?>
				<?php endif; ?> 
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
	
	<?php do_action('wpsc_top_of_products_page'); // Plugin hook for adding things to the top of the products page, like the live search ?>
	
	<div class="productdisplay">
	<?php /** start the product loop here, this is single products view, so there should be only one */?>
		<?php while (wpsc_have_products()) :  wpsc_the_product(); ?>
			<div class="single_product_display product_view_<?php echo wpsc_the_product_id(); ?>">
				<div class="textcol">
					<?php if(get_option('show_thumbnails')) :?>
					<div class="imagecol">
						<?php if(wpsc_the_product_thumbnail()) :?>
								<a rel="<?php echo str_replace(array(" ", '"',"'", '&quot;','&#039;'), array("_", "", "", "",''), wpsc_the_product_title()); ?>" class="thickbox preview_link" href="<?php echo wpsc_the_product_image(); ?>">
									<img class="product_image" id="product_image_<?php echo wpsc_the_product_id(); ?>" alt="<?php echo wpsc_the_product_title(); ?>" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo wpsc_the_product_image($image_width, $image_height); ?>" />
								</a>
						<?php else: ?> 
							<div class="item_no_image">
								<a href="<?php echo wpsc_the_product_permalink(); ?>">
								<span>No Image Available</span>
								</a>
							</div>
						<?php endif; ?> 
					</div>
					<?php endif; ?> 
		
					<div class="producttext">
						<h2 class="prodtitles"><span><?php echo wpsc_the_product_title(); ?></span><?php echo wpsc_edit_the_product_link(); ?></h2>
							<?php				
								do_action('wpsc_product_before_description', wpsc_the_product_id(), $wpsc_query->product);
							?>
						<div class="wpsc_description"><?php echo wpsc_the_product_description(); ?></div>
		
						<?php
							do_action('wpsc_product_addons', wpsc_the_product_id());
						?>
						<?php if(wpsc_the_product_additional_description()) : ?>
						<div class="single_additional_description">
            <?php
							$value = '';
							$the_addl_desc = wpsc_the_product_additional_description();
							if( is_serialized($the_addl_desc) ) {
								$addl_descriptions = @unserialize($the_addl_desc);
							} else {
								$addl_descriptions = array('addl_desc', $the_addl_desc);
							}
							
							if( isset($addl_descriptions['addl_desc']) ) {
								$value = $addl_descriptions['addl_desc'];
							}

            	if( function_exists('wpsc_addl_desc_show') ) {
            		echo wpsc_addl_desc_show( $addl_descriptions );
            	} else {
								echo stripslashes( wpautop($the_addl_desc, $br=1));
            	}

            ?>
						</div>
					<?php endif; ?>
				
					<?php do_action('wpsc_product_addon_after_descr', wpsc_the_product_id()); ?>

					<?php /** the custom meta HTML and loop */ ?>
					<div class="custom_meta">
						<?php while (wpsc_have_custom_meta()) : wpsc_the_custom_meta(); 	
								if (stripos(wpsc_custom_meta_name(),'g:') !== FALSE){
									continue;
								}
							?>
							<strong><?php echo wpsc_custom_meta_name(); ?>: </strong><?php echo wpsc_custom_meta_value(); ?><br />
						<?php endwhile; ?>
					</div>
					<?php /** the custom meta HTML and loop ends here */?>
					
					
					<form class="<?php  echo (in_cart($_GET['product_id'])) ? adjustform : product_form; ?>" enctype="multipart/form-data" action="<?php echo (in_cart($_GET['product_id'])) ? get_option('shopping_cart_url') : wpsc_this_page_url(); ?>" method="post" name="1" id="product_<?php echo wpsc_the_product_id(); ?>" onsubmit="">
					<?php if(wpsc_product_has_personal_text()) : ?>
						<div class='custom_text'>
							<h4><?php echo __('Personalize your product', 'wpsc'); ?></h4>
							<?php echo __('Complete this form to include a personalized message with your purchase.', 'wpsc'); ?><br />
							<input type='text' name='custom_text' value=''  />
						</div>
					<?php endif; ?>
					
					<?php if(wpsc_product_has_supplied_file()) : ?>
						<div class='custom_file'>
							<h4><?php echo __('Upload a File', 'wpsc'); ?></h4>
							<?php echo __('Select a file from your computer to include with this purchase.  ', 'wpsc'); ?><br />
							<input type='file' name='custom_file' value=''  />
						</div>
					<?php endif; ?>
					
					
<?php $query = $wpdb->get_row("SELECT * FROM `".WP_WPSC_PRODUCT_LIST."` WHERE `id` = ".wpsc_the_product_id().""); ?>
<?php
//print_r($_GET);
if ($query->custom_units == 1 || $wpsc_cart->cart_items[$_GET['key']]->custom_units == 1) {
//echo $wpsc_cart->get_cart_item(wpsc_the_product_id())->custom_units;
?>

Please make a selection:<br />
<input type="radio" id="weight<?php echo wpsc_the_product_id(); ?>" name="custom" onchange="enable_custom('<?php echo wpsc_the_product_id(); ?>')" <?php echo (isset($_GET['custom']) && $_GET['custom'] == 1) ? "checked='checked'" : ($wpsc_cart->get_cart_item(wpsc_the_product_id()) && $wpsc_cart->get_cart_item(wpsc_the_product_id())->custom_units == 1) ? "checked='checked'" : ""; ?> />
<label for="weight<?php echo wpsc_the_product_id(); ?>">By Weight</label><br />
<input type="radio" id="items<?php echo wpsc_the_product_id(); ?>" name="custom" onchange="enable_item('<?php echo wpsc_the_product_id(); ?>')" <?php echo (isset($_GET['custom']) && $_GET['custom'] == 0) ? "checked='checked'" : ($wpsc_cart->get_cart_item(wpsc_the_product_id()) && $wpsc_cart->get_cart_item(wpsc_the_product_id())->custom_units == 0) ? "checked='checked'" : ""; ?> />
<label for="items<?php echo wpsc_the_product_id(); ?>">Number of items</label>

<div id="custom<?php echo wpsc_the_product_id(); ?>" style="display:<?php echo ($_GET['custom'] == '1') ? "block" : ($wpsc_cart->get_cart_item(wpsc_the_product_id()) && $wpsc_cart->get_cart_item(wpsc_the_product_id())->custom_units == 1) ? "block" : "none"; ?>">
	<?php if ($query->custom_units == 1) { ?>
		<table>
			<?php if($query->units_grams) { ?>
			<tr>
				<td width="50px">Grams:</td>
				<td>
					<?php $price = substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
					<input size='3' maxlength='3' type='text' class='wpsc_select_variation' name='units_grams<?php echo wpsc_the_product_id(); ?>' id='units_grams<?php echo wpsc_the_product_id(); ?>' value="<?php if (isset($_GET["units_grams".wpsc_the_product_id()])) echo $_GET["units_grams".wpsc_the_product_id()]; else if (isset($_GET['key'])) echo $wpsc_cart->cart_items[$_GET['key']]->units_grams; else echo "''" ; ?>" onblur="check_vals('grams',<?php echo $price; ?>, 'units_grams<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_grams; ?>', '<?php echo $query->max_grams; ?>')" onkeyup="checknumber('grams',<?php echo $price; ?>, 'units_grams<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_grams; ?>', '<?php echo $query->max_grams; ?>')" />
				</td>
				<td>
					<div id='total_grams<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' >
						<?php if(isset($_GET["units_grams".wpsc_the_product_id().""])) {
                                echo "ZAR".(number_format($_GET["units_grams".wpsc_the_product_id().""] * $price / 1000,2));
								/*echo "<script>calculate('grams',".$price.",'units_grams".wpsc_the_product_id()."', '".wpsc_the_product_id()."', '".$query->min_grams."', '".$query->max_grams."')</script>";*/
							} else if ($wpsc_cart->cart_items[$_GET['key']]) {
								echo "ZAR".(number_format(($wpsc_cart->cart_items[$_GET['key']]->units_grams * $price / 1000),2));
							} else {
								echo "ZAR0.00";
							} ?>
					</div>
				</td>
			</tr>
			<?php }
			if($query->units_kilograms) { ?>
			<tr>
				<td width="50px">Kilograms:</td>
				<td>
					<?php $price = substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
					<input size='3' maxlength='3' type='text' class='wpsc_select_variation' name="units_kilograms<?php echo wpsc_the_product_id(); ?>" id="units_kilograms<?php echo wpsc_the_product_id(); ?>" value="<?php if (isset($_GET["units_kilograms".wpsc_the_product_id()])) echo $_GET["units_kilograms".wpsc_the_product_id()]; else if (isset($_GET['key'])) echo $wpsc_cart->cart_items[$_GET['key']]->units_kilograms; else echo "''" ; ?>" onblur="check_vals('kilograms',<?php echo $price; ?>, 'units_kilograms<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_kilograms; ?>', '<?php echo $query->max_kilograms; ?>')" onkeyup="checknumber('kilograms',<?php echo $price; ?>, 'units_kilograms<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_kilograms; ?>', '<?php echo $query->max_kilograms; ?>')" />
				</td>
				<td>
					<div id='total_kilograms<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' >
						<?php if(isset($_GET["units_kilograms".wpsc_the_product_id().""])) {
                                echo "ZAR".(number_format($_GET["units_kilograms".wpsc_the_product_id().""] * $price,2));
                                /*echo "<script>calculate('kilograms',".$price.",'units_kilograms".wpsc_the_product_id()."', '".wpsc_the_product_id()."', '".$query->min_kilograms."', '".$query->max_kilograms."')</script>"; */
							} else if ($wpsc_cart->cart_items[$_GET['key']]) {
								echo "ZAR".(number_format(($wpsc_cart->cart_items[$_GET['key']]->units_kilograms * $price),2));
                            } else {
								echo "ZAR0.00";
							} ?>
					</div>
				</td>
			</tr>
			<?php }
			if($query->units_ounces) { ?>
			<tr>
				<td width="50px">Ounces:</td>
				<td>
					<?php $price = substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
					<input size='3' maxlength='3' type='text' class='wpsc_select_variation' name="units_ounces<?php echo wpsc_the_product_id(); ?>" id="units_ounces<?php echo wpsc_the_product_id(); ?>" value="<?php if (isset($_GET["units_ounces".wpsc_the_product_id()])) echo $_GET["units_ounces".wpsc_the_product_id()]; else if (isset($_GET['key'])) echo $wpsc_cart->cart_items[$_GET['key']]->units_ounces; else echo "''" ; ?>" onblur="check_vals('ounces',<?php echo $price; ?>, 'units_ounces<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_ounces; ?>', '<?php echo $query->max_ounces; ?>')" onkeyup="checknumber('ounces',<?php echo $price; ?>, 'units_ounces<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_ounces; ?>', '<?php echo $query->max_ounces; ?>')" />
				</td>
				<td>
					<div id='total_ounces<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' >
						<?php if(isset($_GET["units_ounces".wpsc_the_product_id().""])) {
                                echo "ZAR".(number_format($_GET["units_ounces".wpsc_the_product_id().""] * $price,2));
                                /*echo "<script>calculate('ounces',".$price.",'units_ounces".wpsc_the_product_id()."', '".wpsc_the_product_id()."', '".$query->min_ounces."', '".$query->max_ounces."')</script>"; */
							} else if ($wpsc_cart->cart_items[$_GET['key']]) {
								echo "ZAR".(number_format(($wpsc_cart->cart_items[$_GET['key']]->units_ounces * $price),2));
							} else {
								echo "ZAR0.00";
							} ?>
					</div>
				</td>
			</tr>
			<?php }
			if($query->units_pounds) { ?>
			<tr>
				<td width="50px">Pounds:</td>
				<td>
					<?php $price = substr_replace(wpsc_the_product_price(get_option('wpsc_hide_decimals')), '', 0, 3); ?>
					<input size='3' maxlength='3' type='text' class='wpsc_select_variation' name="units_pounds<?php echo wpsc_the_product_id(); ?>" id="units_pounds<?php echo wpsc_the_product_id(); ?>" value="<?php if (isset($_GET["units_pounds".wpsc_the_product_id()])) echo $_GET["units_pounds".wpsc_the_product_id()]; else if (isset($_GET['key'])) echo $wpsc_cart->cart_items[$_GET['key']]->units_pounds; else echo "''" ; ?>" onblur="check_vals('pounds',<?php echo $price; ?>, 'units_pounds<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_pounds; ?>', '<?php echo $query->max_pounds; ?>')" onkeyup="checknumber('pounds',<?php echo $price; ?>, 'units_pounds<?php echo wpsc_the_product_id(); ?>', '<?php echo wpsc_the_product_id(); ?>', '<?php echo $query->min_pounds; ?>', '<?php echo $query->max_pounds; ?>')" />
				</td>
				<td>
					<div id='total_pounds<?php echo wpsc_the_product_id(); ?>' class='wpsc_select_variation' >
						<?php if(isset($_GET["units_pounds".wpsc_the_product_id().""])) {
                                echo "ZAR".(number_format($_GET["units_pounds".wpsc_the_product_id().""] * $price,2));
                                /*echo "<script>calculate('pounds',".$price.",'units_pounds".wpsc_the_product_id()."', '".wpsc_the_product_id()."', '".$query->min_pounds."', '".$query->max_pounds."')</script>"; */
							} else if ($wpsc_cart->cart_items[$_GET['key']]) {
								echo "ZAR".(number_format(($wpsc_cart->cart_items[$_GET['key']]->units_pounds * $price),2));
							} else {
								echo "ZAR0.00";
							} ?>
					</div>
				</td>
			</tr>
			<?php } ?>
            <tr>
            	<td colspan="3"><input type='text' name='total_price<?php echo wpsc_the_product_id(); ?>' id='total_price<?php echo wpsc_the_product_id(); ?>'  value='<?php echo ($wpsc_cart->get_cart_item(wpsc_the_product_id())) ? $wpsc_cart->get_cart_item(wpsc_the_product_id())->total_price : "0"; ?>' /></td>
            </tr>
			<tr>
				<td colspan='2'>Total:</td>
				<td>
					<div id='total<?php echo wpsc_the_product_id(); ?>'>
						ZAR0.00<br />
                    <?php echo "<script>updatetotal('".wpsc_the_product_id()."')</script>"; ?>
					</div>
				</td>
			</tr>
		</table>

<?php	} ?>
</div>
<div id="item<?php echo wpsc_the_product_id(); ?>" style="display:<?php echo ($_GET['custom'] == '0') ? "block" : ($wpsc_cart->get_cart_item(wpsc_the_product_id()) && $wpsc_cart->get_cart_item(wpsc_the_product_id())->custom_units == 0) ? "block" : "none"; ?>">

							<?php /** the variation group HTML and loop */?>
							<div class="wpsc_variation_forms">
								<?php $i = 0; ?>
                                <?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
									<p>
										<label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label>
										<?php /** the variation HTML and loop */?>
                                        <?php /*get_variation_name(wpsc_the_vargrp_name()); echo "<script>alert('".get_variation_name(wpsc_the_vargrp_name())."')</script>";*/ ?>
                                        
                                       	<select class='wpsc_select_variation' name="variation[<?php echo wpsc_vargrp_id(); ?>]" id="<?php echo wpsc_vargrp_form_id(); ?>">
                                                <option value="" <?php echo wpsc_the_variation_out_of_stock(); ?> ></option>
                                            <?php $j = 1;
                                                  while (wpsc_have_variations()) : wpsc_the_variation(); ?>
												<option value="<?php echo wpsc_the_variation_id(); ?>" <?php echo wpsc_the_variation_out_of_stock(); ?> <?php echo ($wpsc_cart->get_cart_item(wpsc_the_product_id())->variation_data[$i]['id'] == wpsc_the_variation_id()) ? 'selected="selected"' : ($_GET['group']+1 == wpsc_the_variation_id()) ? 'selected="selected"' : ""; ?> ><?php echo wpsc_the_variation_name(); ?></option>
											<?php $j++;
                                                  endwhile;
                                                $i++;
                                             ?>
											</select>
									</p>
								<?php endwhile; ?>
							</div>
							<?php /** the variation group HTML and loop ends here */?>

						<!-- THIS IS THE QUANTITY OPTION MUST BE ENABLED FROM ADMIN SETTINGS -->
						<?php if(wpsc_has_multi_adding()): ?>
                        	<?php if(wpsc_have_variation_groups()) { ?>
								<label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'><?php echo __('Quantity', 'wpsc'); ?>:</label>
								<input type="text" id='wpsc_quantity_update' name="wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" size="2" value="<?php echo ($wpsc_cart->get_cart_item(wpsc_the_product_id())) ? $wpsc_cart->get_cart_item(wpsc_the_product_id())->quantity : "1"; ?>" />
								<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
								<input type="hidden" name="wpsc_update_quantity" value="true"/>
						<?php } endif ;?>
</div>
<?php } else { ?>
							<?php /** the variation group HTML and loop */?>
							<div class="wpsc_variation_forms">
                                <?php $i = 0; ?>
								<?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
									<p>
										<label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label>
										<?php /** the variation HTML and loop */?>
                                        <?php /*get_variation_name(wpsc_the_vargrp_name()); echo "<script>alert('".get_variation_name(wpsc_the_vargrp_name())."')</script>";*/ ?>

                                       	<select class='wpsc_select_variation' name="variation[<?php echo wpsc_vargrp_id(); ?>]" id="<?php echo wpsc_vargrp_form_id(); ?>">
                                                <option value="" <?php echo wpsc_the_variation_out_of_stock(); ?>></option>
                                            <?php while (wpsc_have_variations()) : wpsc_the_variation(); ?>
												<option value="<?php echo wpsc_the_variation_id(); ?>" <?php echo wpsc_the_variation_out_of_stock(); ?> <?php echo ($wpsc_cart->get_cart_item(wpsc_the_product_id())->variation_data[$i]['id'] == wpsc_the_variation_id()) ?  'selected="selected"' : ""; ?> ><?php echo wpsc_the_variation_name(); ?></option>
											<?php endwhile; 
                                                $i++;
                                                ?>
											</select>
									</p>
								<?php endwhile; ?>
							</div>
							<?php /** the variation group HTML and loop ends here */?>

						<!-- THIS IS THE QUANTITY OPTION MUST BE ENABLED FROM ADMIN SETTINGS -->
						<?php if(wpsc_has_multi_adding()): ?>
                        	<?php if(wpsc_have_variation_groups()) { ?>
								<label class='wpsc_quantity_update' for='wpsc_quantity_update[<?php echo wpsc_the_product_id(); ?>]'><?php echo __('Quantity', 'wpsc'); ?>:</label>
								<input type="text" id='wpsc_quantity_update' name="wpsc_quantity_update<?php echo wpsc_the_product_id(); ?>" size="2" value="<?php echo ($wpsc_cart->get_cart_item(wpsc_the_product_id())) ? $wpsc_cart->get_cart_item(wpsc_the_product_id())->quantity : "1"; ?>" />
								<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
								<input type="hidden" name="wpsc_update_quantity" value="true"/>
						<?php } endif ;?>
<?php } ?>
					
						<div class="wpsc_product_price">
							<?php if(wpsc_product_is_donation()) : ?>
								<label for='donation_price_<?php echo wpsc_the_product_id(); ?>'><?php echo __('Donation', 'wpsc'); ?>:</label>
								<input type='text' id='donation_price_<?php echo wpsc_the_product_id(); ?>' name='donation_price' value='<?php echo $wpsc_query->product['price']; ?>' size='6' />
								<br />
							
							
							<?php else : ?>
								<?php if(wpsc_product_on_special()) : ?>
									<span class='oldprice'><?php echo __('Price', 'wpsc'); ?>: <?php echo wpsc_product_normal_price(); ?></span><br />
								<?php endif; ?>
								<span id="product_price_<?php echo wpsc_the_product_id(); ?>" class="pricedisplay"><?php echo wpsc_the_product_price(); ?></span><?php echo __('Price', 'wpsc'); ?>:  <br/>
								<!-- multi currency code -->
								<?php if(wpsc_product_has_multicurrency()) : ?>
								<?php echo wpsc_display_product_multicurrency(); ?>
								<?php endif; ?>
								<!-- end multi currency code -->
								<?php if(get_option('display_pnp') == 1) : ?>
									<span class="pricedisplay"><?php echo wpsc_product_postage_and_packaging(); ?></span><?php echo __('P&amp;P', 'wpsc'); ?>:  <br />
								<?php endif; ?>							
							<?php endif; ?>
						</div>
					<?php if(function_exists('wpsc_akst_share_link') && (get_option('wpsc_share_this') == 1)) {
						echo wpsc_akst_share_link('return');
					} 
					if (!$wpsc_cart->get_cart_item(wpsc_the_product_id())) { ?>
						<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
                        <input type="hidden" value="weight" name="weight" />
						<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
						<input type="submit" value="<?php echo __('Add To Cart', 'wpsc'); ?>" name="Buy" class="wpsc_buy_button" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button"/>
					<?php } else { ?>
						<input type="hidden" value="update_cart" name="wpsc_ajax_action"/>
                        <input type="hidden" value="items" name="items" />
						<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
						<input type="submit" value="<?php echo __('Update', 'wpsc'); ?>" name="submit" />
					<?php }
					if(wpsc_product_is_customisable()) : ?>				
						<input type="hidden" value="true" name="is_customisable"/>
					<?php endif; ?>
					
					
					<!-- END OF QUANTITY OPTION -->
					<?php if((get_option('hide_addtocart_button') == 0) && (get_option('addtocart_or_buynow') !='1')) : ?>
						<?php if(wpsc_product_has_stock()) : ?>
							<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') :
									$action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
										<input class="wpsc_buy_button" type='button' value='<?php echo __('Buy Now', 'wpsc'); ?>' onclick='gotoexternallink("<?php echo $action; ?>")'>
							<?php endif; ?>
							
							<div class='wpsc_loading_animation'>
								<img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" class="loadingimage" />
								<?php echo __('Updating cart...', 'wpsc'); ?>
							</div>
							
						<?php else : ?>
							<p class='soldout'><?php echo __('This product has sold out.', 'wpsc'); ?></p>
						<?php endif ; ?>
					<?php endif ; ?>
					</form>
					
					<?php if((get_option('hide_addtocart_button') == 0) && (get_option('addtocart_or_buynow')=='1')) : ?>
						<?php echo wpsc_buy_now_button(wpsc_the_product_id()); ?>
					<?php endif ; ?>
					
					<?php echo wpsc_product_rater(); ?>
						
						
					<?php
						if(function_exists('gold_shpcrt_display_gallery')) :					
							echo gold_shpcrt_display_gallery(wpsc_the_product_id());
						endif;

						echo wpsc_also_bought(wpsc_the_product_id());
					?>
					</div>
		<?php if (!in_cart($_GET['product_id'])) {?>
					<form onsubmit="submitform(this);return false;" action="<?php echo wpsc_this_page_url(); ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_extra_<?php echo wpsc_the_product_id(); ?>">
						<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="prodid"/>
						<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="item"/>
					</form>
        <?php } ?>
				</div>
			</div>
		</div>
		
		<?php echo wpsc_product_comments(); ?>
<?php endwhile; ?>
<?php /** end the product loop here */?>

		<?php
		if(function_exists('fancy_notifications')) {
			echo fancy_notifications();
		}
		?>
	

</div>
