<?php

namespace Globalis\WP\Cubi;

use function Sober\Intervention\intervention;

/*
 * Disable dashboard browse-happy requests / widget
 */
if (is_blog_admin() && is_array($_SERVER) && isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
    add_filter('pre_site_transient_browser_' . md5($_SERVER['HTTP_USER_AGENT']), '__return_empty_array');
}

/*
 * @see https://github.com/soberwp/intervention/
 */
add_action('plugins_loaded', function () {
    if (!function_exists('\Sober\Intervention\intervention')) {
        return;
    }

    if (\Globalis\WP\Cubi\is_frontend()) {
        return;
    }

    // @see https://github.com/soberwp/intervention/blob/master/.github/remove-help-tabs.md
    intervention('remove-help-tabs');

    // @see https://github.com/soberwp/intervention/blob/master/.github/remove-update-notices.md
    intervention('remove-update-notices', 'all-not-admin');

    // @see https://github.com/soberwp/intervention/blob/master/.github/remove-howdy.md
    intervention('remove-howdy');

    // @see https://github.com/soberwp/intervention/blob/master/.github/remove-emoji.md
    intervention('remove-emoji');

    // @see https://github.com/soberwp/intervention/blob/master/.github/remove-dashboard-items.md
    intervention('remove-dashboard-items', [
        'welcome',
        'notices',
        'recent-comments',
        'incoming-links',
        'plugins',
        'quick-draft',
        'drafts',
        'news',
    ], 'all');

    // @see https://github.com/soberwp/intervention/blob/master/.github/remove-toolbar-items.md
    intervention('remove-toolbar-items', [
        'updates',
        'comments',
        'customize',
    ], 'all');
}, 99);

/*
 * Disable dashboard site health widget
 */
add_action('wp_dashboard_setup', function () {
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
});
