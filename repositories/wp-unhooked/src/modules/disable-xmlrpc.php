<?php

namespace Globalis\WP\WPUnhooked;

// @see https://github.com/globalis-ms/wp-cubi/blob/1.4.0/web/app/mu-modules/00-wp-cubi-core-mu/src/10-security-xmlrpc.php

remove_action('wp_head', 'rsd_link');
add_filter('xmlrpc_enabled', '__return_false');
