<?php

namespace Globalis\WP\Cubi;

if (true === WP_CUBI_QUERY_MONITOR_HANDLE_PHP_ERRORS) {
    return;
}

add_filter('qm/built-in-collectors', function ($collectors) {
    foreach ($collectors as $key => $collector_path) {
        if (str_ends_with($collector_path, 'php_errors.php')) {
            unset($collectors[$key]);
            break;
        }
    }
    return $collectors;
});

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
