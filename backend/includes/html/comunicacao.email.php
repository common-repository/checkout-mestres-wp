<?php

if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_template_emails';
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    switch ($action) {
        case "add":
            if (isset($_POST['metodo']) && is_array($_POST['metodo'])) {
                $metodos = array_map('sanitize_text_field', wp_unslash($_POST['metodo']));
                $status = isset($_POST['status']) ? sanitize_text_field(wp_unslash($_POST['status'])) : '';
                $titulo = isset($_POST['titulo']) ? sanitize_text_field(wp_unslash($_POST['titulo'])) : '';
                $conteudo = isset($_POST['conteudo']) ? wp_kses_post(wp_unslash($_POST['conteudo'])) : '';
                foreach ($metodos as $metodo) {
                    $wpdb->insert($table_name, array(
                        'metodo' => $metodo,
                        'status' => $status,
                        'titulo' => $titulo,
                        'conteudo' => $conteudo,
                    ));
                }
            }
            break;
        case "edit":
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            $metodo = isset($_POST['metodo']) ? sanitize_text_field(wp_unslash($_POST['metodo'])) : '';
            $status = isset($_POST['status']) ? sanitize_text_field(wp_unslash($_POST['status'])) : '';
            $titulo = isset($_POST['titulo']) ? sanitize_text_field(wp_unslash($_POST['titulo'])) : '';
            $conteudo = isset($_POST['conteudo']) ? wp_kses_post(wp_unslash($_POST['conteudo'])) : '';
            $wpdb->update($table_name, array(
                'metodo' => $metodo,
                'status' => $status,
                'titulo' => $titulo,
                'conteudo' => $conteudo
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
					'slug'=>'emails',
					'title'=>__('Transactional Emails', 'checkout-mestres-wp'),
					'description'=>__('Send personalized transactional emails to your customers.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create Transactional Email', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.email-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.email-',
					'bd'=>array(
						'name'=> 'cwmp_template_emails',
						'lines'=> array(
							'2'=>array(
								'type'=>'text',
								'value'=>'titulo',
							),
							'0'=>array(
								'type'=>'status',
								'value'=>'status',
							),
							'1'=>array(
								'type'=>'shipping',
								'value'=>'metodo',
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
