<?php

namespace Globalis\WP\Cubi;

add_filter('wp_mail_from', __NAMESPACE__ . '\\wp_mail_force_from_email', 99);
add_filter('wp_mail_from_name', __NAMESPACE__ . '\\wp_mail_force_from_name', 99);

function wp_mail_force_from_email($default)
{
    if (defined('WP_MAIL_FROM_EMAIL')) {
        return WP_MAIL_FROM_EMAIL;
    } else {
        return $default;
    }
}

function wp_mail_force_from_name($default)
{
    if (defined('WP_MAIL_FROM_NAME')) {
        return WP_MAIL_FROM_NAME;
    } else {
        return $default;
    }
}
