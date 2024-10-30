<?php

if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_order_bump';
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    switch ($action) {
        case "add":
            $produto = isset($_POST['produto']) ? sanitize_text_field(wp_unslash($_POST['produto'])) : '';
            $bump = isset($_POST['bump']) ? sanitize_text_field(wp_unslash($_POST['bump'])) : '';
            $valor = isset($_POST['valor']) ? floatval($_POST['valor']) : 0;
            $chamada = isset($_POST['chamada']) ? sanitize_text_field(wp_unslash($_POST['chamada'])) : '';
            $wpdb->insert($table_name, array(
                'produto' => $produto,
                'bump' => $bump,
                'valor' => $valor,
                'chamada' => $chamada
            ));
            break;
        case "edit":
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            $produto = isset($_POST['produto']) ? sanitize_text_field(wp_unslash($_POST['produto'])) : '';
            $bump = isset($_POST['bump']) ? sanitize_text_field(wp_unslash($_POST['bump'])) : '';
            $valor = isset($_POST['valor']) ? floatval($_POST['valor']) : 0;
            $chamada = isset($_POST['chamada']) ? sanitize_text_field(wp_unslash($_POST['chamada'])) : '';
            $wpdb->update($table_name, array(
                'produto' => $produto,
                'bump' => $bump,
                'valor' => $valor,
                'chamada' => $chamada
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
					'slug'=>'order-bump',
					'title'=>__('Order Bump', 'checkout-mestres-wp'),
					'description'=>__('Create order bump promotions and increase your sales.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create Offer', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_vendas&type=vendas.order-bump-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_vendas&type=vendas.order-bump-',
					'bd'=>array(
						'name'=> 'cwmp_order_bump',
						'lines'=> array(
							'0'=>array(
								'type'=>'bump',
								'value'=>'produto,bump',
							),
							'1'=>array(
								'type'=>'percent',
								'value'=>'valor',
							)
						),
						'order'=>array(
							'by'=>'DESC',
							'value'=>'valor'
						),
					)
				),
			);
			cwmpAdminCreateLists($args);

			$args = array(
				'box'=> array(
					'0'=> array(
						'title'=>__('Box', 'checkout-mestres-wp'),
						'description'=>__('Customize the Order Bump box.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Background', 'checkout-mestres-wp'),
									'name'=>'cwmp_box_bump_background',
									'class'=>'coloris instance1',
									'info'=>__('Choose the box background color.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Primary Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_box_bump_primary',
									'class'=>'coloris instance1',
									'info'=>__('Select the primary color.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Secundary Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_box_bump_secundary',
									'class'=>'coloris instance1',
									'info'=>__('Select the secundary color.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Button Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_box_bump_button_color',
									'class'=>'coloris instance1',
									'info'=>__('Select the button text color.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="" class="cwmpOptions">';
			cwmpAdminCreateForms($args, 'cwmpUpdateAjaxTrue');
			echo '</form>';
			include "config.copyright.php";
