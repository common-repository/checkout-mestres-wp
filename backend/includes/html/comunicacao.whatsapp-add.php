			<?php

			for ($i = 1; $i <= 10; $i++) {
				$returnSeq[$i]['label']=$i;
				$returnSeq[$i]['value']=$i;
			}
			$i=0;
			$wc_gateways = new WC_Payment_Gateways();
			$payment_gateways = $wc_gateways->payment_gateways();
			foreach( $payment_gateways as $gateway_id => $gateway ){
				if($gateway->enabled=="yes"){
					$returnMethodPayment[$i]['label']=$gateway->title;
					$returnMethodPayment[$i]['value']=$gateway->id;
					$i++;
				}
			}
			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Settings', 'checkout-mestres-wp'),
						'description'=>__('Create your personalized transactional message to better communicate with your customers.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Register', 'checkout-mestres-wp'),
						'action'=>'externo',
						'args'=>array(
							'0' =>array(
								'type'=>'multiple',
								'id'=>'metodo',
								'title'=>__('Payment Method', 'checkout-mestres-wp'),
								'description'=>__('Choose the payment method for the personalized transactional message.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayPaymentMethods(),
							),
							'1' =>array(
								'type'=>'select',
								'id'=>'status',
								'title'=>__('Status', 'checkout-mestres-wp'),
								'description'=>__('Choose your custom transactional message status.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayStatusCom(),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'seq',
								'title'=>__('Sequence', 'checkout-mestres-wp'),
								'description'=>__('Choose the sequential order of the transactional message.', 'checkout-mestres-wp'),
								'options'=>$returnSeq,
							),
							'3' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__('Message', 'checkout-mestres-wp'),
									'name'=>'conteudo',
									'info'=>__('Enter the body of the transactional message.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			
			echo '<form method="post" action="admin.php?page=cwmp_admin_comunicacao&type=comunicacao.whatsapp&action=add">';
			cwmpAdminCreateForms($args, '');
			echo '</form>';
			include "config.copyright.php";
