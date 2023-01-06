<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_bar_menu', function () {
    remove_action('admin_bar_menu', 'wp_admin_bar_my_sites_menu', 20);
}, 0);
