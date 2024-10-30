<?php
function cwmp_admin_checkout() {

	if (get_option('cwmp_license_active') == true) {
		$mwp_pages_admin = array(
			'checkout.config' => array('name' => __('Settings', 'checkout-mestres-wp')),
			'checkout.fields' => array('name' => __('Fields', 'checkout-mestres-wp')),
			'checkout.personalizacao' => array('name' => __('Personalization', 'checkout-mestres-wp')),
			'checkout.contas' => array('name' => __('Contas', 'checkout-mestres-wp')),
			'checkout.thankyou' => array('name' => __('Thank You Page', 'checkout-mestres-wp')),
			'checkout.trafego' => array('name' => __('Traffic', 'checkout-mestres-wp')),
		);
	} else {
		$mwp_pages_admin = array(
			'checkout.config' => array('name' => __('Settings', 'checkout-mestres-wp')),
		);
	}
	?>
	<div class="wrap">
		<h2></h2>
		<div class="mwpbody">
			<div class="mwpbrcolone">
				<div class="mwp-title">
					<svg width="150" height="169" viewBox="0 0 150 169" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M0 129.201V43.3397L16.3934 32.7092V119.389L27.8689 125.113V27.8028L43.4426 17.9901V134.108L54.0984 139.832V11.4482L75.4098 0L150 43.3397V129.201L108.197 152.098V134.108L133.607 119.389V51.517L75.4098 17.9901L68.8525 22.0787V168.452L0 129.201Z" fill="#EE451A"/>
						<path d="M81.1475 168.452V65.4184L97.541 73.5957V108.758L105.738 104.669V65.4184L81.1475 51.517V34.3447L122.951 58.0588V114.482L97.541 129.201V157.822L81.1475 168.452Z" fill="#EE451A"/>
					</svg>
				</div>
				<div class="mwp-sections">
					<ul>
						<?php
						$i = 0;
						foreach ($mwp_pages_admin as $mwp_pages_key => $mwp_pages_value) {
							$page = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : 'checkout.config';
							$request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
							$active = strpos($request_uri, $mwp_pages_key) !== false ? 'mpcw-section-active' : ($mwp_pages_key == $page ? 'mpcw-section-active' : '');

						?>
						<li class="<?php echo esc_html($active); ?> <?php echo esc_html($mwp_pages_key); ?> box_menu">
							<a href="admin.php?page=cwmp_admin_checkout&type=<?php echo esc_html($mwp_pages_key); ?>">
								<h4><?php echo esc_html($mwp_pages_value['name']); ?></h4>
								<svg width="15" height="24" viewBox="0 0 15 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M14.1744 11.3693L1.19313 0.174345C0.709062 -0.241547 0 0.133438 0 0.805001V23.195C0 23.8666 0.709062 24.2415 1.19313 23.8257L14.1744 12.6307C14.546 12.3102 14.546 11.6898 14.1744 11.3693Z" fill="#EE451A"/>
								</svg>
							</a>
						</li>
						<?php
							$i++;
						}
						?>
						<li><a href="https://docs.mestresdowp.com.br" target="_blank"><h4>Documentação</h4></a></li>
					</ul>
				</div>
			</div>
			<div class="mwpbrcoltwo">
				<div class="mwpsectioncontent">
					<div class="">
						<?php
						// Lista de páginas permitidas
						$allowed_pages = array_keys($mwp_pages_admin);
						$custom_pages = array('checkout.fields-add', 'checkout.fields-edit', 'checkout.trafego-add', 'checkout.trafego-edit');
						$allowed_pages = array_merge($allowed_pages, $custom_pages);
						if (isset($_GET['type'])) {
							$type = sanitize_file_name(wp_unslash($_GET['type']));
							
							// Verifica se o arquivo está na lista permitida e existe
							if (in_array($type, $allowed_pages, true) && file_exists(__DIR__ . "/html/{$type}.php")) {
								include __DIR__ . "/html/{$type}.php";
							} else {
								echo '<p>' . esc_html__('Invalid page type.', 'checkout-mestres-wp') . '</p>';
							}
						} else {
							include __DIR__ . "/html/checkout.config.php";
						}
						?>
					</div>
				</div>
			</div>
			<?php do_action("cwmp_admin_sidebar"); ?>
		</div>
	</div>
	<?php
}
?>
