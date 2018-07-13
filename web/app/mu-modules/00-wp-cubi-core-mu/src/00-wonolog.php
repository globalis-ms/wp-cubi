<?php

namespace Globalis\WP\Cubi;

use Inpsyde\Wonolog;
use Monolog\Handler;

if (!defined('WP_CUBI_LOG_ENABLED') || true !== WP_CUBI_LOG_ENABLED) {
    return;
}

if (!is_dir(WP_CUBI_LOG_DIR) || !is_writable(WP_CUBI_LOG_DIR)) {
    trigger_error(sprintf('Log directory %s is not writable', WP_CUBI_LOG_DIR), E_USER_WARNING);
    return;
}

if (defined('QUERY_MONITOR_HANDLE_PHP_ERRORS') && true === QUERY_MONITOR_HANDLE_PHP_ERRORS) {
    add_action('qm/collect/new_php_error', [new \Inpsyde\Wonolog\PhpErrorController(), 'on_error'], 10, 5);
}

$log_level = defined('WP_CUBI_LOG_LEVEL_LOCAL') ? WP_CUBI_LOG_LEVEL_LOCAL : WP_CUBI_LOG_LEVEL;

$handler = new Handler\RotatingFileHandler(WP_CUBI_LOG_DIR . DIRECTORY_SEPARATOR . '.log', WP_CUBI_LOG_MAX_FILES, $log_level);
$handler->setFilenameFormat('{date}', 'Y-m-d');

Wonolog\bootstrap($handler);
