<?php

/**
 * Plugin Name:         wp-cubi-clean-wp-admin
 * Plugin URI:          https://github.com/globalis-ms/wp-cubi
 * Description:         Clean WordPress administration panel
 * Author:              Pierre Dargham, Globalis Media Systems
 * Author URI:          https://www.globalis-ms.com/
 * License:             GPL2
 *
 * Version:             0.1.0
 * Requires at least:   4.0.0
 * Tested up to:        4.8.1
 */

namespace Globalis\WP\Cubi;

use function \Sober\Intervention\intervention;

add_action('plugins_loaded', __NAMESPACE__ . '\\clean_wp_admin', 99);

function clean_wp_admin()
{
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
}
