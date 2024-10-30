<?php
if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_pending_payment_msg';
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    switch ($action) {
        case "add":
            $method = isset($_POST['method']) ? sanitize_text_field(wp_unslash($_POST['method'])) : '';
            $time = isset($_POST['time']) ? absint($_POST['time']) : 0;
            $time2 = isset($_POST['time2']) ? absint($_POST['time2']) : 0;
            $titulo = isset($_POST['titulo']) ? sanitize_text_field(wp_unslash($_POST['titulo'])) : '';
            $mensagem = isset($_POST['mensagem']) ? sanitize_text_field(wp_unslash($_POST['mensagem'])) : '';
            $body = isset($_POST['body']) ? wp_kses_post(wp_unslash($_POST['body'])) : '';
            $wpdb->insert($table_name, array(
                'method' => $method,
                'time' => $time,
                'time2' => $time2,
                'titulo' => $titulo,
                'mensagem' => $mensagem,
                'body' => $body
            ));
            break;
        case "edit":
            $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
            $method = isset($_POST['method']) ? sanitize_text_field(wp_unslash($_POST['method'])) : '';
            $time = isset($_POST['time']) ? absint($_POST['time']) : 0;
            $time2 = isset($_POST['time2']) ? absint($_POST['time2']) : 0;
            $titulo = isset($_POST['titulo']) ? sanitize_text_field(wp_unslash($_POST['titulo'])) : '';
            $mensagem = isset($_POST['mensagem']) ? sanitize_text_field(wp_unslash($_POST['mensagem'])) : '';
            $body = isset($_POST['body']) ? wp_kses_post(wp_unslash($_POST['body'])) : '';
            $wpdb->update($table_name, array(
                'method' => $method,
                'time' => $time,
                'time2' => $time2,
                'titulo' => $titulo,
                'mensagem' => $mensagem,
                'body' => $body
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
					'title'=>__('Payment Recovery', 'checkout-mestres-wp'),
					'description'=>__('Retrieve pending payments by sending emails and messages via WhatsApp.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create Recovery', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_vendas&type=vendas.recuperacao-pagamento-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_vendas&type=vendas.recuperacao-pagamento-',
					'bd'=>array(
						'name'=> 'cwmp_pending_payment_msg',
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
