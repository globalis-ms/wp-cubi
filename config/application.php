<?php

/* ENVIRONMENT */
define('WP_ENV', wpg_local('ENVIRONEMENT'));

/* ENVIRONMENT CONFIGURATION */
require_once __DIR__ . '/environments/' . WP_ENV . '.php';

/* LOCAL CONFIGURATION */
if (file_exists(__DIR__ . '/local.php')) {
    require_once __DIR__ . '/local.php';
}

/* SALT KEYS */
require_once __DIR__ . '/salt-keys.php';

/* DATABASE */
define('DB_NAME', wpg_local('DB_NAME'));
define('DB_USER', wpg_local('DB_USER'));
define('DB_PASSWORD', wpg_local('DB_PASSWORD'));
define('DB_HOST', wpg_local('DB_HOST'));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = empty(wpg_local('DB_PREFIX')) ? 'wp_' : wpg_local('DB_PREFIX');

/* DIRECTORIES */
define('ROOT_DIR', dirname(__DIR__));
define('WP_SCHEME', wpg_local('WEB_SCHEME'));
define('WP_DOMAIN', wpg_local('WEB_DOMAIN'));
define('WP_PATH', wpg_local('WEB_PATH'));
define('WP_HOME', WP_SCHEME . '://' . WP_DOMAIN . WP_PATH);
define('WEBROOT_DIR', ROOT_DIR . '/web');
define('WP_SITEURL', WP_HOME . '/wp');
define('WP_CONTENT_DIR', WEBROOT_DIR . '/app');
define('WP_CONTENT_URL', WP_HOME . '/app');
define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/modules');
define('WP_PLUGIN_URL', WP_CONTENT_URL . '/modules');
define('WPMU_PLUGIN_DIR', WP_CONTENT_DIR . '/mu-modules');
define('WPMU_PLUGIN_URL', WP_CONTENT_URL . '/mu-modules');
define('WP_UPLOADS_DIR', WEBROOT_DIR . '/media');
define('WP_UPLOADS_URL', WP_HOME . '/media');

/* ABSPATH */
if (!defined('ABSPATH')) {
    define('ABSPATH', WEBROOT_DIR . '/wp/');
}

/* THEME */
define('WP_DEFAULT_THEME', wpg_local('WP_DEFAULT_THEME'));

/* UPDATES */
define('AUTOMATIC_UPDATER_DISABLED', true);
define('WP_AUTO_UPDATE_CORE', false);

/* SECURITY */
define('DISALLOW_FILE_MODS', true);
define('DISALLOW_FILE_EDIT', true);

/* PERFORMANCES */
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);
define('CONCATENATE_SCRIPTS', true);
define('ENFORCE_GZIP', true);

/* PUBLIC URLS */
define('WP_PUBLIC_URLS', serialize([
    'staging'    => 'https://staging.example.com/',
    'production' => 'https://example.com/',
]));
