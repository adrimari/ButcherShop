// This is the wp-e-commerce front end javascript "library"
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}

function testing(id) {
	//alert(id);
	var formname = "product_"+id;
	var e = document.forms[formname];
	e.reset();
}
	// Calculates the subtotal
	// units - specifies the type of unit being worked with
	// price - retrieved from database and passed as a parameter
	// id - id of the field being worked on
	// product_id - actual database id of the product
	// minimum & maximum - the minimum and maximum values for the specific unit
	function calculate(units, price, id, product_id, minimum, maximum) {
		var error = 0;
		// generate the id of the unit textbox
		var total = "total_" + units + product_id;
		//generate the id of the product price textbox
		var total_price = "total" + product_id;
		var q = document.getElementById(id);
		var e = "R";
		var amount = 0;
		if (units == 'grams') {
			amount = (Math.abs(q.value) * price / 1000).toFixed(2);
		} else {
		 	amount = (Math.abs(q.value) * price).toFixed(2);
		}
		if (!error) {
			e += amount;
			document.getElementById(total).innerHTML = e;
		}
        updatetotal(product_id);
	}
	
	function checknumber(units, price, id, product_id, minimum, maximum) {
		var e = document.getElementById(id);
		var total = "total_" + units + product_id;
		var total_price = "total" + product_id;
		var error = 0;
		var amount = 0;
		if (units == 'grams') {
			amount = (e.value * price / 1000).toFixed(2);
		} else {
		 	amount = (e.value * price).toFixed(2);
		}
		var length = e.value.length;
		if (isNaN(e.value)) {
			e.value = e.value.substr(0, length - 1);
			if (isNaN(e.value)) {
				e.value = e.value.substr(0, length - 2);
				if (isNaN(e.value)) {
					e.value = e.value.substr(0, length - 3);
				}
			}
			alert('Only numbers are allowed');
		}
		calculate(units, price, id, product_id, minimum, maximum);
	}

    function check_vals(units, price, id, product_id, minimum, maximum) {
		var e = document.getElementById(id);
		var total = "total_" + units + product_id;
		var total_price = "total" + product_id;
		var error = 0;
		var amount = 0;
		if (units == 'grams') {
			amount = (e.value * price / 1000).toFixed(2);
		} else {
		 	amount = (e.value * price).toFixed(2);
		}
		check_min(e, total, total_price, minimum, maximum, units, amount);
    	check_max(e, total, total_price, minimum, maximum, units, amount);
    	updatetotal(product_id);
    }

	function check_min(e, t, t_p, n, m, u, a) {
		if (parseInt(e.value) < parseInt(n)) {
			alert('Value must be between ' + n + ' and ' + m + ' ' + u);
			document.getElementById(t).innerHTML = "R0.00";
			e.value = "";
			document.getElementById(t_p).innerHTML = (document.getElementById(t_p).innerHTML.substr(1) - a).toFixed(2);
			e.focus();
		}
	}
	
	function check_max(e, t, t_p, n, m, u, a) {
		if (parseInt(e.value) > parseInt(m)) {
			alert('Value must be between ' + n + ' and ' + m + ' ' + u);
			document.getElementById(t).innerHTML = "R0.00";
			e.value = "";
			document.getElementById(t_p).innerHTML = (document.getElementById(t_p).innerHTML.substr(1) - a).toFixed(2);
			e.focus();
		}
	}
	
	function build_GET(url, product_id, var_grp) {
		var custom = -1;
        if (document.getElementById('weight'+product_id)) {
            var weight = document.getElementById('weight'+product_id);
            if (weight.checked)
                custom = 1;
        }
        if (document.getElementById('items'+product_id)) {
            var items = document.getElementById('items'+product_id);
            if (items.checked) 
                custom = 0;
        }
        url += "&custom="+custom;
		if (document.getElementById('units_grams'+product_id) && document.getElementById('units_grams'+product_id).value != "") {
			var grams = document.getElementById('units_grams'+product_id);
			url += "&units_grams"+product_id+"="+grams.value;
		}
		if (document.getElementById('units_kilograms'+product_id) && document.getElementById('units_kilograms'+product_id).value != "") {
			var kilograms = document.getElementById('units_kilograms'+product_id);
			url += "&units_kilograms"+product_id+"="+kilograms.value;
		}
		if (document.getElementById('units_ounces'+product_id) && document.getElementById('units_ounces'+product_id).value != "") {
			var ounces = document.getElementById('units_ounces'+product_id);
			url += "&units_ounces"+product_id+"="+ounces.value;
		}
		if (document.getElementById('units_pounds'+product_id) && document.getElementById('units_pounds'+product_id).value != "") {
			var pounds = document.getElementById('units_pounds'+product_id);
			url += "&units_pounds"+product_id+"="+pounds.value;
		}
		if (document.getElementById('total_price'+product_id) && document.getElementById('total_price'+product_id).value != 0) {
			var price = document.getElementById('total_price'+product_id);
			url += "&total_price"+product_id+"="+price.value;
		}
		if (document.getElementById('total'+product_id) && document.getElementById('total'+product_id).innerHTML != "R0.00") {
			var total = document.getElementById('total'+product_id);
			url += "&total"+product_id+"="+trim(total.innerHTML);
		}
		if (document.getElementById('subtotal'+product_id) && document.getElementById('subtotal'+product_id).innerHTML != "R0.00") {
			var subtot = document.getElementById('subtotal'+product_id);
			url += "&subtot"+product_id+"="+trim(subtot.innerHTML);
		}
		if (document.getElementById('custom_wpsc_quantity_update'+product_id) && document.getElementById('custom_wpsc_quantity_update'+product_id).value != 0) {
			var qty = document.getElementById('custom_wpsc_quantity_update'+product_id);
			url += "&custom_wpsc_quantity_update"+product_id+"="+Math.abs(qty.value);
		}
		if (document.getElementById('wpsc_quantity_update'+product_id) && document.getElementById('wpsc_quantity_update'+product_id).value != 0) {
			var qty = document.getElementById('wpsc_quantity_update'+product_id);
			url += "&wpsc_quantity_update"+product_id+"="+Math.abs(qty.value);
		}
		if (document.getElementById('subtot'+product_id) && document.getElementById('subtot'+product_id).innerHTML != "R0.00") {
			var subtot = document.getElementById('subtot'+product_id);
			url += "&subtot"+product_id+"="+trim(subtot.innerHTML);
		}
		if (document.getElementById('tot'+product_id) && document.getElementById('tot'+product_id).innerHTML != "R0.00") {
			var tot = document.getElementById('tot'+product_id);
			url += "&tot"+product_id+"="+trim(tot.innerHTML);
		}
        var group = "variation_select_"+product_id+"_";
		var index = 0;
		for (i=0; i<=100; i++) {
			e = document.getElementById(group+i);
			if (e) {
			   url += "&variation_select_"+product_id+"_"+i+"="+e.value;
			   index++;
			}
		}
		document.location.href = url;
	}
	
	function updatetotal(id) {
		var custom = 'custom' + id;
		var tot_price = 'total_price' + id;
		var tot = 'total' + id;
		var tot_weight = 'tot' + id;
		var quantity = 'custom_wpsc_quantity_update' + id;
		var total = 0.00;
		if (document.getElementById(quantity) && document.getElementById(custom).style.display == 'block') {
			var q = document.getElementById(quantity);
			var units_grams = 'total_grams' + id;
			var units_kilograms = 'total_kilograms' + id;
			var units_ounces = 'total_ounces' + id;
			var units_pounds = 'total_pounds' + id;
			if (document.getElementById(units_grams)) {
		    	var grams = document.getElementById(units_grams).innerHTML.replace(",", "");
				var g = parseFloat(trim(grams).substr(1)).toFixed(2);
			}
			if (document.getElementById(units_kilograms)) {
            	var kilograms = document.getElementById(units_kilograms).innerHTML.replace(",", "");
				var k = parseFloat(trim(kilograms).substr(1)).toFixed(2);
			}
			if (document.getElementById(units_ounces)) {
		    	var ounces = document.getElementById(units_ounces).innerHTML.replace(",", "");
				var o = parseFloat(trim(ounces).substr(1)).toFixed(2);
			}
			if (document.getElementById(units_pounds)) {
            	var pounds = document.getElementById(units_pounds).innerHTML.replace(",", "");
				var p = parseFloat(trim(pounds).substr(1)).toFixed(2);
			}
			if (grams && !isNaN(g))
				total += parseFloat(g);
			if (kilograms && !isNaN(k))
				total += parseFloat(k);
			if (ounces && !isNaN(o))
				total += parseFloat(o);
			if (pounds && !isNaN(p))
				total += parseFloat(p);	
			if (document.getElementById(tot)) {
				var t = document.getElementById(tot_price);
				t.value = parseFloat(total).toFixed(2);// / parseFloat(Math.abs(q.value)).toFixed(2);
				var amount = parseFloat(total).toFixed(2) * parseFloat(Math.abs(q.value)).toFixed(2);
				document.getElementById(tot).innerHTML = "R" + parseFloat(amount).toFixed(2);
			}
			var subtotal = 'subtotal' + id;
			if (document.getElementById(subtotal)) {
				document.getElementById(subtotal).innerHTML = "R" + parseFloat(total).toFixed(2);
				//var t = document.getElementById(tot_price);
				//t.value = parseFloat(total).toFixed(2);			
			}
		} else { 
			quantity = 'wpsc_quantity_update' + id;
			var q = document.getElementById(quantity).value;
			if (document.getElementById(tot_weight)) {
				var subtotal = 'subtot' + id;
				if (document.getElementById(subtotal)) {
					total = document.getElementById(tot_weight).innerHTML.replace(",", "");
					total = parseFloat(trim(total).substr(1)).toFixed(2);
					document.getElementById(tot_weight).innerHTML = "R" + parseFloat(parseFloat(total).toFixed(2) * Math.abs(parseFloat(q).toFixed(2))).toFixed(2);
					//var t = document.getElementById(tot_price);
					//t.value = parseFloat(total).toFixed(2);			
				}
				//var tt = document.getElementById(tot_weight).innerHTML.replace(",", "");
				//var g = parseFloat(trim(grams).substr(1)).toFixed(2);
			}
		}
	}

	function enable_custom(id, group_id) {
		var custom = 'custom' + id;
		var items = 'item' + id;
		var form = "product_" + id;
		e = document.getElementById(custom);
		f = document.getElementById(items);
		if (e.style.display == "none")
			e.style.display = "block";
		if (f.style.display == "block")
			f.style.display = "none";
		e = 'subtot' + id;
		if (f = document.getElementById(e)) {
			f.innerHTML = "R0.00";
		}
		e = 'tot' + id;
		if (f = document.getElementById(e)) {
			f.innerHTML = "R0.00";
		}
		e = 'wpsc_quantity_update' + id;
		if (f = document.getElementById(e)) {
			f.value = "0";
		}
		e = document.getElementById(form);
		e.reset();
	}
	
	function enable_item(id, group_id) {
		var custom = 'custom' + id;
		var items = 'item' + id;
		var e = document.getElementById(custom);
		var f = document.getElementById(items);
		if (e.style.display == "block")
			e.style.display = "none";
		if (f.style.display == "none")
			f.style.display = "block";
		e = 'units_grams' + id;
        if (f = document.getElementById(e)) {
           f.value = "";
        }
		e = 'units_kilograms' + id;
        if (f = document.getElementById(e)) {
           f.value = "";
        }
		e = 'units_ounces' + id;
        if (f = document.getElementById(e)) {
           f.value = "";
        }
		e = 'units_pounds' + id;
        if (f = document.getElementById(e)) {
           f.value = "";
        }
		e = 'total_grams' + id;
        if (f = document.getElementById(e)) {
           f.innerHTML = "R0.00";
        }
		e = 'total_kilograms' + id;
        if (f = document.getElementById(e)) {
           f.innerHTML = "R0.00";
        }
		e = 'total_ounces' + id;
        if (f = document.getElementById(e)) {
           f.innerHTML = "R0.00";
        }
		e = 'total_pounds' + id;
        if (f = document.getElementById(e)) {
           f.innerHTML = "R0.00";
        }
		e = 'total' + id;
        if (f = document.getElementById(e)) {
           f.innerHTML = "R0.00";
        }
		e = 'total_price' + id;
        if (f = document.getElementById(e)) {
           f.value = "0";
        }
		e = 'subtotal' + id;
		if (f = document.getElementById(e)) {
			f.innerHTML = "R0.00";
		}
		e = 'custom_wpsc_quantity_update' + id;
		if (f = document.getElementById(e)) {
			f.value = "0";
		}		
	}
	
	function update_qty(id) {
		var button = '';
		var element_custom = 'custom' + id;
		var element_item = 'item' + id;	
		var c = document.getElementById(element_custom);
		var i = document.getElementById(element_item);
		if (c && c.style.display == "block") {
			button = 'custom_product_'+id+'_submit_button';
			if (document.getElementById(button))
				var b = document.getElementById(button);
			else {
				button = 'update'+id;
				var b = document.getElementById(button);
			}
			b.disabled = false;
			var element = "custom_wpsc_quantity_update" + id;
			var e = document.getElementById(element);
			var length = e.value.length;
			if (isNaN(e.value)) {
				e.value = e.value.substr(0, length - 1);
				if (isNaN(e.value)) {
					e.value = e.value.substr(0, length - 2);
					if (isNaN(e.value)) {
						e.value = e.value.substr(0, length - 3);
					}
				}
				return alert('Only numbers are allowed');
			}
			var element_qty = 'custom_wpsc_quantity_update' + id;
			var element_subtot = 'subtotal' + id;
			var element_total = 'total' + id;
			var element_total_price = 'total_price' + id;
		} else if (i && i.style.display == "block") {
			button = 'product_'+id+'_submit_button';
			if (document.getElementById(button))
				var b = document.getElementById(button);
			else {
				button = 'update'+id;
				var b = document.getElementById(button);
			}
			//alert(b.name);
			b.disabled = false;
			var element = "wpsc_quantity_update" + id;
			var e = document.getElementById(element);
			var length = e.value.length;
			if (isNaN(e.value)) {
				e.value = e.value.substr(0, length - 1);
				if (isNaN(e.value)) {
					e.value = e.value.substr(0, length - 2);
					if (isNaN(e.value)) {
						e.value = e.value.substr(0, length - 3);
					}
				}
				return alert('Only numbers are allowed');
			}
			var element_qty = 'wpsc_quantity_update' + id;
			var element_subtot = 'subtot' + id;
			var element_total = "tot" + id;
		} else {
			button = 'product_'+id+'_submit_button';
			//alert(button);
			if (document.getElementById(button))
				var b = document.getElementById(button);
			else {
				button = 'update'+id;
				var b = document.getElementById(button);
			}
			//alert(b.name);
			b.disabled = false;
			var element = "wpsc_quantity_update" + id;
			//alert(element);
			if (document.getElementById(element))
				var e = document.getElementById(element);
			else {
				element = "update"+id;
				var e = document.getElementById(element);
			}
			var length = e.value.length;
			if (isNaN(e.value)) {
				e.value = e.value.substr(0, length - 1);
				if (isNaN(e.value)) {
					e.value = e.value.substr(0, length - 2);
					if (isNaN(e.value)) {
						e.value = e.value.substr(0, length - 3);
					}
				}
				return alert('Only numbers are allowed');
			}
			var element_qty = 'wpsc_quantity_update' + id;
			var element_subtot = 'subtot' + id;
			var element_total = "total" + id;			
		}
		var e = document.getElementById(element_qty);
		var f = document.getElementById(element_subtot);
		var g = document.getElementById(element_total);
		if (element_total_price) {
			var h = document.getElementById(element_total_price);
			if (e.value == "") {
				h.value = 0;
			} else {
				h.value = parseFloat(trim(f.innerHTML).substr(1)).toFixed(2);// * Math.abs(parseFloat(e.value).toFixed(2));
			}
		}
		var p = parseFloat(trim(f.innerHTML).substr(1)).toFixed(2);
		var a = 'R';
		if (e.value == "") {
			g.innerHTML = a + '0.00';
		} else {
			g.innerHTML = a + (Math.abs(parseFloat(e.value)) * p).toFixed(2).toString();
		}
		//g.innerHTML = a + (parseFloat(e.value) * p).toFixed(2).toString();
	}
	
	function test(id, grp_id) {
		var x = document.getElementById('product_'+id);
		var length = x.elements.length;
		var l = 0;
		var all_variations = new Array();
		for (i=0;i<=length;i++) {
			if (x.elements[i]) {
				var type = x.elements[i].type;
        		if (type=="select-one"){
            		if (x.elements[i].value != "") {
						all_variations[l] = x.elements[i].value;
					}
					l++;
        		}
			}
		}
		var submit = "";
		for (j=0;j<l;j++) {
			if(!all_variations[j]) {
				var e = document.getElementById('subtot'+id);
				e.innerHTML = "R0.00";
				if (document.getElementById('tot'+id))
					e = document.getElementById('tot'+id);
				else
					e = document.getElementById('total'+id)
				e.innerHTML = "R0.00";
				e = document.getElementById('wpsc_quantity_update'+id);
				e.value = 0;
				//e = document.getElementById('product_'+id+'_submit_button');
				//e.disabled = true;
				return;
			}
		}
		//e = document.getElementById('product_'+id+'_submit_button');
		//e.disabled = false;
		//alert(all_variations);
		var e = document.getElementById(grp_id);
		var product_id = id;
		var variation_group_id = grp_id;
		var variation_id = e.value;
		var ajaxRequest;  // The variable that makes Ajax possible!
		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
			} catch (e){
				// Internet Explorer Browsers
				try{
					ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try{
						ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e){
					// Something went wrong
					alert("Your browser broke!");
					return false;
					}
				}
			}
			var custom = 0;
			// Create a function that will receive data sent from the server
			ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				
				var id = 'subtot' + product_id;
				if (!document.getElementById(id))
					id = 'subtotal' + product_id;
				var qty = 'wpsc_quantity_update' + product_id;
				if (!document.getElementById(qty)) {
					qty = 'custom_wpsc_quantity_update' + product_id;
					custom = 1;
				}
				var tot = 'total' + product_id;
				if (!document.getElementById(tot))
					tot = 'tot' + product_id;
				var ajaxDisplay = document.getElementById(id);
				var e = document.getElementById(qty);
				if (e) {
					var total = parseFloat(e.value) * parseFloat(ajaxRequest.responseText.substr(1));
					//alert(ajaxRequest.responseText.substr(1));
					document.getElementById(tot).innerHTML = "R" + total.toFixed(2)
				}
				ajaxDisplay.innerHTML = ajaxRequest.responseText;
			}
		}
		var queryString = "?var_id=" + variation_id + "&var_grp_id=" + variation_group_id + "&prod_id=" + product_id + "&custom=" + custom + "&all_variations=" + all_variations + ""; 
		ajaxRequest.open("GET", "http://www.shop.thebutchershop.co.za/wp-content/plugins/wp-e-commerce/ajax_example.php" + queryString, true);
		ajaxRequest.send(null); 
		update_qty(product_id)
	}
	
	function enable_edit(id) {
		var element = "items" + id;
		var form = "product_" + id;
		if (document.getElementById(element) && document.getElementById(element).disabled == true) {
			element = "weight" + id;
			form = "custom_product_" + id;
		}
		var x = document.getElementById(form);
		var length = x.elements.length;
		for (i=0;i<=length;i++) {
			if (x.elements[i]) {
        		if (x.elements[i].disabled){
					x.elements[i].disabled = false;
					if (x.elements[i].name == 'wpsc_quantity_update'+id) {
						x.elements[i].value = 0;
						update_qty(id);
					}
				}
			}
		}
	}
	
	function add_prod(id) {
		
	}
	
	function check_qty(id) {
		//alert('stuff');
		var element = "quantity" + id;
		var e = document.getElementById(element);
		var length = e.value.length;
		if (isNaN(e.value)) {
			e.value = e.value.substr(0, length - 1);
			if (isNaN(e.value)) {
				e.value = e.value.substr(0, length - 2);
				if (isNaN(e.value)) {
					e.value = e.value.substr(0, length - 3);
				}
			}
			return alert('Only numbers are allowed');
		}
		e.focus();
		var quantity = 'quantity'+id;
		var e = document.getElementById(quantity);
		if (e.value < 0)
			e.value = Math.abs(e.value);
	}
	
	function enable_update(key) {
		var element = "wpsc_update_quantity"+key;
		var e = "";
		//alert(element);
		if (document.getElementById(element))
			var e = document.getElementById(element);
		e.value = 'true';
		//alert(e.value);
	}
	
