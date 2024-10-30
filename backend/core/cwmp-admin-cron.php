<?php
function cwmp_cron_events(){
    global $wpdb;
    global $woocommerce;
	global $table_prefix;
	global $product;
	if (get_option('cwmp_activate_cart') == "S") {
		$get_abandoned_cart = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned WHERE status ='0' ORDER BY id DESC LIMIT 50");
		foreach($get_abandoned_cart as $key){
			$cwmp_cart_recovery =  str_replace('\"','"',$key->cart);
			$cwmp_cart_recovery = json_decode($cwmp_cart_recovery);
			if($cwmp_cart_recovery){
				foreach($cwmp_cart_recovery as $cart => $cart_value){
					$cardKey = $cart_value->key;
					$cart_value = $cart_value->line_total;
				}
			}
			$returnCartKey = cwmp_check_order_by_recovery_cart($key->id);
			if($returnCartKey==0){
				$get_msgs_abandoned_cart = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned_msg");
				foreach($get_msgs_abandoned_cart as $value){
					$get_verify_cart = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned_relation WHERE cart='".$key->id."' AND type='".$value->id."'");
					if(count($get_verify_cart)==0){
						if($value->time2=="0"){
							$periodo="minutes";
						}elseif($value->time2=="1"){
							$periodo="hour";
						}elseif($value->time2=="2"){
							$periodo="days";
						}
						if (current_time('Y-m-d H:i:s') >= gmdate("Y-m-d H:i:s", strtotime("+ {$value->time} {$periodo}", strtotime($key->time)))) {
							if($value->discount=="yes"){
								$valor_desconto = $value->discount_value;
								$tipo_desconto = 'percent';
								$limite_uso = 1;
								$prazo_validade = '+'.$value->discount_time.' days';
								$codigo_cupom = cwmp_create_coupon($valor_desconto, $tipo_desconto, $limite_uso, $prazo_validade);
							}
							if(get_option('cwmp_activate_whatsapp')=="S"){
								if($value->mensagem){
									$string_wpp_content = str_replace('[cwmp_recovery_link]', get_home_url() . '/?cwmp_recovery_cart=' . base64_encode($key->id), $value->mensagem);
									if(!empty($codigo_cupom)){
										$string_wpp_content = str_replace('{{cupom}}', $codigo_cupom, $string_wpp_content);
									}
									$string_wpp_content = str_replace('[cwmp_loja_name]', get_bloginfo('name'), str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('[cwmp_recovery_client_name]', $key->nome, str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('[cwmp_recovery_total]', str_replace(" ","",html_entity_decode(wp_strip_all_tags(wc_price($cart_value)))), str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('https://', "", str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('http://', "", str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content_renovada = do_shortcode($string_wpp_content);
									$numero = cwmp_trata_numero($key->phone);
									cwmp_send_whatsapp($numero,$string_wpp_content_renovada);
								}
							}
							if(get_option('cwmp_activate_emails')=="S"){
								if($value->body){
									$string_title = str_replace('[cwmp_recovery_client_name]', $key->nome, str_replace("\'","'",str_replace('\"','"',$value->titulo)));
									$string_title_renovada = do_shortcode($string_title);
									$string_wpp_content = str_replace('[cwmp_recovery_link]', home_url() . '/?cwmp_recovery_cart=' . base64_encode($key->id), str_replace("\'","'",str_replace('\"','"',$value->body)));
									if(!empty($codigo_cupom)){
										$string_wpp_content = str_replace('{{cupom}}', $codigo_cupom, $string_wpp_content);
									}
									$string_wpp_content = str_replace('[cwmp_loja_name]', get_bloginfo('name'), str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('[cwmp_recovery_client_name]', $key->nome, str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('[cwmp_recovery_total]', str_replace(" ","",html_entity_decode(wp_strip_all_tags(wc_price($cart_value)))), str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('https://', "", str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('http://', "", str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_content_renovada = do_shortcode($string_wpp_content);
									cwmp_send_mail($key->email,$string_title_renovada,$string_content_renovada);
								}
							}
							$wpdb->insert($wpdb->prefix . 'cwmp_cart_abandoned_relation', array(
								'cart' => $key->id,
								'type' => $value->id
							));
						}
					}
				}
			}
			$countSendCartRecovery = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned_relation WHERE cart='".$key->id."'");
			$countMsgsCartAbandoned = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned_msg");
			if(count($countSendCartRecovery)==count($countMsgsCartAbandoned)){
				$wpdb->update($wpdb->prefix . 'cwmp_cart_abandoned', array('status' => 1), array('id'=> $key->id));
			}
		}	
	}
	if (get_option('cwmp_activate_recupera_pgto') == "S") {
		$table_name1 = $wpdb->prefix . 'cwmp_pending_payment_msg';
		$table_name2 = $wpdb->prefix . 'cwmp_pending_payment_status';
		$get_pending_payment = $wpdb->get_results("SELECT * FROM ".$table_name1."");
		foreach($get_pending_payment as $key){
			if($key->time2=="0"){
				$periodo="minutes";
			}elseif($key->time2=="1"){
				$periodo="hour";
			}elseif($key->time2=="2"){
				$periodo="days";
			}			
			$args1 = array(
				'date_created' => gmdate('Y-m-d', strtotime( "-".$key->time." ".$periodo )),
				'status' => array('on-hold','pending'),
				'limit' => 20 
			);
			$orders = wc_get_orders( $args1 );
			foreach($orders as $pedido){
				if($periodo=="minutes" OR $periodo=="hour" ){
					if(date("Y-m-d H:i:s")>=date("Y-m-d H:i:s", strtotime("+ ".$key->time." ".$periodo, strtotime($pedido->get_date_created())))){
						$get_pending_payment_status = $wpdb->get_results("SELECT * FROM ".$table_name2." WHERE pedido='".$pedido->get_ID()."' AND method='".$pedido->get_payment_method()."' AND msg='".$key->id."' AND status='1'");
						if(count($get_pending_payment_status)==0){
							if(get_option('cwmp_activate_whatsapp')=="S"){
								if($key->mensagem){
									$string_wpp_content = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $key->mensagem));
									$string_wpp_content_renovada = do_shortcode($string_wpp_content);
									$numero = cwmp_trata_numero($pedido->get_billing_phone());
									cwmp_send_whatsapp($numero,$string_wpp_content_renovada);
								}
							}
							if(get_option('cwmp_activate_emails')=="S"){
								if($key->body){
									$string_title = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $key->titulo));
									$string_title_renovada = do_shortcode($string_title);
									$string_content = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$key->body))));
									$string_content_renovada = do_shortcode($string_content);
									cwmp_send_mail($pedido->get_billing_email(),$string_title_renovada,$string_content_renovada);
								}
							}
							$wpdb->insert($table_name2, array('pedido' => $pedido->get_id(),'method' => $pedido->get_payment_method(),'msg'=>$key->id,'status'=>'1'));
						}else{ }
					}
				}
				if($periodo=="days"){
					if(date("H:i:s")>="12:00:00"){
						$get_pending_payment_status = $wpdb->get_results("SELECT * FROM ".$table_name2." WHERE pedido='".$pedido->get_ID()."' AND method='".$pedido->get_payment_method()."' AND msg='".$key->id."' AND status='1'");
						if(count($get_pending_payment_status)==0){
							if(get_option('cwmp_activate_whatsapp')=="S"){
								if($key->mensagem){
									$string_wpp_content = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $key->mensagem));
									$string_wpp_content_renovada = do_shortcode($string_wpp_content);
									$numero = cwmp_trata_numero($pedido->get_billing_phone());
									cwmp_send_whatsapp($numero,$string_wpp_content_renovada);
								}
							}
							if(get_option('cwmp_activate_emails')=="S"){
								if($key->body){
									$string_title = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $key->titulo));
									$string_title_renovada = do_shortcode($string_title);
									$string_content = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$key->body))));
									$string_content_renovada = do_shortcode($string_content);
									cwmp_send_mail($pedido->get_billing_email(),$string_title_renovada,$string_content_renovada);
								}
							}
							$wpdb->insert($table_name2, array('pedido' => $pedido->get_id(),'method' => $pedido->get_payment_method(),'msg'=>$key->id,'status'=>'1'));
						}else{ }
					}
				}
			}
		}		
	}
	$customerTable18 = $wpdb->prefix . 'cwmp_template_emails_produto';
	$results = $wpdb->get_results("SELECT * FROM $customerTable18");
	if ($results) {
		foreach ($results as $result) {
			$product_id = $result->metodo;
			$periodo = $result->time2;
			$quantidade = $result->time;
			$status = $result->status;
			$dataAtual = time();
			switch ($periodo) {
				case 'day':
					$quantidade *= 24 * 60 * 60;
					break;
				case 'months':
					$quantidade *= 30 * 24 * 60 * 60;
					break;
				case 'years':
					$quantidade *= 365 * 24 * 60 * 60;
					break;
			}
			$dataSubtraida = $dataAtual - $quantidade;
			$dataSubtraidaFormatada = gmdate('Y-m-d', $dataSubtraida);
			$orderIds = cwmpListBuyProduct($product_id, $dataSubtraidaFormatada, $status);
			foreach ($orderIds as $value) {
				$exists = $wpdb->get_var($wpdb->prepare(
					"SELECT COUNT(*) FROM {$customerTable18}_send WHERE ordem = %s AND id_email = %d",
					sanitize_text_field($value),
					absint($result->id)
				));
				if ($exists == 0) {
					$order = wc_get_order($value);
					if (get_option('cwmp_activate_whatsapp') == "S" && !empty($result->msg)) {
						$numero = cwmp_trata_numero($order->get_billing_phone());
						$string_wpp_content = str_replace("]", " val='" . $order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $result->msg));
						cwmp_send_whatsapp($numero, do_shortcode($string_wpp_content));
					}
					if (get_option('cwmp_activate_emails') == "S" && !empty($result->titulo) && !empty($result->conteudo)) {
						$string_title = str_replace("]", " val='" . $order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $result->titulo));
						$string_content = str_replace("]", " val='" . $order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $result->conteudo));
						cwmp_send_mail($order->get_billing_email(), do_shortcode($string_title), do_shortcode($string_content));
					}
					$wpdb->insert($customerTable18 . "_send", array(
						'ordem' => $value,
						'id_email' => $result->id,
						'status' => '1'
					));
				}
			}
		}
	}
}
add_action('cwmp_cron_events', 'cwmp_cron_events');