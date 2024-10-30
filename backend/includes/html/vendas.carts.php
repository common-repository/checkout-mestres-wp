<?php

			$args = array(
				'box'=>array(
					'title'=>__('Abandoned Carts', 'checkout-mestres-wp'),
					'description'=>__('See the list of abandoned carts in your store.', 'checkout-mestres-wp'),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_vendas&type=vendas.recuperacao-pagamento-',
					'bd'=>array(
						'name'=> 'cwmp_cart_abandoned',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'nome',
							),
							'1'=>array(
								'type'=>'productsCart',
								'value'=>'email',
							),
							'2'=>array(
								'type'=>'emailL',
								'value'=>'email',
							),
							'3'=>array(
								'type'=>'whatsappL',
								'value'=>'phone',
							),
							'4'=>array(
								'type'=>'data',
								'value'=>'time',
							),

						),
						'order'=>array(
							'by'=>'DESC',
							'value'=>'time'
						),
					)
				),
			);
			cwmpAdminCreateListsCarts($args);
			include "config.copyright.php";
