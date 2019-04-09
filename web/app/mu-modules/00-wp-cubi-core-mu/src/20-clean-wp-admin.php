<?php

namespace Globalis\WP\Cubi;

use function \Sober\Intervention\intervention;

/*
 * Disable dashboard browse-happy requests / widget
 */
if (is_blog_admin() && !empty($_SERVER['HTTP_USER_AGENT'])) {
    add_filter('pre_site_transient_browser_' . md5($_SERVER['HTTP_USER_AGENT']), '__return_true');
}

/*
 * Disable dashboard php version widget (avoid unecessary SQL queries and HTTP requests)
 */
if (is_blog_admin()) {
    add_filter('pre_site_transient_php_check_' . md5(phpversion()), function ($default) {
        return ['is_acceptable' => true];
    });
}

/*
 * @see https://github.com/soberwp/intervention/
 */
add_action('plugins_loaded', function () {
    if (!function_exists('\Sober\Intervention\intervention')) {
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
