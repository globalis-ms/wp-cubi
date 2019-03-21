<?php

namespace Globalis\WP\Cubi;

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
