<?php

$nzshpcrt_gateways[$num]['name'] = 'Iveri (Enterprise)';
$nzshpcrt_gateways[$num]['internalname'] = 'iveri_enterprise';
$nzshpcrt_gateways[$num]['function'] = 'gateway_iveri_enterprise'; //Processing transaction
$nzshpcrt_gateways[$num]['form'] = "form_iveri_enterprise"; //Back-office form fields
$nzshpcrt_gateways[$num]['submit_function'] = "submit_iveri_enterprise"; //Save back-office form fields
$nzshpcrt_gateways[$num]['payment_type'] = "credit_card";

$nzshpcrt_gateways[$num]['supported_currencies']['currency_list'] = array('USD', 'CAD', 'AUD', 'EUR', 'GBP', 'JPY', 'NZD', 'CHF', 'HKD', 'SGD', 'SEK', 'HUF', 'DKK', 'PLN', 'NOK', 'CZK', 'MXN', 'ZAR');
$nzshpcrt_gateways[$num]['supported_currencies']['option_name'] = 'iveri_curcode';

if (session_id() == "")
	session_start();

if(in_array('iveri_enterprise',(array)get_option('custom_gateway_options'))) {
	$curryear = date('Y');
	
	//Merge the post data
	/*
	if (!isset($_SESSION["POSTdata"]))
		$_SESSION["POSTdata"]=$_POST;
	else {
		$_POST=array_merge($_POST,$_SESSION["POSTdata"]);
		unset($_SESSION["POSTdata"]);
	}
	*/
	
	//generate year options
	for($i=0; $i < 10; $i++){
		$years .= "<option value='".$curryear."'>".$curryear."</option>\r\n";
		$curryear++;
	}
	
	if (isset($_POST)) {
		if ($_POST["ccholdertype"] == 'c'){ //Is a company
			$ccholdertype2="checked='checked'";
		} else {
			$ccholdertype1 ="checked='checked'";
		}
	}
 
 	//General variable used by the eCommerce package for the form (fields are appended to the current form)
	$gateway_checkout_form_fields[$nzshpcrt_gateways[$num]['internalname']] = "
	<tr class='wpsc_checkout_field19'>
		<td colspan='2'>
			<h3>3. Banking details:</h3>
		</td>
	</tr>
	<tr id='wpsc_pppro_cc_type' class='card_type' %s>
		<td class='wpsc_pppro_cc_holdertype1' colspan='2'>
		<input id='ccholdertype1' type='radio' class='wpsc_ccBox' name='ccholdertype' value='i' {$ccholdertype1}>&nbsp;<label for='ccholdertype1'>Individual</label>
		<input id='ccholdertype2' type='radio' class='wpsc_ccBox' name='ccholdertype' value='c' {$ccholdertype2}>&nbsp;<label for='ccholdertype2'>Company</label>
		<p class='validation-error'>%s</p>
		</td>
	</tr>
	<tr id='wpsc_pppro_cc_type' class='card_type' %s>
		<td class='wpsc_pppro_cc_type1'>Card Type: *</td>
		<td class='wpsc_pppro_cc_type2'>
		<select class='wpsc_ccBox' name='cctype'>
			<option value='Visa'>Visa</option>
			<option value='Mastercard'>MasterCard</option>
			<option value='Diners'>Diners</option>
			<option value='Amex'>American Express</option>
		</select>
		<p class='validation-error'>%s</p>
		</td>
	</tr>
	<tr id='wpsc_pppro_cc_number' %s>
		<td class='wpsc_pppro_cc_number1'>Card Number: *</td>
		<td class='wpsc_pppro_cc_number2'>
			<input type='text' value='' name='card_number' style='width:140px;' maxlength='16' />
			<p class='validation-error'>%s</p>
		</td>
	</tr>
	<tr id='wpsc_pppro_cc_expiry' %s>
		<td class='wpsc_pppro_cc_expiry1'>Expiry: *</td>
		<td class='wpsc_pppro_cc_expiry2'>
			<select class='wpsc_ccBox' name='expiry[month]'>
			<option value='01'>01</option>
			<option value='02'>02</option>
			<option value='03'>03</option>
			<option value='04'>04</option>
			<option value='05'>05</option>						
			<option value='06'>06</option>						
			<option value='07'>07</option>					
			<option value='08'>08</option>						
			<option value='09'>09</option>						
			<option value='10'>10</option>						
			<option value='11'>11</option>																			
			<option value='12'>12</option>																			
			</select>
			<select class='wpsc_ccBox' name='expiry[year]'>
			".$years."
			</select>
			<p class='validation-error'>%s</p>
		</td>
	</tr>
	<tr id='wpsc_pppro_cc_code' class='card_cvv' %s>
		<td class='wpsc_pppro_cc_code1'>CVV: *</td>
		<td class='wpsc_pppro_cc_code2'><input type='text' size='4' value='' maxlength='4' name='card_code' />
		<p class='validation-error'>%s</p>
		</td>
	</tr>

	";
	
	
	/*
	if (isset($_SESSION['prevent_checkout'])) 
		if ($_SESSION['prevent_checkout'])
			$gateway_checkout_form_fields=null;
	*/

}


