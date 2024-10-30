<?php
function cwmpAdminCreateForms($args, $ajax){
	global $wpdb, $table_prefix;
	$url = '';
	foreach($args as $args){
		foreach($args as $box){
		if(!empty($box['typeThankyou'])){
			if(is_array($box['typeThankyou'])){
				foreach(cwmpArrayPaymentMethods() as $valor){
					echo '<div class="mwp-box">';
						echo '<div class="col-1">';
						if(isset($valor['label'])){
							echo '<h3>'.esc_html($valor['label']).'</h3>';
						}
						if(isset($valor['value'])){
						echo '<p>#'.esc_html($valor['value']).'</p>';
						}
						echo '</div>';
						echo '<div class="col-2">';
						echo '<p>';
						echo '<strong>'.esc_html__('Pending payment', 'checkout-mestres-wp').'</strong>';
						echo '<span>'.esc_html__('It is mandatory to choose custom thank you page for pending payments.', 'checkout-mestres-wp').'</span>';
						echo '<select name="cwmp_thankyou_page_pending_'.esc_html(str_replace("-","_",$valor['value'])).'" class="input-150 select2-offscreen" id="cwmp_thankyou_page_pending_'.esc_html(str_replace("-","_",$valor['value'])).'" tabindex="-1" title="">';
						$pages = cwmpArrayPages();
						foreach($pages as $page){
							if(get_option('cwmp_thankyou_page_pending_'.str_replace("-","_",$valor['value']))==$page['value']){ $selected="selected"; }else{ $selected=""; }
							echo '<option value="'.esc_html($page['value']).'" '.esc_html($selected).'>'.esc_html($page['label']).'</option>';
						}
						echo '</select>';
						$url .= 'cwmp_thankyou_page_pending_'.str_replace("-","_",$valor['value']).",";
						echo '</p>';
						echo '<p>';
						echo '<strong>'.esc_html__('Success', 'checkout-mestres-wp').'</strong>';
						echo '<span>'.esc_html__('It is mandatory to choose custom thank you page for paid orders.', 'checkout-mestres-wp').'</span>';
						echo '<select name="cwmp_thankyou_page_aproved_'.esc_html(str_replace("-","_",$valor['value'])).'" class="input-150 select2-offscreen" id="cwmp_thankyou_page_aproved_'.esc_html(str_replace("-","_",$valor['value'])).'" tabindex="-1" title="">';
						$pages = cwmpArrayPages();
						foreach($pages as $page){
							if(get_option('cwmp_thankyou_page_aproved_'.str_replace("-","_",$valor['value']))==$page['value']){ $selected="selected"; }else{ $selected=""; }
							echo '<option value="'.esc_html($page['value']).'" '.esc_html($selected).'>'.esc_html($page['label']).'</option>';
						}
						echo '</select>';
						$url .= 'cwmp_thankyou_page_aproved_'.str_replace("-","_",$valor['value']).",";
						echo '</p>';
						echo '</div>';
					echo '</div>';
				}

			}
		}else{
			if(!empty($box['bd'])){
				$id = isset($_GET['id']) ? sanitize_text_field(wp_unslash($_GET['id'])) : '';
				$table_name = $table_prefix . $box['bd'];
				$result = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM $table_name WHERE id = %d",
						$id
					)
				);
				echo'<input type="hidden" name="id" id="id" size="45" value="'.esc_html($id).'"  />';
			}
			echo '<div class="mwp-box">';
				echo '<div class="col-1">';
				if(isset($box['title'])){
				echo '<h3>'.esc_html($box['title']).'</h3>';
				}
				if(isset($box['description'])){
				echo '<p>'.esc_html($box['description']).'</p>';
				}
				if(!empty($box['button']['url'])){ echo '<a href="'.esc_url($box['button']['url']).'" class="action">'.esc_html($box['button']['label']).'</a>'; }
				if(!empty($box['help'])){ echo '<a href="'.esc_url($box['help']).'" target="blank">'.esc_html__( 'Help? See the documentation', 'checkout-mestres-wp').'</a>'; }
				echo '</div>';
				echo '<div class="col-2">';
				if(isset($box['args'])){
				foreach($box['args'] as $option){
					echo '<p>';
					if(is_array($option['type'])){
						echo '<p ';
						if(isset($option['value']['class'])){ echo 'id="'.esc_html($option['value']['class']).'" '; }
						echo '>';
						echo '<strong>'.esc_html($option['label']).'</strong>';
						echo '<span>'.esc_html($option['info']).'</span>';
						foreach($option['type'] as $fields){
							echo '<input ';
							if(isset($fields['type'])){ echo 'type="'.esc_html($fields['type']).'"'; }
							if(isset($fields['value']['name'])){ echo ' name="'.esc_html($fields['value']['name']).'"'; }
							if(isset($fields['value']['class'])){ echo ' class="array '.esc_html($fields['value']['class']).'"'; }
							if(isset($fields['value']['placeholder'])){ echo ' placeholder="'.esc_html($fields['value']['placeholder']).'"'; }
							if(isset($fields['value']['step'])){ echo ' step="0.01"'; }
							echo ' value="'.esc_html($value).'" />';
							if(isset($fields['value'])){ $url .= $fields['value']['name'].','; }
						}
						echo '</p>';
					}else{
						if($option['type']=="icon"){
							echo '<p ';
							if(isset($option['value']['class'])){ echo 'id="'.esc_html($option['value']['class']).'" '; }
							echo '>';
							echo '<strong>'.esc_html($option['value']['label']).'</strong>';
							echo '<span>'.esc_html($option['value']['info']).'</span>';
							echo '<div class="input-group">';
							echo '<input data-placement="bottomRight" class="icp demo2" name="'.esc_html($option['value']['name']).'" value="'.esc_html(get_option($option['value']['name'])).'" type="hidden"/>';
							echo '<span class="input-group-addon"></span>';
							echo '</div>';
							echo '</p>';
							$url .= $option['value']['name'].',';
						}elseif($option['type']=="datetime"){
							if(!empty($box['bd'])){ $value=$result[0]->{$option['value']['row']}; }else{ $value=esc_html(get_option($option['value']['name'])); }
							echo '<p ';
							if(isset($option['value']['class'])){ echo 'id="'.esc_html($option['value']['class']).'" '; }
							echo '>';
							if(isset($option['value']['label'])){
							echo '<strong>'.esc_html($option['value']['label']).'</strong>';
							}
							if(isset($option['value']['info'])){
							echo '<span>'.esc_html($option['value']['info']).'</span>';
							}
							echo '<input type="datetime-local" ';
							if(isset($option['value']['name'])){
							echo 'name="'.esc_html($option['value']['name']).'" ';
							}
							if(isset($option['value']['class'])){
							echo 'class="'.esc_html($option['value']['class']).'" ';
							}
							if(isset($option['value']['placeholder'])){
							echo 'placeholder="'.esc_html($option['value']['placeholder']).'" ';
							}
							echo 'value="'.esc_html($value).'" ';
							echo '/>';
							echo '</p>';
							$url .= $option['value']['name'].',';
						}elseif($option['type']=="checkbox"){
							echo '<p class="'.esc_html($option['value']['name']).'">';
							echo '<strong>'.esc_html($option['title']).'</strong>';
							echo '<span>'.esc_html($option['description']).'</span>';
							foreach($option['options'] as $rows){
								if(!empty($box['bd'])){
									if($rows['value']==$result[0]->{$option['row']}){ $selected = "selected"; }else{ $selected = ""; }
								}else{
									if($rows['value']==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
								}
								echo '<label for="'.esc_html($rows['value']).'"><input type="checkbox" name="'.esc_html($rows['value']).'" value="'.esc_html($rows['value']).'" id="'.esc_html($rows['value']).'" '.esc_html($selected).' />'.esc_html($rows['label']).'</label>';
								$url .= $rows['value'].',';
							}
							echo '</p>';
							
						}elseif($option['type']=="select"){
							echo '<p ';
							if(isset($option['class'])){ echo 'id="'.esc_html($option['class']).'" '; }
							echo '>';
							echo '<strong>'.esc_html($option['title']).'</strong>';
							echo '<span>'.esc_html($option['description']).'</span>';
							echo '<select ';
							if(isset($option['id'])){
							echo 'name="'.esc_html($option['id']).'" ';
							}
							if(isset($option['class'])){
							echo 'class="'.esc_html($option['class']).'" ';
							}
							echo '/>';
							foreach($option['options'] as $rows){
								if(!empty($box['bd'])){
									if($rows['value']==$result[0]->{$option['row']}){ $selected = "selected"; }else{ $selected = ""; }
								}else{
									if($rows['value']==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
								}
								echo '<option value="'.esc_html($rows['value']).'" '.esc_html($selected).'>'.esc_html($rows['label']).'</option>';
							}
							echo '</select />';
							echo '</p>';
							if(isset($option['id'])){
							$url .= $option['id'].',';
							}
						}elseif($option['type']=="multiple"){
							echo '<p ';
							if(isset($option['value']['class'])){ echo 'id="'.esc_html($option['class']).'" '; }
							echo '>';
							echo '<strong>'.esc_html($option['title']).'</strong>';
							echo '<span>'.esc_html($option['description']).'</span>';
							echo '<select ';
							if(isset($option['id'])){
							echo 'name="'.esc_html($option['id']).'[]" ';
							}
							if(isset($option['class'])){
							echo 'class="'.esc_html($option['class']).'" ';
							}
							echo 'multiple />';
							foreach($option['options'] as $rows){
								if(!empty($box['bd'])){
									if($rows['value']==$result[0]->{$option['row']}){
										$selected = "selected"; }else{ $selected = ""; }
								}else{
									if($rows['value']==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
								}
								echo '<option value="'.esc_html($rows['value']).'" '.esc_html($selected).'>'.esc_html($rows['label']).'</option>';
							}
							echo '</select />';
							echo '</p>';
							if(isset($option['value']['name'])){
							$url .= $option['value']['name'].',';
							}
						}elseif($option['type']=="textarea"){
							if(!empty($box['bd'])){ $value=$result[0]->{$option['value']['row']}; }else{ $value=esc_html(get_option($option['value']['name'])); }
							echo '<p ';
							if(isset($option['value']['class'])){ echo 'id="'.esc_html($option['value']['class']).'"  '; }
							echo '>';
							echo '<strong>'.esc_html($option['value']['label']).'</strong>';
							echo '<span>'.esc_html($option['value']['info']).'</span>';
							echo '<textarea ';
							if(isset($option['value']['name'])){
							echo 'name="'.esc_html($option['value']['name']).'" ';
							}
							if(isset($option['value']['class'])){
							echo 'class="'.esc_html($option['value']['class']).'" ';
							}
							if(isset($option['value']['placeholder'])){
							echo 'placeholder="'.esc_html($option['value']['placeholder']).'"';
							}
							echo '>'.esc_html(str_replace("\'","'",str_replace('\"','"',$value))).'</textarea>';
							echo '</p>';
							if(isset($option['value']['name'])){
							$url .= $option['value']['name'].',';
							}
						}elseif($option['type']=="payment_method"){
							echo '<p ';
							if(isset($option['value']['class'])){ echo 'id="'.esc_html($option['value']['class']).'"  '; }
							echo '>';
							echo '<strong>'.esc_html($option['value']['label']).'</strong>';
							echo '<span>'.esc_html($option['value']['info']).'</span>';
							echo '<select name="'.esc_html($option['value']['name']).'" id="'.esc_html($option['value']['name']).'">';
								$wc_gateways      = new WC_Payment_Gateways();
								$payment_gateways = $wc_gateways->payment_gateways();
								foreach( $payment_gateways as $gateway_id => $gateway ){
								if($gateway->enabled=="yes"){
									if(!empty($box['bd'])){
										if($gateway->id==$result[0]->{$option['name']}){
											$selected = "selected";
										}else{
											$selected = "";
										}
									}else{
										print_r($option);
										if(isset($option['id'])){
										if($gateway->id==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
										}
									}
									echo '<option value="'.esc_html($gateway->id).'" '.esc_html($selected).'>';
									echo esc_html($gateway->title).' ('.esc_html($gateway->id).')';
									echo '</option>';
								}
								}
							echo '</select>';
							echo '</p>';
							$url .= $option['value']['name'].',';
						}elseif($option['type']=="allProducts"){
							echo '<p ';
							if(isset($option['value']['class'])){ echo 'id="'.esc_html($option['value']['class']).'" '; }
							echo '>';
							echo '<strong>'.esc_html($option['value']['label']).'</strong>';
							echo '<span>'.esc_html($option['value']['info']).'</span>';
							echo '<select name="'.esc_html($option['value']['name']).'" id="'.esc_html($option['value']['name']).'">';
								$args     = array( 'post_type'      => array('product', 'product_variation'), 'posts_per_page' => -1 );
								$products = get_posts( $args );
								foreach($products as $product){
									if(!empty($box['bd'])){
										if($product->ID==$result[0]->{$option['row']}){
											$selected = "selected"; }else{ $selected = ""; }
									}else{
										if($rows['value']==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
									}
									echo '<option value="'.esc_html(str_replace('-', '_', $product->ID)).'" '.esc_html($selected).'>'.esc_html($product->post_title).'</option>';
								}
							echo '</select>';
							echo '</p>';
							$url .= $option['value']['name'].',';
						}elseif($option['type']=="listPayments"){
								$wc_gateways      = new WC_Payment_Gateways();
								$payment_gateways = $wc_gateways->payment_gateways();
								foreach( $payment_gateways as $gateway_id => $gateway ){
								if($gateway->enabled=="yes"){
									echo '<p class="'.esc_html($option['value']['name']).'">';
									echo '<strong>'.esc_html($gateway->title).' ('.esc_html($gateway->id).')</strong>';
									echo '<input type="number" name="parcelas_mwp_desconto_status_'.esc_html($gateway->id).'" value="'.esc_html(get_option("parcelas_mwp_desconto_status_".$gateway->id)).'" />';
									echo '</p>';
									$url .= "parcelas_mwp_desconto_status_".esc_html($gateway->id).",";
								}
								}
						}else{
							if(!empty($box['bd'])){ $value=$result[0]->{$option['value']['row']}; }else{ $value=esc_html(get_option($option['value']['name'])); }
							echo '<p ';
							if(isset($option['value']['class'])){ echo 'id="'.esc_html($option['value']['class']).'" '; }
							echo '>';
							if(isset($option['value']['label'])){
							echo '<strong>'.esc_html($option['value']['label']).'</strong>';
							}
							if(isset($option['value']['info'])){
							echo '<span>'.esc_html($option['value']['info']).'</span>';
							}
							echo '<input ';
							if(isset($option['type'])){ echo 'type="'.esc_html($option['type']).'" '; }
							if(isset($option['value']['name'])){ echo 'name="'.esc_html($option['value']['name']).'" '; }
							if(isset($option['value']['class'])){ echo 'class="'.esc_html($option['value']['class']).'" '; }
							if(isset($option['value']['placeholder'])){ echo 'placeholder="'.esc_html($option['value']['placeholder']).'" '; }
							if(isset($option['value']['step'])){ echo ' step="any"'; }
							echo 'value="'.esc_html($value).'" />';
							echo '</p>';
							$url .= $option['value']['name'].',';
						}
					}
				}
				}
				echo '</div>';
			echo '</div>';
			}
		}
		echo '<input type="submit" name="Submit" class="mwpbuttonupdatesection '.$ajax.'" id="mwpbuttonupdatesection"';
	if(isset($box['formButton'])){
		echo 'value="'.esc_html($box['formButton']).'"';
	}else{
		echo 'value="'.esc_html__( 'Update', 'checkout-mestres-wp').'"';
	}
	echo '/>';
	echo '<input type="hidden" name="action" value="update" />';
	echo '<input type="hidden" name="page_options" value="'.esc_url(substr($url,0,-1)).'" />';
	
	}
}