<?php

namespace Globalis\WP\Cubi;

if (!defined('WP_CUBI_JQUERY_VERSION')) {
    define('WP_CUBI_JQUERY_VERSION', '3.6.3');
}

if (!defined('WP_CUBI_JQUERY_NO_CONFLICT')) {
    define('WP_CUBI_JQUERY_NO_CONFLICT', false);
}

if (!defined('WP_CUBI_JQUERY_CDN_URL')) {
    define('WP_CUBI_JQUERY_CDN_URL', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/%s/jquery.min.js');
    // define('WP_CUBI_JQUERY_CDN_URL', 'https://ajax.googleapis.com/ajax/libs/jquery/%s/jquery.min.js');
    // define('WP_CUBI_JQUERY_CDN_URL', 'https://code.jquery.com/jquery-%s.min.js');
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\register_jquery', 200);
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\jquery_dns_prefetch', 999);

function jquery_url()
{
    return sprintf(WP_CUBI_JQUERY_CDN_URL, WP_CUBI_JQUERY_VERSION);
}

function jquery_domain()
{
    $parts = parse_url(jquery_url());

    if (is_array($parts) && !empty($parts['host'])) {
        return $parts['host'];
    }

    return false;
}

function register_jquery()
{
    if (!is_frontend()) {
        return;
    }

    wp_deregister_script('jquery');
    wp_register_script('jquery', jquery_url(), [], null, true);

    if (WP_CUBI_JQUERY_NO_CONFLICT) {
        wp_add_inline_script('jquery', 'jQuery.noConflict();', 'after');
    }
}

function jquery_dns_prefetch()
{
    if (!is_frontend()) {
        return;
    }

    if (!wp_script_is('jquery', 'enqueued')) {
        return;
    }

    add_filter('wp_resource_hints', function ($urls, $relation_type) {
        if ($relation_type === 'dns-prefetch' && $jquery_domain = jquery_domain()) {
            $urls[] = $jquery_domain;
        }
        return $urls;
    }, 10, 2);
}
