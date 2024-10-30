<?php
if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_events_pixels';
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    switch ($action) {
        case "add":
            $tipo = isset($_POST['tipo']) ? sanitize_text_field(wp_unslash($_POST['tipo'])) : '';
            $pixel = isset($_POST['pixel']) ? sanitize_text_field(wp_unslash($_POST['pixel'])) : '';
            $token = isset($_POST['token']) ? sanitize_text_field(wp_unslash($_POST['token'])) : '';
            $test = isset($_POST['test']) ? sanitize_text_field(wp_unslash($_POST['test'])) : '';
            $ref = isset($_POST['ref']) ? sanitize_text_field(wp_unslash($_POST['ref'])) : '';
            $wpdb->insert($table_name, array(
                'tipo' => $tipo,
                'pixel' => $pixel,
                'token' => $token,
                'test' => $test,
                'ref' => $ref
            ));
            break;
        case "edit":
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            $tipo = isset($_POST['tipo']) ? sanitize_text_field(wp_unslash($_POST['tipo'])) : '';
            $pixel = isset($_POST['pixel']) ? sanitize_text_field(wp_unslash($_POST['pixel'])) : '';
            $token = isset($_POST['token']) ? sanitize_text_field(wp_unslash($_POST['token'])) : '';
            $test = isset($_POST['test']) ? sanitize_text_field(wp_unslash($_POST['test'])) : '';
            $ref = isset($_POST['ref']) ? sanitize_text_field(wp_unslash($_POST['ref'])) : '';
            $wpdb->update($table_name, array(
                'tipo' => $tipo,
                'pixel' => $pixel,
                'token' => $token,
                'test' => $test,
                'ref' => $ref
            ), array('id' => $id));
            break;
        case "delete":
            $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
            $wpdb->delete($table_name, array('id' => $id));
            break;
    }
}
			$args = array(
				'box'=>array(
					'slug'=>'trafego',
					'title'=>__('Traffic', 'checkout-mestres-wp'),
					'description'=>__('Create your pixel to use integration with Facebook Ads, Google Ads, Google Analytics, etc.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create your pixel', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_checkout&type=checkout.trafego-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_checkout&type=checkout.trafego-',
					'bd'=>array(
						'name'=> 'cwmp_events_pixels',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'tipo',
							),
							'1'=>array(
								'type'=>'text',
								'value'=>'pixel',
							),
							'2'=>array(
								'type'=>'text',
								'value'=>'ref',
							),
						),
						'order'=>array(
							'by'=>'ASC',
							'value'=>'ref'
						)
					),
				),
			);
			cwmpAdminCreateLists($args);
			include "config.copyright.php";