// empty the cart using ajax when the form is submitted,  
function check_make_purchase_button(){
	toggle = jQuery('#noca_gateway').attr('checked');
	if(toggle == true){
		//jQuery('.make_purchase').hide();
		jQuery('#OCPsubmit').show();
	}else{
		jQuery('.make_purchase').show();	
		jQuery('#OCPsubmit').hide();		
	}
}	
// this function is for binding actions to events and rebinding them after they are replaced by AJAX
// these functions are bound to events on elements when the page is fully loaded.
jQuery(document).ready(function () {
  	
  	//this bit of code runs on the checkout page. If the checkbox is selected it copies the valus in the billing country and puts it in the shipping country form fields. 23.07.09
	//jQuery('.wpsc_shipping_forms').hide();
     jQuery("#shippingSameBilling").click(function(){
       				jQuery('.wpsc_shipping_forms').show();
        // If checked
        jQuery("#shippingSameBilling").livequery(function(){
        
        	if(jQuery(this).is(":checked")){    
	            var fname = jQuery("input[title='billingfirstname']").val();
				var lname = jQuery("input[title='billinglastname']").val();            
	            var addr = jQuery("textarea[title='billingaddress']").val();
				var city = jQuery("input[title='billingcity']").val(); 
	            var pcode = jQuery("input[title='billingpostcode']").val();
				var phone = jQuery("input[title='billingphone']").val(); 
	            var email = jQuery("input[title='billingfirstname']").val();
	            var state = jQuery("select[title='billingregion'] :selected").text();
	            var stateID = jQuery("select[title='billingregion'] :selected").val();
				var country = jQuery("select[title='billingcountry'] :selected").text();
				var countryID = jQuery("select[title='billingcountry'] :selected").val();             
				var	shipID;
				var shipName;
				jQuery("input[title='shippingfirstname']").val(fname);
				jQuery("input[title='shippinglastname']").val(lname); 
				jQuery("textarea[title='shippingaddress']").val(addr);
				jQuery("input[title='shippingcity']").val(city);
				jQuery("input[title='shippingpostcode']").val(pcode);				
				jQuery("input[title='shippingphone']").val(phone);				
				jQuery("input[title='shippingemail']").val(email);		
				jQuery("input[title='shippingstate']").val(stateID);														
				jQuery("input.shipping_country").val(countryID);
				jQuery("span.shipping_country_name").html(country);
				jQuery("input.shipping_region").val(countryID);
				//jQuery("span.shipping_region_name").html(state);
				jQuery("select#current_country").val(countryID);
				if(state == ''){
					state = jQuery("select.current_region :selected").text();
	          		stateID = jQuery("select.current_region :selected").val();
	          		if(state == ''){
						jQuery("select#region").hide();	
						shipName = jQuery('input.shipping_region').attr('name');
						shipID = jQuery('input.shipping_region').attr('id');
						jQuery('input.shipping_region').replaceWith('<input  class="shipping_region" type="text" value="'+state+'" name="'+shipName+'" id="'+shipName+'" />');	
						jQuery('span.shipping_region_name').replaceWith('<span class="shipping_region_name"></span>');	
					}else{
						jQuery("select#region").show();	
						jQuery("select#region :selected").html(state).attr('selected','selected');
						shipName = jQuery('input.shipping_region').attr('name');
						shipID = jQuery('input.shipping_region').attr('id');
						jQuery('input.shipping_region').replaceWith('<input type="hidden" value="'+stateID+'" name="'+shipName+'" id="'+shipName+'" class="shipping_region" />');	
						jQuery('input.shipping_region').append('<span class="shipping_region_name">'+state+'</span>');
//						jQuery('span.shipping_region_name').html(state);
					}
				}else{
					jQuery("select#region").show();	
					shipName = jQuery('input.shipping_region').attr('name');
					shipID = jQuery('input.shipping_region').attr('id');
					jQuery('input.shipping_region').replaceWith('<input type="hidden"  class="shipping_region" value="'+stateID+'" name="'+shipName+'" id="'+shipName+'" />');	
					jQuery('.shipping_region_name').replaceWith('<span class="shipping_region_name">'+state+'</span>');
					jQuery("select#region :selected").html(state).attr('selected','selected');
					jQuery("select[title='shippingregion']").val(stateID);
					//jQuery('span.shipping_region_name').html(state);
				}
				jQuery("select[title='shipping_country']").val(countryID);
				var html_form_id = jQuery("select[title='shipping_country']").attr('id');
				var form_id =  jQuery("select[title='shipping_country']").attr('name');
				if(form_id != null){
					form_id = form_id.replace("collected_data[", "");
					form_id = form_id.replace("]", "");
					form_id = form_id.replace("[0]", "");
					set_shipping_country(html_form_id, form_id)
					if(jQuery("select[title='billingcountry'] :selected").val() != jQuery("select[name='country'] :selected").val()){
						id = jQuery("select[name='country'] :selected").val();
						if(id == 'undefined'){
							jQuery("select[name='country']").val(countryID);
//							submit_change_country();
						}
					}
				}
				submit_change_country(true);
	
			}
         
            //otherwise, hide it
            //jQuery("#extra").hide("fast");
        });
 	 });
	// Submit the product form using AJAX
  jQuery("form.product_form").submit(function() {
    // we cannot submit a file through AJAX, so this needs to return true to submit the form normally if a file formfield is present
    file_upload_elements = jQuery.makeArray(jQuery('input[type=file]', jQuery(this)));
		if(file_upload_elements.length > 0) {
			return true;
		} else {
			jQuery("#dragdrop_spinner").css('display', 'block');
			form_values = jQuery(this).serialize();
			// Sometimes jQuery returns an object instead of null, using length tells us how many elements are in the object, which is more reliable than comparing the object to null
			if(jQuery('#fancy_notification').length == 0) {
				jQuery('div.wpsc_loading_animation',this).css('visibility', 'visible');
			}
			jQuery.post( 'index.php?ajax=true', form_values, function(returned_data) {
				eval(returned_data);
				jQuery('div.wpsc_loading_animation').css('visibility', 'hidden');
				
				if(jQuery('#fancy_notification') != null) {
					jQuery('#loading_animation').css("display", 'none');
					//jQuery('#fancy_notificationimage').css("display", 'none');
				}
				jQuery("#dragdrop_spinner").css('display', 'none');
			});
			wpsc_fancy_notification(this);
			return false;
		}
	});


	jQuery('a.wpsc_category_link, a.wpsc_category_image_link').click(function(){
    product_list_count = jQuery.makeArray(jQuery('ul.category-product-list'));
		if(product_list_count.length > 0) {
			jQuery('ul.category-product-list', jQuery(this).parent()).toggle();
			return false;
		}
	});
  
  //  this is for storing data with the product image, like the product ID, for things like dropshop and the the like.
	jQuery("form.product_form").livequery(function(){
			product_id = jQuery('input[name=product_id]',this).val();
			image_element_id = 'product_image_'+product_id;
			jQuery("#"+image_element_id).data("product_id", product_id);			
			parent_container = jQuery(this).parents('div.product_view_'+product_id);
			jQuery("div.item_no_image", parent_container).data("product_id", product_id);
	});
  //jQuery("form.product_form").trigger('load');
  
  // Toggle the additional description content  
  jQuery("a.additional_description_link").click(function() {
    parent_element = jQuery(this).parent('.additional_description_span');
    jQuery('.additional_description',parent_element).toggle();
		return false;
	});
	
	
  // update the price when the variations are altered.
  jQuery("div.wpsc_variation_forms .wpsc_select_variation").change(function() {
    parent_form = jQuery(this).parents("form.product_form");
    form_values =jQuery("input[name=product_id],div.wpsc_variation_forms .wpsc_select_variation",parent_form).serialize( );
		jQuery.post( 'index.php?update_product_price=true', form_values, function(returned_data) {
			eval(returned_data);
      if(product_id != null) {
        target_id = "product_price_"+product_id;
        second_target_id = "donation_price_"+product_id;
				buynow_id = "BB_BuyButtonForm"+product_id;
				
				//document.getElementById(target_id).firstChild.innerHTML = price;
				if(jQuery("input#"+target_id).attr('type') == 'text') {
					jQuery("input#"+target_id).val(numeric_price);
				} else {
					jQuery("#"+target_id+".pricedisplay").html(price);
				}
				jQuery("input#"+second_target_id).val(numeric_price);
			}
		});
		return false;
	});
	
	// Force variation price to update on page load
	// Fixes issue where some browsers (IE and FF) default to selecting the first
	// non-disabled menu item if the first variation is out of stock.
	if ( jQuery("div.wpsc_variation_forms .wpsc_select_variation").length > 0 ) {
		jQuery("div.wpsc_variation_forms .wpsc_select_variation:first").trigger("change");
	}
	
	// Object frame destroying code.
	jQuery("div.shopping_cart_container").livequery(function(){
		object_html = jQuery(this).html();
		window.parent.jQuery("div.shopping-cart-wrapper").html(object_html);
	});

	
	// Ajax cart loading code.
	jQuery("div.wpsc_cart_loading").livequery(function(){
		form_values = "ajax=true"
		jQuery.post( 'index.php?wpsc_ajax_action=get_cart', form_values, function(returned_data) {
			eval(returned_data);
		});
	});

	
	

	// Object frame destroying code.
	jQuery("form.wpsc_product_rating").livequery(function(){
    jQuery(this).rating();
	});




	jQuery("form.wpsc_empty_the_cart").livequery(function(){
		jQuery(this).submit(function() {
			form_values = "ajax=true&";
			form_values += jQuery(this).serialize();
			jQuery.post( 'index.php', form_values, function(returned_data) {
				eval(returned_data);
			});
			return false;
		});
	});

	jQuery("form.wpsc_empty_the_cart span.emptycart a").livequery(function(){
		jQuery(this).click(function() {
			parent_form = jQuery(this).parents("form.wpsc_empty_the_cart");
			form_values = "ajax=true&";
			form_values += jQuery(parent_form).serialize();
			jQuery.post( 'index.php', form_values, function(returned_data) {
				eval(returned_data);
			});
			return false;
		});
	}); 
	//Shipping bug fix by James Collins
	var radios = jQuery(".productcart input:radio[name=shipping_method]");
 	if (radios.length == 1) {
 		// If there is only 1 shipping quote available during checkout, automatically select it
 		jQuery(radios).click();
 	} else if (radios.length > 1) {
 		// There are multiple shipping quotes, simulate a click on the checked one
 		jQuery(".productcart input:radio[name=shipping_method]:checked").click();
 	}
});


