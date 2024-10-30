			<?php

			$args = array(
				'box'=>array(
					'slug'=>__( 'cart-recovery', 'checkout-mestres-wp'),
					'title'=>__('Pending Orders', 'checkout-mestres-wp'),
					'description'=>__('See the list of unpaid orders in your store.', 'checkout-mestres-wp'),
					'help'=>'https://docs.mestresdowp.com.br',
					'action'=>array(
						'value'=>'start'
					),
					'orders'=>array(
						'status'=> 'pending,on-hold',
						'lines'=> array(
							'0'=>array(
								'type'=>'createDate',
								'value'=>'id',
							),
						),
						'order'=>array(
							'by'=>'ASC',
							'value'=>'status'
						),

					)
				),
			);
			cwmpAdminCreateListsOrders($args);
			include "config.copyright.php";

