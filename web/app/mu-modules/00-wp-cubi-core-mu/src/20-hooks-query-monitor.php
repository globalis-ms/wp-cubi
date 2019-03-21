<?php

namespace Globalis\WP\Cubi;

add_action('wp_head', function () {
    if (!class_exists(('QM_Dispatchers'))) {
        return;
    }
    if (\QM_Dispatchers::get('html')->user_can_view()) {
        return;
    }
    global $debug_bar;
    if (isset($debug_bar)) {
        remove_action('wp_head', [$debug_bar, 'ensure_ajaxurl'], 1);
    }
}, 0);

if (defined('SAVEQUERIES') && !SAVEQUERIES) {
    add_filter('qm/output/title', function (array $title) {
        foreach (apply_filters('qm/collect/db_objects', ['$wpdb' => $GLOBALS['wpdb']]) as $key => $db) {
            $title[] = sprintf(esc_html_x('%s Q', 'Query count', 'query-monitor'), number_format_i18n($db->num_queries));
        }

        foreach ($title as &$t) {
            $t = preg_replace('#\s?([^0-9,\.]+)#', '<small>$1</small>', $t);
        }

        return $title;
    }, 20);
}
