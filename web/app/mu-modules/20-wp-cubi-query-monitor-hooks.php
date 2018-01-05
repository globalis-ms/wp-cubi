<?php

/**
 * Plugin Name:         wp-cubi-query-monitor-hooks
 * Plugin URI:          https://github.com/globalis-ms/wp-cubi
 * Description:         Add some configuration hooks on query-monitor
 * Author:              Pierre Dargham, Globalis Media Systems
 * Author URI:          https://www.globalis-ms.com/
 * License:             GPL2
 *
 * Version:             0.1.0
 * Requires at least:   4.7.0
 * Tested up to:        4.9.1
 */

namespace Globalis\WP\Cubi;

if (true !== QUERY_MONITOR_HANDLE_PHP_ERRORS) {
    add_filter('qm/built-in-collectors', function ($collectors) {
        foreach ($collectors as $key => $collector_path) {
            if (str_ends_with($collector_path, 'php_errors.php')) {
                unset($collectors[$key]);
                break;
            }
        }
        return $collectors;
    });
}
