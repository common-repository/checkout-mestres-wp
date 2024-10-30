<?php
	$args = array(
		'box'=>array(
			'0'=> array(
				
				'title'=>__( 'Smart Login', 'checkout-mestres-wp'),
				'description'=>__( 'Create the email template for the Smart Login.', 'checkout-mestres-wp'),
				'help'=>'https://docs.mestresdowp.com.br',
				'args'=>array(
					'0' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Subject', 'checkout-mestres-wp'),
							'name'=>'cwmp_remember_password_subject',
							'info'=>__( 'Enter the subject of the email.', 'checkout-mestres-wp'),
						),
					),
					'1' =>array(
						'type'=>'textarea',
						'value'=>array(
							'label'=>__( 'Body', 'checkout-mestres-wp'),
							'name'=>'cwmp_remember_password_body',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
				),
			),
			'2'=> array(
				'title'=>__( 'Gmail Social Login', 'checkout-mestres-wp'),
				'description'=>__( 'Create the email template for the Smart Login.', 'checkout-mestres-wp'),
				'help'=>'https://docs.mestresdowp.com.br',
				'args'=>array(
					'0' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Google Client ID', 'checkout-mestres-wp'),
							'name'=>'cwmp_google_client_id',
							'info'=>__( 'Enter the subject of the email.', 'checkout-mestres-wp'),
						),
					),
					'1' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Google Client Secret', 'checkout-mestres-wp'),
							'name'=>'cwmp_google_client_secret',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
				),
			),
			'3'=> array(
				'title'=>__( 'Facebook Social Login', 'checkout-mestres-wp'),
				'description'=>__( 'Create the email template for the Smart Login.', 'checkout-mestres-wp'),
				'help'=>'https://docs.mestresdowp.com.br',
				'args'=>array(
					'2' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Facebook APP ID', 'checkout-mestres-wp'),
							'name'=>'cwmp_facebook_app_id',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
					'3' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Facebook APP Secret', 'checkout-mestres-wp'),
							'name'=>'cwmp_facebook_app_secret',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
				),
			),
			'4'=> array(
				'title'=>__( 'Linkedin Social Login', 'checkout-mestres-wp'),
				'description'=>__( 'Create the email template for the Smart Login.', 'checkout-mestres-wp'),
				'help'=>'https://docs.mestresdowp.com.br',
				'args'=>array(
					'4' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Linkedin Client ID', 'checkout-mestres-wp'),
							'name'=>'cwmp_linkedin_client_id',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
					'5' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Linkedin Client Secret', 'checkout-mestres-wp'),
							'name'=>'cwmp_linkedin_client_secret',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
				),
			),
			'5'=> array(
				'title'=>__( 'Github Social Login', 'checkout-mestres-wp'),
				'description'=>__( 'Create the email template for the Smart Login.', 'checkout-mestres-wp'),
				'help'=>'https://docs.mestresdowp.com.br',
				'args'=>array(
					'0' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Github Client ID', 'checkout-mestres-wp'),
							'name'=>'cwmp_github_client_id',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
					'1' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Github Client Secret', 'checkout-mestres-wp'),
							'name'=>'cwmp_github_client_secret',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
				),
			),
			'6'=> array(
				'title'=>__( 'Amazon Social Login', 'checkout-mestres-wp'),
				'description'=>__( 'Create the email template for the Smart Login.', 'checkout-mestres-wp'),
				'help'=>'https://docs.mestresdowp.com.br',
				'args'=>array(
					'0' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Amazon Client ID', 'checkout-mestres-wp'),
							'name'=>'cwmp_amazon_client_id',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
					'1' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Amazon Client Secret', 'checkout-mestres-wp'),
							'name'=>'cwmp_amazon_client_secret',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
				),
			),
			'7'=> array(
				'title'=>__( 'Microsoft Social Login', 'checkout-mestres-wp'),
				'description'=>__( 'Create the email template for the Smart Login.', 'checkout-mestres-wp'),
				'help'=>'https://docs.mestresdowp.com.br',
				'args'=>array(
					'0' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Microsoft Client ID', 'checkout-mestres-wp'),
							'name'=>'cwmp_microsoft_client_id',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
						),
					),
					'1' =>array(
						'type'=>'text',
						'value'=>array(
							'label'=>__( 'Microsoft Client Secret', 'checkout-mestres-wp'),
							'name'=>'cwmp_microsoft_client_secret',
							'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
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
