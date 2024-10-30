<?php
function cwmp_order_billing_info($number_id, $info_type) {
    global $wp;
    global $woocommerce;
    $order_id = null;

    if (isset($number_id['val'])) {
        $order_id = intval($number_id['val']);
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        if (preg_match('/^[a-zA-Z0-9\/+=]+$/', $cwmp_order_raw)) {
            $cwmp_order_decoded = base64_decode($cwmp_order_raw);
            if (is_numeric($cwmp_order_decoded)) {
                $order_id = intval($cwmp_order_decoded);
            }
        }
    }
    if ($order_id) {
        $cwmp_order_info = wc_get_order($order_id);
        if ($cwmp_order_info) {
            if (method_exists($cwmp_order_info, 'get_' . $info_type)) {
                return esc_html(call_user_func(array($cwmp_order_info, 'get_' . $info_type)));
            } elseif ($cwmp_order_info->meta_exists('_' . $info_type)) {
                return esc_html($cwmp_order_info->get_meta('_' . $info_type));
            }
        }
    }
    return '';
}
function cwmp_code_shipping_link($number_id){
    global $wp;
    global $wpdb;
    global $woocommerce;
    global $table_prefix;
    $cwmp_order_info = null;
    if (isset($number_id['val'])) {
        $cwmp_order_info = wc_get_order(intval($number_id['val']));
    } 
    elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        if (preg_match('/^[a-zA-Z0-9\/+=]+$/', $cwmp_order_raw)) {
            $cwmp_order_decoded = base64_decode($cwmp_order_raw);
            if (is_numeric($cwmp_order_decoded)) {
                $cwmp_order_info = wc_get_order(intval($cwmp_order_decoded));
            }
        }
    }
    if ($cwmp_order_info) {
        $get_campanha = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}cwmp_transportadoras WHERE id LIKE %s",
                get_post_meta($cwmp_order_info->get_ID(), '_cwmp_codigo_transportadora_slug', true)
            )
        );
        if (isset($get_campanha[0]->estrutura)) {
            $track_code = get_post_meta($cwmp_order_info->get_id(), '_cwmp_codigo_rastreio_slug', true);
            return str_replace("{track}", $track_code, str_replace("{{track}}", $track_code, $get_campanha[0]->estrutura));
        }
    }
    return '';
}
function cwmp_code_shipping($number_id){
    global $wp;
    global $woocommerce;
    $cwmp_order_info = null;
    if (isset($number_id['val'])) {
        $cwmp_order_info = wc_get_order(intval($number_id['val']));
    } 
    elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        if (preg_match('/^[a-zA-Z0-9\/+=]+$/', $cwmp_order_raw)) {
            $cwmp_order_decoded = base64_decode($cwmp_order_raw);
            if (is_numeric($cwmp_order_decoded)) {
                $cwmp_order_info = wc_get_order(intval($cwmp_order_decoded));
            }
        }
    }
    if ($cwmp_order_info) {
        $shipping_code = get_post_meta($cwmp_order_info->get_ID(), '_cwmp_codigo_rastreio_slug', true);
        return $shipping_code;
    }
    return '';
}
function cwmp_shipping_name($number_id){
    global $wp;
    global $woocommerce;
    $cwmp_order_info = null;
    if (isset($number_id['val'])) {
        $cwmp_order_info = wc_get_order(intval($number_id['val']));
    } 
    elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        if (preg_match('/^[a-zA-Z0-9\/+=]+$/', $cwmp_order_raw)) {
            $cwmp_order_decoded = base64_decode($cwmp_order_raw);
            if (is_numeric($cwmp_order_decoded)) {
                $cwmp_order_info = wc_get_order(intval($cwmp_order_decoded));
            }
        }
    }
    if ($cwmp_order_info) {
        $shipping_items = $cwmp_order_info->get_items('shipping');
        foreach ($shipping_items as $item_id => $item) {
            return esc_html($item->get_name());
        }
    }

    return '';
}
function cwmp_shipping_total($number_id){
    global $wp;
    global $woocommerce;
    $cwmp_order_info = null;
    if (isset($number_id['val'])) {
        $cwmp_order_info = wc_get_order(intval($number_id['val']));
    } 
    elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        if (preg_match('/^[a-zA-Z0-9\/+=]+$/', $cwmp_order_raw)) {
            $cwmp_order_decoded = base64_decode($cwmp_order_raw);
            if (is_numeric($cwmp_order_decoded)) {
                $cwmp_order_info = wc_get_order(intval($cwmp_order_decoded));
            }
        }
    }
    if ($cwmp_order_info) {
        $shipping_items = $cwmp_order_info->get_items('shipping');
        foreach ($shipping_items as $item_id => $item) {
            return "R$" . esc_html(number_format($item->get_total(), 2, ',', '.'));
        }
    }
    return '';
}
function cwmp_order_meio_de_pagamento($number_id){
    global $wp;
    global $woocommerce;
    $cwmp_order_info = null;
    if (isset($number_id['val'])) {
        $cwmp_order_info = wc_get_order($number_id['val']);
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $cwmp_order_decoded = base64_decode($cwmp_order_raw);
        if ($cwmp_order_decoded) {
            $cwmp_order_info = wc_get_order($cwmp_order_decoded);
        }
    } else {
        $orders = wc_get_orders(array(
            'limit' => 1,
            'return' => 'ids',
        ));
        if (!empty($orders)) {
            $cwmp_order_info = wc_get_order($orders[0]);
        }
    }
    if (!empty($cwmp_order_info) && $cwmp_order_info->get_payment_method_title()) {
        return esc_html($cwmp_order_info->get_payment_method_title());
    }
    return '';
}
function cwmp_order_produtos($number_id){
    global $wp;
    global $woocommerce;
    $cwmp_order_info = null;
    $note = '';
    if (isset($number_id['val'])) {
        $cwmp_order_info = wc_get_order($number_id['val']);
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $cwmp_order_decoded = base64_decode($cwmp_order_raw);
        if ($cwmp_order_decoded) {
            $cwmp_order_info = wc_get_order($cwmp_order_decoded);
        }
    } else {
        $orders = wc_get_orders(array(
            'limit' => 1,
            'return' => 'ids',
        ));
        if (!empty($orders)) {
            $cwmp_order_info = wc_get_order($orders[0]);
        }
    }
    if (!empty($cwmp_order_info)) {
        $items = $cwmp_order_info->get_items();
        foreach ($items as $lineItem) {
            $note .= esc_html($lineItem['name']) . " x " . esc_html($lineItem['quantity']) . "\n";
        }
    }
    if ($note) {
        return $note;
    }
    return '';
}
function cwmp_order_product_download($number_id){
    global $wp;
    global $woocommerce;
    $cwmp_order_info = null;
    $html = [];
    if (isset($number_id['val'])) {
        $cwmp_order_info = wc_get_order($number_id['val']);
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $cwmp_order_decoded = base64_decode($cwmp_order_raw);
        if ($cwmp_order_decoded) {
            $cwmp_order_info = wc_get_order($cwmp_order_decoded);
        }
    } else {
        $orders = wc_get_orders(array(
            'limit' => 1,
            'return' => 'ids',
        ));
        if (!empty($orders)) {
            $cwmp_order_info = wc_get_order($orders[0]);
        }
    }
    if (!empty($cwmp_order_info)) {
        if ($downloads = $cwmp_order_info->get_downloadable_items()) {
            foreach ($downloads as $download) {
                $html[] = esc_html($download["file"]['file']);
            }
        }
        if (!empty($html)) {
            return implode('<br>', $html);
        }
    }
    return '';
}
function cwmp_payment_link($number_id){
    global $wp;
    global $woocommerce;
    $cwmp_order_info = null;
    if (isset($number_id['val'])) {
        $cwmp_order_info = wc_get_order($number_id['val']);
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $cwmp_order_decoded = base64_decode($cwmp_order_raw);
        if ($cwmp_order_decoded) {
            $cwmp_order_info = wc_get_order($cwmp_order_decoded);
        }
    } else {
        $orders = wc_get_orders(array(
            'limit' => 1,
            'return' => 'ids',
        ));
        if (!empty($orders)) {
            $cwmp_order_info = wc_get_order($orders[0]);
        }
    }
    if (!empty($cwmp_order_info)) {
        $payment_status = $cwmp_order_info->get_status();
        $payment_method = $cwmp_order_info->get_payment_method();
        if ($payment_status == "pending" || $payment_status == "on-hold") {
            return get_permalink(get_option('cwmp_thankyou_page_pending_' . $payment_method)) . "?cwmp_order=" . base64_encode($cwmp_order_info->get_id());
        } else {
            return get_permalink(get_option('cwmp_thankyou_page_aproved_' . $payment_method)) . "?cwmp_order=" . base64_encode($cwmp_order_info->get_id());
        }
    }
    return '';
}
function cwmp_order_coupon_name($number_id){
    global $wp;
    global $woocommerce;
    $order = null;
    if (isset($number_id['val'])) {
        $order = wc_get_order($number_id['val']);
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $cwmp_order_decoded = base64_decode($cwmp_order_raw);
        if ($cwmp_order_decoded) {
            $order = wc_get_order($cwmp_order_decoded);
        }
    }
    if ($order) {
        $coupon = $order->get_coupon_codes();
        if (isset($coupon[0])) {
            return esc_html($coupon[0]);
        }
    }
    return '';
}
function cwmpPixQRCode($number_id){
    global $wp;
    global $woocommerce;
    $order_id = null;
    if (isset($number_id['val'])) {
        $order_id = $number_id['val'];
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $order_id = base64_decode($cwmp_order_raw);
    }
    if (!empty($order_id)) {
        $order = wc_get_order($order_id);
    }
    if (isset($order)) {
        switch ($order->get_payment_method()) {
            case "agregapay_pix":
                $retorno = get_post_meta($order_id, 'qrcode');
                $qrcode = '<img src="' . esc_url($retorno[0]) . '">';
                break;
            case "mwp_inter_pix":
                $retorno = get_post_meta($order_id, 'qrcode');
                $qrcode = '<img src="' . esc_url($retorno[0]) . '">';
                break;
            case "WC_Gerencianet_Pix":
                $retorno = get_post_meta($order_id, '_gn_pix_qrcode', true);
                $qrcode = '<img src="' . esc_url($retorno) . '" width="200" height="auto" />';
                break;
            case "woocommerce_openpix_pix":
                $retorno = $order->get_meta('openpix_transaction');
                $qrcode = '<img src="' . esc_url($retorno['qrCodeImage']) . '" width="200" height="auto" />';
                break;
            case "appmax_pix":
                $qrcode = '<img src="data:image/png;base64,' . esc_attr($order->get_meta('_appmax_transaction_data')['post_payment']['pix_qrcode']) . '" width="200" height="auto" />';
                break;
            case "pagamentos_para_woocommerce_com_appmax_pix":
                $qrcode = '<img src="' . esc_url($order->get_meta('_pagamentos_para_woocommerce_com_appmax_media')) . '" width="200" height="auto" />';
                break;
            case "iugu-pix":
                $retorno = get_post_meta($order_id, '_iugu_wc_transaction_data');
                $qrcode = "<img src='data:image/jpeg;base64," . esc_attr($retorno[0]['qrcode']) . "' width='200' height='200' />";
                break;
            case "asaas-pix":
                $retorno = json_decode($order->get_meta('__ASAAS_ORDER'));
                $qrcode = '<img height="250px" width="250px" src="data:image/jpeg;base64,' . esc_attr($retorno->encodedImage) . '" />';
                break;
            case "paghiper_pix":
                $retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
                $qrcode = '<img src="' . esc_url($retorno['qrcode_image_url']) . '" width="200" height="auto" />';
                break;
            case "click2pay-pix":
                $retorno = $order->get_meta('_click2pay_data');
                $qrcode = "<img src='" . esc_url($retorno->pix->qrCodeImage->base64) . "' width='200' height='200' />";
                break;
            case "woo-pagarme-payments-pix":
                $retorno = json_decode($order->get_meta('_pagarme_response_data'));
                $qrcode = '<img src="' . esc_url($retorno->charges[0]->transactions[0]->postData->qr_code_url) . '" width="200" height="auto" />';
                break;
            case "woo-mercado-pago-pix":
                $qrcode = "<img src='data:image/jpeg;base64," . esc_attr($order->get_meta('mp_pix_qr_base64')) . "' width='200' height='200' />";
                break;
            case "wc_yapay_intermediador_pix":
                $data = get_post_meta($order->get_id(), 'yapay_transaction_data', true);
                if (is_serialized($data)) {
                    $data = unserialize($data);
                    $qrcode = "<img src='" . esc_url($data['qrcode_path']) . "' width='200' height='200' />";
                }
                break;
        }
        return $qrcode;
    }
    return '';
}
function cwmpPixCopyPast($number_id){
    global $wp;
    global $woocommerce;
    $order_id = null;
    if (isset($number_id['val'])) {
        $order_id = $number_id['val'];
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $order_id = base64_decode($cwmp_order_raw);
    }
    if (!empty($order_id)) {
        $order = wc_get_order($order_id);
    }
    if (isset($order)) {
        switch ($order->get_payment_method()) {
            case "agregapay_pix":
                $retorno = get_post_meta($order_id, 'copypast');
                return esc_attr($retorno[0]);
                break;
            case "mwp_inter_pix":
                $retorno = get_post_meta($order_id, 'copypast');
                return esc_attr($retorno[0]);
                break;
            case "WC_Gerencianet_Pix":
                $retorno = get_post_meta($order_id, '_gn_pix_copy', true);
                return esc_attr($retorno);
                break;
            case "woocommerce_openpix_pix":
                $retorno = $order->get_meta('openpix_transaction');
                return esc_attr($retorno['brCode']);
                break;
            case "appmax_pix":
                return esc_attr($order->get_meta("_appmax_transaction_data")["post_payment"]["pix_emv"]);
                break;
            case "pagamentos_para_woocommerce_com_appmax_pix":
                return esc_attr($order->get_meta('_pagamentos_para_woocommerce_com_appmax_payment_code'));
                break;
            case "iugu-pix":
                $retorno = get_post_meta($order_id, '_iugu_wc_transaction_data');
                return esc_attr($retorno[0]['qrcode_text']);
                break;
            case "asaas-pix":
                $retorno = json_decode($order->get_meta('__ASAAS_ORDER'));
                return esc_attr($retorno->payload);
                break;
            case "paghiper_pix":
                $retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
                return esc_attr($retorno['emv']);
                break;
            case "click2pay-pix":
                $retorno = $order->get_meta('_click2pay_data');
                return esc_attr($retorno->pix->textPayment);
                break;
            case "woo-pagarme-payments-pix":
                $retorno = json_decode($order->get_meta('_pagarme_response_data'));
                return esc_attr($retorno->charges[0]->transactions[0]->postData->qr_code);
                break;
            case "woo-mercado-pago-pix":
                return esc_attr($order->get_meta('mp_pix_qr_code'));
                break;
            case "wc_yapay_intermediador_pix":
                $data = get_post_meta($order->get_id(), 'yapay_transaction_data', true);
                if (is_serialized($data)) {
                    $data = unserialize($data);
                    return esc_attr($data['qrcode_original_path']);
                }
                break;
        }
    }
    return '';
}
function cwmpBilletBarcode($number_id){
    global $wp;
    global $woocommerce;
    $order_id = null;
    if (isset($number_id['val'])) {
        $order_id = $number_id['val'];
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $order_id = base64_decode($cwmp_order_raw);
    }
    if (!empty($order_id)) {
        $order = wc_get_order($order_id);
    }
    if (isset($order)) {
        switch ($order->get_payment_method()) {
            case "WC_Gerencianet_Boleto":
                $retorno = get_post_meta($order_id, '_gn_barcode');
                return esc_html($retorno[0]);
                break;
            case "woo-pagarme-payments-billet":
                $retorno = json_decode($order->get_meta('_pagarme_response_data'));
                return esc_html($retorno->charges[0]->transactions[0]->postData->line);
                break;
            case "paghiper_billet":
                $retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
                return esc_html($retorno['digitable_line']);
                break;
            case "click2pay-bank-slip":
                $retorno = $order->get_meta('_click2pay_boleto_barcode');
                return esc_html($retorno);
                break;
            case "appmax_boleto":
                $retorno = get_post_meta($order->get_id(), 'appmax_digitable_line', true);
                return esc_html($retorno);
                break;
            case "pagamentos_para_woocommerce_com_appmax_boleto":
                return esc_html($order->get_meta('_pagamentos_para_woocommerce_com_appmax_payment_code'));
                break;
            case "pagamentos_para_woocommerce_com_appmax_boleto":
                return esc_html($order->get_meta('_pagamentos_para_woocommerce_com_appmax_media'));
                break;
            case "wc_yapay_intermediador_bs":
                $retorno = get_post_meta($order->get_id(), 'yapay_transaction_data', true);
                if (is_serialized($retorno)) {
                    $data = unserialize($retorno);
                    return esc_html($data['typeful_line']);
                }
                break;
            case "cora":
                $retorno = get_post_meta($order->get_id(), 'cora_digitable', true);
                return esc_html($retorno);
                break;
            case "mwp_inter_billet":
                $retorno = get_post_meta($order->get_id(), 'copypast', true);
                return esc_html($retorno);
                break;
        }
    }
    return '';
}
function cwmpBilletLink($number_id){
    global $wp;
    global $woocommerce;
    $order_id = null;
    if (isset($number_id['val'])) {
        $order_id = $number_id['val'];
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $order_id = base64_decode($cwmp_order_raw);
    }
    if (!empty($order_id)) {
        $order = wc_get_order($order_id);
    }
    if (isset($order)) {
        switch ($order->get_payment_method()) {
            case "WC_Gerencianet_Boleto":
                $retorno = get_post_meta($order_id, '_gn_link_responsive');
                return esc_url($retorno[0]);
                break;
            case "asaas-ticket":
                $retorno = json_decode($order->get_meta('__ASAAS_ORDER'));
                return esc_url($retorno->bankSlipUrl);
                break;
            case "woo-pagarme-payments-billet":
                $retorno = json_decode($order->get_meta('_pagarme_response_data'));
                return esc_url($retorno->charges[0]->transactions[0]->postData->url);
                break;
            case "paghiper_billet":
                $retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
                return esc_url($retorno['url_slip_pdf']);
                break;
            case "woo-mercado-pago-ticket":
                return esc_url($order->get_meta('_transaction_details_ticket'));
                break;
            case "click2pay-bank-slip":
                $retorno = $order->get_meta('_click2pay_boleto_url');
                return esc_url($retorno);
                break;
            case "agregapay_boleto":
                $retorno = get_post_meta($order_id, 'agregapay_boleto_link', true);
                return esc_url($retorno);
                break;
            case "mwp_inter_billet":
                $retorno = get_post_meta($order->get_id(), 'arquivo', true);
                return esc_url($retorno);
                break;
            case "appmax_boleto":
                $retorno = get_post_meta($order->get_id(), 'appmax_link_billet', true);
                return esc_url($retorno);
                break;
            case "pagamentos_para_woocommerce_com_appmax_boleto":
                return esc_url($order->get_meta('_pagamentos_para_woocommerce_com_appmax_media'));
                break;
            case "wc_yapay_intermediador_bs":
                $retorno = get_post_meta($order->get_id(), 'yapay_transaction_data', true);
                if (is_serialized($retorno)) {
                    $data = unserialize($retorno);
                    return esc_url($data['url_payment']);
                }
                break;
            case "cora":
                $retorno = get_post_meta($order->get_id(), 'cora_url', true);
                return esc_url($retorno);
                break;
        }
    }
    return '';
}
function cwmpPixQRCodeLink($number_id){
    global $wp;
    global $woocommerce;
    $order_id = null;
    if (isset($number_id['val'])) {
        $order_id = $number_id['val'];
    } elseif (filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING)) {
        $cwmp_order_raw = filter_input(INPUT_GET, 'cwmp_order', FILTER_SANITIZE_STRING);
        $cwmp_order_raw = wp_unslash($cwmp_order_raw);
        $order_id = base64_decode($cwmp_order_raw);
    }
    if (!empty($order_id)) {
        $order = wc_get_order($order_id);
    }
    if (isset($order)) {
        switch ($order->get_payment_method()) {
            case "agregapay_pix":
                $retorno = get_post_meta($order_id, 'qrcode');
                $qrcode = esc_url($retorno[0]);
                break;
            case "WC_Gerencianet_Pix":
                $retorno = get_post_meta($order_id, '_gn_pix_qrcode', true);
                $qrcode = esc_url($retorno);
                break;
            case "woocommerce_openpix_pix":
                $retorno = $order->get_meta('openpix_transaction');
                $qrcode = esc_url($retorno['qrCodeImage']);
                break;
            case "appmax_pix":
                $qrcode = 'data:image/png;base64,' . esc_attr($order->get_meta('_appmax_transaction_data')['post_payment']['pix_qrcode']);
                break;
            case "pagamentos_para_woocommerce_com_appmax_pix":
                $qrcode = esc_url($order->get_meta('_pagamentos_para_woocommerce_com_appmax_media'));
                break;
            case "iugu-pix":
                $retorno = get_post_meta($order_id, '_iugu_wc_transaction_data');
                $qrcode = "data:image/jpeg;base64," . esc_attr($retorno[0]['qrcode']);
                break;
            case "asaas-pix":
                $retorno = json_decode($order->get_meta('__ASAAS_ORDER'));
                $qrcode = 'data:image/jpeg;base64,' . esc_attr($retorno->encodedImage);
                break;
            case "paghiper_pix":
                $retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
                $qrcode = esc_url($retorno['qrcode_image_url']);
                break;
            case "click2pay-pix":
                $retorno = $order->get_meta('_click2pay_data');
                $qrcode = esc_attr($retorno->pix->qrCodeImage->base64);
                break;
            case "woo-pagarme-payments-pix":
                $retorno = json_decode($order->get_meta('_pagarme_response_data'));
                $qrcode = esc_url($retorno->charges[0]->transactions[0]->postData->qr_code_url);
                break;
            case "woo-mercado-pago-pix":
                $qrcode = "data:image/jpeg;base64," . esc_attr($order->get_meta('mp_pix_qr_base64'));
                break;
            case "wc_yapay_intermediador_pix":
                $data = get_post_meta($order->get_id(), 'yapay_transaction_data', true);
                if (is_serialized($data)) {
                    $data = unserialize($data);
                    $qrcode = esc_url($data['qrcode_path']);
                }
                break;
        }
        return $qrcode;
    }
    return '';
}
function cwmpPixTextArea(){
	return "
		<textarea class='copypast'>" . do_shortcode('[cwmpPixCopyPast]') . "</textarea>
		<button class='buttoncopypast'>Copiar</button>
		<p style='display:none' class='return_copy'>Código Copiado</p>
		<script type='text/javascript'>
		jQuery(document).ready(function($) {
		$('.buttoncopypast').click(function(){
			navigator.clipboard.writeText($('textarea.copypast').val());
			$('.return_copy').show();
		});
		});
		</script>
	";
}
function cwmpBilletTextArea(){
	return "
		<textarea class='copypast'>" . do_shortcode('[cwmpBilletBarcode]') . "</textarea>
		<button class='buttoncopypast'>Copiar</button>
		<p style='display:none' class='return_copy'>Código Copiado</p>
		<script type='text/javascript'>
		jQuery(document).ready(function($) {
		$('.buttoncopypast').click(function(){
			navigator.clipboard.writeText($('textarea.copypast').val());
			$('.return_copy').show();
		});
		});
		</script>
	";
}
function cwmp_loja_name($number_id){
    global $wp;
	if(get_option('blogname'))	{
		return get_option('blogname');
	}
}
function cwmp_loja_url($number_id){
    global $wp;
	if(get_option('siteurl'))	{
		return get_option('siteurl');
	}
}
function cwmp_loja_email($number_id){
    global $wp;
	if(get_option('admin_email'))	{
		return get_option('admin_email');
	}
}
function cwmp_loja_logo($number_id){
    global $wp;
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$logo_url = wp_get_attachment_image_url( $custom_logo_id , 'full' );
	return esc_url($logo_url);
}
function cwmp_cart_logo($number_id){
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$custom_logo_data = wp_get_attachment_image_src( $custom_logo_id , 'full' );
	$custom_logo_url = $custom_logo_data[0];
	if($custom_logo_url){
	return $custom_logo_url;
	}
}
function cwmp_cart_loja_name($number_id){
    global $wp;
	if(get_option('blogname')){
		return get_option('blogname');
	}
}
function cwmp_cart_loja_url($number_id){
    global $wp;
	if(get_option('siteurl')){
		return get_option('siteurl');
	}
}
function cwmp_cart_loja_email($number_id){
    global $wp;
	if(get_option('admin_email')){
		return get_option('admin_email');
	}
}

