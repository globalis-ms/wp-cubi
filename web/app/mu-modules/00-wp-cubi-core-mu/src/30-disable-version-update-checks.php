<?php

namespace Globalis\WP\Cubi;

/*
 * Disable all version checks (core, plugins, themes)
 */

if(!WP_AUTO_UPDATE_CORE) {
    add_filter('pre_option_auto_update_core_dev', '__return_zero');
    add_filter('pre_option_auto_update_core_minor', '__return_zero');
    add_filter('pre_option_auto_update_core_major', '__return_zero');
    add_filter('pre_option_auto_core_update_failed', '__return_zero');
    add_filter('pre_option_auto_update_themes', '__return_zero');
    add_filter('pre_option_auto_update_plugins', '__return_zero');
}

if (!defined('WP_CUBI_DISABLE_ALL_VERSION_UPDATE_CHECKS') || !WP_CUBI_DISABLE_ALL_VERSION_UPDATE_CHECKS) {
    return;
}

remove_action('admin_init', '_maybe_update_core');
remove_action('wp_version_check', 'wp_version_check');
remove_action('load-plugins.php', 'wp_update_plugins');
remove_action('load-update.php', 'wp_update_plugins');
remove_action('load-update-core.php', 'wp_update_plugins');
remove_action('admin_init', '_maybe_update_plugins');
remove_action('wp_update_plugins', 'wp_update_plugins');
remove_action('load-themes.php', 'wp_update_themes');
remove_action('load-update.php', 'wp_update_themes');
remove_action('load-update-core.php', 'wp_update_themes');
remove_action('admin_init', '_maybe_update_themes');
remove_action('wp_update_themes', 'wp_update_themes');
remove_action('update_option_WPLANG', 'wp_clean_update_cache', 10, 0);
remove_action('wp_maybe_auto_update', 'wp_maybe_auto_update');
remove_action('init', 'wp_schedule_update_checks');

add_filter('pre_option_dismissed_update_core', '__return_empty_array');
add_filter('pre_site_transient_update_core', __NAMESPACE__ . '\\removeWordPressUpdates');
add_filter('pre_site_transient_update_plugins', __NAMESPACE__ . '\\removeWordPressUpdates');
add_filter('pre_site_transient_update_themes', __NAMESPACE__ . '\\removeWordPressUpdates');

add_action('admin_init', function () {
    remove_submenu_page('index.php', 'update-core.php');
    remove_action('admin_notices', 'update_nag', 3);
    remove_action('admin_notices', 'maintenance_nag', 10);
    remove_filter('update_footer', 'core_update_footer');
});

function removeWordPressUpdates()
{
    global $wp_version;

    return(object) [
        'last_checked' => time(),
        'version_checked' => $wp_version,
        'updates' => [],
    ];
}