// update the totals when shipping methods are changed.
function switchmethod(key,key1){
// 	total=document.getElementById("shopping_cart_total_price").value;
	form_values = "ajax=true&";
	form_values += "wpsc_ajax_action=update_shipping_price&";
	form_values += "key1="+key1+"&";
	form_values += "key="+key;
	jQuery.post( 'index.php', form_values, function(returned_data) {
		eval(returned_data);
	});
}

// submit the country forms.
function submit_change_country(ajax){
	if(!ajax && (document.forms.change_country)){
	  	document.forms.change_country.submit();
	}else{
		var country_code = jQuery('#current_country  :selected').val();
		var params = 'ajax=true&wpsc_ajax_actions=update_location&country='+country_code;
		var region_code = jQuery('#region :selected').val();
		if(typeof(region_code) != 'undefined'){
			params += '&region='+region_code;
		}
		
		jQuery.post( 'index.php', params, function(returned_data) {  });
		jQuery.post( 'index.php', 'wpsc_ajax_action=update_shipping_price', function(returned_data) { 
			eval(returned_data);
		});
			
	}
}

// submit the fancy notifications forms.
function wpsc_fancy_notification(parent_form){
  if(typeof(WPSC_SHOW_FANCY_NOTIFICATION) == 'undefined'){
    WPSC_SHOW_FANCY_NOTIFICATION = true;
	}
	if((WPSC_SHOW_FANCY_NOTIFICATION == true) && (jQuery('#fancy_notification') != null)){
    var options = {
      margin: 1 ,
      border: 1 ,
      padding: 1 ,
      scroll: 1 
		};

    form_button_id = jQuery(parent_form).attr('id') + "_submit_button";
    //console.log(form_button_id);
    //return;
    var container_offset = {};
    new_container_offset = jQuery('#products_page_container').offset();
    
		if(container_offset['left'] == null) {
      container_offset['left'] = new_container_offset.left;
      container_offset['top'] = new_container_offset.top;
    }    

    var button_offset = {};
    new_button_offset = jQuery('#'+form_button_id).offset()
    
    if(button_offset['left'] == null) {
      button_offset['left'] = new_button_offset.left;
      button_offset['top'] = new_button_offset.top;
    }
//     console.log((button_offset['left'] - container_offset['left'] + 10));   
    jQuery('#fancy_notification').css("left", (button_offset['left'] - container_offset['left'] + 10) + 'px');
    jQuery('#fancy_notification').css("top", ((button_offset['top']  - container_offset['top']) -60) + 'px');
       
    
    jQuery('#fancy_notification').css("display", 'block');
    jQuery('#loading_animation').css("display", 'block');
    jQuery('#fancy_notification_content').css("display", 'none');  
	}
}

