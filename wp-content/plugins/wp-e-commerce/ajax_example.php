<?php
//print_r($_GET);
global $wpdb;
$dbhost = "localhost";
$dbuser = "root";
$dbpass = '';//Vwj$s9@Q9z1YbccCK';
$dbname = "shop.thebutchershop.co.za";
//Connect to MySQL Server
$link = mysql_connect($dbhost, $dbuser, $dbpass);
//Select Database
mysql_select_db($dbname) or die(mysql_error());
// Retrieve data from Query String
$variation_id = $_GET['var_id'];
$variation_group_id = $_GET['var_grp_id'];
$product_id = $_GET['prod_id'];

if ($_GET['custom'] == 1) {
	$priceandstock_id = "SELECT `priceandstock_id` FROM `wp_wpsc_variation_combinations` WHERE `product_id` = '".$product_id."' AND `value_id` IN ( '".$variation_id."' ) LIMIT 1";
	$qry_result = mysql_query($priceandstock_id) or die(mysql_error());
	$row = mysql_fetch_array($qry_result);
	$output = $row['priceandstock_id']; 

	$variation_price = "SELECT `price` FROM `wp_wpsc_variation_properties` WHERE `id` = '{$output}' LIMIT 1";
	$qry_result = mysql_query($variation_price) or die(mysql_error());
	$row = mysql_fetch_array($qry_result);
	$output = $row['price'];
} else {
	if(count($_GET['all_variations']) > 0) {
		$ar = explode(",", $_GET['all_variations']);
		// if there are variations, get the price of the combination and the names of the variations.
		$qry = "SELECT * FROM `wp_wpsc_variation_values` WHERE `id` IN ('".implode("','",$ar)."')";
		$qry_result = mysql_query($qry) or die(mysql_error());

		$variation_names = array();
		$variation_ids = array();
		while ($rows = mysql_fetch_array($qry_result)) {
			$variation_names[] = $rows['name'];
			$variation_ids[] = $rows['variation_id'];
			$rows++;
		}

		asort($variation_ids);         
		$variation_id_string = implode(",", $variation_ids);
		$priceandstock_id = "SELECT `priceandstock_id` FROM `wp_wpsc_variation_combinations` WHERE `product_id` = '{$product_id}' AND `value_id` IN ( '".implode("', '",$ar )."' ) AND `all_variation_ids` IN ('$variation_id_string') GROUP BY `priceandstock_id` HAVING COUNT( `priceandstock_id` ) = '".count($ar)."' LIMIT 1";	
		$qry_result = mysql_query($priceandstock_id) or die(mysql_error());
		$row = mysql_fetch_array($qry_result);
			
		$priceandstock_values = "SELECT * FROM `wp_wpsc_variation_properties` WHERE `id` = '".$row['priceandstock_id']."' LIMIT 1";
		$qry_result = mysql_query($priceandstock_values) or die(mysql_error());
		$row = mysql_fetch_array($qry_result);
		$output = $row['price'];
	}
}
mysql_close($link);
//echo 666;
if ($output)
	echo "R" . $output;
else
	echo "R0.00";
?>