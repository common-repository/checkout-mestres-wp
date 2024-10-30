<?php
function cwmp_admin_diagnostico(){
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
			
			
			
			
				<div id="health-check-site-status-recommended" class="health-check-accordion issues" bis_skin_checked="1">
				
				
					<?php
					$checkwp = cwmp_check_wordpress_update_status();
					if($checkwp==0){
					?>
					<h4 class="health-check-accordion-heading">
						<button aria-expanded="true" class="health-check-accordion-trigger" aria-controls="health-check-accordion-block-wp_version" type="button">
							<span class="title">Você deve atualizar o WordPress</span>
							
								<span class="badge blue">Segurança</span>
							
							<span class="icon"></span>
						</button>
					</h4>
					<div id="health-check-accordion-block-wp_version" class="health-check-accordion-panel" bis_skin_checked="1">
						<p>Manter o WordPress atualizado é crucial para garantir a segurança e o desempenho do seu site. Atualizações frequentes corrigem vulnerabilidades de segurança, protegem contra ataques cibernéticos e melhoram a estabilidade do sistema. Além disso, as atualizações trazem novos recursos e melhorias de desempenho que podem otimizar a experiência do usuário e facilitar a gestão do site. Manter o WordPress, os plugins e os temas sempre na versão mais recente ajuda a evitar incompatibilidades e garante um funcionamento fluido do seu site.</p>
					</div>
					<?php } ?>
					<?php
					$checkwoo = cwmp_check_woocommerce_update_status();
					if($checkwoo==0){
					?>
					<h4 class="health-check-accordion-heading">
						<button aria-expanded="true" class="health-check-accordion-trigger" aria-controls="health-check-accordion-block-woo_version" type="button">
							<span class="title">Você deve atualizar o WooCommerce</span>
							
								<span class="badge blue">Segurança</span>
							
							<span class="icon"></span>
						</button>
					</h4>
					<div id="health-check-accordion-block-woo_version" class="health-check-accordion-panel" bis_skin_checked="1">
						<p>Manter o WooCommerce atualizado é essencial para garantir a segurança e o funcionamento adequado da sua loja virtual. Atualizações do WooCommerce corrigem vulnerabilidades de segurança que podem ser exploradas por hackers e introduzem novas funcionalidades que aprimoram a experiência do cliente e a gestão de vendas. Além disso, as atualizações garantem compatibilidade com a última versão do WordPress e dos plugins, prevenindo conflitos que podem causar falhas no site. Para garantir que sua loja funcione de maneira eficiente e segura, é fundamental manter o WooCommerce sempre na versão mais recente.</p>
					</div>
					<?php } ?>
					<?php
					$checkAllPlugins = cwmp_check_all_plugins_update_status();
					if($checkAllPlugins!=1){
					?>
					<h4 class="health-check-accordion-heading">
						<button aria-expanded="true" class="health-check-accordion-trigger" aria-controls="health-check-accordion-block-plugins_version" type="button">
							<span class="title">Você tem plugins desatualizados</span>
							
								<span class="badge blue">Segurança</span>
							
							<span class="icon"></span>
						</button>
					</h4>
					<div id="health-check-accordion-block-plugins_version" class="health-check-accordion-panel" bis_skin_checked="1">
						<p>Manter os plugins do WordPress atualizados é fundamental para a segurança, desempenho e funcionalidade do seu site. Plugins desatualizados podem conter vulnerabilidades que permitem ataques cibernéticos, comprometendo dados e o funcionamento da plataforma. Além disso, as atualizações de plugins trazem correções de bugs, melhorias de desempenho e novos recursos, garantindo que seu site se mantenha rápido, eficiente e compatível com a versão mais recente do WordPress. Ao manter os plugins atualizados, você protege seu site e garante uma melhor experiência para seus usuários.</p>
					</div>
					<?php } ?>
				</div>				
			</div>
		</div>
		<?php do_action("cwmp_admin_sidebar"); ?>
	</div>
	</div>
	<?php
	}
