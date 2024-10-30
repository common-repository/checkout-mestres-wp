		<?php
		if ( is_plugin_active( 'checkout-mestres-wp/checkout-woocommerce-mestres-wp.php' ) ) { ?>
			<div class="mwp-box">
				<div class="col-1">
					<h3><?php echo esc_html__( 'License', 'checkout-mestres-wp'); ?></h3>
					<?php if(get_option('cwmp_license_active')==true){ ?>
						<a name="Submit" class="action" id="cwmp_license_cwmwp_button_remove" style="cursor:pointer;" /><?php echo esc_html__( 'Disconnect License', 'checkout-mestres-wp'); ?></a>
					<?php }else{ ?>
						<p><?php echo esc_html__( 'Fill in the email field using the same one provided at the time of purchase and click connect license.', 'checkout-mestres-wp'); ?></p>
						<a name="Submit" class="action" id="cwmp_license_cwmwp_button" style="cursor:pointer;" /><?php echo esc_html__( 'Connect License', 'checkout-mestres-wp'); ?></a>
					<?php } ?>
					<a href="https://docs.mestresdowp.com.br"><?php echo esc_html__( 'Help? See the documentation', 'checkout-mestres-wp'); ?></a>
				</div>
				<div class="col-2" style="align-items: center;">
				<?php if(get_option('cwmp_license_active')==true){ ?>
						<p style="line-height:32px;">
						<?php echo esc_html__( 'Your license is active for email:', 'checkout-mestres-wp'); ?> <strong style="display:inline-block;"><?php echo esc_html(get_option('cwmp_license_email')); ?></strong>
						<br/>
						<?php
						
						if(get_option('cwmp_license_tipo')=="309632"){
							echo esc_html__( 'Licença de teste válida até', 'checkout-mestres-wp');
						}
						if(get_option('cwmp_license_tipo')=="309480"){
							echo esc_html__( 'Licença mensal válida até', 'checkout-mestres-wp');
						}
						if(get_option('cwmp_license_tipo')=="309481"){
							echo esc_html__( 'Licença anual válida até', 'checkout-mestres-wp');
						}
						if(get_option('cwmp_license_tipo')=="309482"){
							echo esc_html__( 'Licença bienal válida até', 'checkout-mestres-wp');
						}
						if(get_option('cwmp_license_tipo')=="309483" OR get_option('cwmp_license_tipo')=="0"){
							echo "<strong>".esc_html__( 'Licença Vitalícia', 'checkout-mestres-wp')."</strong>";
						}
						?>
						<strong style="display:inline-block;">
							<?php
							if(get_option('cwmp_license_expired')=="0000-00-00"){
							}else{
								$dataObj = new DateTime(get_option('cwmp_license_expired'));
								$dataAtualObj = new DateTime($dataAtual);
								$dataResultado = $dataObj->format('d/m/Y');
								$diferenca = $dataAtualObj->diff($dataObj);
								$diasFaltantes = $diferenca->days;
								//echo $dataResultado;
								if ($diasFaltantes <= 3) {
									if(get_option('cwmp_license_tipo')=="309480" && get_option('cwmp_license_tipo')=="309481" && get_option('cwmp_license_tipo')=="309482"){
										echo '<a href="www.mestresdowp.com.br/finalizar-compra/?add-to-cart='.esc_html(get_option('cwmp_license_tipo')).'"  target="blank" style="margin-left:5px;background:green;color:#FFF;text-transform:uppercase;padding:7px 15px;text-decoration:none;border-radius:15px;">Renovar plano</a>';
									}
									if(get_option('cwmp_license_tipo')=="309632"){
										echo '<a href="https://www.mestresdowp.com.br/produto/chechout-mestres-do-wp/" target="blank" style="margin-left:5px;background:green;color:#FFF;text-transform:uppercase;padding:7px 15px;text-decoration:none;border-radius:15px;">Comprar plano</a>';
									}
								}
							}
							?>
						</strong> 
						</p>
				<?php }else{ ?>
				<strong><?php echo esc_html__( 'Plan', 'checkout-mestres-wp'); ?></strong>
				<select name="cwmp_license_cmwp_tipo" class="input-150" value="<?php echo esc_html(get_option('cwmp_license_cwmwp_tipo')); ?>">
					<option value='1' <?php if(get_option('cwmp_license_cwmwp_tipo')=="1"){ echo "selected"; } ?>><?php echo esc_html__( 'Vitalício', 'checkout-mestres-wp'); ?></option>
					<option value='2' <?php if(get_option('cwmp_license_cwmwp_tipo')=="2"){ echo "selected"; } ?>><?php echo esc_html__( 'Recorrente', 'checkout-mestres-wp'); ?></option>
				</select>
				</p>
				<p class="col col-1-2">
					<strong><?php echo esc_html__( 'E-mail', 'checkout-mestres-wp'); ?></strong>
					<input type="text" name="cwmp_license_cmwp_email" class="input-150" placeholder="E-mail" value="<?php echo esc_html(get_option('cwmp_license_cwmwp_email')); ?>" />
					<input type="hidden" name="cwmp_license_cmwp_url" placeholder="E-mail" value="<?php echo bloginfo('url'); ?>" />
				</p>
				<?php } ?>
				</div>
			</div>
		<?php
		include "config.copyright.php";
		}
 ?>
