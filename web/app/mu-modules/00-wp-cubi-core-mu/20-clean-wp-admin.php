<?php

namespace Globalis\WP\Cubi;

use function \Sober\Intervention\intervention;

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
