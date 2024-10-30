<?php

if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_cart_abandoned_msg';
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    switch ($action) {
        case "add":
            $discount = isset($_POST['discount']) ? sanitize_text_field(wp_unslash($_POST['discount'])) : '';
            $discount_value = isset($_POST['discount_value']) ? floatval($_POST['discount_value']) : 0;
            $discount_time = isset($_POST['discount_time']) ? absint($_POST['discount_time']) : 0;
            $time = isset($_POST['time']) ? absint($_POST['time']) : 0;
            $time2 = isset($_POST['time2']) ? absint($_POST['time2']) : 0;
            $titulo = isset($_POST['titulo']) ? sanitize_text_field(wp_unslash($_POST['titulo'])) : '';
            $body = isset($_POST['body']) ? wp_kses_post(wp_unslash($_POST['body'])) : '';
            $mensagem = isset($_POST['mensagem']) ? sanitize_text_field(wp_unslash($_POST['mensagem'])) : '';
            $wpdb->insert($table_name, array(
                'discount' => $discount,
                'discount_value' => $discount_value,
                'discount_time' => $discount_time,
                'time' => $time,
                'time2' => $time2,
                'titulo' => $titulo,
                'body' => $body,
                'mensagem' => $mensagem
            ));
            break;
        case "edit":
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            $discount = isset($_POST['discount']) ? sanitize_text_field(wp_unslash($_POST['discount'])) : '';
            $discount_value = isset($_POST['discount_value']) ? floatval($_POST['discount_value']) : 0;
            $discount_time = isset($_POST['discount_time']) ? absint($_POST['discount_time']) : 0;
            $time = isset($_POST['time']) ? absint($_POST['time']) : 0;
            $time2 = isset($_POST['time2']) ? absint($_POST['time2']) : 0;
            $titulo = isset($_POST['titulo']) ? sanitize_text_field(wp_unslash($_POST['titulo'])) : '';
            $body = isset($_POST['body']) ? wp_kses_post(wp_unslash($_POST['body'])) : '';
            $mensagem = isset($_POST['mensagem']) ? sanitize_text_field(wp_unslash($_POST['mensagem'])) : '';
            $wpdb->update($table_name, array(
                'discount' => $discount,
                'discount_value' => $discount_value,
                'discount_time' => $discount_time,
                'time' => $time,
                'time2' => $time2,
                'titulo' => $titulo,
                'body' => $body,
                'mensagem' => $mensagem
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
					'slug'=>__( 'cart-recovery', 'checkout-mestres-wp'),
					'title'=>__( 'Cart Recovery', 'checkout-mestres-wp'),
					'description'=>__( 'Recover abandoned carts by sending emails and messages via WhatsApp.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__( 'Create Recovery', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_vendas&type=vendas.recuperacao-carrinhos-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_vendas&type=vendas.recuperacao-carrinhos-',
					'bd'=>array(
						'name'=> 'cwmp_cart_abandoned_msg',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'titulo',
							),
							'2'=>array(
								'type'=>'time',
								'value'=>'time,time2',
							),
						),
						'order'=>array(
							'by'=>'DESC',
							'value'=>'titulo'
						),
					)
				),
			);
			cwmpAdminCreateLists($args);
			include "config.copyright.php";