function shopping_cart_collapser() {
  switch(jQuery("#sliding_cart").css("display")) {
    case 'none':
    jQuery("#sliding_cart").slideToggle("fast",function(){
			jQuery.post( 'index.php', "ajax=true&set_slider=true&state=1", function(returned_data) { });
      jQuery("#fancy_collapser").attr("src", (WPSC_URL+"/images/minus.png"));
		});
    break;
    
    default:
    jQuery("#sliding_cart").slideToggle("fast",function(){
			jQuery.post( 'index.php', "ajax=true&set_slider=true&state=0", function(returned_data) { });
      jQuery("#fancy_collapser").attr("src", (WPSC_URL+"/images/plus.png"));
		});
    break;
	}
  return false;
}
  
function set_billing_country(html_form_id, form_id){
  var billing_region = '';
  country = jQuery(("select[class=current_country]")).val();
  region = jQuery(("select[class=current_region]")).val();
  if(/[\d]{1,}/.test(region)) {
    billing_region = "&billing_region="+region;
	}
	
	form_values = "wpsc_ajax_action=change_tax&form_id="+form_id+"&billing_country="+country+billing_region;
	jQuery.post( 'index.php', form_values, function(returned_data) {
		eval(returned_data);
	});
  //ajax.post("index.php",changetaxntotal,("ajax=true&form_id="+form_id+"&billing_country="+country+billing_region));
}
function set_shipping_country(html_form_id, form_id){
  var shipping_region = '';
  country = jQuery(("div#"+html_form_id+" select[class=current_country]")).val();
  
  if(country == 'undefined'){
//      alert(country);
 	country =  jQuery("select[title='billingcountry']").val();
  }

  region = jQuery(("div#"+html_form_id+" select[class=current_region]")).val();  
  if(/[\d]{1,}/.test(region)) {
    shipping_region = "&shipping_region="+region;
	}
	
	form_values = "wpsc_ajax_action=change_tax&form_id="+form_id+"&shipping_country="+country+shipping_region;
	jQuery.post( 'index.php', form_values, function(returned_data) {
		eval(returned_data);
	});
  //ajax.post("index.php",changetaxntotal,("ajax=true&form_id="+form_id+"&billing_country="+country+billing_region));
}
