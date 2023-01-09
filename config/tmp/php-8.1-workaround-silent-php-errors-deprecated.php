<?php

\Globalis\WP\Cubi\add_filter('enable_wp_debug_mode_checks', 'wp_debug_mode_turn_off_error_deprecated');

function wp_debug_mode_turn_off_error_deprecated($default)
{
    remove_filter('enable_wp_debug_mode_checks', 'wp_debug_mode_turn_off_error_deprecated');

    wp_debug_mode();

    if (WP_DEBUG) {
        error_reporting(E_ALL & ~E_DEPRECATED);
    } else {
        error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR);
    }

    return false;
}
