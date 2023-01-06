<?php

namespace Globalis\WP\WPUnhooked;

add_action('wp_before_admin_bar_render', function () {
    $GLOBALS['wp_admin_bar']->remove_node('menus');
});
