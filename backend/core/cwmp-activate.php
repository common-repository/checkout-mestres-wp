<?php
function cwmp_desactivate() {
	wp_clear_scheduled_hook('cwmp_cron_events');
}
function cwmp_activate() {
	add_option( 'cwmp_activate_plugin', 'checkout-woocommerce-mestres-wp' );
	include( 'includes/core/cwmp-reset.php' );
	include( 'includes/core/cwmp-create-cron.php' );
	include( 'includes/core/cwmp-create-database.php' );
}
register_activation_hook( __FILE__, 'cwmp_activate' );
register_deactivation_hook( __FILE__, 'cwmp_desactivate' );

function cwmp_load() {
    if ( is_admin() && get_option( 'cwmp_activate_plugin' ) == 'checkout-woocommerce-mestres-wp' ) {
        delete_option( 'cwmp_activate_plugin' );
    }
}
add_action( 'admin_init', 'cwmp_load' );


function cwmpFullReset() {
    if (isset($_POST['cwmpFullResetExecute'])) {
        cwmpFullResetRun();
    }
}
add_action('admin_init', 'cwmpFullReset');


function cwmpFullResetRun() {
    global $wpdb;
    $excecoes = "'cwmp_license_active', 'cwmp_license_id', 'cwmp_license_email', 'cwmp_license_tipo', 'cwmp_license_expired'";
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE (option_name LIKE 'cwmp_%' OR option_name LIKE 'parcelas_mwp%') AND option_name NOT IN ($excecoes)");
    $tabelas_personalizadas = array(
        'cwmp_recupera_pedidos',
        'cwmp_send_thank',
        'cwmp_order_bump',
        'cwmp_cart_abandoned',
        'cwmp_cart_abandoned_relation',
        'cwmp_cart_abandoned_msg',
        'cwmp_template_msgs',
        'cwmp_orders_buy',
        'cwmp_template_emails',
        'cwmp_pending_payment_msg',
        'cwmp_pending_payment_status',
        'cwmp_events_pixels',
        'cwmp_template_emails_produto',
        'cwmp_template_emails_produto_send',
        'cwmp_transportadoras',
        'cwmp_pixel_thank',
        'cwmp_pixel_events',
        'cwmp_session_cart',
        'cwmp_fields',
        'cwmp_discounts'
    );
    foreach ($tabelas_personalizadas as $tabela) {
        $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}$tabela");
    }
    wp_redirect(add_query_arg('limpeza_sucesso', 'true'));
    exit;
}
function cwmp_check_wordpress_update_status() {
    $current_version = get_bloginfo('version');
    include_once( ABSPATH . WPINC . '/version.php' );
    wp_version_check();
    $updates = get_site_transient('update_core');
    if (!empty($updates->updates) && isset($updates->updates[0]->version)) {
        $latest_version = $updates->updates[0]->version;
        if (version_compare($current_version, $latest_version, '<')) {
            return false;
        } else {
            return true;
        }
    } else {
        return "Não foi possível verificar atualizações ou o WordPress já está atualizado.";
    }
}
function cwmp_check_woocommerce_update_status() {
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        return "WooCommerce não está ativo.";
    }
    if (defined('WC_VERSION')) {
        $current_version = WC_VERSION;
    } else {
        return "Não foi possível determinar a versão do WooCommerce.";
    }
    wp_update_plugins();
    $updates = get_site_transient('update_plugins');
    if (!empty($updates->response['woocommerce/woocommerce.php'])) {
        $latest_version = $updates->response['woocommerce/woocommerce.php']->new_version;
        if (version_compare($current_version, $latest_version, '<')) {
            return false;
        } else {
            return true;
        }
    } else {
        return "O WooCommerce já está atualizado.";
    }
}

function cwmp_check_all_plugins_update_status() {
    wp_update_plugins();
    $updates = get_site_transient('update_plugins');
    $all_plugins = get_plugins();
    $plugins_to_update = [];
    foreach ($all_plugins as $plugin_file => $plugin_data) {
        if (!empty($updates->response[$plugin_file])) {
            $current_version = $plugin_data['Version'];
            $latest_version = $updates->response[$plugin_file]->new_version;
            $plugin_name = $plugin_data['Name'];
            if (version_compare($current_version, $latest_version, '<')) {
                $plugins_to_update[] = "$plugin_name (Versão atual: $current_version, Nova versão: $latest_version)";
            }
        }
    }
    if (!empty($plugins_to_update)) {
        return "Os seguintes plugins estão desatualizados:<br>" . implode('<br>', $plugins_to_update);
    } else {
        return true;
    }
}