<?php

namespace Globalis\WP\Cubi;

if (true === WP_CUBI_ENABLE_XMLRPC) {
    return;
}

remove_action('wp_head', 'rsd_link');
add_filter('xmlrpc_enabled', '__return_false');
