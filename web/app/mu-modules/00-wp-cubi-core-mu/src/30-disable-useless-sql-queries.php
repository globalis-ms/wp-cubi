<?php

namespace Globalis\WP\Cubi;

/*
 * Disable dashboard browse-happy requests / widget
 */
if (is_blog_admin() && is_array($_SERVER) && isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
    add_filter('pre_site_transient_browser_' . md5($_SERVER['HTTP_USER_AGENT']), '__return_empty_array');
}

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
    $alloptions = wp_load_alloptions();

    if (isset($alloptions['theme_switched'])) {
        return $alloptions['theme_switched'];
    } else {
        return '';
    }
}

add_filter('pre_option_can_compress_scripts', '__return_zero');
remove_filter('theme_mod_custom_logo', '_override_custom_logo_theme_mod');

disable_php_version_check();

function disable_php_version_check()
{
    if (!is_admin()) {
        return;
    }
    $version = phpversion();
    $key = md5($version);
    add_filter('pre_site_transient_php_check_' . $key, function () {
        return ['is_acceptable' => true];
    });
}

add_filter('pre_site_transient_theme_roots', function () {
    return [WP_DEFAULT_THEME => "/themes"];
});

add_action('admin_init', function () {
    remove_action('admin_init', ['WP_Privacy_Policy_Content', 'text_change_check'], 100);
});

add_filter('pre_option_https_migration_required', function () {
    return '0';
});

add_filter('default_wp_template_part_areas', '__return_empty_array');
add_filter('default_template_types', '__return_empty_array');
