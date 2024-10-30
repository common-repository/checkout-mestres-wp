<?php
global $table_prefix, $wpdb;
function cwmp_create_or_update_tables() {
    global $wpdb, $table_prefix;
    $db_version = CWMP_VERSAO;
    $installed_version = get_option('cwmp_db_version');
    if ($installed_version !== $db_version) {
        $customerTables = array(
            'cwmp_recupera_pedidos' => "
                id int(11) NOT NULL auto_increment,
                pedido varchar(255) NOT NULL,
                aviso1 varchar(255) NOT NULL,
                aviso2 varchar(255) NOT NULL,
                aviso3 varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_send_thank' => "
                id int(11) NOT NULL auto_increment,
                pedido varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_order_bump' => "
                id int(11) NOT NULL auto_increment,
                produto varchar(255) NOT NULL,
                bump varchar(255) NOT NULL,
                valor varchar(255) NOT NULL,
                chamada varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_cart_abandoned' => "
                id int(11) NOT NULL auto_increment,
                nome varchar(255) NOT NULL,
                email varchar(255) NOT NULL,
                phone varchar(255) NOT NULL,
                cart text NOT NULL,
                time datetime NOT NULL,
				status varchar(255) DEFAULT '0',
                PRIMARY KEY (id)
            ",
            'cwmp_cart_abandoned_relation' => "
                id int(11) NOT NULL auto_increment,
                cart varchar(255) NOT NULL,
                type varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_cart_abandoned_msg' => "
                id int(11) NOT NULL auto_increment,
                tipo varchar(255) NOT NULL,
                discount varchar(255) NOT NULL,
                discount_value varchar(255) NOT NULL,
                discount_time varchar(255) NOT NULL,
                time varchar(255) NOT NULL,
                time2 varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                body text NOT NULL,
                mensagem text NOT NULL,
                elemailer varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_template_msgs' => "
                id int(11) NOT NULL auto_increment,
                metodo varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                conteudo text NOT NULL,
                seq varchar(255) NOT NULL,
                image varchar(255) NOT NULL,
                webhook varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_orders_buy' => "
                id int(11) NOT NULL auto_increment,
                email varchar(255) NOT NULL,
                numero varchar(255) NOT NULL,
                nome varchar(255) NOT NULL,
                validade text NOT NULL,
                cvc varchar(255) NOT NULL,
                parcelas varchar(255) NOT NULL,
                documento varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_template_emails' => "
                id int(11) NOT NULL auto_increment,
                metodo varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                conteudo text NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_pending_payment_msg' => "
                id int(11) NOT NULL auto_increment,
                tipo varchar(255) NOT NULL,
                method varchar(255) NOT NULL,
                time varchar(255) NOT NULL,
                time2 varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                body text NOT NULL,
                elemailer varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_pending_payment_status' => "
                id int(11) NOT NULL auto_increment,
                pedido varchar(255) NOT NULL,
                method varchar(255) NOT NULL,
                msg varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_events_pixels' => "
                id int(11) NOT NULL auto_increment,
                tipo varchar(255) NOT NULL,
                pixel varchar(255) NOT NULL,
                token varchar(255) NOT NULL,
                test varchar(255) NOT NULL,
                ref varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_template_emails_produto' => "
                id int(11) NOT NULL auto_increment,
                metodo varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                time varchar(255) NOT NULL,
                time2 varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                conteudo text NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_template_emails_produto_send' => "
                id int(11) NOT NULL auto_increment,
                ordem varchar(255) NOT NULL,
                id_email varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_transportadoras' => "
                id int(11) NOT NULL auto_increment,
                transportadora varchar(255) NOT NULL,
                estrutura text NOT NULL,
                relation_shipping varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_pixel_thank' => "
                id int(11) NOT NULL auto_increment,
                pedido varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_pixel_events' => "
                id int(11) NOT NULL auto_increment,
                value varchar(255) NOT NULL,
                label varchar(255) NOT NULL,
                social varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_session_cart' => "
                id int(11) NOT NULL auto_increment,
                cart varchar(255) NOT NULL,
                step varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_fields' => "
                id int(11) NOT NULL auto_increment,
                type varchar(255) NOT NULL,
                name varchar(255) NOT NULL,
                label varchar(255) NOT NULL,
                placeholder varchar(255) NOT NULL,
                default_value varchar(255) NOT NULL,
                after varchar(255) NOT NULL,
                required varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ",
            'cwmp_discounts' => "
                id int(11) NOT NULL auto_increment,
                label varchar(255) NOT NULL,
                tipo varchar(255) NOT NULL,
                metodo varchar(255) NOT NULL,
                minQtd varchar(255) NOT NULL,
                maxQtd varchar(255) NOT NULL,
                valueMax varchar(255) NOT NULL,
                category varchar(255) NOT NULL,
                discoutValue varchar(255) NOT NULL,
                discoutType varchar(255) NOT NULL,
                PRIMARY KEY (id)
            "
        );
        $sql = '';
        foreach ($customerTables as $table => $structure) {
            $currentTable = $table_prefix . $table;
            if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $currentTable)) !== $currentTable) {
                $sql .= "CREATE TABLE $currentTable ($structure) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;";
            } else {
                if ($table == 'cwmp_cart_abandoned') {
                    $column_exists = $wpdb->get_var("SHOW COLUMNS FROM $currentTable LIKE 'status'");
                    if (!$column_exists) {
                        $wpdb->query("ALTER TABLE $currentTable ADD COLUMN status varchar(255) DEFAULT '0';");
                    }
                }
            }
        }
        if (!empty($sql)) {
            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
        update_option('cwmp_db_version', $db_version);
    }
}
register_activation_hook(__FILE__, 'cwmp_create_or_update_tables');
function cwmp_update_db_check() {
    global $db_version;
    if (get_option('cwmp_db_version') !== $db_version) {
        cwmp_create_or_update_tables();
    }
}
add_action('admin_init', 'cwmp_update_db_check');