//Once form is submitted the data gets passed to this function
function gateway_iveri_enterprise($seperator, $sessionid)
  { 
  	//print_r($_POST);
	//exit($_SESSION['iveriAmount']);
	
	$_SESSION['iveriMessage']= '<h4>Transaction Canceled</h4>';

	// ==================================
	// iveri Checkout Module
	// ==================================
	
	//'------------------------------------
	//' The paymentAmount is the total value of 
	//' the shopping cart, that was set 
	//' earlier in a session variable 
	//' by the shopping cart page
	//'------------------------------------
	
	//exit('<pre>'.print_r($_SESSION, true).'</pre>');
	
	global $wpdb, $wpsc_cart;
	
	$purchase_log = $wpdb->get_row("SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `sessionid`= ".$sessionid." LIMIT 1",ARRAY_A) ;

	
	$usersql = "SELECT `".WPSC_TABLE_SUBMITED_FORM_DATA."`.value, `".WPSC_TABLE_CHECKOUT_FORMS."`.`name`, `".WPSC_TABLE_CHECKOUT_FORMS."`.`unique_name` FROM `".WPSC_TABLE_CHECKOUT_FORMS."` LEFT JOIN `".WPSC_TABLE_SUBMITED_FORM_DATA."` ON `".WPSC_TABLE_CHECKOUT_FORMS."`.id = `".WPSC_TABLE_SUBMITED_FORM_DATA."`.`form_id` WHERE  `".WPSC_TABLE_SUBMITED_FORM_DATA."`.`log_id`=".$purchase_log['id']." ORDER BY `".WPSC_TABLE_CHECKOUT_FORMS."`.`order`";
		//exit($usersql);
		$userinfo = $wpdb->get_results($usersql, ARRAY_A);
		//exit('<pre>'.print_r($userinfo, true).'</pre>');
		
	$transact_url = get_option('transact_url');
		
	//BUILD DATA TO SEND TO Iveri 

	$data = array();
	
	$data['VERSION']				= "52.0";
	$data['METHOD']					= "DoDirectPayment";
	$data['PAYMENTACTION']			= "Sale";
	$data['IPADDRESS']				= $_SERVER["REMOTE_ADDR"];
	$data['RETURNFMFDETAILS']		= "1"; // optional - return fraud management filter data
    
    $sql = 'SELECT `code` FROM `'.WPSC_TABLE_CURRENCY_LIST.'` WHERE `id`='.get_option('currency_type');
    $data['CURRENCYCODE'] = $wpdb->get_var($sql);
	
	foreach((array)$userinfo as $key => $value){
		if(($value['unique_name']=='billingfirstname') && $value['value'] != ''){
			$data['FIRSTNAME']	= $value['value'];
		}
		if(($value['unique_name']=='billinglastname') && $value['value'] != ''){
			$data['LASTNAME']	= $value['value'];
		}
		if(($value['unique_name']=='billingemail') && $value['value'] != ''){
			$data['EMAIL']	= $value['value'];
		}
		if(($value['unique_name']=='billingphone') && $value['value'] != ''){
			$data['PHONENUM']	= $value['value'];
		}
		if(($value['unique_name']=='billingaddress') && $value['value'] != ''){
			$data['STREET']	= $value['value'];
		}
		if(($value['unique_name']=='billingcity') && $value['value'] != ''){
			$data['CITY']	= $value['value'];
		}
		if(($value['unique_name']=='billingstate') && $value['value'] != ''){
			$sql = "SELECT `code` FROM `".WPSC_TABLE_REGION_TAX."` WHERE `id` ='".$value['value']."' LIMIT 1";
			$data['STATE'] = $wpdb->get_var($sql);
		}else{
			
		//	$data['STATE']='CA';
		}
		if(($value['unique_name']=='billingcountry') && $value['value'] != ''){
			$value['value'] = maybe_unserialize($value['value']);
			if($value['value'][0] == 'UK'){
				$data['COUNTRYCODE'] = 'GB';
			}else{
				$data['COUNTRYCODE']	= $value['value'][0];
			}
			if(is_numeric($value['value'][1])){
				$sql = "SELECT `code` FROM `".WPSC_TABLE_REGION_TAX."` WHERE `id` ='".$value['value'][1]."' LIMIT 1";
				$data['STATE'] = $wpdb->get_var($sql);
			}
		}		
		if(($value['unique_name']=='billingpostcode') && $value['value'] != ''){
			$data['ZIP']	= $value['value'];
		}

		//
		
		if((($value['unique_name']=='shippingfirstname') && $value['value'] != '')){
			$data1['SHIPTONAME1']	= $value['value'];
		}
		if((($value['unique_name']=='shippinglastname') && $value['value'] != '')){
			$data1['SHIPTONAME2']	= $value['value'];
		}
		if(($value['unique_name']=='shippingaddress') && $value['value'] != ''){
			$data['SHIPTOSTREET']	= $value['value'];
		}	
		if(($value['unique_name']=='shippingcity') && $value['value'] != ''){
			$data['SHIPTOCITY']	= $value['value'];
		}	
			//$data['SHIPTOCITY'] = 'CA';
		if(($value['unique_name']=='shippingstate') && $value['value'] != ''){
		//	$data['SHIPTOSTATE'] = $value['value'];
			$sql = "SELECT `code` FROM `".WPSC_TABLE_REGION_TAX."` WHERE `id` ='".$value['value']."' LIMIT 1";
			$data['SHIPTOSTATE'] = $wpdb->get_var($sql);
		}else{
		}	
		if(($value['unique_name']=='shippingcountry') && $value['value'] != ''){
			$value['value'] = maybe_unserialize($value['value']);
			if(is_array($value['value'])){
			if($value['value'][0] == 'UK'){
				$data['SHIPTOCOUNTRY'] = 'GB';
			}else{
				$data['SHIPTOCOUNTRY']	= $value['value'][0];
			}
			if(is_numeric($value['value'][1])){
				$sql = "SELECT `code` FROM `".WPSC_TABLE_REGION_TAX."` WHERE `id` ='".$value['value'][1]."' LIMIT 1";
				$data['SHIPTOSTATE'] = $wpdb->get_var($sql);
			}
			}else{
				$data['SHIPTOCOUNTRY']	= $value['value'];
			}
			
		}	
		if(($value['unique_name']=='shippingpostcode') && $value['value'] != ''){
			$data['SHIPTOZIP']	= $value['value'];
		}	
		//exit($key.' > '.print_r($value,true));
	}
	$data['SHIPTONAME'] = $data1['SHIPTONAME1'].' '.$data1['SHIPTONAME2'];
	//	exit('<pre>'.print_r($data, true).'</pre>');

	if( ($data['SHIPTONAME'] == null) || ($data['SHIPTOSTREET'] == null) || ($data['SHIPTOCITY'] == null) ||
			($data['SHIPTOSTATE'] == null) || ($data['SHIPTOCOUNTRY'] == null) || ($data['SHIPTOZIP'] == null)) {
			// if any shipping details are empty, the order will simply fail, this deletes them all if one is empty
			unset($data['SHIPTONAME']);
			unset($data['SHIPTOSTREET']);
			unset($data['SHIPTOCITY']);
			unset($data['SHIPTOSTATE']);
			unset($data['SHIPTOCOUNTRY']);
			unset($data['SHIPTOZIP']);
	} 


	$data['CREDITCARDHOLDERTYPE'] = $_POST['ccholdertype'];
	$data['CREDITCARDTYPE'] = $_POST['cctype'];
	$data['ACCT']			= $_POST['card_number'];
	$data['EXPDATE']		= $_POST['expiry']['month']."/".$_POST['expiry']['year'];
	$data['CVV2']			= $_POST['card_code'];
	
	$data['AMT']			= number_format($wpsc_cart->total_price,2,".","");
	$data['ITEMAMT']		= number_format($wpsc_cart->subtotal,2,".","");
	$data['SHIPPINGAMT']	= number_format($wpsc_cart->base_shipping,2,".","");
	$data['TAXAMT']			= number_format($wpsc_cart->total_tax, 2,".","");
	
	// Ordered Items
	$discount = $wpsc_cart->coupons_amount;
	//exit($discount);
	
	if(($discount > 0)) {
		$i = 1;
		$data['AMT']			= number_format(sprintf("%01.2f", $wpsc_cart->calculate_total_price()),2,'.','');

		$data['ITEMAMT']		= number_format(sprintf("%01.2f", $wpsc_cart->calculate_total_price()),2,'.','');

		$data['SHIPPINGAMT']	= 0;
		$data['TAXAMT']			= 0;
		$data['L_NAME'.$i] = "Your Shopping Cart";
		$data['L_AMT'.$i] = number_format(sprintf("%01.2f", $wpsc_cart->calculate_total_price()),2,'.','');
		$data['L_QTY'.$i] = 1;
		// $data['item_number_'.$i] = 0;
		$data['L_TAXAMT'.$i] = 0;
	} else {

		//Loop through the items
		foreach($wpsc_cart->cart_items as $i => $Item) {
			$data['L_NAME'.$i]			= $Item->product_name;
			$data['L_AMT'.$i]			= number_format($Item->unit_price,2);
			$data['L_NUMBER'.$i]		= $i;
			$data['L_QTY'.$i]			= $Item->quantity;
			//$data['L_TAXAMT'.$i]		= number_format($Item->tax,2);
		}
	}
	
	$transaction = "";
	//Coverting the POST to a GET
	foreach($data as $key => $value) {
		if (is_array($value)) {
			foreach($value as $item) {
				if (strlen($transaction) > 0) $transaction .= "&";
				$transaction .= "$key=".urlencode($item);
			}
		} else {
			if (strlen($transaction) > 0) $transaction .= "&";
			$transaction .= "$key=".urlencode($value);
		}
	}
	//exit($transaction);



	//************************
	//Process the transaction
	//************************
	
	$response = process_transaction($data,$sessionid); //$purchase_log['id']); //$sessionid
	
	//***********************

	//exit('<pre>'.print_r($response, true).'</pre><pre>'.print_r($data, true).'</pre>');
	if($response["Successful"] == true){
		//redirect to  transaction page and store in DB as a order with accepted payment
		$sql = "UPDATE `".WPSC_TABLE_PURCHASE_LOGS."` SET `processed`= '2' WHERE `sessionid`=".$sessionid;
		$wpdb->query($sql);

		unset($_SESSION['WpscGatewayErrorMessage']);
		$_SESSION['iveri_processed'] = 'success';
		
		header("Location: ".$transact_url.$seperator."sessionid=".$sessionid);
		exit(); // on some servers, a header that is not followed up with an exit does nothing.
		
	}else{
	
		unset($_SESSION["POST_backup"]);
		
		//redirect back to checkout page with errors
		$sql = "UPDATE `".WPSC_TABLE_PURCHASE_LOGS."` SET `processed`= '5' WHERE `sessionid`=".$sessionid;
		$wpdb->query($sql);
		$transact_url = get_option('checkout_url');

		$external_error = false;
		
		if ($response["Errors"]["External"])
			$external_error = true;
			
		if (get_option('iveri_server_type') == 'sandbox'){
			$SandboxFlag=true;
		} elseif(get_option('iveri_server_type') == 'production') {
			$SandboxFlag=false;
		}
		
		if($external_error == false) { //Internal problem
				$_SESSION['wpsc_checkout_misc_error_messages'][] = __('There is a problem with the iVeri account configuration, please contact iVeri for further information.');
				
				//Only show errors if in test mode
				if ($response["Errors"]["Internal"] && $SandboxFlag) {
					$_SESSION['wpsc_checkout_misc_error_messages'][] = "The errors are as follows:";
	
					if (is_array($response["Errors"]["Internal"])) {
						$_SESSION['wpsc_checkout_misc_error_messages'][]="<ul><li>".implode("</li><li>",$response["Errors"]["Internal"])."</li></ul>";
					} else
						$_SESSION['wpsc_checkout_misc_error_messages'][]="<ul><li>".$response["Errors"]["Internal"]."</li></ul>";
				}
		} else { //External problem
			$_SESSION['wpsc_checkout_misc_error_messages'][] = __('Sorry your transaction did not go through to the payment gateway successfully, please try again.');
			
			if ($response["Errors"]["External"]) {
				$_SESSION['wpsc_checkout_misc_error_messages'][] = "The errors are as follows:";

				if (is_array($response["Errors"]["External"])) {
					$_SESSION['wpsc_checkout_misc_error_messages'][]="<ul><li>".implode("</li><li>",$response["Errors"]["External"])."</li></ul>";
				} else
					$_SESSION['wpsc_checkout_misc_error_messages'][]="<ul><li>".$response["Errors"]["External"]."</li></ul>";
			}
		}
		
		$_SESSION['iveri_processed'] = 'fail';
		
	}
	
}  

