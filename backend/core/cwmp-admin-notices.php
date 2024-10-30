<?php
function cwmp_extensions_check_checkout (){
	if ( is_plugin_active( 'checkout-field-editor-and-manager-for-woocommerce/start.php' ) ) {
		echo "<div class='notice  error'>aa<p>
		".esc_html__( 'The <strong>Checkout Field Editor and Manager for WooCommerce</strong> plugin is incompatible with Checkout Mestres WP.', 'checkout-mestres-wp')."
		</p></div>";
	}
	if ( is_plugin_active( 'flexible-checkout-fields/flexible-checkout-fields.php' ) ) {
		echo "<div class='notice  error'><p>
		".esc_html__( 'The <strong>Flexible Checkout Fields for WooCommerce</strong> plugin is incompatible with Checkout Mestres WP.', 'checkout-mestres-wp')."
		</p></div>";
	}
	if ( is_plugin_active( 'woo-checkout-field-editor-pro/checkout-form-designer.php' ) ) {
		echo "<div class='notice error'><p>
		".esc_html__( 'The <strong>Checkout Field Editor (Checkout Manager) for WooCommerce</strong> plugin is incompatible with Checkout Mestres WP.', 'checkout-mestres-wp')."
		</p></div>";
	}
	if ( get_option('woocommerce_ship_to_destination')!="billing_only" ) {
		echo "<div class='notice  error'><p>
		".esc_html__( 'You must activate the option <strong>Force delivery to the customer`s billing address</strong> for <strong>Checkout Mestres WP</strong> to work correctly. <a href="/wp-admin/admin.php?page=wc-settings&tab=shipping&section=options">Click here</a>', 'checkout-mestres-wp')."
		</p></div>";
	}
	if(get_option('cwmp_license_active')!=true){
		echo "
		<div class='notice cwmp-notice' style='background:#ff6243;'>
			<p>
			<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
			<mask id='mask0_528_3' style='mask-type:luminance' maskUnits='userSpaceOnUse' x='2' y='2' width='20' height='20'>
			<path fill-rule='evenodd' clip-rule='evenodd' d='M3 7.5H21L20 21H4L3 7.5Z' fill='white' stroke='white' stroke-width='2' stroke-linejoin='round'/>
			<path d='M8 9.5V3H16V9.5' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
			<path d='M8 17H16' stroke='black' stroke-width='2' stroke-linecap='round'/>
			</mask>
			<g mask='url(#mask0_528_3)'>
			<path d='M0 0H24V24H0V0Z' fill='white'/>
			</g>
			</svg>
			
			".esc_html__( 'Get the pro version of Checkout Mestres WP now.', 'checkout-mestres-wp')."</p><a href='https://www.mestresdowp.com.br/produto/chechout-mestres-do-wp/' target='blank' style='color:#ff6243;'>".esc_html__( 'Buy right now', 'checkout-mestres-wp')."</a>
		</div>
		";
	}
	if ( !cwmpVerifyCron( 'cwmp_cron_events' ) ) {
		echo "<div class='notice error'><p>
		".esc_html__( 'O <strong>WP Cron</strong> não está funcionando corretamente em seu WordPress, desative e ativa o plugin <strong>Checkout Mestres do WP</strong> para solucionar.', 'checkout-mestres-wp')."
		</p></div>";
	}
	if(get_option('cwmp_view_docs')!="S"){
	echo "
	<div class='notice cwmp-notice' style='background:#0E93D2;'>
	<p><svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M14.7274 6.727H14.0004V0H4.91044C4.00544 0 3.27344 0.732 3.27344 1.636V22.364C3.27344 23.268 4.00544 24 4.90944 24H19.0914C19.9954 24 20.7274 23.268 20.7274 22.364V6.727H14.7274ZM14.1824 17.182H7.09044V15.818H14.1804V17.182H14.1824ZM16.9094 13.909H7.09144V12.545H16.9094V13.909ZM16.9094 10.636H7.09144V9.273H16.9094V10.636ZM14.7274 6H20.7274L14.7274 0V6Z' fill='white'/></svg>	
	".esc_html__( 'Veja a documentação do Checkout Mestres do WP', 'checkout-mestres-wp')."</p><a href='https://docs.mestresdowp.com.br' target='blank' style='color:#0E93D2;'>".esc_html__( 'Ver documentação', 'checkout-mestres-wp')."</a>
	</div>
	";
	}
	if(get_option('cwmp_license_active')==true){
		if(get_option('cwmp_license_expired')=="0000-00-00"){}else{
			$dataObj = new DateTime(get_option('cwmp_license_expired'));
			$dataAtualObj = new DateTime($dataAtual);
			$dataResultado = $dataObj->format('d/m/Y');
			$diferenca = $dataAtualObj->diff($dataObj);
			$diasFaltantes = $diferenca->days;
			$html .= $dataResultado;
			if ($diasFaltantes <= 3) {
				echo "<div class='notice cwmp-notice' style='background:#0E93D2;'>";
				echo "<p>Sua licença vence em ".esc_html($dataResultado)."</p>";
				if(get_option('cwmp_license_tipo')=="309480" && get_option('cwmp_license_tipo')=="309481" && get_option('cwmp_license_tipo')=="309482"){
					echo '<a href="www.mestresdowp.com.br/finalizar-compra/?add-to-cart='.esc_html(get_option('cwmp_license_tipo')).'"  target="blank" style="color:#0E93D2;">Renovar plano</a>';
				}
				if(get_option('cwmp_license_tipo')=="309632"){
					echo '<a href="https://www.mestresdowp.com.br/produto/chechout-mestres-do-wp/" target="blank" style="color:#0E93D2;">Comprar plano</a>';
				}
				echo "</div>";
			}
		}
	}
	
	
	
}
add_action('admin_notices', 'cwmp_extensions_check_checkout',999);
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets',9999999999);
function my_custom_dashboard_widgets() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget('custom_help_widget', 'Mestres do WP', 'custom_dashboard_help','side','high');
}
function custom_dashboard_help() {
	$cwmp_banners_arquivo = 'https://www.mestresdowp.com.br/checkout/banners.php';
	$cwmp_banner_xml = wp_remote_get($cwmp_banners_arquivo, array(
		'method' => 'POST'
	));
	$cwmp_banner_xml = json_decode(wp_remote_retrieve_body($cwmp_banner_xml));
	foreach ($cwmp_banner_xml as $cwmp_banner) {
		echo "<a href='".esc_url($cwmp_banner->url)."' target='blank'><img src='".esc_url($cwmp_banner->imagem)."' width='100%' /></a>";
	}
}
function cwmpVerifyCron( $nome_acao ) {
    $eventos_agendados = _get_cron_array();
    if ( empty( $eventos_agendados ) ) {
        return false;
    }
    foreach ( $eventos_agendados as $timestamp => $cronhooks ) {
        if ( isset( $cronhooks[ $nome_acao ] ) ) {
            return true;
        }
    }
    return false;
}