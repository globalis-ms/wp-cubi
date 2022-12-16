<?php

namespace Globalis\WP\Cubi;

if (WP_ENV === 'production') {
    return;
}

if (defined('WP_CUBI_MAIL_TRAPPING') && false === WP_CUBI_MAIL_TRAPPING) {
    return;
}

add_filter('wp_mail', function ($args) {

    if (apply_filters('wp_cubi_mail_trapper_paused', false)) {
        return $args;
    }

    $args_to         = is_array($args['to']) ? implode(', ', $args['to']) : $args['to'];
    $from_url        = str_replace(['http://', 'https://'], ['', ''], WP_HOME);
    $args['to']      = defined('WP_CUBI_MAIL_TRAPPING') ? WP_CUBI_MAIL_TRAPPING : WP_CUBI_CONFIG['DEV_MAIL'];
    $args['subject'] = sprintf('[%s - Mail to %s] %s', $from_url, $args_to, $args['subject']);
    return $args;
});

function pause_mail_trapper()
{
    add_filter('wp_cubi_mail_trapper_paused', '__return_true');
}

function resume_mail_trapper()
{
    remove_filter('wp_cubi_mail_trapper_paused', '__return_true');
}