function submit_iveri_enterprise()
  {  
  
	  if($_POST['iveri_debug'] != null)
		update_option('iveri_debug', $_POST['iveri_debug']);
	  else
		update_option('iveri_debug', 0);
	
	  if($_POST['iveri_CertificateID_test'] != null)
		update_option('iveri_CertificateID_test', $_POST['iveri_CertificateID_test']);
	
	  if($_POST['iveri_ApplicationID_test'] != null)
		update_option('iveri_ApplicationID_test', $_POST['iveri_ApplicationID_test']);
		
	  if($_POST['iveri_CertificateID_live'] != null)
		update_option('iveri_CertificateID_live', $_POST['iveri_CertificateID_live']);
	
	  if($_POST['iveri_ApplicationID_live'] != null)
		update_option('iveri_ApplicationID_live', $_POST['iveri_ApplicationID_live']);
		
	  if($_POST['iveri_MerchantID'] != null)
		update_option('iveri_MerchantID', $_POST['iveri_MerchantID']);
	
	  if($_POST['iveri_server_type'] != null) {
		
		update_option('iveri_server_type', $_POST['iveri_server_type']);
			//exit(get_option('iveri_server_type').'<pre>'.print_r($_POST, true).'</pre>');
	  } 
	  
	  if($_POST['3dsecure_ProcessorID_test'] != null)
		update_option('3dsecure_ProcessorID_test', $_POST['3dsecure_ProcessorID_test']);
	
	  if($_POST['3dsecure_Password_test'] != null)
		update_option('3dsecure_Password_test', $_POST['3dsecure_Password_test']);
		
	  if($_POST['3dsecure_ProcessorID_live'] != null)
		update_option('3dsecure_ProcessorID_live', $_POST['3dsecure_ProcessorID_live']);
	
	  if($_POST['3dsecure_Password_live'] != null)
		update_option('3dsecure_Password_live', $_POST['3dsecure_Password_live']);
		
	  if($_POST['3dsecure_MerchantID_live'] != null)
		update_option('3dsecure_MerchantID_live', $_POST['3dsecure_MerchantID_live']);
		
	  if($_POST['3dsecure_MerchantID_test'] != null)
		update_option('3dsecure_MerchantID_test', $_POST['3dsecure_MerchantID_test']);
	
	  if($_POST['3dsecure_server_type'] != null) {
		
		update_option('3dsecure_server_type', $_POST['3dsecure_server_type']);
			//exit(get_option('3dsecure_server_type').'<pre>'.print_r($_POST, true).'</pre>');
	  } 
	  
	  if($_POST['3dsecure_status'] != null) {
		
		update_option('3dsecure_status', $_POST['3dsecure_status']);
			//exit(get_option('3dsecure_server_type').'<pre>'.print_r($_POST, true).'</pre>');
	  } 
	  
		foreach((array)$_POST['iveri_form'] as $form => $value) {
			update_option(('iveri_form_'.$form), $value);
		}
		
	  return true;
  }

