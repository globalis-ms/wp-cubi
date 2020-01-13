<?php

namespace Globalis\WP\Cubi;

if (WP_IS_HTTPS) {
    add_filter('secure_auth_cookie', '__return_true');
    add_filter('secure_logged_in_cookie', '__return_true');
}
