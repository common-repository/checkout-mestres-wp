<?php

// Adiciona o intervalo personalizado de 'a cada minuto'
function cwmp_add_cron_interval($schedules) {
    $schedules['every_minute'] = array(
        'interval' => 60, // 60 segundos em um minuto
        'display' => __('A cada minuto')
    );
    return $schedules;
}
add_filter('cron_schedules', 'cwmp_add_cron_interval');

// Verifica e agenda o evento cron se não estiver agendado
if (!wp_next_scheduled('cwmp_cron_events')) {
    wp_schedule_event(time(), 'every_minute', 'cwmp_cron_events');
}