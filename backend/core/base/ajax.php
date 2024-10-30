<?php
add_action( 'wp_ajax_cwmpChangeFuncionalidade', 'cwmpChangeFuncionalidade' );
add_action( 'wp_ajax_nopriv_cwmpChangeFuncionalidade', 'cwmpChangeFuncionalidade' );
function cwmpChangeFuncionalidade(){
	if(current_user_can('manage_options')){
		if (isset($_POST['cwmp_id']) && !empty($_POST['cwmp_id'])) {
			$cwmp_id = sanitize_text_field(wp_unslash($_POST['cwmp_id']));
			$option_name = 'cwmp_' . $cwmp_id;
			$status = get_option($option_name);
			switch($status){
				case "S":
					update_option($option_name, "N");
					echo esc_url(CWMP_PLUGIN_ADMIN_URL).'assets/images/mwp-ico-off.png';
					break;
				case "N":
					update_option($option_name, "S");
					echo esc_url(CWMP_PLUGIN_ADMIN_URL).'assets/images/mwp-ico-on.png';
					break;
				default:
					update_option($option_name, "S");
					echo esc_url(CWMP_PLUGIN_ADMIN_URL).'assets/images/mwp-ico-on.png';
			}
			wp_die();
		} else {
			wp_die(esc_html__('Invalid request: missing or invalid cwmp_id', 'checkout-mestres-wp'));
		}
	}
}
add_action( 'wp_ajax_cwmpUpdateOptions', 'cwmpUpdateOptions' );
add_action( 'wp_ajax_nopriv_cwmpUpdateOptions', 'cwmpUpdateOptions' );
function cwmpUpdateOptions() {
    if (isset($_POST['data'])) {
        parse_str($_POST['data'], $parsed_data);
        $campos_com_html = array('cwmp_remember_password_body', 'parcelas_mwp_payment_second_pre', 'parcelas_mwp_payment_list_format_s_juros', 'parcelas_mwp_payment_list_format_c_juros');
        foreach ($parsed_data as $campo => $valor) {
            if (in_array($campo, $campos_com_html)) {
                $safe_value = $valor;
            } else {
                $safe_value = sanitize_text_field($valor);
            }
            update_option(sanitize_text_field($campo), $safe_value);
        }
        echo "Opções atualizadas com sucesso.";
    } else {
        echo "Nenhum dado foi enviado.";
    }

    wp_die();
}