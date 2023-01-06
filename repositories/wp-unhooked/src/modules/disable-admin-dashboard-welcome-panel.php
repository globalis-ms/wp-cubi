<?php

namespace Globalis\WP\WPUnhooked;

add_action('wp_dashboard_setup', function () {
    remove_action('welcome_panel', 'wp_welcome_panel');
});
