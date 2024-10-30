<?php
function cwmpAdminCreateLists($args){
	global $wpdb;
	global $table_prefix;
	$url = '';
	foreach($args as $box){
		echo '<div class="mwp-box">';
			echo '<div class="col-1">';
			echo '<h3>'.esc_html($box['title']).'</h3>';
			echo '<p>'.esc_html($box['description']).'</p>';
			if(!empty($box['button']['url'])){ echo '<a href="'.esc_url($box['button']['url']).'" class="action">'.esc_html($box['button']['label']).'</a>'; }
			if(!empty($box['help'])){ echo '<a href="'.esc_url($box['help']).'" target="blank">Dúvidas? Veja a documentação</a>'; }
			echo '</div>';
			echo '<div class="col-2">';
			echo '<table class="widefat fixed cwmp_table" cellspacing="0">';
			if($box['slug']=="campos"){
				echo "
				<thead>
					<th >Nome</th>
					<th style=''>Placeholder</th>
					<th style=''>Ações</th>
				</thead>
				";
			}
			if($box['slug']=="trafego"){
				echo "
				<thead>
					<th >Integração</th>
					<th style=''>Pixel</th>
					<th style=''>Referência</th>
					<th style=''>Ações</th>
				</thead>
				";
			}
			if($box['slug']=="order-bump"){
				echo "
				<thead>
					<th >Título</th>
					<th style='width:100px;text-align:center;'>Desconto</th>
					<th style='width:100px;'>Ações</th>
				</thead>
				";
			}
			if($box['slug']=="cart-recovery"){
				echo "
				<thead>
					<th >Título</th>
					<th style='width:100px;text-align:center;'>Periodo</th>
					<th style='width:100px;'>Ações</th>
				</thead>
				";
			}
			if($box['slug']=="method-payment"){
				echo "
				<thead>
					<th >Label</th>
					<th style='width:100px;text-align:center;'>Desconto</th>
					<th style='width:100px;'>Ações</th>
				</thead>
				";
			}
			if($box['slug']=="transportadoras"){
				echo "
				<thead>
					<th style='width:200px;'>Transportadora</th>
					<th >Link</th>
					<th style='width:100px;'>Ações</th>
				</thead>
				";
			}
			if($box['slug']=="emails"){
				echo "
				<thead>
					<th style='width:200px;'>Assunto</th>
					<th style='text-align:center;'>Status</th>
					<th style='text-align:center;'>Método de Pagamento</th>
					<th style='width:100px;'>Ações</th>
				</thead>
				";
			}
			if($box['slug']=="whatsapp"){
				echo "
				<thead>
					<th style='text-align:center;'>Status</th>
					<th style='text-align:center;'>Método de Pagamento</th>
					<th style='text-align:center;width:200px;'>Sequencia</th>
					<th style='width:100px;'>Ações</th>
				</thead>
				";
			}
			if($box['slug']=="email-produto"){
				echo "
				<thead>
					<th style='text-align:left;'>Produto</th>
					<th style='text-align:left;'>Título</th>
					<th style='text-align:center;width:200px;'>Status</th>
					<th style='width:100px;'>Ações</th>
				</thead>
				";
			}
			echo '<tbody>';
			$query = "";
			if (!empty($box['bd']['args'])) {
				$query .= "WHERE ";
			}
			if (isset($box['bd']['args'])) {
				foreach ($box['bd']['args'] as $rows) {
					$query .= $rows['action'] . " ";
					$query .= $rows['row'] . "=%s ";
					$values[] = $rows['value'];
				}
			}
			if (!empty($box['bd']['order'])) {
				$order = "ORDER BY " . $box['bd']['order']['value'] . " " . $box['bd']['order']['by'] . "";
			}
			if (!empty($box['bd']['limit'])) {
				$order .= " LIMIT " . $box['bd']['limit']['value'] . "";
			}
			$result = $wpdb->get_results("SELECT * FROM {$table_prefix}{$box['bd']['name']} $query $order");

			if(count($result)==0){
				echo '<tr>';
				if($box['slug']=="cart-recovery"){
					echo '<td colspan="3">';
				}elseif($box['slug']=="order-bump"){
					echo '<td colspan="3">';
				}elseif($box['slug']=="campos"){
					echo '<td colspan="3">';
				}elseif($box['slug']=="trafego"){
					echo '<td colspan="4">';
				}elseif($box['slug']=="method-payment"){
					echo '<td colspan="3">';
				}elseif($box['slug']=="transportadoras"){
					echo '<td colspan="3">';
				}elseif($box['slug']=="emails"){
					echo '<td colspan="4">';
				}elseif($box['slug']=="whatsapp"){
					echo '<td colspan="4">';
				}elseif($box['slug']=="email-produto"){
					echo '<td colspan="4">';
				}else{
					echo '<td>';
				}
				echo esc_html__('We found no record of your query.', 'checkout-mestres-wp');
				echo '</td></tr>';
			}
			foreach($result as $lines){
				echo '<tr>';
				foreach($box['bd']['lines'] as $line){
					if($line['type']=="text"){ echo '<td>'.esc_html($lines->{$line['value']}).'</td>'; }
					if($line['type']=="number"){ echo '<td style="text-align:center;">'.esc_html($lines->{$line['value']}).'</td>'; }
					if($line['type']=="discount"){ echo '<td style="text-align:center;">'.esc_html($lines->{$line['value']}).'</td>'; }
					if($line['type']=="percent"){ echo '<td style="text-align:center;">'.esc_html(cwmpFormatPercent($lines->{$line['value']})).'</td>'; }
					if($line['type']=="shipping"){ echo '<td style="text-align:center;">'.esc_html(cwmpGetNameShipping($lines->{$line['value']})).'</td>'; }
					if($line['type']=="status"){ echo '<td style="text-align:center;">'.esc_html(cwmpGetStatus($lines->{$line['value']})).'</td>'; }
					if($line['type']=="product"){ echo '<td>'.esc_html(cwmpGetNameProduct($lines->{$line['value']})).'</td>'; }
					if($line['type']=="newsletterSends"){ echo '<td>'.esc_html(cwmpGetNewsletterSends($lines->{$line['value']})).'</td>'; }
					if($line['type']=="newsletterClicks"){ echo '<td>'.esc_html(cwmpGetNewsletterClicks($lines->{$line['value']})).'</td>'; }
					if($line['type']=="newsletterOpen"){ echo '<td>'.esc_html(cwmpGetNewsletterOpen($lines->{$line['value']})).'</td>'; }
					if($line['type']=="icon"){ echo '<td style="text-align:center;width:20px;">'.cwmpGetIcon($lines->{$line['value']}).'</td>'; }
					if($line['type']=="bump"){
						$bump = explode(",",$line['value']);
						echo '<td style="text-align:left;width:370px;">'.esc_html(cwmpProductsBump($lines->{$bump[0]},$lines->{$bump[1]})).'</td>'; 
					}
					if($line['type']=="time"){
						$time = explode(",",$line['value']);
						$array = array($lines->{$time[0]},'');
						$return = cwmpFormatTime($lines->{$time[0]},$lines->{$time[1]});
						echo '<td style="text-align:center;">';
						echo esc_html($return);
						echo '</td>';
					}
					if($line['type']=="page"){
						if(is_numeric($lines->{$line['value']})){
							echo '<td>'.esc_html(get_the_title($lines->{$line['value']})).'</td>';
						}else{
							echo '<td>'.esc_html($lines->{$line['value']}).'</td>';
						}
					}
				}
				echo '<td class="actions"><a href="'.esc_url($box['patch']).'edit&id='.esc_html($lines->id).'"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="10" fill="#43D19E"/><path d="M12.1904 5.69723C12.3414 5.54146 12.5218 5.41728 12.7212 5.33191C12.9206 5.24655 13.135 5.20171 13.3519 5.2C13.5688 5.1983 13.7839 5.23976 13.9846 5.32198C14.1853 5.4042 14.3677 5.52553 14.521 5.6789C14.6744 5.83228 14.7958 6.01464 14.878 6.21536C14.9602 6.41608 15.0017 6.63115 14.9999 6.84806C14.9982 7.06496 14.9534 7.27935 14.868 7.47875C14.7827 7.67816 14.6585 7.85858 14.5027 8.00953L13.8623 8.64999L11.55 6.33769L12.1904 5.69723ZM11.1091 6.77852L6.04079 11.8469C5.8578 12.0299 5.72472 12.2569 5.65485 12.5065L5.01148 14.8042C4.99662 14.8575 4.99618 14.9137 5.01021 14.9672C5.02425 15.0207 5.05225 15.0695 5.09136 15.1086C5.13046 15.1477 5.17925 15.1757 5.23274 15.1897C5.28623 15.2038 5.34249 15.2033 5.39575 15.1885L7.69308 14.5451C7.94258 14.4753 8.16989 14.3424 8.35309 14.1592L13.4218 9.09124L11.1095 6.77894L11.1091 6.77852Z" fill="white"/></svg></a>';
				echo '<a href="'.esc_url(substr($box['patch'],0,-1)).'&action=delete&id='.esc_html($lines->id).'"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="10" fill="#DA2424"/><path d="M14.0661 13.0907L13.09 14.0658C13.0036 14.1518 12.8867 14.2 12.7648 14.2C12.6429 14.2 12.526 14.1518 12.4396 14.0658L10.0005 11.6264L7.56134 14.0658C7.51857 14.1085 7.46779 14.1423 7.41191 14.1653C7.35604 14.1883 7.29617 14.2001 7.23574 14.1999C7.17532 14.1997 7.11552 14.1876 7.05979 14.1642C7.00406 14.1409 6.95348 14.1067 6.91097 14.0638L5.93391 13.0907C5.84811 13.004 5.79999 12.8869 5.79999 12.7649C5.79999 12.643 5.84811 12.5259 5.93391 12.4392L8.37306 10.0009L5.93391 7.56154C5.84824 7.47519 5.80016 7.35847 5.80016 7.23683C5.80016 7.11518 5.84824 6.99847 5.93391 6.91212L6.90997 5.93598C6.95248 5.89292 7.00311 5.85873 7.05893 5.8354C7.11475 5.81206 7.17465 5.80005 7.23516 5.80005C7.29566 5.80005 7.35556 5.81206 7.41138 5.8354C7.4672 5.85873 7.51784 5.89292 7.56034 5.93598L10.0005 8.37532L12.4396 5.93598C12.4821 5.89295 12.5326 5.85878 12.5883 5.83546C12.6441 5.81214 12.7039 5.80013 12.7643 5.80013C12.8247 5.80013 12.8846 5.81214 12.9403 5.83546C12.996 5.85878 13.0466 5.89295 13.089 5.93598L14.0661 6.91011C14.1519 6.9968 14.2 7.11385 14.2 7.23583C14.2 7.3578 14.1519 7.47485 14.0661 7.56154L11.6269 10.0009L14.0661 12.4392C14.1514 12.5262 14.1992 12.6431 14.1992 12.7649C14.1992 12.8868 14.1514 13.0037 14.0661 13.0907Z" fill="white"/></svg></a></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		echo '</div>';
		
	}
}
function cwmpAdminCreateListsOrders($args){
	global $wpdb;
	global $table_prefix;
	$url = '';

	foreach($args as $box){
		echo '<div class="mwp-box">';
			echo '<div class="col-1">';
			echo '<h3>'.esc_html($box['title']).'</h3>';
			echo '<p>'.esc_html($box['description']).'</p>';
			if(!empty($box['button']['url'])){ echo '<a href="'.esc_url($box['button']['url']).'" class="action">'.esc_html($box['button']['label']).'</a>'; }
			if(!empty($box['help'])){ echo '<a href="'.esc_url($box['help']).'" target="blank">Dúvidas? Veja a documentação</a>'; }
			echo '</div>';
			echo '<div class="col-2">';
			echo '<table class="widefat fixed cwmp_table" cellspacing="0">';
			echo '<thead>';
			echo '<th>Data</th>';
			echo '<th>Cliente</th>';
			echo '<th style="text-align:center;">Forma de Pagamento</th>';
			echo '<th style="text-align:center;">Total</th>';
			echo '</thead>';
			echo '<tbody>';
			if(isset($box['orders']['status'])){
				$array = explode(",",$box['orders']['status']);
				$orders = wc_get_orders( array( 'numberposts' => -1, 'status' => $array,'date_after' => gmdate('Y-m-d', strtotime( '-4 days' )) ) );
				if(count($orders)==0){
					echo '<tr><td colspan="4">';
					echo esc_html__('We found no record of your query.', 'checkout-mestres-wp');
					echo '</td></tr>';
				}
				foreach($orders as $order){
					echo "<tr>";
					echo "<td style='width:20%;'>#".esc_html($order->get_ID())."<br/>".esc_html(date_format($order->get_date_created(),"d/m/Y"))."<br/>".esc_html(date_format($order->get_date_created(),"H:i:s"))."</td>";
					echo "<td style='width:38%;'>".esc_html($order->get_billing_first_name())." ".esc_html($order->get_billing_last_name())."<br/>".esc_html($order->get_billing_email())."<br/>".esc_html($order->get_billing_phone())."</td>";
					echo "<td style='width:22%;text-align:center;'>".esc_html($order->get_payment_method_title())."</td>";
					echo "<td style='width:20%;text-align:center;'>".wp_kses_post(wc_price($order->get_total()))."</td>";
					echo "</tr>";
				}
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		echo '</div>';
		
	}
	
}
function cwmpAdminCreateListsCarts($args){
	global $wpdb;
	global $table_prefix;
	$url = '';
	foreach($args as $box){
		echo '<div class="mwp-box">';
			echo '<div class="col-1">';
			echo '<h3>'.esc_html($box['title']).'</h3>';
			echo '<p>'.esc_html($box['description']).'</p>';
			if(!empty($box['button']['url'])){ echo '<a href="'.esc_url($box['button']['url']).'" class="action">'.esc_html($box['button']['label']).'</a>'; }
			if(!empty($box['help'])){ echo '<a href="'.esc_url($box['help']).'" target="blank">Dúvidas? Veja a documentação</a>'; }
			echo '</div>';
			echo '<div class="col-2">';
			echo '<table class="widefat fixed cwmp_table" cellspacing="0">';
			echo '<thead>';
			echo '<th>Data</th>';
			echo '<th>Cliente</th>';
			echo '<th>Produtos</th>';
			echo '<th style="text-align:center;">Total</th>';
			echo '<th style="text-align:center;">Tentativas</th>';
			echo '<th>Status</th>';
			echo '</thead>';
			echo '<tbody>';
$query = "";
$values = array();

if (!empty($box['bd']['args'])) {
    $query .= "WHERE ";
    foreach ($box['bd']['args'] as $rows) {
        $query .= $rows['action'] . " ";
        $query .= $rows['row'] . "=%s ";
        $values[] = $rows['value'];
    }
}

if (!empty($box['bd']['order'])) {
    $order = "ORDER BY " . $box['bd']['order']['value'] . " " . $box['bd']['order']['by'] . "";
}

$result = $wpdb->get_results("SELECT * FROM {$table_prefix}{$box['bd']['name']} $query $order");
			if(count($result)==0){
				echo '<tr><td colspan="6">';
				echo esc_html__('We found no record of your query.', 'checkout-mestres-wp');
				echo '</td></tr>';
			}
			foreach($result as $lines){
				echo '<tr>';
				echo '<td style="">'.wp_kses_post(cwmpFormatData($lines->time)).'</td>';
				echo '<td style="">
				'.esc_html($lines->nome).'<br/>
				<a href="mailto:'.esc_html($lines->email).'">'.cwmpGetIcon('email').'</a>
				<a href="https://wa.me/'.esc_html(preg_replace('/[^0-9]/', '',$lines->phone)).'">'.cwmpGetIcon('whatsapp').'</a>
				</td>'; 
				foreach($box['bd']['lines'] as $line){
					if($line['type']=="productsCart"){
						$sum_cart = array();
						global $wpdb;
						$carts_abandoneds = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned WHERE id = %s",
								$lines->id
							)
						);
						$cwmp_cart_recovery =  str_replace('\"','"',$carts_abandoneds[0]->cart);
						$cwmp_cart_recovery = json_decode($cwmp_cart_recovery);
						$produtos_recuperados = "";
						if($cwmp_cart_recovery){
							foreach($cwmp_cart_recovery as $key => $value){
								$produto = wc_get_product($value->product_id);
								if($produto){
									$sum_cart[] = $value->line_total;
									$produtos_recuperados .= "<a href='".$produto->get_permalink()."' target='blank'>".$produto->get_title()."</a><br/>";
								}
							}
							if(isset($produtos_recuperados)){
								echo "<td>".wp_kses_post($produtos_recuperados)."</td>";
							}
							if(!empty($sum_cart)){
								echo"<td style='text-align:center;'><strong>".wp_kses_post(wc_price(array_sum(($sum_cart))))."</strong></td>";
							}

							
						}
						unset($sum_cart);
						
					}
				}
				echo '<td style="text-align:center;">'.cwmpCountSendRecoveryCart($carts_abandoneds[0]->id).'</td>';
				$returnCartKey = cwmp_check_order_by_recovery_cart(base64_encode($carts_abandoneds[0]->id));
				if($returnCartKey==0){
					echo '<td style="text-align:center;"><strong style="color:red;">Abandonado</strong></td>';
				}else{
					echo '<td style="text-align:center;"><strong style="color:green;">Recuperado</strong></td>';
				}
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		echo '</div>';
		
	}
}