<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_bar_menu', function () {
    remove_action('admin_bar_menu', 'wp_admin_bar_wp_menu', 10);
}, 0);
