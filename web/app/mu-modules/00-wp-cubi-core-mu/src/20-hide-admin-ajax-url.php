<?php

namespace Globalis\WP\Cubi;

if (!defined('WP_CUBI_HIDE_ADMIN_AJAX_URL') || !WP_CUBI_HIDE_ADMIN_AJAX_URL) {
    return;
}

if (is_admin() && !wp_doing_ajax()) {
    return;
}

add_filter('admin_url', function ($url, $path, $blog_id) {
    if ('admin-ajax.php' !== $path) {
        return $url;
    }
    return home_url('/ajax.php');
}, 10, 3);
