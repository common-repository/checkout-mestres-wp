<?php

if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_template_emails_produto';
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    switch ($action) {
        case "add":
            $metodo = isset($_POST['cwmp_template_email_payment']) ? sanitize_text_field(wp_unslash($_POST['cwmp_template_email_payment'])) : '';
            $status = isset($_POST['cwmp_template_email_status']) ? sanitize_text_field(wp_unslash($_POST['cwmp_template_email_status'])) : '';
            $time = isset($_POST['time']) ? absint($_POST['time']) : 0;
            $time2 = isset($_POST['time2']) ? absint($_POST['time2']) : 0;
            $titulo = isset($_POST['cwmp_template_email_title']) ? sanitize_text_field(wp_unslash($_POST['cwmp_template_email_title'])) : '';
            $conteudo = isset($_POST['cwmp_template_email_conteudo']) ? wp_kses_post(wp_unslash($_POST['cwmp_template_email_conteudo'])) : '';
            $msg = isset($_POST['msg']) ? sanitize_text_field(wp_unslash($_POST['msg'])) : '';
            $wpdb->insert($table_name, array(
                'metodo' => $metodo,
                'status' => $status,
                'time' => $time,
                'time2' => $time2,
                'titulo' => $titulo,
                'conteudo' => $conteudo,
                'msg' => $msg
            ));
            break;
        case "edit":
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            $metodo = isset($_POST['cwmp_template_email_payment']) ? sanitize_text_field(wp_unslash($_POST['cwmp_template_email_payment'])) : '';
            $status = isset($_POST['cwmp_template_email_status']) ? sanitize_text_field(wp_unslash($_POST['cwmp_template_email_status'])) : '';
            $time = isset($_POST['time']) ? absint($_POST['time']) : 0;
            $time2 = isset($_POST['time2']) ? absint($_POST['time2']) : 0;
            $titulo = isset($_POST['cwmp_template_email_title']) ? sanitize_text_field(wp_unslash($_POST['cwmp_template_email_title'])) : '';
            $conteudo = isset($_POST['cwmp_template_email_conteudo']) ? wp_kses_post(wp_unslash($_POST['cwmp_template_email_conteudo'])) : '';
            $msg = isset($_POST['msg']) ? sanitize_text_field(wp_unslash($_POST['msg'])) : '';
            $wpdb->update($table_name, array(
                'metodo' => $metodo,
                'status' => $status,
                'time' => $time,
                'time2' => $time2,
                'titulo' => $titulo,
                'conteudo' => $conteudo,
                'msg' => $msg
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
					'slug'=>'email-produto',
					'title'=>__('Email per product', 'checkout-mestres-wp'),
					'description'=>__('Send emails with information about products sold.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create email', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.email-produto-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.email-produto-',
					'bd'=>array(
						'name'=> 'cwmp_template_emails_produto',
						'lines'=> array(
							'2'=>array(
								'type'=>'product',
								'value'=>'metodo',
							),
							'0'=>array(
								'type'=>'text',
								'value'=>'titulo',
							),
							'1'=>array(
								'type'=>'status',
								'value'=>'status',
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
