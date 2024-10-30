<?php
function cwmpAdminCreateButtons($args){
	foreach($args['box'] as $box){
		echo '<div class="mwp-box">';
			echo '<div class="col-1">';
			echo '<h3>'.esc_html($box['title']).'</h3>';
			echo '<p>'.esc_html($box['description']).'</p>';
			if(!empty($box['button']['url'])){ echo '<a href="'.esc_url($box['button']['url']).'" class="action">'.esc_html($box['button']['label']).'</a>'; }
			if(!empty($box['help'])){ echo '<a href="'.esc_url($box['help']).'">Dúvidas? Veja a documentação</a>'; }
			echo '</div>';
			echo '<ul class="col-2">';
			echo '<li style="text-align:right;">';
				if(get_option("cwmp_".$box['button']['id'])=="S"){
					echo '<input type="image" class="buttonFuncionalidade" id="'.esc_html($box['button']['id']).'" src="'.esc_url(CWMP_PLUGIN_ADMIN_URL).'assets/images/mwp-ico-on.png" width="80" alt="" style="width:80px !important;display:inline;" />';
				}else{
					echo '<input type="image" class="buttonFuncionalidade" id="'.esc_html($box['button']['id']).'" src="'.esc_url(CWMP_PLUGIN_ADMIN_URL).'assets/images/mwp-ico-off.png" width="80" alt="" style="width:80px !important;display:inline;" />';
				}
				echo '</li>';
			echo '</ul>';
		echo '</div>';
	}
	if (isset($_GET['limpeza_sucesso']) && $_GET['limpeza_sucesso'] == 'true') {
		echo '<div class="notice notice-success is-dismissible"><p>Registros limpos com sucesso!</p></div>';
	}
		echo '<form method="post" action="">';
		submit_button('Full Reset', 'primary', 'cwmpFullResetExecute');
		echo '</form>';
}