<?php
if(esc_html(get_option('cwmp_activate_checkout'))=="S"){
	function cwmp_hooks() {
		remove_all_actions('woocommerce_before_checkout_form');
		remove_all_actions('woocommerce_checkout_before_order_review_heading');
		remove_all_actions('woocommerce_checkout_before_customer_details');
		remove_all_actions('woocommerce_checkout_after_customer_details');
		remove_all_actions('woocommerce_checkout_after_order_review');
		remove_all_actions('woocommerce_checkout_order_review');

		add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 10 );
		add_action( 'woocommerce_checkout_before_order_review_heading', 'woocommerce_checkout_coupon_form', 10 );
		add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
		
		$my_theme = wp_get_theme();
		/* STOREFRONT */
		if($my_theme->Name=="Storefront" AND is_checkout()){
			remove_all_actions( 'storefront_page_before' );
			remove_all_actions( 'storefront_sidebar' );
		}
		
		/* GENERATEPRESS */
		if($my_theme->Name=="GeneratePress" AND is_checkout()){
			remove_action( 'generate_sidebars', 'generate_construct_sidebars' );
		}
		/* NEVE */
		if($my_theme->Name=="Neve" AND is_checkout()){
			add_action( 'woocommerce_checkout_after_customer_details', 'cwmp_open_div_compat_neve', 10 );
		}
		/* BLOCKSY */
		if($my_theme->Name=="Blocksy" AND is_checkout()){
			if(is_checkout()){
				add_action('woocommerce_checkout_after_customer_details', 'cwmp_open_div_compat_blocksy', PHP_INT_MAX);
			}
		}
		if($my_theme->Name=="Shoptimizer" AND is_checkout()){
			if(is_checkout()){
				remove_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form' );
				remove_filter( 'woocommerce_cart_item_name', 'shoptimizer_product_thumbnail_in_checkout', 20, 3 );
			}
		}
		if(is_checkout()){
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
			add_action( 'woocommerce_sidebar', 'custom_empty_sidebar', 10 );
		}
	}
	function cwmp_open_div_compat_neve(){
		global $ct_skip_checkout;
		if ($ct_skip_checkout) {
			return;
		}
		echo '<div>';
	}
	function cwmp_open_div_compat_blocksy(){
		global $ct_skip_checkout;
		if ($ct_skip_checkout) {
			return;
		}
		echo '<div>';
	}
	add_action( 'init', 'cwmp_hooks', 10 );
}

