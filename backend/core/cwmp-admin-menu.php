<?php
function cwmp_admin_menu(){
	global $submenu;
	if(empty($GLOBALS['admin_page_hooks']['mwp_plugins'])){ add_menu_page(__( 'Mestres do WP', 'checkout-mestres-wp' ), __( 'Mestres do WP', 'checkout-mestres-wp' ), 'manage_options', 'mwp_plugins','mwp_plugins', CWMP_PLUGIN_ADMIN_URL.'assets/images/faviconmwp.png',3); }
	if(get_option('cwmp_license_active')==true){
		add_submenu_page('mwp_plugins', __( 'Checkout', 'checkout-mestres-wp' ), __( 'Checkout', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_checkout', 'cwmp_admin_checkout');
		add_submenu_page('mwp_plugins', __( 'Sales', 'checkout-mestres-wp' ), __( 'Sales', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_vendas', 'cwmp_admin_vendas');
		add_submenu_page('mwp_plugins', __( 'Payments', 'checkout-mestres-wp' ), __( 'Payments', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_parcelamento', 'cwmp_admin_parcelamento');
		add_submenu_page('mwp_plugins', __( 'Shipping', 'checkout-mestres-wp' ), __( 'Shipping', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_entrega', 'cwmp_admin_entrega');
		add_submenu_page('mwp_plugins', __( 'Communication', 'checkout-mestres-wp' ), __( 'Communication', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_comunicacao', 'cwmp_admin_comunicacao');	
		add_submenu_page('mwp_plugins', __( 'License', 'checkout-mestres-wp' ), __( 'License', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_licensas', 'cwmp_admin_licensas');
		//add_submenu_page('mwp_plugins', __( 'Diagnostico', 'checkout-mestres-wp' ), __( 'Diagnostico', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_diagnostico', 'cwmp_admin_diagnostico');
		add_submenu_page('mwp_plugins', __( 'Plugins', 'checkout-mestres-wp' ), __( 'Plugins', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_plugins', 'cwmp_admin_plugins');
	}else{
		add_submenu_page('mwp_plugins', __( 'Checkout', 'checkout-mestres-wp' ), __( 'Checkout', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_checkout', 'cwmp_admin_checkout');
		add_submenu_page('mwp_plugins', __( 'License', 'checkout-mestres-wp' ), __( 'License', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_licensas', 'cwmp_admin_licensas');	
		//add_submenu_page('mwp_plugins', __( 'Diagnostico', 'checkout-mestres-wp' ), __( 'Diagnostico', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_diagnostico', 'cwmp_admin_diagnostico');
		add_submenu_page('mwp_plugins', __( 'Plugins', 'checkout-mestres-wp' ), __( 'Plugins', 'checkout-mestres-wp' ), 'manage_options', 'cwmp_admin_plugins', 'cwmp_admin_plugins');	
	}
	unset( $submenu['mwp_plugins'][0] );
}
add_action( 'admin_menu', 'cwmp_admin_menu' );