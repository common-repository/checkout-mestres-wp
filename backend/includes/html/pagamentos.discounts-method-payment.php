<?php
if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_discounts';
    $action = sanitize_text_field(wp_unslash($_GET['action']));
    if ($action == "add") {
        $label = isset($_POST['label']) ? sanitize_text_field(wp_unslash($_POST['label'])) : '';
        $tipo = isset($_POST['tipo']) ? absint($_POST['tipo']) : 0;
        $metodo = '';
        if ($tipo == 1) {
            $metodo = isset($_POST['metodoPayment']) ? sanitize_text_field(wp_unslash($_POST['metodoPayment'])) : '';
        } elseif ($tipo == 2) {
            $metodo = isset($_POST['metodoShipping']) ? sanitize_text_field(wp_unslash($_POST['metodoShipping'])) : '';
        } elseif ($tipo == 3) {
            $metodo = isset($_POST['product']) ? sanitize_text_field(wp_unslash($_POST['product'])) : '';
        }
        $discoutValue = isset($_POST['discoutValue']) ? sanitize_text_field(wp_unslash($_POST['discoutValue'])) : '';
        $discoutType = isset($_POST['discoutType']) ? sanitize_text_field(wp_unslash($_POST['discoutType'])) : '';
        $min = isset($_POST['minQtd']) ? absint($_POST['minQtd']) : 0;
        $max = isset($_POST['maxQtd']) ? absint($_POST['maxQtd']) : 0;
        $valueMax = isset($_POST['valueMax']) ? absint($_POST['valueMax']) : 0;
        $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';
        $wpdb->insert(
            $table_name,
            array(
                'label' => $label,
                'tipo' => $tipo,
                'metodo' => $metodo,
                'discoutValue' => $discoutValue,
                'discoutType' => $discoutType,
                'minQtd' => $min,
                'maxQtd' => $max,
                'valueMax' => $valueMax,
                'category' => $category,
            )
        );
    }
    if ($action == "edit") {
        $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
        $label = isset($_POST['label']) ? sanitize_text_field(wp_unslash($_POST['label'])) : '';
        $tipo = absint($_POST['tipo']);
        $metodo = '';
        if ($tipo == 1) {
            $metodo = isset($_POST['metodoPayment']) ? sanitize_text_field(wp_unslash($_POST['metodoPayment'])) : '';
        } elseif ($tipo == 2) {
            $metodo = isset($_POST['metodoShipping']) ? sanitize_text_field(wp_unslash($_POST['metodoShipping'])) : '';
        } elseif ($tipo == 3) {
            $metodo = isset($_POST['product']) ? sanitize_text_field(wp_unslash($_POST['product'])) : '';
        }
        $discoutValue = isset($_POST['discoutValue']) ? sanitize_text_field(wp_unslash($_POST['discoutValue'])) : '';
        $discoutType = isset($_POST['discoutType']) ? sanitize_text_field(wp_unslash($_POST['discoutType'])) : '';
        $min = isset($_POST['minQtd']) ? absint($_POST['minQtd']) : 0;
        $max = isset($_POST['maxQtd']) ? absint($_POST['maxQtd']) : 0;
        $valueMax = isset($_POST['valueMax']) ? absint($_POST['valueMax']) : 0;
        $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';
        $wpdb->update(
            $table_name,
            array(
                'label' => $label,
                'tipo' => $tipo,
                'metodo' => $metodo,
                'discoutValue' => $discoutValue,
                'discoutType' => $discoutType,
                'minQtd' => $min,
                'maxQtd' => $max,
                'valueMax' => $valueMax,
                'category' => $category,
            ),
            array('id' => $id)
        );
    }
    if ($action == "delete") {
        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        $wpdb->delete($table_name, array('id' => $id));
    }
}
?>
<?php
$args = array(
    'box' => array(
        'slug' => 'method-payment',
        'title' => __('Discounts', 'checkout-mestres-wp'),
        'description' => __('Create discounts to increase your sales', 'checkout-mestres-wp'),
        'button' => array(
            'label' => __('Create Discount', 'checkout-mestres-wp'),
            'url' => 'admin.php?page=cwmp_admin_parcelamento&type=pagamentos.discounts-method-payment-add',
        ),
        'help' => 'https://docs.mestresdowp.com.br',
        'patch' => 'admin.php?page=cwmp_admin_parcelamento&type=pagamentos.discounts-method-payment-',
        'bd' => array(
            'name' => 'cwmp_discounts',
            'lines' => array(
                '0' => array(
                    'type' => 'text',
                    'value' => 'label',
                ),
                '1' => array(
                    'type' => 'discount',
                    'value' => 'discoutValue',
                ),
            ),
            'order' => array(
                'by' => 'ASC',
                'value' => 'metodo'
            )
        )
    ),
);
cwmpAdminCreateLists($args);
include "config.copyright.php";
