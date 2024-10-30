<?php

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
function cwmpCheckHpos(){
	$hpos = get_option("woocommerce_custom_orders_table_enabled");
	if($hpos=="yes"){
		return true;
	}else{
		return false;
	}
}

add_action( 'add_meta_boxes', 'cwmp_add_meta_boxes' );
function cwmp_add_meta_boxes(){
	if(cwmpCheckHpos()==true){
		$screen = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
			? wc_get_page_screen_id( 'shop-order' )
			: 'shop_order';

		add_meta_box(
			'mv_other_fields',
			__('Rastreio','woocommerce'),
			'cwmp_add_other_fields_for_packaging',
			$screen,
			'side',
			'high'
		);
	}else{
		add_meta_box( 'mv_other_fields', __('Rastreio','woocommerce'), 'cwmp_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
	}
}

function cwmp_add_other_fields_for_packaging(){
    global $wp;
    global $wpdb;
    global $post;
	global $table_prefix;
	if(cwmpCheckHpos()==true){
		$order = wc_get_order( $_GET['id'] );
	}else{
		$order = wc_get_order( $post->ID );
	}
    echo '<input type="hidden" id="cwmp_pedido_id" name="cwmp_pedido_id" value="' . $order->get_id(). '">';
	$cwmp_codigo_transportadora = '';
	if ( $order && is_callable( [ $order, 'get_meta' ] ) ) {
		if(cwmpCheckHpos()==true){
			$cwmp_codigo_transportadora = $order->get_meta( '_cwmp_codigo_transportadora_slug', true, 'view' );
		}else{
			$cwmp_codigo_transportadora = get_post_meta( $post->ID, '_cwmp_codigo_transportadora_slug', true );
		}
		if ( empty( $cwmp_codigo_transportadora ) ) {
			$cwmp_codigo_transportadora = '';
		}
	}

	$cwmp_codigo_rastreio = '';
	if ( $order && is_callable( [ $order, 'get_meta' ] ) ) {
		if(cwmpCheckHpos()==true){
			$cwmp_codigo_rastreio = $order->get_meta( '_cwmp_codigo_rastreio_slug', true, 'view' );
		}else{
			$cwmp_codigo_rastreio = get_post_meta( $post->ID, '_cwmp_codigo_rastreio_slug', true );
		}
		if ( empty( $cwmp_codigo_rastreio ) ) {
			$cwmp_codigo_rastreio = '';
		}
	}
	echo '<input type="hidden" name="cwmp_other_meta_field_nonce" value="' . esc_html(wp_create_nonce()) . '">';
	echo '<p style="">';
    echo '<select style="width:100%;" name="cwmp_codigo_transportadora" id="cwmp_codigo_transportadora">';
	$get_campanha = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}cwmp_transportadoras"
		)
	);
	foreach($get_campanha as $campanha){
		echo '<option value='.esc_html($campanha->id).'';
		if($campanha->id==$cwmp_codigo_transportadora){
			echo ' selected="selected" ';
		}
		echo '>';
		echo esc_html($campanha->transportadora);
		echo '</option>';
	}
	echo '</select>';
	echo '</p>';
    echo '<p style="">';
    echo '<input type="text" style="width:100%;" id="cwmp_codigo_rastreio" name="cwmp_codigo_rastreio" placeholder="Código de Rastreio" value="' . esc_html($cwmp_codigo_rastreio) . '">';
	echo '</p>';
    echo '<p style="">';
	echo '<button class="button button-primary" id="cwmp_button_add_rastreio" style="width:100% !important;"> Adicionar </button>';
	echo '</p>';
}

