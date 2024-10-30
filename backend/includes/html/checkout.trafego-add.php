			<?php
			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Create your pixel', 'checkout-mestres-wp'),
						'description'=>__('Create your pixel to use integration with Facebook Ads, Google Ads, Google Analytics, etc.', 'checkout-mestres-wp'),
						'formButton'=>__('Register', 'checkout-mestres-wp'),
						'action'=>'externo',
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'tipo',
								'title'=>__('Integration', 'checkout-mestres-wp'),
								'description'=>__('Choose the integration you want to use.', 'checkout-mestres-wp'),
								'options'=>array(
									'1'=>array('label'=>'Google Tag Manager','value'=>'GTM'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Pixel', 'checkout-mestres-wp'),
									'name'=>'pixel',
									'info'=>__('Enter the Pixel ID of your integration.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Token', 'checkout-mestres-wp'),
									'name'=>'token',
									'info'=>__('Enter the Token for your integration.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Test Code', 'checkout-mestres-wp'),
									'name'=>'test',
									'info'=>__('Enter the Test Code for your integration.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Reference', 'checkout-mestres-wp'),
									'name'=>'ref',
									'info'=>__('Enter the reference title of your integration.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_checkout&type=checkout.trafego&action=add">';
			cwmpAdminCreateForms($args, '');
			echo '</form>';
			include "config.copyright.php";
