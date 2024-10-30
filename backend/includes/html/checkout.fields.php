<?php
global $wpdb;
if (isset($_GET['action'])) {
    $table_name = $wpdb->prefix . 'cwmp_fields';
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    switch ($action) {
        case "add":
            $type = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : '';
            $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
            $label = isset($_POST['label']) ? sanitize_text_field(wp_unslash($_POST['label'])) : '';
            $placeholder = isset($_POST['placeholder']) ? sanitize_text_field(wp_unslash($_POST['placeholder'])) : '';
            $default_value = isset($_POST['default_value']) ? sanitize_text_field(wp_unslash($_POST['default_value'])) : '';
            $after = isset($_POST['after']) ? sanitize_text_field(wp_unslash($_POST['after'])) : '';
            $required = isset($_POST['required']) ? absint($_POST['required']) : 0;
            $add_bump = $wpdb->insert($table_name, array(
                'type' => $type,
                'name' => $name,
                'label' => $label,
                'placeholder' => $placeholder,
                'default_value' => $default_value,
                'after' => $after,
                'required' => $required,
            ));
            break;
        case "edit":
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            $type = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : '';
            $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
            $label = isset($_POST['label']) ? sanitize_text_field(wp_unslash($_POST['label'])) : '';
            $placeholder = isset($_POST['placeholder']) ? sanitize_text_field(wp_unslash($_POST['placeholder'])) : '';
            $default_value = isset($_POST['default_value']) ? sanitize_text_field(wp_unslash($_POST['default_value'])) : '';
            $after = isset($_POST['after']) ? sanitize_text_field(wp_unslash($_POST['after'])) : '';
            $required = isset($_POST['required']) ? absint($_POST['required']) : 0;
            $add_bump = $wpdb->update($table_name, array(
                'type' => $type,
                'name' => $name,
                'label' => $label,
                'placeholder' => $placeholder,
                'default_value' => $default_value,
                'after' => $after,
                'required' => $required,
            ), array('id' => $id));
            break;
        case "delete":
            $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
            $add_bump = $wpdb->delete($table_name, array('id' => $id));
            break;
    }
}
$args = array(
    'box' => array(
		'slug'=>'campos',
        'title' => __('Fields', 'checkout-mestres-wp'),
        'description' => __('Create a custom field for your checkout', 'checkout-mestres-wp'),
        'button' => array(
            'label' => __('Create Field', 'checkout-mestres-wp'),
            'url' => 'admin.php?page=cwmp_admin_checkout&type=checkout.fields-add',
        ),
        'help' => 'https://docs.mestresdowp.com.br',
        'patch' => 'admin.php?page=cwmp_admin_checkout&type=checkout.fields-',
        'bd' => array(
            'name' => 'cwmp_fields',
            'lines' => array(
                '0' => array(
                    'type' => 'text',
                    'value' => 'name',
                ),
                '1' => array(
                    'type' => 'text',
                    'value' => 'placeholder',
                ),

            ),
            'order' => array(
                'by' => 'ASC',
                'value' => 'name',
            ),
        ),
    ),
);
cwmpAdminCreateLists($args);
include "config.copyright.php";
