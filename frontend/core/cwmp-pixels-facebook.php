<?php
add_action("wp_head","cwmpAddTagManagerHead",20);
function cwmpAddTagManagerHead(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	foreach($result as $value){
		if($value->tipo=="GTM"){
			echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','".esc_html($value->pixel)."');</script>";
		}
	}	
}
add_action("wp_body_open","cwmpAddTagManagerBody",);
function cwmpAddTagManagerBody(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	foreach($result as $value){
		if($value->tipo=="GTM"){
			echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.esc_html($value->pixel).'" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
		}
	}	
}
function getBrowserInfo() {
    $browserInfo = array(
        'user_agent' => '',
        'browser' => 'Unknown',
        'browser_version' => 'Unknown',
        'os_platform' => 'Unknown',
        'pattern' => '',
        'device' => 'Desktop'
    );
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        return $browserInfo;
    }
    $u_agent = sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT']));
    $platform = 'Unknown';
    $bname = 'Unknown';
    $version = '';
    $deviceType = 'Desktop';
    if (preg_match('/mobile|android|kindle|silk|midp|phone|tablet|touch|pda|palm/i', $u_agent)) {
        $deviceType = 'Mobile';
    }
    if (preg_match('/ipad|playbook/i', $u_agent)) {
        $deviceType = 'Tablet';
    }
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }
    if (preg_match('/MSIE|Trident/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/OPR|Opera/i', $u_agent)) {
        $bname = 'Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/OPR|Opera/i', $u_agent)) {
        $bname = 'Safari';
        $ub = "Safari";
    } elseif (preg_match('/OPR|Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (preg_match_all($pattern, $u_agent, $matches)) {
        $i = count($matches['browser']);
        if ($i > 1 && strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1] ?? $matches['version'][0];
        }
    }
    if (empty($version)) {
        $version = "?";
    }
    return array(
        'user_agent' => $u_agent,
        'browser' => $bname,
        'browser_version' => $version,
        'os_platform' => $platform,
        'pattern' => $pattern,
        'device' => $deviceType
    );
}
function get_user_orders_total_by_email($email) {
    $args = array(
        'billing_email' => $email,
        'limit' => -1,
    );
    $orders = wc_get_orders($args);
    if (empty($orders) || !is_array($orders)) {
        return false;
    }
    $total = array_reduce($orders, function ($carry, $order) {
        $carry += (float)$order->get_total();
        return $carry;
    }, 0.0);
    return $total;
}
function get_user_orders_count_by_email($email) {
    $args = array(
        'billing_email' => $email,
        'limit' => -1,
    );
    $orders = wc_get_orders($args);

    return count($orders);
}
function get_user_orders_total($user_id) {
    $args = array(
        'customer_id' => $user_id,
        'limit' => -1,
    );
    $orders = wc_get_orders($args);
    if (empty($orders) || !is_array($orders)) {
        return false;
    }
    $total = array_reduce($orders, function ($carry, $order) {
        $carry += (float)$order->get_total();
        return $carry;
    }, 0.0);
    return $total;
}
function cwmpGetCurrentShippingLabel($valor){
	$shipping_packages = WC()->cart->get_shipping_packages();
	foreach( array_keys( $shipping_packages ) as $key ) {
		if( $shipping_for_package = WC()->session->get('shipping_for_package_'.$key) ) {
			if( isset($shipping_for_package['rates']) ) {
				foreach ( $shipping_for_package['rates'] as $rate_key => $rate ) {
					if($rate->id==$valor){
						$rate_id = $rate->id;
						$method_id = $rate->method_id;
						$instance_id = $rate->instance_id;
						$cost = $rate->label;
						$label = $rate->label;
						$taxes = $rate->taxes;
						return $label;
					}
				}
			}
		}
	}
}
function cwmpGetCurrentShippingTotal($valor){
	$shipping_packages = WC()->cart->get_shipping_packages();
	foreach( array_keys( $shipping_packages ) as $key ) {
		if( $shipping_for_package = WC()->session->get('shipping_for_package_'.$key) ) {
			if( isset($shipping_for_package['rates']) ) {
				foreach ( $shipping_for_package['rates'] as $rate_key => $rate ) {
					$currentShipping = WC()->session->get('chosen_shipping_methods');
					if($rate->id==$valor){
						$rate_id = $rate->id;
						$method_id = $rate->method_id;
						$instance_id = $rate->instance_id;
						$cost = $rate->cost;
						$label = $rate->label;
						$taxes = $rate->taxes;
						return $cost;
					}
				}
			}
		}
	}
}
function cwmpCreateDataLayer($event,$brownser,$currentUser,$currentProduct,$currentCart,$shipping,$payment,$order,$order_id){
global $post;
$dataLayer = array();
$dataLayer['event'] = $event;
$dataLayer['ecommerce']['currencyCode'] = get_woocommerce_currency();
if($brownser==true){
$ua=getBrowserInfo();
if ( is_user_logged_in() ) { $dataLayer['visitorLoginState'] = "logged-in"; }else{ $dataLayer['visitorLoginState'] = "logged-out"; }
$dataLayer['visitorIP'] = $_SERVER['REMOTE_ADDR'];
$dataLayer['pageTitle'] = get_the_title();
$dataLayer['pagePostType'] = get_post_type();
$dataLayer['browserName'] = $ua['browser'];
$dataLayer['browserVersion'] = $ua['browser_version'];
$dataLayer['osName'] = $ua['os_platform'];
$dataLayer['deviceType'] = $ua['device'];
}
if($currentUser==true){
if(is_user_logged_in()){
$current_user = wp_get_current_user();
$dataLayer['customerTotalOrders'] = wc_get_customer_order_count($current_user->ID);
$dataLayer['customerTotalOrderValue'] = get_user_orders_total($current_user->ID);
$dataLayer['customerFirstName'] = $current_user->user_firstname;
$dataLayer['customerLastName'] = $current_user->user_lastname;
$dataLayer['customerBillingFirstName'] = $current_user->billing_first_name;
$dataLayer['customerBillingLastName'] = $current_user->billing_last_name;
$dataLayer['customerBillingCompany'] = $current_user->billing_company;
$dataLayer['customerBillingAddress1'] = $current_user->billing_address_1;
$dataLayer['customerBillingAddress2'] = $current_user->billing_address_2;
$dataLayer['customerBillingCity'] = $current_user->billing_city;
$dataLayer['customerBillingState'] = $current_user->billing_state;
$dataLayer['customerBillingPostcode'] = $current_user->billing_postcode;
$dataLayer['customerBillingCountry'] = $current_user->billing_country;
$dataLayer['customerBillingEmail'] = $current_user->billing_email;
$dataLayer['customerBillingEmailHash'] = hash('sha256',$current_user->billing_email);
$dataLayer['customerBillingPhone'] = $current_user->billing_phone;
$dataLayer['customerShippingFirstName'] = $current_user->shipping_first_name;
$dataLayer['customerShippingLastName'] = $current_user->shipping_last_name;
$dataLayer['customerShippingCompany'] = $current_user->shipping_company;
$dataLayer['customerShippingAddress1'] = $current_user->shipping_address_1;
$dataLayer['customerShippingAddress2'] = $current_user->shipping_address_2;
$dataLayer['customerShippingCity'] = $current_user->shipping_city;
$dataLayer['customerShippingState'] = $current_user->shipping_state;
$dataLayer['customerShippingPostcode'] = $current_user->shipping_postcode;
$dataLayer['customerShippingCountry'] = $current_user->shipping_country;
}else{
if(isset($_POST['billing_first_name'])){ $dataLayer['customerFirstName'] = $_POST['billing_first_name']; }
if(isset($_POST['billing_last_name'])){ $dataLayer['customerLastName'] = $_POST['billing_last_name']; }
if(isset($_POST['billing_first_name'])){ $dataLayer['customerBillingFirstName'] = $_POST['billing_first_name']; }
if(isset($_POST['billing_last_name'])){ $dataLayer['customerBillingLastName'] = $_POST['billing_last_name']; }
if(isset($_POST['billing_company'])){ $dataLayer['customerBillingCompany'] = $_POST['billing_company']; }
if(isset($_POST['billing_address_1'])){ $dataLayer['customerBillingAddress1'] = $_POST['billing_address_1']; }
if(isset($_POST['billing_address_2'])){ $dataLayer['customerBillingAddress2'] = $_POST['billing_address_2']; }
if(isset($_POST['billing_city'])){ $dataLayer['customerBillingCity'] = $_POST['billing_city']; }
if(isset($_POST['billing_state'])){ $dataLayer['customerBillingState'] = $_POST['billing_state']; }
if(isset($_POST['billing_postcode'])){ $dataLayer['customerBillingPostcode'] = $_POST['billing_postcode']; }
if(isset($_POST['billing_country'])){ $dataLayer['customerBillingCountry'] = $_POST['billing_country']; }
if(isset($_POST['billing_email'])){ $dataLayer['customerBillingEmail'] = $_POST['billing_email']; }
if(isset($_POST['billing_email'])){ $dataLayer['customerBillingEmailHash'] = hash('sha256',$_POST['billing_email']); }
if(isset($_POST['billing_phone'])){ $dataLayer['customerBillingPhone'] = $_POST['billing_phone']; }

}
}
if($currentProduct==true){
$product = wc_get_product( $post->ID );
$dataLayer['ecomm_pagetype'] = "product";
$dataLayer['ecommerce']['product']['detail']['id'] = $post->ID;
$dataLayer['ecommerce']['product']['detail']['name'] = $product->get_title();
$dataLayer['ecommerce']['product']['detail']['sku'] = $product->get_sku();
$product_cats = wp_get_post_terms( $post->ID, 'product_cat', array('fields' => 'names') );
$u=0;
foreach($product_cats as $category){
if($u==0){
$dataLayer['ecommerce']['product']['detail']['category'] = $category;
}else{
$dataLayer['ecommerce']['product']['detail']['category'.$u] = $category;
}
$u++;
}
$dataLayer['ecommerce']['product']['detail']['price'] = (float)$product->get_price();
$dataLayer['ecommerce']['product']['detail']['stocklevel'] = (float)$product->get_stock_quantity();
}
if($currentCart==true){
$i=0;
foreach(WC()->cart->applied_coupons AS $coupon){
$dataLayer['ecommerce']['cart']['totals']['applied_coupons'][$i] = $coupon; $i++;
}
$dataLayer['ecommerce']['cart']['totals']['discount_total'] = WC()->cart->get_cart_discount_total();
$dataLayer['ecommerce']['cart']['totals']['subtotal'] = (float)WC()->cart->subtotal;
$dataLayer['ecommerce']['cart']['totals']['total'] = (float)WC()->cart->cart_contents_total;
$dataLayer['ecommerce']['cart']['totals']['count'] = (float)WC()->cart->get_cart_contents_count();
$i=0;
foreach ( WC()->cart->get_cart() as $cart_item ) {
$dataLayer['ecommerce']['cart']['contentId'][$i] = $cart_item['product_id']; $i++;
}
$i=0;
foreach ( WC()->cart->get_cart() as $cart_item ) {
$product = $cart_item['data'];
$dataLayer['ecommerce']['cart']['items'][$i]['id'] = $cart_item['product_id'];
$dataLayer['ecommerce']['cart']['items'][$i]['internal_id'] = $cart_item['variation_id'];
$dataLayer['ecommerce']['cart']['items'][$i]['name'] = $cart_item['data']->get_title();
$dataLayer['ecommerce']['cart']['items'][$i]['sku'] = $product->get_sku();
$product_cats = wp_get_post_terms( $cart_item['product_id'], 'product_cat', array('fields' => 'names') );
$u=0;
foreach($product_cats as $category){
$dataLayer['ecommerce']['cart']['items'][$i]['category'][$u] = $category; $u++;
}
$dataLayer['ecommerce']['cart']['items'][$i]['price'] = (float)$cart_item['data']->get_price();
$dataLayer['ecommerce']['cart']['items'][$i]['stocklevel'] = (float)$product->get_stock_quantity();
$dataLayer['ecommerce']['cart']['items'][$i]['quantity'] = (float)$cart_item['quantity'];
$i++;
}
}
if($shipping==true){
$dataLayer['ecommerce']['shipping']['type'] = cwmpGetCurrentShippingLabel($_POST['method_shipping']);
$dataLayer['ecommerce']['shipping']['value'] = cwmpGetCurrentShippingTotal($_POST['method_shipping']);
}
if($payment==true){
$dataLayer['ecommerce']['payment']['type'] = cwmpGetNamePayment($_POST['payment_method']);
}
if($order==true){
global $product;
$order = wc_get_order($order_id);
$dataLayer['customerTotalOrders'] = get_user_orders_count_by_email($order->get_billing_email());
$dataLayer['customerTotalOrderValue'] = get_user_orders_total_by_email($order->get_billing_email());
$dataLayer['customerFirstName'] = $order->get_billing_first_name();
$dataLayer['customerLastName'] = $order->get_billing_last_name();
$dataLayer['customerBillingFirstName'] = $order->get_billing_first_name();
$dataLayer['customerBillingLastName'] = $order->get_billing_last_name();
$dataLayer['customerBillingCompany'] = $order->get_billing_company();
$dataLayer['customerBillingAddress1'] = $order->get_billing_address_1();
$dataLayer['customerBillingAddress2'] = $order->get_billing_address_2();
$dataLayer['customerBillingCity'] = $order->get_billing_city();
$dataLayer['customerBillingState'] = $order->get_billing_state();
$dataLayer['customerBillingPostcode'] = $order->get_billing_postcode();
$dataLayer['customerBillingCountry'] = $order->get_billing_country();
$dataLayer['customerBillingEmail'] = $order->get_billing_email();
$dataLayer['customerBillingEmailHash'] = hash('sha256',$order->get_billing_email());
$dataLayer['customerBillingPhone'] = $order->get_billing_phone();
$dataLayer['customerShippingFirstName'] = $order->get_shipping_first_name();
$dataLayer['customerShippingLastName'] = $order->get_shipping_last_name();
$dataLayer['customerShippingCompany'] = $order->get_shipping_company();
$dataLayer['customerShippingAddress1'] = $order->get_shipping_address_1();
$dataLayer['customerShippingAddress2'] = $order->get_shipping_address_2();
$dataLayer['customerShippingCity'] = $order->get_shipping_city();
$dataLayer['customerShippingState'] = $order->get_shipping_state();
$dataLayer['customerShippingPostcode'] = $order->get_shipping_postcode();
$dataLayer['customerShippingCountry'] = $order->get_shipping_country();
$dataLayer['ecommerce']['checkout']['id']=$order->get_id();
$coupons = $order->get_coupon_codes();
$i=0;
foreach($coupons AS $coupon){
$dataLayer['ecommerce']['checkout']['totals']['applied_coupons'][$i] = $coupon; $i++;
}
$dataLayer['ecommerce']['checkout']['totals']['discount_total'] = $order->get_discount_total();
$dataLayer['ecommerce']['checkout']['totals']['subtotal'] = $order->get_subtotal();
$dataLayer['ecommerce']['checkout']['totals']['total'] = $order->get_total();
$dataLayer['ecommerce']['checkout']['totals']['count'] = $order->get_item_count();
$i=0;
foreach ($order->get_items() as $item_key => $item ){
$product = $item->get_product();
$dataLayer['ecommerce']['checkout']['contentId'][$i] = $item->get_product_id();
}
$i=0;
foreach ($order->get_items() as $item_key => $item ){
$product = $item->get_product();
$dataLayer['ecommerce']['checkout']['items'][$i]['id'] = $item->get_product_id();
$dataLayer['ecommerce']['checkout']['items'][$i]['internal_id'] = $item->get_variation_id();
$dataLayer['ecommerce']['checkout']['items'][$i]['name'] = $item->get_name();
$dataLayer['ecommerce']['checkout']['items'][$i]['sku'] = $product->get_sku();
$product_cats = wp_get_post_terms( $item->get_product_id(), 'product_cat', array('fields' => 'names') );
$u=0;
foreach($product_cats as $category){
$dataLayer['ecommerce']['checkout']['items'][$i]['category'][$u] = $category; $u++;
}
$dataLayer['ecommerce']['checkout']['items'][$i]['price'] = $item->get_total();
$dataLayer['ecommerce']['checkout']['items'][$i]['stocklevel'] = (float)$product->get_stock_quantity();
$dataLayer['ecommerce']['checkout']['items'][$i]['quantity'] = $item->get_quantity();
$i++;
}
$dataLayer['ecommerce']['checkout']['shipping']['type'] = $order->get_shipping_method();
$dataLayer['ecommerce']['checkout']['shipping']['value'] = $order->get_shipping_total();
$dataLayer['ecommerce']['checkout']['payment']['type'] = cwmpGetNamePayment($order->get_payment_method());
}
return $dataLayer;
}
add_action("wp_head","cwmpAddTagManagerDataLayer",30);
function cwmpAddTagManagerDataLayer(){
	global $wp;
	if(is_product()){
		$dataLayer = cwmpCreateDataLayer('view_item',true,true,true,true,false,false,false,'');
	}elseif(is_cart()){
		$dataLayer = cwmpCreateDataLayer('view_cart',true,true,false,true,false,false,false,'');
	}elseif(is_checkout() AND !isset($wp->query_vars['order-received'])){
		if(get_option('cwmp_activate_login')=="S"){
			if(is_user_logged_in()){
			$dataLayer = cwmpCreateDataLayer('begin_checkout',true,true,false,true,false,false,false,'');
			}else{}
		}else{
			$dataLayer = cwmpCreateDataLayer('begin_checkout',true,true,false,true,false,false,false,'');
		}
	}else{
		$dataLayer = cwmpCreateDataLayer('',true,true,false,true,false,false,false,'');
	}
	if(isset($dataLayer)){
	echo '<script type="text/javascript">
		var dataLayer = dataLayer || [];
		var dataLayerContent = '.wp_json_encode($dataLayer, true).';
		dataLayer.push( dataLayerContent );
	</script>';
	}
}
if(get_option('woocommerce_enable_ajax_add_to_cart')=="yes" AND get_option("woocommerce_cart_redirect_after_add")=="yes"){
	add_action('woocommerce_add_to_cart', 'set_cart_addition_cookie', 10, 6);
}
if(get_option('woocommerce_cart_redirect_after_add')=="yes" AND get_option("woocommerce_enable_ajax_add_to_cart")=="no"){
	add_action('woocommerce_add_to_cart', 'set_cart_addition_cookie', 10, 6);
}
if(get_option('woocommerce_cart_redirect_after_add')=="no" AND get_option("woocommerce_enable_ajax_add_to_cart")=="yes"){
	add_action( 'woocommerce_add_to_cart', 'cwmpAddToCart');
}
if(get_option('woocommerce_cart_redirect_after_add')=="no" AND get_option("woocommerce_enable_ajax_add_to_cart")=="no"){
	add_action( 'woocommerce_add_to_cart', 'cwmpAddToCart');
}
function cwmpAddToCart(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	foreach($result as $value){
		if($value->tipo=="GTM"){
			$dataLayer = cwmpCreateDataLayer('add_to_cart',true,true,false,true,false,false,false,'');
			echo '<script type="text/javascript">
				var dataLayer = dataLayer || [];
				var dataLayerContent = '.wp_json_encode($dataLayer, true).';
				dataLayer.push( dataLayerContent );
			</script>';
			echo  "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','".esc_html($value->pixel)."');</script>";
		}
	}
}
function set_cart_addition_cookie($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart) {
    setcookie('new_product_added', '1', time() + 3600, '/');
}
add_action('wp_footer', 'cwmpAddToCartFooter');
function cwmpAddToCartFooter() {
    if (is_cart() && isset($_COOKIE['new_product_added'])) {
		cwmpAddToCart();
    }
}
add_action( 'wp_ajax_cwmpAddEventAddRate', 'cwmpAddEventAddRate' );
add_action( 'wp_ajax_nopriv_cwmpAddEventAddRate', 'cwmpAddEventAddRate' );
function cwmpAddEventAddRate(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	foreach($result as $value){
		if($value->tipo=="GTM"){
			$dataLayer = cwmpCreateDataLayer('add_shipping_info',true,true,false,true,true,false,false,'');
			echo '<script type="text/javascript">
				var dataLayer = dataLayer || [];
				var dataLayerContent = '.wp_json_encode($dataLayer, true).';
				dataLayer.push( dataLayerContent );
			</script>';
			echo  "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','".esc_html($value->pixel)."');</script>";
			
		}
	}
}
add_action( 'wp_ajax_cwmpAddEventPaymentInfo', 'cwmpAddEventPaymentInfo' );
add_action( 'wp_ajax_nopriv_cwmpAddEventPaymentInfo', 'cwmpAddEventPaymentInfo' );
function cwmpAddEventPaymentInfo(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	foreach($result as $value){
		if($value->tipo=="GTM"){
			$dataLayer = cwmpCreateDataLayer('add_payment_info',true,true,false,true,false,true,false,'');
			echo '<script type="text/javascript">
				var dataLayer = dataLayer || [];
				var dataLayerContent = '.wp_json_encode($dataLayer, true).';
				dataLayer.push( dataLayerContent );
			</script>';
			echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','".esc_html($value->pixel)."');</script>";
			
		}
	}
}

