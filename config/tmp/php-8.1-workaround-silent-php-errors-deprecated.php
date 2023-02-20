<?php

if (version_compare(PHP_VERSION, '8.1.0') < 0) {
    return;
}

\Globalis\WP\Cubi\add_filter('enable_wp_debug_mode_checks', 'wp_debug_mode_turn_off_errors_deprecated');
\Globalis\WP\Cubi\add_filter('qm/collect/php_errors_return_value', 'query_monitor_turn_off_errors_deprecated');

function wp_debug_mode_turn_off_errors_deprecated($default)
{
    remove_filter('enable_wp_debug_mode_checks', 'wp_debug_mode_turn_off_errors_deprecated');

    wp_debug_mode();

    if (WP_DEBUG) {
        error_reporting(E_ALL & ~E_DEPRECATED);
    } else {
        error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR);
    }

    return false;
}

function query_monitor_turn_off_errors_deprecated($default)
{
    $qm_php_error_collector = \QM_Collectors::get("php_errors");
    $data = $qm_php_error_collector->get_data();
    $errors = $data->errors;
    if (isset($errors['deprecated'])) {
        unset($errors['deprecated']);
    }
    $qm_php_error_collector->set_php_errors($errors);
    return $default;
}
