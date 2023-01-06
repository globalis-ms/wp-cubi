<?php

namespace Globalis\WP\WPUnhooked;

add_action('wp_before_admin_bar_render', function () {
    if (!isset($GLOBALS['wp_admin_bar'])) {
        return;
    }
    $howdy_str_replace = str_replace(' %s', '', __('Howdy, %s'));
    $acc_title = str_replace($howdy_str_replace, '', $GLOBALS['wp_admin_bar']->get_node('my-account')->title);
    $GLOBALS['wp_admin_bar']->add_node(['id' => 'my-account', 'title' => $acc_title]);
});
