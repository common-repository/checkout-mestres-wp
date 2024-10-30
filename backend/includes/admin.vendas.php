<?php
function cwmp_admin_vendas() {
    $mwp_pages_admin = array(
        'vendas.pedidos-pendentes' => array('name' => __( 'Pending Orders', 'checkout-mestres-wp')),
        'vendas.carts' => array('name' => __( 'Abandoned Carts', 'checkout-mestres-wp')),
        'vendas.recuperacao-carrinhos' => array('name' => __( 'Cart Recovery', 'checkout-mestres-wp')),
        'vendas.recuperacao-pagamento' => array('name' => __( 'Payment Recovery', 'checkout-mestres-wp')),
        'vendas.order-bump' => array('name' => __( 'Order Bump', 'checkout-mestres-wp'))
    );
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
                        $page = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : 'vendas.pedidos-pendentes';
                        $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
                        foreach ($mwp_pages_admin as $mwp_pages_key => $mwp_pages_value) {
                            $active = (strpos($request_uri, $mwp_pages_key) !== false || $mwp_pages_key === $page) ? 'mpcw-section-active' : '';
                        ?>
                        <li class="<?php echo esc_html($active); ?> <?php echo esc_html($mwp_pages_key); ?> box_menu">
                            <a href="admin.php?page=cwmp_admin_vendas&type=<?php echo esc_html($mwp_pages_key); ?>">
                                <h4><?php echo esc_html($mwp_pages_value['name']); ?></h4>
                                <svg width="15" height="24" viewBox="0 0 15 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.1744 11.3693L1.19313 0.174345C0.709062 -0.241547 0 0.133438 0 0.805001V23.195C0 23.8666 0.709062 24.2415 1.19313 23.8257L14.1744 12.6307C14.546 12.3102 14.546 11.6898 14.1744 11.3693Z" fill="#EE451A"/>
                                </svg>
                            </a>
                        </li>
                        <?php } ?>
                        <li><a href="https://docs.mestresdowp.com.br" target="_blank"><h4><?php esc_html_e('Documentation', 'checkout-mestres-wp'); ?></h4></a></li>
                    </ul>
                </div>
            </div>
            <div class="mwpbrcoltwo">
                <div class="mwpsectioncontent">
                    <div class="">
                        <?php
                        // Lista de páginas permitidas (whitelist)
                        $allowed_pages = array_keys($mwp_pages_admin);
						$custom_pages = array('vendas.order-bump-add', 'vendas.order-bump-edit', 'vendas.recuperacao-carrinhos-add', 'vendas.recuperacao-carrinhos-edit', 'vendas.recuperacao-pagamento-add', 'vendas.recuperacao-pagamento-edit');
						$allowed_pages = array_merge($allowed_pages, $custom_pages);
                        if (isset($_GET['type'])) {
                            $type = sanitize_text_field(wp_unslash($_GET['type']));

                            // Verifica se o tipo está na lista permitida e se o arquivo existe antes de incluir
                            if (in_array($type, $allowed_pages, true) && file_exists(__DIR__ . "/html/{$type}.php")) {
                                include __DIR__ . "/html/{$type}.php";
                            } else {
                                echo '<p>' . esc_html__('Invalid page type.', 'checkout-mestres-wp') . '</p>';
                            }
                        } else {
                            include __DIR__ . "/html/vendas.pedidos-pendentes.php";
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
