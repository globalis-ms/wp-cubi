<?php

namespace Globalis\WP\Cubi;

use Inpsyde\Wonolog;
use Monolog\Handler;

if (!defined('WP_CUBI_LOG_ENABLED') || true !== WP_CUBI_LOG_ENABLED) {
    return;
}

$log_level = defined('WP_CUBI_LOG_LEVEL_LOCAL') ? WP_CUBI_LOG_LEVEL_LOCAL : WP_CUBI_LOG_LEVEL;

$handler = false;

if (defined('WP_CUBI_LOG_DIR')) {
    if (!is_dir(WP_CUBI_LOG_DIR)) {
        $parent_directory = dirname(WP_CUBI_LOG_DIR);
        if (is_dir($parent_directory) && is_writable($parent_directory)) {
            mkdir(WP_CUBI_LOG_DIR);
        }
    }

    if (!is_dir(WP_CUBI_LOG_DIR) || !is_writable(WP_CUBI_LOG_DIR)) {
        return;
    }

    $max_files = defined('WP_CUBI_LOG_MAX_FILES') ? WP_CUBI_LOG_MAX_FILES : 30;
    $handler = new Handler\RotatingFileHandler(WP_CUBI_LOG_DIR . DIRECTORY_SEPARATOR . '.log', $max_files, $log_level);
    $handler->setFilenameFormat('{date}', 'Y-m-d');
} elseif (defined('WP_CUBI_LOG_FILE')) {
    if (!is_file(WP_CUBI_LOG_FILE)) {
        $parent_directory = dirname(WP_CUBI_LOG_FILE);
        if (is_dir($parent_directory) && is_writable($parent_directory)) {
            touch(WP_CUBI_LOG_FILE);
        }
    }

    if (!is_file(WP_CUBI_LOG_FILE) || !is_writable(WP_CUBI_LOG_FILE)) {
        return;
    }

    $handler = new Handler\StreamHandler(WP_CUBI_LOG_FILE, $log_level);
}

if (false !== $handler) {
    add_action('qm/collect/new_php_error', [new \Inpsyde\Wonolog\PhpErrorController(), 'on_error'], 10, 5);
    Wonolog\bootstrap($handler);
}
