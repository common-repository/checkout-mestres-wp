<?php

			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__( 'Connection', 'checkout-mestres-wp'),
						'description'=>__( 'Configure the connection data with the Whatsapp API you use.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'cwmp_template_whatsapp_type',
								'class'=>'cwmp_template_whatsapp_type',
								'title'=>__( 'Plan', 'checkout-mestres-wp'),
								'description'=>__( 'Let us know which plan you have contracted for.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'Select', 'checkout-mestres-wp'),
										'value'=>'0',
									),
									'2'=>array(
										'label'=>__( 'Whatstotal', 'checkout-mestres-wp'),
										'value'=>'2',
									),
									'3'=>array(
										'label'=>__( 'Personalizada', 'checkout-mestres-wp'),
										'value'=>'3',
									),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Endpoint', 'checkout-mestres-wp'),
									'name'=>'cwmp_key_endpoint_wpp',
									'class'=>'cwmp_key_endpoint_wpp',
									'info'=>__( 'Enter your WhatsApp API endpoint.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'URL', 'checkout-mestres-wp'),
									'name'=>'cwmp_key_url_wpp',
									'class'=>'cwmp_key_url_wpp',
									'info'=>__( 'Enter your WhatsApp API endpoint.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Method', 'checkout-mestres-wp'),
									'name'=>'cwmp_key_method_wpp',
									'class'=>'cwmp_key_method_wpp',
									'info'=>__( 'Enter your WhatsApp API endpoint.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__( 'Header', 'checkout-mestres-wp'),
									'name'=>'cwmp_key_header_wpp',
									'class'=>'cwmp_key_header_wpp',
									'info'=>__( 'Enter your WhatsApp API endpoint.', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__( 'Body', 'checkout-mestres-wp'),
									'name'=>'cwmp_key_body_wpp',
									'class'=>'cwmp_key_body_wpp',
									'info'=>__( 'Enter your WhatsApp API endpoint.', 'checkout-mestres-wp'),
								),
							),
						),
					),
					'1'=> array(
						'title'=>__( 'Settings', 'checkout-mestres-wp'),
						'description'=>__( 'Basic settings for sending transactional messages via Whatsapp.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__( 'Country', 'checkout-mestres-wp'),
									'name'=>'cwmp_whatsapp_ddi',
									'info'=>__( 'Enter the DDI of the country in which your store sells.', 'checkout-mestres-wp'),
								),
							),
						),
					),
					'2'=> array(
						'title'=>__( 'Notify Shopkeeper', 'checkout-mestres-wp'),
						'description'=>__( 'The retailer can be notified via Whatsapp about orders according to their status.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'cwmp_template_whatsapp_notify_lojista',
								'title'=>__( 'Notify Shopkeeper', 'checkout-mestres-wp'),
								'description'=>__( 'Let us know if you want to be notified via Whatsapp about orders from your store.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'1',
									),
									'1'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'0',
									),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Numbers', 'checkout-mestres-wp'),
									'name'=>'cwmp_whatsapp_number_lojista',
									'info'=>__( 'Enter the numbers you want to be notified by Whatsapp, if you want to be notified by more than one number, separate them with commas. (example: 5511999999999,5511999999999)', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'cwmp_template_whatsapp_status_active',
								'title'=>__( 'Status for notification', 'checkout-mestres-wp'),
								'description'=>__( 'Choose which status you would like to be notified about an order.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayStatus(),
							),
							'3' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__( 'Message', 'checkout-mestres-wp'),
									'name'=>'cwmp_whatsapp_template_lojista',
									'info'=>__( 'Enter the model that you would like to receive notifications for retailers via Whatsapp.', 'checkout-mestres-wp'),
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
