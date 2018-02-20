<?php

require_once __DIR__ . '/../vendor/autoload.php';

\Globalis\WP\LocalConfig\Vars::init(__DIR__ . '/../config/vars.php');

define('WP_ENV', wpg_local('ENVIRONEMENT'));

require_once __DIR__ . '/../config/application.php';
require_once __DIR__ . '/../config/salt-keys.php';
require_once __DIR__ . '/../config/environments/' . WP_ENV . '.php';

if (file_exists(__DIR__ . '/../config/local.php')) {
    require_once __DIR__ . '/../config/local.php';
}

require_once ABSPATH . 'wp-settings.php';
