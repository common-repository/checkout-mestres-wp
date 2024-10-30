<?php
function cwmp_admin_plugins(){


	?>
	<div class="wrap">
	<h2></h2>
	<div class="mwpbody">
		<div class="mwpbrcolone">
			<div class="mwp-title">
				<svg width="150" height="169" viewBox="0 0 150 169" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 129.201V43.3397L16.3934 32.7092V119.389L27.8689 125.113V27.8028L43.4426 17.9901V134.108L54.0984 139.832V11.4482L75.4098 0L150 43.3397V129.201L108.197 152.098V134.108L133.607 119.389V51.517L75.4098 17.9901L68.8525 22.0787V168.452L0 129.201Z" fill="#EE451A"/><path d="M81.1475 168.452V65.4184L97.541 73.5957V108.758L105.738 104.669V65.4184L81.1475 51.517V34.3447L122.951 58.0588V114.482L97.541 129.201V157.822L81.1475 168.452Z" fill="#EE451A"/></svg>
			</div>

		</div>
		<div class="mwpbrcoltwo">
			<div class="mwpsectioncontent">
				
			<?php
				$cwmp_banners_arquivo = 'https://www.mestresdowp.com.br/checkout/plugins.php';
				$cwmp_banner_xml = wp_remote_get($cwmp_banners_arquivo, array(
					'method' => 'POST'
				));
				$cwmp_banner_xml = json_decode(wp_remote_retrieve_body($cwmp_banner_xml));
					
				foreach ($cwmp_banner_xml as $cwmp_banner) {
					foreach ($cwmp_banner as $banners) {
			?>
				
				<div style="display:flex;gap:20px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #CCC;">
				
					<div style="">
						<a href="<?php echo esc_url($banners->url); ?>"><img src="<?php echo wp_kses_post($banners->imagem); ?>" width="200" /></a>
					</div>
					<div style="">
						<?php if($banners->oferta==true){ ?><span style="text-transform:uppercase;font-weight:700;background:#3FC583;color:#FFF;display:inline-block;padding:5px;">Oferta</span><?php } ?>
						<a href="<?php echo esc_url($banners->url); ?>" style="text-decoration:none;"><h2 style="text-transform:uppercase;font-weight:900;color:#FF6243;"><?php echo esc_html($banners->name); ?></h2></a>
						<p style="color:#000;"><?php echo esc_html($banners->descricao); ?></p>
						<a href="<?php echo esc_url($banners->url); ?>" style="text-transform:uppercase;font-weight:700;text-decoration:none;color:#000;"><?php echo esc_html($banners->preco); ?></a>
					</div>
					
				</div>
				
				<?php
					}
					}
				?>
				
			</div>
		</div>
		<?php do_action("cwmp_admin_sidebar"); ?>
	</div>
	</div>
	<?php
	}