add_action( 'wp_head', 'cwmpAddEventPurchase',50);
function cwmpAddEventPurchase() {
	if (get_option('cwmp_activate_thankyou_page') == "S") {
		if (isset($_GET['cwmp_order'])) {
			global $wp, $wpdb;
			$cwmp_order = sanitize_text_field(wp_unslash($_GET['cwmp_order']));
			$order_id = base64_decode($cwmp_order);
			$order = wc_get_order($order_id);
			$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
			$get_send_order = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}cwmp_pixel_thank WHERE pedido LIKE %s",
					$order->get_id()
				)
			);
			if (count($get_send_order) == 0) {
				foreach ($result as $value) {
					if ($value->tipo == "GTM") {
						$dataLayer = cwmpCreateDataLayer('purchase', true, false, false, false, false, false, true, $order->get_id());
						echo '<script type="text/javascript">
							var dataLayer = dataLayer || [];
							var dataLayerContent = ' . wp_json_encode($dataLayer, true) . ';
							dataLayer.push( dataLayerContent );
						</script>';
						echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
						new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
						j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
						'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
						})(window,document,'script','dataLayer','" . esc_html($value->pixel) . "');</script>";
					}
				}
				cwmp_register_purchase($order->get_id(), cwmp_get_status_order($order->get_id()));
			}
		}
	} else {
		if (is_wc_endpoint_url('order-received')) {
			global $wp, $wpdb;
			if (isset($_GET['key'])) {
				$key = sanitize_text_field(wp_unslash($_GET['key']));
				$current_order_id = wc_get_order_id_by_order_key($key);
				$order = wc_get_order($current_order_id);
				$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
				$get_send_order = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}cwmp_pixel_thank WHERE pedido LIKE %s",
						$order->get_id()
					)
				);
				if (count($get_send_order) == 0) {
					foreach ($result as $value) {
						if ($value->tipo == "GTM") {
							
							$dataLayer = cwmpCreateDataLayer('purchase', true, false, false, false, false, false, true, $order->get_id());

							echo '<script type="text/javascript">
								var dataLayer = dataLayer || [];
								var dataLayerContent = ' . wp_json_encode($dataLayer, true) . ';
								dataLayer.push( dataLayerContent );
							</script>';
							echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
							new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
							j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
							'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
							})(window,document,'script','dataLayer','" . esc_html($value->pixel) . "');</script>";
						}
					}
					cwmp_register_purchase($order->get_id(), cwmp_get_status_order($order->get_id()));
				}
			}
		}
	}
}