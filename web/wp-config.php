<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (!defined('WP_CUBI_CONFIG')) {
    define('WP_CUBI_CONFIG', require __DIR__ . '/../config/vars.php');
}

if (PHP_SAPI === 'cli') {
    $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';
    $_SERVER['HTTP_USER_AGENT'] = '';
    $_SERVER['REQUEST_METHOD']  = 'GET';
    $_SERVER['REMOTE_ADDR']     = '127.0.0.1';
    $_SERVER['SERVER_NAME']     = WP_CUBI_CONFIG['WEB_DOMAIN'];
    $_SERVER['HTTP_HOST']       = WP_CUBI_CONFIG['WEB_DOMAIN'];
}

if (!file_exists(__DIR__ . '/../config/salt-keys.php')) {
    die('Error: config/salt-keys.php is missing.');
}
require_once __DIR__ . '/../config/salt-keys.php';

require_once __DIR__ . '/../config/application.php';
require_once __DIR__ . '/../config/environments/' . WP_CUBI_CONFIG['ENVIRONEMENT'] . '.php';

define('FS_METHOD', 'direct');

if (!file_exists(__DIR__ . '/../config/local.php')) {
    die('Error: config/local.php is missing.');
}
require_once __DIR__ . '/../config/local.php';

if (defined('SQL_CACHE_QUERIES') && true !== SQL_CACHE_QUERIES) {
    \Globalis\WP\Cubi\mysql_enable_nocache_mod();
}

require_once ABSPATH . 'wp-settings.php';
