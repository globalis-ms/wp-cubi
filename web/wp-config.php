<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('WP_CUBI_CONFIG', require __DIR__ . '/../config/vars.php');

require_once __DIR__ . '/../config/application.php';
require_once __DIR__ . '/../config/salt-keys.php';
require_once __DIR__ . '/../config/environments/' . WP_CUBI_CONFIG['ENVIRONEMENT'] . '.php';

if (file_exists(__DIR__ . '/../config/local.php')) {
    require_once __DIR__ . '/../config/local.php';
}

require_once ABSPATH . 'wp-settings.php';
