<?php

/* DATABASE */
define('DB_NAME', wpg_local('DB_NAME'));
define('DB_USER', wpg_local('DB_USER'));
define('DB_PASSWORD', wpg_local('DB_PASSWORD'));
define('DB_HOST', wpg_local('DB_HOST'));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = 'cubi_';

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

/* HTTPS */
define('WP_IS_HTTPS', ('https' === WP_SCHEME));

/* ABSPATH */
if (!defined('ABSPATH')) {
    define('ABSPATH', WEBROOT_DIR . '/wp/');
}

/* THEME */
define('WP_DEFAULT_THEME', 'cubi-2018');

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

/* WONOLOG */
define('WP_DEBUG_LOG', false);
define('WP_CUBI_LOG_DIR', ROOT_DIR . '/log');
define('WP_CUBI_LOG_MAX_FILES', 30);

/* PUBLIC URLS */
define('WP_PUBLIC_URLS', serialize([
    'staging'    => 'https://staging.example.com/',
    'production' => 'https://example.com/',
]));
