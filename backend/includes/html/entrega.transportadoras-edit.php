			<?php

			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Update a carrier', 'checkout-mestres-wp'),
						'description'=>__('Create a carrier to send tracking links to your customers.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Update', 'checkout-mestres-wp'),
						'bd'=>'cwmp_transportadoras',
						'action'=>'externo',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Carrier', 'checkout-mestres-wp'),
									'name'=>'transportadora',
									'row'=>'transportadora',
									'info'=>__('Enter the name of the carrier.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Link', 'checkout-mestres-wp'),
									'name'=>'estrutura',
									'row'=>'estrutura',
									'info'=>__('Enter your custom tracking link.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'relation_shipping',
								'row'=>'relation_shipping',
								'title'=>__( 'Related Delivery Method', 'checkout-mestres-wp'),
								'description'=>__( 'Link the Delivery Method with the Carrier.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayShippingMethod(),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_entrega&type=entrega.transportadoras&action=edit">';
			cwmpAdminCreateForms($args, '');
			echo '</form>';
			include "config.copyright.php";
