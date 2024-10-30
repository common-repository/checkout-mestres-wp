<?php
if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_transportadoras';
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    switch ($action) {
        case "add":
            $transportadora = isset($_POST['transportadora']) ? sanitize_text_field(wp_unslash($_POST['transportadora'])) : '';
            $estrutura = isset($_POST['estrutura']) ? sanitize_text_field(wp_unslash($_POST['estrutura'])) : '';
            $relation_shipping = isset($_POST['relation_shipping']) ? sanitize_text_field(wp_unslash($_POST['relation_shipping'])) : '';
            $wpdb->insert($table_name, array(
                'transportadora' => $transportadora,
                'estrutura' => $estrutura,
                'relation_shipping' => $relation_shipping,
            ));
            break;
        case "edit":
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            $transportadora = isset($_POST['transportadora']) ? sanitize_text_field(wp_unslash($_POST['transportadora'])) : '';
            $estrutura = isset($_POST['estrutura']) ? sanitize_text_field(wp_unslash($_POST['estrutura'])) : '';
            $relation_shipping = isset($_POST['relation_shipping']) ? sanitize_text_field(wp_unslash($_POST['relation_shipping'])) : '';
            $wpdb->update($table_name, array(
                'transportadora' => $transportadora,
                'estrutura' => $estrutura,
                'relation_shipping' => $relation_shipping,
            ), array('id' => $id));
            break;
        case "delete":
            $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
            $wpdb->delete($table_name, array('id' => $id));
            break;
    }
}
		?>
			<?php
			$args = array(
				'box'=>array(
					'slug'=>'transportadoras',
					'title'=>__('Carriers', 'checkout-mestres-wp'),
					'description'=>__('Create a carrier to send tracking links to your customers.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create Carrier', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_entrega&type=entrega.transportadoras-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_entrega&type=entrega.transportadoras-',
					'bd'=>array(
						'name'=> 'cwmp_transportadoras',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'transportadora',
							),
							'1'=>array(
								'type'=>'text',
								'value'=>'estrutura',
							),

						),
						'order'=>array(
							'by'=>'DESC',
							'value'=>'transportadora'
						),
					)
				),
			);
			cwmpAdminCreateLists($args);
			include "config.copyright.php";
