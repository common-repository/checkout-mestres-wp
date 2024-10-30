			<?php

			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Create your personalized transactional email', 'checkout-mestres-wp'),
						'description'=>__('Create your personalized transactional email for better communication with your customers.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Register', 'checkout-mestres-wp'),
						'action'=>'externo',
						'args'=>array(
							'0' =>array(
								'type'=>'multiple',
								'id'=>'metodo',
								'title'=>__('Payment Method', 'checkout-mestres-wp'),
								'description'=>__('Choose the payment method for the personalized transactional email.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayPaymentMethods(),
							),
							'1' =>array(
								'type'=>'select',
								'id'=>'status',
								'title'=>__('Status', 'checkout-mestres-wp'),
								'description'=>__('Choose your custom transactional email status.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayStatusCom(),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Subject', 'checkout-mestres-wp'),
									'name'=>'titulo',
									'info'=>__('Enter the title of the personalized transactional email.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__('Body', 'checkout-mestres-wp'),
									'name'=>'conteudo',
									'info'=>__('Enter the body of the content of the personal transactional email in HTML.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_comunicacao&type=comunicacao.email&action=add">';
			cwmpAdminCreateForms($args, '');
			echo '</form>';
			include "config.copyright.php";