add_shortcode('cwmp_order_name', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_first_name'); });
add_shortcode('cwmp_order_firstname', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_first_name'); });
add_shortcode('cwmp_order_lastname', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_last_name'); });
add_shortcode('cwmp_order_phone', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_phone'); });
add_shortcode('cwmp_order_email', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_email'); });
add_shortcode('cwmp_order_cpf', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_cpf'); });
add_shortcode('cwmp_order_cnpj', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_cnpj'); });
add_shortcode('cwmp_order_cellphone', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_cellphone'); });
add_shortcode('cwmp_order_billing_logradouro', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_address_1'); });
add_shortcode('cwmp_order_billing_numero', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_number'); });
add_shortcode('cwmp_order_billing_complemento', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_address_2'); });
add_shortcode('cwmp_order_billing_bairro', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_neighborhood'); });
add_shortcode('cwmp_order_billing_cidade', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_city'); });
add_shortcode('cwmp_order_billing_estado', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_state'); });
add_shortcode('cwmp_order_billing_cep', function($number_id) { return cwmp_order_billing_info($number_id, 'billing_postcode'); });
add_shortcode('cwmp_order_status', function($number_id) { return cwmp_order_billing_info($number_id, 'status'); });
add_shortcode('cwmp_order_date', function($number_id) { return cwmp_order_billing_info($number_id, 'date_created'); });
add_shortcode('cwmp_order_time', function($number_id) { return cwmp_order_billing_info($number_id, 'date_created'); });
add_shortcode('cwmp_order_number', function($number_id) { return cwmp_order_billing_info($number_id, 'id'); });
add_shortcode('cwmp_order_total', function($number_id) { return cwmp_order_billing_info($number_id, 'total'); });
add_shortcode('cwmp_contingencia_pagamento_link', function($number_id) { return cwmp_order_billing_info($number_id, 'checkout_payment_url'); });
add_shortcode('cwmp_code_shipping_link', 'cwmp_code_shipping_link');
add_shortcode('cwmp_code_shipping', 'cwmp_code_shipping');
add_shortcode('cwmp_shipping_name', 'cwmp_shipping_name');
add_shortcode('cwmp_shipping_total', 'cwmp_shipping_total');
add_shortcode('cwmp_order_meio_de_pagamento', 'cwmp_order_meio_de_pagamento');
add_shortcode('cwmp_order_produtos', 'cwmp_order_produtos');
add_shortcode('cwmp_order_product_download', 'cwmp_order_product_download');
add_shortcode('cwmp_payment_link', 'cwmp_payment_link');
add_shortcode('cwmp_order_coupon_name', 'cwmp_order_coupon_name');
add_shortcode('cwmpPixQRCode', 'cwmpPixQRCode');
add_shortcode('cwmpPixCopyPast', 'cwmpPixCopyPast');
add_shortcode('cwmpBilletBarcode', 'cwmpBilletBarcode');
add_shortcode('cwmpBilletLink', 'cwmpBilletLink');
add_shortcode('cwmpPixQRCodeLink', 'cwmpPixQRCodeLink');
add_shortcode('cwmpPixTextArea', 'cwmpPixTextArea');
add_shortcode('cwmpBilletTextArea', 'cwmpBilletTextArea');
add_shortcode('cwmp_loja_name', 'cwmp_loja_name');
add_shortcode('cwmp_loja_url', 'cwmp_loja_url');
add_shortcode('cwmp_loja_email', 'cwmp_loja_email');
add_shortcode('cwmp_loja_logo', 'cwmp_loja_logo');
add_shortcode('cwmp_cart_logo', 'cwmp_cart_logo');
add_shortcode('cwmp_cart_loja_name', 'cwmp_cart_loja_name');
add_shortcode('cwmp_cart_loja_url', 'cwmp_cart_loja_url');
add_shortcode('cwmp_cart_loja_email', 'cwmp_cart_loja_email');