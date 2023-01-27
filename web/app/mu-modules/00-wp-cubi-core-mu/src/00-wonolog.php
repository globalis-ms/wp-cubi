<?php

namespace Globalis\WP\Cubi;

use Monolog\Handler;
use Inpsyde\Wonolog\Configurator;

if (!defined('WP_CUBI_LOG_ENABLED') || true !== WP_CUBI_LOG_ENABLED) {
    define('WONOLOG_DISABLE', true);
    return;
}

add_action(
    'wonolog.setup',
    function (\Inpsyde\Wonolog\Configurator $config) {
        $config->disableFallbackHandler();
    }
);

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
    $handler = new Handler\RotatingFileHandler(WP_CUBI_LOG_DIR . DIRECTORY_SEPARATOR . '.log', $max_files, $log_level, true, 0777);
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

    $handler = new Handler\StreamHandler(WP_CUBI_LOG_FILE, $log_level, true, 0777);
}

if (empty($handler)) {
    return;
}

add_action(
    'wonolog.setup',
    function (Configurator $config) use ($handler) {
        $config->pushHandler($handler);
    }
);

if (defined('WP_CUBI_LOG_PHP_ERRORS') && WP_CUBI_LOG_PHP_ERRORS) {
    add_action('qm/collect/new_php_error', __NAMESPACE__ . '\\forward_php_error_handled_by_qm', 10, 4);
}

function forward_php_error_handled_by_qm(int $errno, string $errstr, string $errfile, int $errline)
{
    $qm_php_error_collector = \QM_Collectors::get("php_errors");

    if (empty($qm_php_error_collector)) {
        return;
    }

    $previous_error_handler = null;
    $qm_php_error_collector_properties = (array) $qm_php_error_collector;

    if (!is_array($qm_php_error_collector_properties)) {
        return;
    }

    foreach ($qm_php_error_collector_properties as $property_name => $property_value) {
        if (false !== strpos($property_name, "previous_error_handler")) {
            $previous_error_handler = $property_value;
        }
    }

    if (!is_array($previous_error_handler) || !isset($previous_error_handler[0])) {
        return;
    }

    $object = $previous_error_handler[0];

    if (is_a($object, 'Inpsyde\Wonolog\PhpErrorController')) {
        call_user_func($previous_error_handler, $errno, $errstr, $errfile, $errline);
    }
}

if (defined("WP_CUBI_LOG_CUSTOM_CHANNELS") && !empty(WP_CUBI_LOG_CUSTOM_CHANNELS)) {
    add_action(
        'wonolog.setup',
        function (Configurator $config) {
            $config->enableWpContextProcessorForChannels(...WP_CUBI_LOG_CUSTOM_CHANNELS);
            $config->enableWpContextProcessor();
        }
    );
}

/*

Usage:

1. Add "MY_CUSTOM_CHANNEL" to constant WP_CUBI_LOG_CUSTOM_CHANNELS in config/application.php

2. Add custom log actions in your code :

(assuming $e is a PHP Exception object)

$context = [
    'code' => "My error code",
    'details' => "Some details about the error",
    'trace' => $e->getTraceAsString(),
];

\Inpsyde\Wonolog\makeLogger("MY_CUSTOM_CHANNEL")->error($e->getMessage(), $context);

*/