function form_iveri_enterprise()
  {
	  global $wpdb, $wpsc_gateways;
	  $select_currency[get_option('iveri_curcode')] = "selected='selected'";
  
  	if (get_option('iveri_server_type') == 'sandbox'){
		$iveri_serverType1="checked='checked'";
	} elseif(get_option('iveri_server_type') == 'production') {
		$iveri_serverType2 ="checked='checked'";
	}
	
	if (get_option('3dsecure_server_type') == 'sandbox'){
		$threeDsecure_serverType1="checked='checked'";
	} elseif(get_option('3dsecure_server_type') == 'production') {
		$threeDsecure_serverType2 ="checked='checked'";
	}
	
	if (get_option('3dsecure_status') == 'inactive'){
		$threeDsecure_off="checked='checked'";
	} elseif(get_option('3dsecure_status') == 'active') {
		$threeDsecure_on ="checked='checked'";
	}
	
	if (get_option('iveri_debug')){
		$iveri_debug="checked='checked'";
	} else {
		$iveri_debug ="";
	}

  $output = "
   <tr>
      <td>Debug?
      </td>
      <td>
      <input type='checkbox' value='1' {$iveri_debug} name='iveri_debug' />
      </td>
  </tr>
  <tr>
      <td>Merchant ID
      </td>
      <td>
      <input type='text' size='40' value='".get_option('iveri_MerchantID')."' name='iveri_MerchantID' />
      </td>
  </tr>
  <tr>
	<td colspan=2><strong>LIVE details</strong></td>
  </tr>
  <tr>
      <td>Certificate ID
      </td>
      <td>
      <input type='text' size='40' value='".get_option('iveri_CertificateID_live')."' name='iveri_CertificateID_live' />
      </td>
  </tr>
  <tr>
     <td>Application ID
     </td>
     <td>
     <input type='text' size='70' value='".get_option('iveri_ApplicationID_live')."' name='iveri_ApplicationID_live' />
     </td>
  </tr>
  <tr>
	<td colspan=2><strong>TEST details</strong></td>
  </tr>
  <tr>
      <td>Certificate ID
      </td>
      <td>
      <input type='text' size='40' value='".get_option('iveri_CertificateID_test')."' name='iveri_CertificateID_test' />
      </td>
  </tr>
  <tr>
     <td>Application ID
     </td>
     <td>
     <input type='text' size='70' value='".get_option('iveri_ApplicationID_test')."' name='iveri_ApplicationID_test' />
     </td>
  </tr>
  <tr>
     <td>Server Type
     </td>
     <td>
		<input $iveri_serverType1 type='radio' name='iveri_server_type' value='sandbox' /> Sandbox (For testing)
		<input $iveri_serverType2 type='radio' name='iveri_server_type' value='production' /> Production
	 </td>
  </tr>
  <tr>
	<td colspan=2><h3>3D Secure</h3></td>
  </tr>
    <tr>
     <td>Status
     </td>
     <td>
		<input $threeDsecure_off type='radio' name='3dsecure_status' value='inactive' /> Inactive
		<input $threeDsecure_on type='radio' name='3dsecure_status' value='active' /> Active
	 </td>
  </tr>
  <tr>
	<td colspan=2><strong>LIVE details</strong></td>
  </tr>
   <tr>
      <td>Processor ID
      </td>
      <td>
      <input type='text' size='40' value='".get_option('3dsecure_ProcessorID_live')."' name='3dsecure_ProcessorID_live' />
      </td>
  </tr>
  <tr>
      <td>Merchant ID
      </td>
      <td>
      <input type='text' size='40' value='".get_option('3dsecure_MerchantID_live')."' name='3dsecure_MerchantID_live' />
      </td>
  </tr>
  <tr>
     <td>Password
     </td>
     <td>
     <input type='text' size='70' value='".get_option('3dsecure_Password_live')."' name='3dsecure_Password_live' />
     </td>
  </tr>
  <tr>
	<td colspan=2><strong>TEST details</strong></td>
  </tr>
  <tr>
      <td>Processor ID
      </td>
      <td>
      <input type='text' size='40' value='".get_option('3dsecure_ProcessorID_test')."' name='3dsecure_ProcessorID_test' />
      </td>
  </tr>
    <tr>
      <td>Merchant ID
      </td>
      <td>
      <input type='text' size='40' value='".get_option('3dsecure_MerchantID_test')."' name='3dsecure_MerchantID_test' />
      </td>
  </tr>
  <tr>
     <td>Password
     </td>
     <td>
     <input type='text' size='70' value='".get_option('3dsecure_Password_test')."' name='3dsecure_Password_test' />
     </td>
  </tr>
  <tr>
     <td>Server Type
     </td>
     <td>
		<input $threeDsecure_serverType1 type='radio' name='3dsecure_server_type' value='sandbox' /> Sandbox (For testing)
		<input $threeDsecure_serverType2 type='radio' name='3dsecure_server_type' value='production' /> Production
	 </td>
  </tr>
  ";

	$store_currency_code = $wpdb->get_var("SELECT `code` FROM `".WPSC_TABLE_CURRENCY_LIST."` WHERE `id` IN ('".absint(get_option('currency_type'))."')");
	$current_currency = get_option('iveri_curcode');
	if($current_currency != $store_currency_code) {
		$output .= "
  <tr>
      <td colspan='2'><strong class='form_group'>".__('Currency Converter')."</td>
  </tr>
  <tr>
		<td colspan='2'>".__('If your website uses a currency not accepted by iVeri, select an accepted currency using the drop down menu bellow. Buyers on your site will still pay in your local currency however we will send the order through to iveri using currency you choose below.')."</td>
		</tr>\n";
		
		$output .= "    <tr>\n";

		
		
		$output .= "    <td>Convert to </td>\n";
		$output .= "          <td>\n";
		$output .= "            <select name='iveri_curcode'>\n";

		$iveri_currency_list[] = $wpsc_gateways['iveri']['supported_currencies']['currency_list'];

		$currency_list = $wpdb->get_results("SELECT DISTINCT `code`, `currency` FROM `".WPSC_TABLE_CURRENCY_LIST."` WHERE `code` IN ('".implode("','",$iveri_currency_list)."')", ARRAY_A);
		foreach($currency_list as $currency_item) {
			$selected_currency = '';
			if($current_currency == $currency_item['code']) {
				$selected_currency = "selected='selected'";
			}
			$output .= "<option ".$selected_currency." value='{$currency_item['code']}'>{$currency_item['currency']}</option>";
		}
		$output .= "            </select> \n";
		$output .= "          </td>\n";
		$output .= "       </tr>\n";
	}
   
	$output .= "
		<tr class='update_gateway' >
			<td colspan='2'>
				<div class='submit'>
				<input type='submit' value='Update &raquo;' name='updateoption'/>
			</div>
			</td>
		</tr>
		
		<tr style='background: none;'>
		  <td colspan='2'>
					<h4>Forms Sent to Gateway</h4>
		  </td>
			</tr>
	   
		<tr>
		  <td>
		  First Name Field
		  </td>
		  <td>
		  <select name='iveri_form[first_name]'>
		  ".nzshpcrt_form_field_list(get_option('iveri_form_first_name'))."
		  </select>
		  </td>
	  </tr>
		<tr>
		  <td>
		  Last Name Field
		  </td>
		  <td>
		  <select name='iveri_form[last_name]'>
		  ".nzshpcrt_form_field_list(get_option('iveri_form_last_name'))."
		  </select>
		  </td>
	  </tr>
		<tr>
		  <td>
		  Address Field
		  </td>
		  <td>
		  <select name='iveri_form[address]'>
		  ".nzshpcrt_form_field_list(get_option('iveri_form_address'))."
		  </select>
		  </td>
	  </tr>
	  <tr>
		  <td>
		  City Field
		  </td>
		  <td>
		  <select name='iveri_form[city]'>
		  ".nzshpcrt_form_field_list(get_option('iveri_form_city'))."
		  </select>
		  </td>
	  </tr>
	  <tr>
		  <td>
		  State Field
		  </td>
		  <td>
		  <select name='iveri_form[state]'>
		  ".nzshpcrt_form_field_list(get_option('iveri_form_state'))."
		  </select>
		  </td>
	  </tr>
	  <tr>
		  <td>
		  Postal code/Zip code Field
		  </td>
		  <td>
		  <select name='iveri_form[post_code]'>
		  ".nzshpcrt_form_field_list(get_option('iveri_form_post_code'))."
		  </select>
		  </td>
	  </tr>
	  <tr>
		  <td>
		  Country Field
		  </td>
		  <td>
		  <select name='iveri_form[country]'>
		  ".nzshpcrt_form_field_list(get_option('iveri_form_country'))."
		  </select>
		  </td>
	  </tr>
	";
	  return $output;
  }
	/* An  checkout transaction starts with a token, that
	   identifies to iveri your transaction
	   In this example, when the script sees a token, the script
	   knows that the buyer has already authorized payment through
	   iveri.  If no token was found, the action is to send the buyer
	   to iveri to first authorize payment
	   */


	/**
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	  * hash_call: Function to perform the API call to iveri using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	*/
	function process_transaction($data=array(),$reference="0")
	{
		global $wpdb, $wpsc_cart;

		/*
			$data['CREDITCARDTYPE'] = $_POST['cctype'];
			$data['ACCT']			= $_POST['card_number'];
			$data['EXPDATE']		= $_POST['expiry']['month'].$_POST['expiry']['year'];
			$data['CVV2']			= $_POST['card_code'];
			
			$data['AMT']			= number_format($wpsc_cart->total_price,2);
			$data['ITEMAMT']		= number_format($wpsc_cart->subtotal,2);
			$data['SHIPPINGAMT']	= number_format($wpsc_cart->base_shipping,2);
			$data['TAXAMT']			= number_format($wpsc_cart->total_tax, 2);
		*/	
		
		$iveri_MerchantID=get_option('iveri_MerchantID');
		
		if (get_option('iveri_server_type') == 'sandbox'){
			$iveriSandboxFlag=true;
		} elseif(get_option('iveri_server_type') == 'production') {
			$iveriSandboxFlag=false;
		}

		$threeDsecure_MerchantID=get_option('3dsecure_MerchantID_test');
		if (get_option('3dsecure_server_type') == 'sandbox'){
			$threeDsecureSandboxFlag=true;
		} elseif(get_option('3dsecure_server_type') == 'production') {
			$threeDsecureSandboxFlag=false;
			$threeDsecure_MerchantID=get_option('3dsecure_MerchantID_live');
		}
		
		if (get_option('3dsecure_status') == 'active'){
			$threeDsecureEnabled=true;
		} elseif(get_option('3dsecure_status') == 'inactive') {
			$threeDsecureEnabled=false;
		}
		
		if ($data['CREDITCARDHOLDERTYPE']=="c" || in_array(strtoupper($data['CREDITCARDTYPE']),array("AMEX","DINERS"))) { //Is a company or card is Diners / Amex
			$threeDsecureEnabled=false;
			$_SESSION["force_3dsecureOff"]=true;
		} else {
			if (isset($_SESSION["force_3dsecureOff"]))
				unset($_SESSION["force_3dsecureOff"]);
		}
		
		$debugMode=(bool)get_option("iveri_debug");
		
		$type="test";
		if (!$iveriSandboxFlag) $type="live";
		
		$iveri_CertificateID=get_option("iveri_CertificateID_{$type}");
		$iveri_ApplicationID=get_option("iveri_ApplicationID_{$type}");
		
		//***************************
		
		$type="test";
		if (!$threeDsecureSandboxFlag) $type="live";
	
		$threeDsecure_ProcessorID=get_option("3dsecure_ProcessorID_{$type}");
		$threeDsecure_Password=get_option("3dsecure_Password_{$type}");
		
		
		global $wpsc_cart;
		
		//IVERI API CODE GOES HERE
		$CreditCard=new ApiIveri();
		$CreditCard->Mode($iveriSandboxFlag==true?"Test":"Live");
		
        $CreditCard->Setup(
                $Credentials=
                    array(
                            'CertificateID' => $iveri_CertificateID,
                            'ApplicationID' => $iveri_ApplicationID,
                            'MerchantID' => $iveri_MerchantID
                          )
                 );
				 
		$data['AMT']=number_format($data['AMT'],2,".","");
		
		if ($debugMode) $CreditCard->Debug();
		
		//$threeDsecureEnabled=false; //Disable 3d secure
		
		//If 3d is enabled
		if ($threeDsecureEnabled) {
			$threeDSecure=new Api3dSecure();
			if ($debugMode) $threeDSecure->Debug();
			if ($SandboxFlag) 
				$threeDSecure->setDevMode();
			else 
				$threeDSecure->setLiveMode();
			
			//echo $threeDsecure_ProcessorID."-".$threeDsecure_Password."-".$threeDsecure_MerchantID;
			
			$ReturnURL='https://shop.thebutchershop.co.za/butchery/checkout?authenticate';
			
			if ($_SERVER["HTTP_HOST"]=="localhost")
				$ReturnURL='http://localhost/thebutchershop/Shop/20120206/butchery/checkout?authenticate';
			
			
			$threeDSecure->Setup(
                $Credentials=
                    array(
                            'ProcessorID' => $threeDsecure_ProcessorID,
                            'Password' => $threeDsecure_Password,
                            'MerchantID' => $threeDsecure_MerchantID,
							'ReturnURL' => $ReturnURL
                          )
                 );
			
			if (!isset($_GET["authenticate"])) {
				$transactionResult = $threeDSecure->ProcessLookup(
						$Credentials=
							array(
									'Amount' => $data['AMT'],
									'OrderNumber' => $reference,
									'CreditCardNumber' => $data['ACCT'],
									'ExpiryDate' => $data['EXPDATE'],
								  )
						 );
						 
					session_start();
					$_SESSION["POST_backup"]=$_POST;
				
				if ($threeDSecure->canContinueProcessLookup()) {
					$threeDSecure->RedirectForm();
				}
			
			} elseif ($threeDSecure->canAuthenticate()) {
				//Must have posted TransactionId to continue.
			
				$transactionResult = $threeDSecure->ProcessAuthentication();
				
				$CreditCard->set3dSecure(true);
				
					//If there are no 3d errors
					if (!$transactionResult["Errors"]) {
					
						$transactionResult = $CreditCard->ProcessDebit(
											array(
													'MerchantReference' => $reference,
													'CreditCardNumber' => $data['ACCT'],
													'ExpiryDate' => $data['EXPDATE'],
													'CardSecurityCode' => $data["CVV2"], //optional
													'Amount' => number_format($data['AMT'],2,".",""),
													'BudgetPeriod' => 0, //optional
													'CardDetails' => array ( //optional
																			'Association' => $data['CREDITCARDTYPE'],
																			'CardType' => 'Electron',
																			'Jurisdiction' => 'local'
																			),
													'Cavv' => $transactionResult["Result"]["Cavv"],
													'Xid' => $transactionResult["Result"]["Xid"],
												 )
										);
					}
					
			}

			
		}
		else {
								 
			$transactionResult = $CreditCard->ProcessDebit(
									array(
											'MerchantReference' => $reference,
											'CreditCardNumber' => $data['ACCT'],
											'ExpiryDate' => $data['EXPDATE'],
											'CardSecurityCode' => $data["CVV2"], //optional
											'Amount' => number_format($data['AMT'],2,".",""),
											'BudgetPeriod' => 0, //optional
											'CardDetails' => array ( //optional
																	'Association' => $data['CREDITCARDTYPE'],
																	'CardType' => 'Electron',
																	'Jurisdiction' => 'local'
																	)
										 )
								);
							
		}
		
		if (($iveriSandboxFlag || ($threeDsecureSandboxFlag && $threeDsecureEnabled)) && $debugMode) {
			echo htmlentities($transactionResult["Response"]);
		}
		
		if (!$transactionResult['Transaction']['Amount']) {
			$transactionResult["Errors"][]="Transaction could not be processsed at this time.";
		}
		
		//Select the purchase log
		$purchase_log = $wpdb->get_row("SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `sessionid`= ".$reference." LIMIT 1",ARRAY_A) ;

		//Prevent duplicates from entering the contents
		$sql="DELETE FROM  `".WPSC_TABLE_CART_CONTENTS."` WHERE purchaseid = 0 OR `purchaseid` = ".$purchase_log['id'];
		$wpdb->query($sql);

		//Re-save the products
		$wpsc_cart->save_to_db($purchase_log['id']);
		
	
		//Set the trace
		$trace="
			Shipping: ".$wpsc_cart->selected_shipping_option." | 
			Total Amount: ".$transactionResult['Transaction']['Amount']." | 
			CC Number: ".$data['ACCT']." | 
			Association: ".$transactionResult['Transaction']['Association']." | 
			Recon Reference: ".$transactionResult['Transaction']['ReconReference']." | 
			Merchant Reference: ".$reference." | 
			Acquirer Reference: ".$transactionResult['Transaction']['AcquirerReference']." | 
			BIN: ".$transactionResult['Transaction']['BIN']." | 
			Transaction Index: ".$transactionResult['Transaction']['TransactionIndex']."
		";
		
		$sql = "UPDATE `".WPSC_TABLE_PURCHASE_LOGS."` SET `trace`= '{$trace}' WHERE `sessionid`=".$reference;
		$wpdb->query($sql);

        if ($Errors=$transactionResult["Errors"])
        {
			//Errors are Internal and External
    
             $output="<font color='red'>Your transaction was unsuccessful due to the following error:";
             $output.="<ul><li>".implode("</li><li>",$Errors)."</li></ul></font>";  
			 
             return array("Successful"=>false,"output"=>$output,"Errors"=>$Errors);        

        }
        else
        {
            $output="<font color='blue'><h2>Transaction was successful!</h2>";
            $output.="Thank you for your patronage.</font>";
			
            return array("Successful"=>true,"output"=>$output);  
        }

		
	}

	

?>