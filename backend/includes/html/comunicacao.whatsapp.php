<?php

if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_template_msgs';
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    switch ($action) {
        case "add":
            if (isset($_POST['metodo']) && is_array($_POST['metodo'])) {
                $metodos = array_map('sanitize_text_field', wp_unslash($_POST['metodo']));
                $status = isset($_POST['status']) ? sanitize_text_field(wp_unslash($_POST['status'])) : '';
                $conteudo = isset($_POST['conteudo']) ? wp_kses_post(wp_unslash($_POST['conteudo'])) : '';
                $seq = isset($_POST['seq']) ? absint($_POST['seq']) : 0;
                $webhook = isset($_POST['webhook']) ? sanitize_text_field(wp_unslash($_POST['webhook'])) : '';
                foreach ($metodos as $metodo) {
                    $wpdb->insert($table_name, array(
                        'metodo' => $metodo,
                        'status' => $status,
                        'conteudo' => $conteudo,
                        'seq' => $seq,
                        'webhook' => $webhook
                    ));
                }
            }
            break;
        case "edit":
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            $metodo = isset($_POST['metodo']) ? sanitize_text_field(wp_unslash($_POST['metodo'])) : '';
            $status = isset($_POST['status']) ? sanitize_text_field(wp_unslash($_POST['status'])) : '';
            $conteudo = isset($_POST['conteudo']) ? wp_kses_post(wp_unslash($_POST['conteudo'])) : '';
            $seq = isset($_POST['seq']) ? absint($_POST['seq']) : 0;
            $webhook = isset($_POST['webhook']) ? sanitize_text_field(wp_unslash($_POST['webhook'])) : '';
            $wpdb->update($table_name, array(
                'metodo' => $metodo,
                'status' => $status,
                'conteudo' => $conteudo,
                'seq' => $seq,
                'webhook' => $webhook
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
					'slug'=>'whatsapp',
					'title'=>__( 'Mensagens Transacionais', 'checkout-mestres-wp'),
					'description'=>__( 'Create your personalized transactional message', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__( 'Create Message', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.whatsapp-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.whatsapp-',
					'bd'=>array(
						'name'=> 'cwmp_template_msgs',
						'lines'=> array(
							'0'=>array(
								'type'=>'status',
								'value'=>'status',
							),
							'1'=>array(
								'type'=>'shipping',
								'value'=>'metodo',
							),
							'2'=>array(
								'type'=>'number',
								'value'=>'seq',
							),
						),
						'order'=>array(
							'by'=>'ASC',
							'value'=>'status'
						)
					)
				),
			);
			cwmpAdminCreateLists($args);
			include "config.copyright.php";
