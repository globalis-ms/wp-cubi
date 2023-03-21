<?php

/**
 * Plugin Name:         wp-cubi-webhooks
 * Plugin URI:          https://github.com/globalis-ms/wp-cubi/blob/master/web/app/mu-modules/20-wp-cubi-webhooks
 * Description:         Add wp-cubi webhooks to application
 * Author:              Pierre Dargham, Globalis Media Systems
 * Author URI:          https://www.globalis-ms.com/
 * License:             GPL2
 */

namespace Globalis\WP\Cubi;

add_action('template_redirect', __NAMESPACE__ . '\\trigger_webhooks', 10);

function trigger_webhooks()
{
    if (!isset($_GET['wp-cubi-webhooks-run'])) {
        return;
    }

    if (!isset($_GET['wp-cubi-webhooks-secret'])) {
        return;
    }

    if (WP_CUBI_WEBHOOKS_SECRET !== $_GET['wp-cubi-webhooks-secret']) {
        return;
    }

    switch ($_GET['wp-cubi-webhooks-run']) {
        case 'clear-wp-cubi-transient-cache':
            clear_wp_cubi_transient_cache();
            break;
        case 'flush-rewrite-rules':
            flush_rewrite_rules();
            break;
        case 'reset-opcache':
            reset_opcache();
            break;
        case 'clear-statcache':
            clear_statcache();
            break;
        default:
            return;
    }
}

function clear_wp_cubi_transient_cache()
{
    do_action('wp-cubi\transient-cache\clear');
    header("HTTP/1.1 204 NO CONTENT");
    exit;
}

function flush_rewrite_rules()
{
    \flush_rewrite_rules(false);
    header("HTTP/1.1 204 NO CONTENT");
    exit;
}

function reset_opcache()
{
    opcache_reset();
    header("HTTP/1.1 204 NO CONTENT");
    exit;
}

function clear_statcache()
{
    clearstatcache(true);
    header("HTTP/1.1 204 NO CONTENT");
    exit;
}
