<?php

namespace Globalis\WP\WPUnhooked;

add_filter('wp_sitemaps_enabled', '__return_false');
remove_action('init', 'wp_sitemaps_get_server');