function cwmp_save_wc_order_other_fields() {
    $pedido_id = isset($_POST['pedido']) ? absint($_POST['pedido']) : null;
    $track = isset($_POST['track']) ? sanitize_text_field(wp_unslash($_POST['track'])) : '';
    $transportadora = isset($_POST['transportadora']) ? sanitize_text_field(wp_unslash($_POST['transportadora'])) : '';
    if ($pedido_id) {
        $order = wc_get_order($pedido_id);
        if ($order) {
            $codigo_transportadora = get_post_meta($order->get_id(), '_cwmp_codigo_transportadora', true);
            if ($codigo_transportadora !== $track && $track !== "") {
				echo "a";
				if(cwmpCheckHpos()==true){
					$order->update_meta_data('_cwmp_codigo_transportadora_slug', $transportadora);
					$order->update_meta_data('_cwmp_codigo_rastreio_slug', $track);
					$order->save();
					$order->update_status('wc-pedido-enviado');
				}else{
					update_post_meta($order->get_id(), '_cwmp_codigo_transportadora_slug', $transportadora);
					update_post_meta($order->get_id(), '_cwmp_codigo_rastreio_slug', $track);
					$order->update_status( 'wc-pedido-enviado' );
				}
				
            }
        }
    }
    wp_die();
}
add_action('wp_ajax_cwmp_save_wc_order_other_fields', 'cwmp_save_wc_order_other_fields');
add_action('wp_ajax_nopriv_cwmp_save_wc_order_other_fields', 'cwmp_save_wc_order_other_fields');
if(get_option('cwmp_activate_whatsapp')=="S"){
	add_action( 'add_meta_boxes', 'cwmp_add_meta_whats_manual' );
	function cwmp_add_meta_whats_manual(){
		if(cwmpCheckHpos()==true){
			$screen = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
				? wc_get_page_screen_id( 'shop-order' )
				: 'shop_order';

			add_meta_box(
				'mv_whats_manual',
				__('WhatsApp Manual','woocommerce'),
				'cwmp_add_other_whats_manual',
				$screen,
				'side',
				'high'
			);
		}else{
			add_meta_box( 'mv_whats_manual', __('WhatsApp Manual','woocommerce'), 'cwmp_add_other_whats_manual', 'shop_order', 'side', 'core' );
		}
	}
	function cwmp_add_other_whats_manual(){
		global $post;
		global $wpdb;
		global $table_prefix;
		if(cwmpCheckHpos()==true){
			$postId = $_GET['id'];
		}else{
			$postId = $post->ID;
		}
		if ( empty( $postId ) ) {
			$postId = '';
		}
		$html = "";
		$html .= '<p>';
		$html .= '<input type="hidden" class="cwmp_whats_manual_send_pedido" value="'.$postId.'" />';
		$html .= '<select style="width:100%;" id="cwmp_whats_manual_send_template">';
		$get_campanha = $wpdb->get_results(
		"SELECT * FROM ".$table_prefix."cwmp_template_msgs GROUP BY status"
		);
		foreach($get_campanha as $campanha){
			$wc_gateways      = new WC_Payment_Gateways();
			$payment_gateways = $wc_gateways->payment_gateways();
			foreach( $payment_gateways as $gateway_id => $gateway ){
				if($gateway->enabled=="yes"){
					if($campanha->metodo==$gateway->id){
						$gateway_html2 = $gateway->id;
						$gateway_html = $gateway->title;
					}
				}
			}
			$order_statuses = wc_get_order_statuses();
			$statuse_html = "";
			foreach($order_statuses as $key => $status){
				if(str_replace('_', '-', $campanha->status)==$key){
					$statuse_html = $status."";
				}
			}
			$html .= '<option value="'.$gateway_html2.' | '.$campanha->status.'">'.$gateway_html.' | '.$statuse_html.'</option>';
		}
		$html .= '</select>';
		$html .= '</p>';
		$html .= '<p style="">';
		$html .= '<button class="button button-primary" id="cwmp_send_whatsapp_manual" style="width:100% !important;"> Enviar </button>';
		$html .= '</p>';
		echo $html;
	}
	add_action('wp_ajax_cwmp_add_other_whats_manual_send', 'cwmp_add_other_whats_manual_send');
	add_action('wp_ajax_nopriv_cwmp_add_other_whats_manual_send', 'cwmp_add_other_whats_manual_send');
	function cwmp_add_other_whats_manual_send() {
		global $wpdb;
		$get_template = explode(" | ", $_POST['pedido']);
		$order = wc_get_order($_POST['template']);
		$query = "SELECT * FROM " . $wpdb->prefix . "cwmp_template_msgs WHERE metodo = '". $get_template['0'] ."' AND status = '". $get_template['1'] ."' ORDER BY seq ASC";

		$template_whatsapp = $wpdb->get_results($query);
		$numero = cwmp_trata_numero($order->get_billing_phone());
		print_r($template_whatsapp);
		if(isset($template_whatsapp[0])){
			foreach($template_whatsapp as $key => $value){
				$string_wpp_content = str_replace("]", " val='" .$order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$value->conteudo))));
				$string_wpp_content_renovada = do_shortcode($string_wpp_content);
				if(get_option('cwmp_activate_whatsapp')=="S"){
					cwmp_send_whatsapp($numero,$string_wpp_content_renovada); 
				}
			}
		}
		die();
	}
}