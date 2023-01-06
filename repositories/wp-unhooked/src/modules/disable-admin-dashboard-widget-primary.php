<?php

namespace Globalis\WP\WPUnhooked;

add_action('wp_dashboard_setup', function () {
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
});

add_action('admin_init', function () {
    remove_action('admin_print_scripts-index.php', 'wp_localize_community_events');
});
