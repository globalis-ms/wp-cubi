<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_bar_menu', function () {
    remove_action('admin_bar_menu', 'wp_admin_bar_my_account_menu', 0);
}, 0);

add_action('wp_before_admin_bar_render', function () {
    $GLOBALS['wp_admin_bar']->remove_node('my-account');
});
