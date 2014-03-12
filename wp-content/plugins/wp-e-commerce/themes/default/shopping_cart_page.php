<?php
global $wpsc_cart, $wpdb, $wpsc_checkout, $wpsc_gateway, $wpsc_coupons;
//print_r($wpsc_cart);
$grand_total = 0.00;
$wpsc_checkout = new wpsc_checkout();
$wpsc_gateway = new wpsc_gateways();
$wpsc_coupons = new wpsc_coupons($_SESSION['coupon_numbers']);
$wpsc_cart_item_count = wpsc_cart_item_count();
//echo "<pre>".print_r($wpsc_cart, true)."</pre>";
// //echo "<pre>".print_r($wpsc_checkout, true)."</pre>";

if ($_SERVER["HTTP_HOST"]=="localhost")
	unset($_SESSION['prevent_checkout']); //remove when live
	
//$total_checkouts_total = $checkouts_today[0]["Total"];
$prevent_checkout=false;
/*
if (isset($_SESSION['prevent_checkout'])) {
	if ($_SERVER["HTTP_HOST"]=="localhost")
		$prevent_checkout=$_SESSION['prevent_checkout'];
}
*/
		
if($wpsc_cart_item_count > 0) :
?>
	<?php if (!$prevent_checkout) { ?>
	<div id='wpsc_category_boundary'>
		<h4>SHOPPING CART</h4>
	</div>
	<div style="clear: both"></div>
	<div class="outer_product_container" style="padding:6px 0 0 10px;">
		<div class="cart">
	<?php if (isset($_GET['update']) && $_GET['update'] == "true") { ?>
			<p><?php echo __('Your cart has been updated. Please review your order:', 'wpsc'); ?></p>
	<?php } else { ?>
			<p><?php echo __('Please review your order:', 'wpsc'); ?></p>
	<?php } ?>
	
			<table id="cart_table" width="100%" style="background: #fff;">
				<tr class="header">
					<th class="header" width="69px">&nbsp;</th>
					<th class="header">Product</th>
					<th class="header" width="102px">Quantity</th>
					<th class="header" width="62px">Price</th>
					<th class="header" width="110px">&nbsp;</th>
				</tr>
	<?php while (wpsc_have_cart_items()) : wpsc_the_cart_item(); 
	//print_R($wpsc_cart);exit;
	
			if (wpsc_cart_item_custom_units() == 1 && wpsc_cart_custom_weight()>0)
				$custom = 1;
			else if (wpsc_cart_item_custom_units() == 0 && wpsc_cart_custom_weight()<=0)
				$custom = 0;
			else 
				$custom = -1;
	?>
				<tr>
					<td class="gallery" style="text-align: center;">
						<?php $prod = $wpdb->get_var("SELECT `image` FROM `wp_wpsc_product_images` WHERE `product_id` = ".wpsc_cart_item_product_id()); 
							  if ($prod != 0 || $prod != "") {
						?>
						<a href="<?php echo home_url( '/' ).'wp-content/uploads/wpsc/product_images/'.$prod; ?>" title="<?php echo wpsc_cart_item_name(); ?>">
							<img src='<?php echo wpsc_cart_item_image(48,48); ?>' alt='<?php echo wpsc_cart_item_name(); ?>' title='<?php echo wpsc_cart_item_name(); ?>' />
						</a>
						<?php } else { ?>
							<div class="item_no_image_small"></div>
						<?php } ?>
					</td>
					<td><span class="greylink"><a href='<?php echo wpsc_cart_item_url()."?key=".wpsc_the_cart_item_key()."&custom=".$custom;?>'><?php echo wpsc_cart_item_name(); ?></a></span><br />
						<span class="greylinksmall"><a href='<?php echo wpsc_cart_item_url()."?key=".wpsc_the_cart_item_key()."&custom=".$custom;?>'><?php echo wpsc_cart_item_variation_str() ?></a></span><br />
						<span class="greenlink"><a href='<?php echo wpsc_cart_item_url()."?key=".wpsc_the_cart_item_key()."&custom=".$custom."&edit=true";?>'>[edit]</a></span></td>
					<td>
	<?php if (wpsc_cart_item_custom_units() == 1) {
		//echo $wpsc_cart->cart_item->custom_weight; 
	?>
						<form action="<?php echo get_option('shopping_cart_url'); ?>" method="post" class="adjustform">
							<input type="text" name="quantity<?php echo wpsc_the_cart_item_key(); ?>" id="quantity<?php echo wpsc_the_cart_item_key(); ?>" size="2" maxlength="3" value="<?php echo wpsc_cart_item_quantity(); ?>" onblur="check_qty(<?php echo wpsc_the_cart_item_key(); ?>)" onkeyup="check_qty(<?php echo wpsc_the_cart_item_key(); ?>)" />
							<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
							<input type="hidden" name="custom_wpsc_update_quantity" value="true" />
							<input type="hidden" name="weight" value="weight" />
							<input type="submit" value="" name="submit" class="update" />
							<input type='hidden' name='wpsc_ajax_action' value='update_cart' />
						</form>	
	<?php } else { ?>
						<form action="<?php echo get_option('shopping_cart_url'); ?>" method="post" class="adjustform">
							<input type="text" name="quantity<?php echo wpsc_the_cart_item_key(); ?>" id="quantity<?php echo wpsc_the_cart_item_key(); ?>" size="2" maxlength="3" value="<?php echo wpsc_cart_item_quantity(); ?>" onblur="check_qty(<?php echo wpsc_the_cart_item_key(); ?>)" onkeyup="check_qty(<?php echo wpsc_the_cart_item_key(); ?>)" />
							<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
							<input type="hidden" name="wpsc_update_quantity<?php echo wpsc_the_cart_item_key(); ?>" id="wpsc_update_quantity<?php echo wpsc_the_cart_item_key(); ?>" value="false" />
							<input type="submit" value="" name="submit" class="update" onclick="enable_update('<?php echo wpsc_the_cart_item_key(); ?>')" />
							<input type="hidden" name="items" value="items" />
							<input type='hidden' name='wpsc_ajax_action' value='update_cart' />
							<input type='hidden' name='prod_id' value='<?php echo wpsc_the_product_id(); ?>' />
						</form>				
	<?php } ?>
					</td>
	<?php //if(wpsc_uses_shipping()) { ?>
	<!--				<td><span class="pricedisplay" id='shipping_<?php //echo wpsc_the_cart_item_key(); ?>'><?php //echo wpsc_cart_item_shipping(); ?></span></td> -->
	<?php //} ?>
					<td><span class="pricedisplay3">
						<?php $total = str_replace(",", "", wpsc_cart_item_price()); echo substr($total,2); ?></span>
						<?php $grand_total += (float)substr($total,3); ?>
					</td>
					<td style="text-align:center;">
						<form action="<?php echo get_option('shopping_cart_url'); ?>" method="post" class="adjustform">
							<input type="hidden" name="quantity" value="0" />
							<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
							<input type="hidden" name="wpsc_update_quantity" value="true" />
							<button class='remove_button' type="submit"></button>
						</form>
					</td>
				</tr>
	<?php endwhile; 
	//this HTML displays coupons if there are any active coupons to use 
	if(wpsc_uses_coupons()) { 
		if(wpsc_coupons_error()) { ?>
				<tr><td colspan="5"><?php echo __('Coupon is not valid.', 'wpsc'); ?></td></tr>
	<?php 
		} ?>
				<tr>
					<td colspan="2"><?php _e('Enter your coupon number'); ?> :</td>
					<td  colspan="3" align='left'>
						<form  method='post' action="<?php echo get_option('shopping_cart_url'); ?>">
							<input type='text' name='coupon_num' id='coupon_num' value='<?php echo $wpsc_cart->coupons_name; ?>' />
							<input type='submit' value='<?php echo __('Update', 'wpsc') ?>' />
						</form>
					</td>
				</tr>
	<?php 
	} ?>	
			
	<?php  //this HTML dispalys the calculate your order HTML
	if(isset($_SESSION['nocamsg']) && isset($_GET['noca']) && $_GET['noca'] == 'confirm') { ?>
			<p class='validation-error'><?php echo $_SESSION['nocamsg']; ?></p>
	<?php }
	if($_SESSION['categoryAndShippingCountryConflict'] != '') { ?>
			<p class='validation-error'><?php echo $_SESSION['categoryAndShippingCountryConflict']; ?></p>
	<?php
			$_SESSION['categoryAndShippingCountryConflict'] = '';
	}
	if($_SESSION['WpscGatewayErrorMessage'] != '') { ?>
			<p class='validation-error'><?php echo $_SESSION['WpscGatewayErrorMessage']; ?></p>
	<?php 
	}
	do_action('wpsc_before_shipping_of_shopping_cart'); ?>

	<?php if(wpsc_uses_shipping()  && wpsc_has_shipping_form()) { ?>
	<?php 
		if (!wpsc_have_shipping_quote()) { // No valid shipping quotes 
			if (($_SESSION['wpsc_zipcode'] == '') || ($_SESSION['wpsc_zipcode'] == 'Your Zipcode')) { // No valid shipping quotes 
				if ($_SESSION['wpsc_update_location'] == true) { ?>
				<tr>
					<td colspan='5' class='shipping_error' >
						<?php echo __('Please provide a Zipcode and click Calculate in order to continue.', 'wpsc'); ?>
					</td>
				</tr>
	<?php		} else { ?>
				<tr>
					<td colspan='5' class='shipping_error' >
						<?php echo __('Sorry, online ordering is unavailable to this destination and/or weight. Please double check your destination details.', 'wpsc'); ?>
					</td>
				</tr>
	<?php		}
			} ?>
				<tr>
					<td colspan='5'>
						<form name='change_country' id='change_country' action='' method='post'>
							<?php echo wpsc_shipping_country_list();?>
							<input type='hidden' name='wpsc_update_location' value='true' />
							<input type='submit' name='wpsc_submit_zipcode' value='Calculate' />
						</form>
					</td>
				</tr>
				
	<?php 
			if (wpsc_have_morethanone_shipping_quote()) {
				while (wpsc_have_shipping_methods()) : wpsc_the_shipping_method();
					if (!wpsc_have_shipping_quotes()) { continue; } // Don't display shipping method if it doesn't have at least one quote ?>
				<tr><td class='shipping_header' colspan='5'><?php echo wpsc_shipping_method_name().__('- Choose a Shipping Rate', 'wpsc'); ?> </td></tr>
	<?php 				while (wpsc_have_shipping_quotes()) : wpsc_the_shipping_quote();	?>
				<tr>
					<td colspan='3'>
						<label for='<?php echo wpsc_shipping_quote_html_id(); ?>'><?php echo wpsc_shipping_quote_name(); ?></label>
					</td>
					<td style='text-align:center;'>
						<label for='<?php echo wpsc_shipping_quote_html_id(); ?>'><?php echo wpsc_shipping_quote_value(); ?></label>
					</td>
					<td style='text-align:center;'>
	<?php 
						if(wpsc_have_morethanone_shipping_methods_and_quotes()) { ?>
						<input type='radio' id='<?php echo wpsc_shipping_quote_html_id(); ?>' <?php echo wpsc_shipping_quote_selected_state(); ?>  onclick='switchmethod("<?php echo wpsc_shipping_quote_name(); ?>", "<?php echo wpsc_shipping_method_internal_name(); ?>")' value='<?php echo wpsc_shipping_quote_value(true); ?>' name='shipping_method' />
	<?php				} else { ?>
						<input <?php echo wpsc_shipping_quote_selected_state(); ?> disabled='disabled' type='radio' id='<?php echo wpsc_shipping_quote_html_id(); ?>'  value='<?php echo wpsc_shipping_quote_value(true); ?>' name='shipping_method' />
	<?php 				wpsc_update_shipping_single_method();
						}?>
					</td>
				</tr>
	<?php 				endwhile;
				endwhile;
			} ?>
	<?php wpsc_update_shipping_multiple_methods(); 
			if (!wpsc_have_shipping_quote()) {  // No valid shipping quotes ?>
			</table>

	<?php 		return;
			}?>

	<?php } ?>
	
	<?php if (wpsc_have_morethanone_shipping_quote()) :?>
				<?php while (wpsc_have_shipping_methods()) : wpsc_the_shipping_method(); ?>
						<?php 	if (!wpsc_have_shipping_quotes()) { continue; } // Don't display shipping method if it doesn't have at least one quote ?>
						<?php while (wpsc_have_shipping_quotes()) : wpsc_the_shipping_quote();	?>
							<tr>
								<td colspan='3'>
									<label for='<?php echo wpsc_shipping_quote_html_id(); ?>'><?php echo wpsc_shipping_quote_name(); ?></label>
								</td>
								<td style='text-align:center;'>
									<label for='<?php echo wpsc_shipping_quote_html_id(); ?>'><?php echo wpsc_shipping_quote_value(); ?></label>
								</td>
								<td style='text-align:center;'>
									<?php if(wpsc_have_morethanone_shipping_methods_and_quotes()): ?>
										<input type='radio' id='<?php echo wpsc_shipping_quote_html_id(); ?>' <?php echo wpsc_shipping_quote_selected_state(); ?>  onclick='switchmethod("<?php echo wpsc_shipping_quote_name(); ?>", "<?php echo wpsc_shipping_method_internal_name(); ?>")' value='<?php echo wpsc_shipping_quote_value(true); ?>' name='shipping_method' />
									<?php else: ?>
										<input <?php echo wpsc_shipping_quote_selected_state(); ?> disabled='disabled' type='radio' id='<?php echo wpsc_shipping_quote_html_id(); ?>'  value='<?php echo wpsc_shipping_quote_value(true); ?>' name='shipping_method' />
											<?php wpsc_update_shipping_single_method(); ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endwhile; ?>
				<?php endwhile; ?>
			<?php endif; ?>
			
			<?php wpsc_update_shipping_multiple_methods(); ?>
			
	<?php if(wpsc_uses_shipping())  ?>
			<tr class="total_price total_shipping">
				<td colspan="3" style="text-align:right;">
					<?php echo __('Total Shipping', 'wpsc'); ?>
				</td>
				<td>
					<?php if (substr(wpsc_cart_shipping(),3) >= 500)
							$shipping = substr(wpsc_cart_shipping(),2);
						else
							$shipping = "R0.00";
					?>
					<span id="checkout_shipping" class="pricedisplay3 checkout-shipping"><?php echo substr(wpsc_cart_shipping(),2); ?></span>
				</td>
				<td></td>
			</tr>
	<?php } ?>
	

	<?php if(wpsc_uses_coupons() && (wpsc_coupon_amount(false) > 0)) { ?>
			<tr class="total_price">
				<td colspan="3" style="text-align:right;">
					<?php echo __('Discount', 'wpsc'); ?>
				</td>
				<td>
					<span id="coupons_amount" class="pricedisplay3"><?php echo wpsc_coupon_amount(); ?></span>
				</td>
				<td></td>
			</tr>
		</table>
	<?php } ?>
			<tr class='total_price'>
				<td colspan="3" style="text-align:right;">
					<?php echo 'Total Price'; ?>
				</td>
				<td>
					<?php if ((float)str_replace(",", "", substr(wpsc_cart_total(),3)) >= 500) {
							if  ((float)$grand_total != (float)substr(wpsc_cart_total(),3)) {
								$wpsc_cart->subtotal = number_format((float)$grand_total,2,".","");
								$total = "R".number_format((float)$wpsc_cart->subtotal+substr(wpsc_cart_shipping(),3),2,".","");
							} else {
								$total = "R".number_format((float)$grand_total,2,".","");
								//$wpsc_cart->subtotal = number_format((float)$grand_total,2,".","");
							}
						} else {
							$total = "R".number_format(str_replace(",", "", substr(wpsc_cart_total(),3)),2,".","");
								//$wpsc_cart->subtotal = number_format((float)$grand_total,2,".","");
						}
					?>
					<span id='checkout_total' class="pricedisplay3 checkout-total"><?php echo $total; //str_replace(",", "", substr(wpsc_cart_total(),2)); ?></span>
				</td>
				<td style="text-align:center;">
	<div class="empty_cart">
		<form action='' method='post' class='wpsc_empty_the_cart'>
			<input type='hidden' name='wpsc_ajax_action' value='empty_cart' />
			
				<a href='<?php echo htmlentities(add_query_arg('wpsc_ajax_action', 'empty_cart', remove_query_arg('ajax')), ENT_QUOTES); ?>'><?php echo __('Empty your cart', 'wpsc'); ?></a>
																										
		</form>
	</div>
				</td>
			</tr>
		</table>
    </div>
</div>
<div class="productdisplayend">
</div>
<?php } else { echo "<br/><p>You are unable to proceed with a transaction.<p>"; } ?>
<?php do_action('wpsc_before_form_of_shopping_cart'); ?>
<form name='wpsc_checkout_forms' class='wpsc_checkout_forms' action='butchery/checkout' method='post' enctype="multipart/form-data">
<?php 
/**  
* Both the registration forms and the checkout details forms must be in the same form element as they are submitted together, you cannot have two form elements submit together without the use of JavaScript.
*/
?>
 <?php if(!is_user_logged_in() && get_option('users_can_register') && get_option('require_register')) :
			 global $current_user;
    		 get_currentuserinfo();	  ?>
		<h2><?php _e('Not yet a member?');?></h2>
		<p><?php _e('In order to buy from us, you\'ll need an account. Joining is free and easy. All you need is a username, password and valid email address.');?></p>
		<?php	if(count($_SESSION['wpsc_checkout_user_error_messages']) > 0) : ?>
			<div class="login_error"> 
				<?php		  
				foreach($_SESSION['wpsc_checkout_user_error_messages'] as $user_error ) {
				  echo $user_error."<br />\n";
				}
				$_SESSION['wpsc_checkout_user_error_messages'] = array();
				?>			
			</div>
		<?php endif; ?>
		
	  <fieldset class='wpsc_registration_form'>
			<label><?php _e('Username'); ?>:</label><input type="text" name="log" id="log" value="" size="20"/>
			<label><?php _e('Password'); ?>:</label><input type="password" name="pwd" id="pwd" value="" size="20" />
			<label><?php _e('E-mail'); ?>:</label><input type="text" name="user_email" id="user_email" value="<?php echo attribute_escape(stripslashes($user_email)); ?>" size="20" />
		</fieldset>
	<?php endif; ?>
	
	<?php if (!$prevent_checkout) : ?>

	<h2><?php echo __('Please enter your contact details:', 'wpsc'); ?></h2>
	<?php /* echo __('Note, Once you press submit, you will need to have your Credit card handy.', 'wpsc'); <br /> */ ?>
	<?php echo __('Fields marked with an asterisk must be filled in.', 'wpsc'); ?>
	<?php
	  if(count($_SESSION['wpsc_checkout_misc_error_messages']) > 0) {
			echo "<div class='login_error'>\n\r";
			foreach((array)$_SESSION['wpsc_checkout_misc_error_messages'] as $user_error ) {
				echo $user_error."<br />\n";
			}
			echo "</div>\n\r";
		}
		$_SESSION['wpsc_checkout_misc_error_messages'] =array();
	?>
	<table class='wpsc_checkout_table'>
		<?php while (wpsc_have_checkout_items()) : wpsc_the_checkout_item(); ?>
			<?php if(wpsc_is_shipping_details()) : ?>
					<tr>
						<td colspan ='2'>
							<br />
							<input type='checkbox' value='true' name='shippingSameBilling' id='shippingSameBilling' />
							<label class="dark" for='shippingSameBilling'>Shipping Address same as Billing Address?</label>
						
						</td>
					</tr>
			<?php endif; ?>

		  <?php if(wpsc_checkout_form_is_header() == true) : ?>
		  		<tr <?php echo wpsc_the_checkout_item_error_class();?>>
			<td <?php if(wpsc_is_shipping_details()) echo "class='wpsc_shipping_forms'"; ?> colspan='2'>
				<h3>
					<?php echo wpsc_checkout_form_name();?>
				</h3>
			</td>
				</tr>
		  <?php else: ?>
		  <?php if((!wpsc_uses_shipping()) && $wpsc_checkout->checkout_item->unique_name == 'shippingstate'): ?>
		  <?php else : ?>
		  		<tr <?php echo wpsc_the_checkout_item_error_class();?>>
			<td>
				<label id="dark" for='<?php echo wpsc_checkout_form_element_id(); ?>'>
				<?php echo wpsc_checkout_form_name();?>
				</label>
			</td>
			<td>
				<?php echo wpsc_checkout_form_field();?>
				
		    <?php if(wpsc_the_checkout_item_error() != ''): ?>
		    <p class='validation-error'><?php echo wpsc_the_checkout_item_error(); ?></p>
		    
			<?php endif; ?>
			</td>
			</tr>
			<?php endif; ?>
		
			<?php endif; ?>
		
		<?php endwhile; ?>
		
		<?php if (get_option('display_find_us') == '1') : ?>
		<tr>
			<td>How did you find us:</td>
			<td>
				<select name='how_find_us'>
					<option value='Word of Mouth'>Word of mouth</option>
					<option value='Advertisement'>Advertising</option>
					<option value='Internet'>Internet</option>
					<option value='Customer'>Existing Customer</option>
				</select>
			</td>
		</tr>
		<?php endif; ?>	
		<?php do_action('wpsc_inside_shopping_cart'); ?>	
		<tr>
			<td colspan='2' class='wpsc_gateway_container'>
			
			<?php  //this HTML displays activated payment gateways?>
			  
				<?php if(wpsc_gateway_count() > 1): // if we have more than one gateway enabled, offer the user a choice ?>
					<h3><?php echo __('Select a payment gateway', 'wpsc');?></h3>
					<?php while (wpsc_have_gateways()) : wpsc_the_gateway(); ?>
						<div class="custom_gateway">
							<?php if(wpsc_gateway_internal_name() == 'noca'){ ?>
								<label class="dark"><input type="radio" id='noca_gateway' value="<?php echo wpsc_gateway_internal_name();?>" <?php echo wpsc_gateway_is_checked(); ?> name="custom_gateway" class="custom_gateway"/><?php echo wpsc_gateway_name();?></label>
							<?php }else{ ?>
								<label class="dark"><input type="radio" value="<?php echo wpsc_gateway_internal_name();?>" <?php echo wpsc_gateway_is_checked(); ?> name="custom_gateway" class="custom_gateway"/><?php echo wpsc_gateway_name();?></label>
							<?php } ?>

							
							<?php if(wpsc_gateway_form_fields()): ?> 
								<table class='<?php echo wpsc_gateway_form_field_style();?>'>
									<?php echo wpsc_gateway_form_fields();?> 
								</table>		
							<?php endif; ?>			
						</div>
					<?php endwhile; ?>
				<?php else: // otherwise, there is no choice, stick in a hidden form ?>
					<?php while (wpsc_have_gateways()) : wpsc_the_gateway(); ?>
						<input name='custom_gateway' value='<?php echo wpsc_gateway_internal_name();?>' type='hidden' />
						
							<?php if(wpsc_gateway_form_fields()): ?> 
								<table>
									<?php echo wpsc_gateway_form_fields();?> 
								</table>		
							<?php endif; ?>	
					<?php endwhile; ?>				
				<?php endif; ?>				
				
			</td>
		</tr>
		<?php if(get_option('terms_and_conditions') != '') : ?>
		<tr>
			<td colspan='2'>
     			 <span style="float:left"><input type='checkbox' value='yes' name='agree' /></span><span style="float:left">&nbsp;I agree to&nbsp;</span><span style="float:left;"><a href="<?php echo home_url('/'); ?>" style="font-size:12px">the terms and conditions</a></span>
   		   </td>
 	   </tr>
		<?php endif; ?>	
		
		<tr>
			<td colspan='2'>
				<?php if(get_option('terms_and_conditions') == '') : ?>
					<input type='hidden' value='yes' name='agree' />
				<?php endif; ?>	
				<?php //exit('<pre>'.print_r($wpsc_gateway->wpsc_gateways[0]['name'], true).'</pre>');
				 if(count($wpsc_gateway->wpsc_gateways) == 1 && $wpsc_gateway->wpsc_gateways[0]['name'] == 'Noca'){}else{?>
					<input type='hidden' value='submit_checkout' name='wpsc_action' />
					<input type='submit' value='<?php //echo __('Make Purchase', 'wpsc');?>' name='submit' class='purchase' />
				<?php }/* else: ?>
				
				<br /><strong><?php echo __('Please login or signup above to make your purchase', 'wpsc');?></strong><br />
				<?php echo __('If you have just registered, please check your email and login before you make your purchase', 'wpsc');?>
				</td>
				<?php endif;  */?>				
			</td>
		</tr>
	</table>
	<?php endif; ?>
</form>
<?php
else:
?>
<div class="shopcart_empty">
	<div class="title"><h4>SHOPPING CART</h4></div>
	<div class="no_items">No products</div>
	<div class="redtext">Sorry, your shopping cart is currently empty.</div>
	<div class="blacktext"><div id="greenlinkbig"><a href="<?php echo home_url( '/' )."?page_id=10"; ?>">Click here</a>&nbsp;to view our selection of meats...</div></div>
</div>
<?php 
endif;
do_action('wpsc_bottom_of_shopping_cart');
?>