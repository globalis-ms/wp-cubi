<?php

namespace Globalis\WP\Cubi;

/*
 * Disable legacy filter causing useless SQL queries
 */
add_filter('pre_option__split_terms', '__return_empty_array');

/*
 * Avoid useless SQL queries caused by script_concat_settings()
 */
add_action('widgets_init', __NAMESPACE__ . '\\declare_global_wp_concat_settings', 5, 1);

function declare_global_wp_concat_settings()
{
    if (is_admin()) {
        return;
    }

    global $concatenate_scripts, $compress_scripts, $compress_css;
    $concatenate_scripts = false;
    $compress_scripts    = false;
    $compress_css        = false;
}

/*
 * Disable get_option('theme_switched') useless queries (check autoload options only)
 */
add_filter('pre_option_theme_switched', __NAMESPACE__ . '\\get_option_autoload_theme_switched', 10, 1);

function get_option_autoload_theme_switched($default)
{
    if (!\Globalis\WP\Cubi\is_frontend()) {
        return $default;
    }

    $alloptions = wp_load_alloptions();

    if (isset($alloptions['theme_switched'])) {
        return $alloptions['theme_switched'];
    } else {
        return '';
    }
}
