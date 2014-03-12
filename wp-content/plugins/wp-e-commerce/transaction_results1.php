<?php
global $wpdb, $user_ID, $nzshpcrt_gateways, $sessionid, $cart_log_id;
do_action('wpsc_transaction_results');
$sessionid = $_GET['sessionid'];
if(!isset($_GET['sessionid']) && isset($_GET['ms']) ){
	$sessionid = $_GET['ms'];
}elseif(isset($_GET['ssl_result_message']) && $_GET['ssl_result_message']== 'APPROVAL' ){
	$sessionid = $_SESSION['wpsc_sessionid'];
	if(get_option('permalink_structure') != '') {
		$seperator = "?";
	} else {
		$seperator = "&";
	}
	//unset($_SESSION['wpsc_sessionid']);
	//header('Location: '.get_option('transact_url').$seperator.'sessionid='.$sessionid);
}
if($_GET['gateway'] == 'google'){
	wpsc_google_checkout_submit();
	unset($_SESSION['wpsc_sessionid']);
}elseif($_GET['gateway'] == 'noca'){
	wpsc_submit_checkout();
}
if($_SESSION['wpsc_previous_selected_gateway']== 'dps') {
		$sessionid = decrypt_dps_response();
}
$errorcode = '';
$transactid = '';
if($_REQUEST['eway']=='1') {
	$sessionid = $_GET['result'];
}elseif($_REQUEST['eway']=='0'){
	echo $_SESSION['eway_message'];
}elseif ($_REQUEST['payflow']=='1') {	
	echo $_SESSION['payflow_message'];
	$_SESSION['payflow_message']='';
}
if($_SESSION['wpsc_previous_selected_gateway'] == 'paypal_certified' && $_SESSION['paypalExpressMessage'] != 'Completed'){
	echo $_SESSION['paypalExpressMessage'];
	$sessionid = $_SESSION['paypalexpresssessionid'];

}elseif($sessionid == ''){
	?>
<div class="shopcart_empty">
	<div class="title"><h4>
		Transaction History
	</h4></div>
	<div class="no_items">No products</div>
	<div class="redtext"><?php _e('Sorry, no transaction history is available.'); ?></div>
	<div class="blacktext"><a class="greenlinkbig" href="<?php echo home_url( '/' )."?page_id=11"; ?>">Click here</a> to go back to checkout page.</div>
</div>	
<?php 
}else{
	echo transaction_results($sessionid, true);	
}

$cart_log_id = $wpdb->get_var( "SELECT `id` FROM `" . WPSC_TABLE_PURCHASE_LOGS . "` WHERE `sessionid`= " . $sessionid . " LIMIT 1" );

?>