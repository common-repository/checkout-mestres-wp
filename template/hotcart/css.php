<?php
echo "<style type='text/css'>";
if(is_checkout()){
	if(get_option('cwmp_activate_checkout')=="S"){
		echo "
			:root {
			  --bg-color: ".esc_html(get_option('cwmp_checkout_background')).";
			  --bg-box-color: ".esc_html(get_option('cwmp_checkout_box_background')).";		  
			  --color-primary: ".esc_html(get_option('cwmp_checkout_primary_color')).";
			  --color-secundary: ".esc_html(get_option('cwmp_checkout_secundary_color')).";
			  --color-secundary-contrast: ".esc_html(get_option('cwmp_checkout_secundary_color_contrast')).";
			  --background-input: ".esc_html(get_option('cwmp_checkout_input_background')).";
			  --color-input: ".esc_html(get_option('cwmp_checkout_input_color')).";
			  --background-input-hover: ".esc_html(get_option('cwmp_checkout_input_hover_background')).";
			  --color-input-hover: ".esc_html(get_option('cwmp_checkout_input_hover_color')).";
			  --background-button: ".esc_html(get_option('cwmp_checkout_button_background')).";
			  --color-button: ".esc_html(get_option('cwmp_checkout_button_color')).";
			  --background-button-hover: ".esc_html(get_option('cwmp_checkout_button_hover_background')).";
			  --color-button-hover: ".esc_html(get_option('cwmp_checkout_button_hover_color')).";
			  --background-success: ".esc_html(get_option('cwmp_checkout_success_background')).";
			  --color-success: ".esc_html(get_option('cwmp_checkout_success_color')).";
			}
			".esc_html(get_option('cwmp_checkout_css_personalizado'))."
		";
		if(get_option('cwmp_activate_order_bump')=="S"){
		echo "
			:root {
			--bump-bg-color: ".esc_html(get_option('cwmp_box_bump_background')).";
			--bump-color-primary: ".esc_html(get_option('cwmp_box_bump_primary')).";
			--bump-color-secundary: ".esc_html(get_option('cwmp_box_bump_secundary')).";
			--bump-color-button: ".esc_html(get_option('cwmp_box_bump_button_color')).";
			}
		";
		}
	}
}
if(!is_checkout()){
	if(get_option('cwmp_pmwp_active')=="S"){
		echo "
			:root {
				--price-align-items: ".esc_html(get_option('parcelas_mwp_price_regular_align')).";
				--price-align-position: ".esc_html(get_option('parcelas_mwp_price_regular_position')).";
				--price-regular-size: ".esc_html(get_option('parcelas_mwp_price_regular_size'))."px;
				--price-regular-color: ".esc_html(get_option('parcelas_mwp_price_regular_color')).";
				--price-regular-weight: ".esc_html(get_option('parcelas_mwp_price_regular_weight')).";
				--price-regular-decoration: ".esc_html(get_option('parcelas_mwp_price_regular_decoration')).";
				--price-sale-size: ".esc_html(get_option('parcelas_mwp_price_sale_size'))."px;
				--price-sale-color: ".esc_html(get_option('parcelas_mwp_price_sale_color')).";
				--price-sale-weight: ".esc_html(get_option('parcelas_mwp_price_sale_weight')).";
				--price-list-size: ".esc_html(get_option('parcelas_mwp_list_size_text'))."px;
				--price-list-color: ".esc_html(get_option('parcelas_mwp_list_color_text')).";
				--price-box-size: ".esc_html(get_option('parcelas_mwp_box_size_text'))."px;
				--price-box-color: ".esc_html(get_option('parcelas_mwp_box_color_text')).";
				--price-catalog-align: ".esc_html(get_option('parcelas_mwp_price_catalog_align')).";
				--price-product-align: ".esc_html(get_option('parcelas_mwp_price_product_align')).";
			}
			".esc_html(get_option('cwmp_parcelas_css_personalizado'))."
		";
	}
}


echo "</style>";


