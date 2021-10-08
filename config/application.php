<?php

/* ENVIRONEMENT */
define('WP_ENV', WP_CUBI_CONFIG['ENVIRONEMENT']);
define('WP_LOCAL_DEV', WP_ENV === 'development');

/* DATABASE */
define('DB_NAME', WP_CUBI_CONFIG['DB_NAME']);
define('DB_USER', WP_CUBI_CONFIG['DB_USER']);
define('DB_PASSWORD', WP_CUBI_CONFIG['DB_PASSWORD']);
define('DB_HOST', WP_CUBI_CONFIG['DB_HOST']);
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = '<##DB_PREFIX##>';

/* DIRECTORIES */
define('ROOT_DIR', dirname(__DIR__));
define('WP_SCHEME', WP_CUBI_CONFIG['WEB_SCHEME']);
define('WP_DOMAIN', WP_CUBI_CONFIG['WEB_DOMAIN']);
define('WP_PATH', WP_CUBI_CONFIG['WEB_PATH']);
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
define('FORCE_SSL_ADMIN', ('https' === WP_SCHEME));
$_SERVER['HTTPS'] = ('https' === WP_SCHEME) ? 1 : 0;

/* ABSPATH */
if (!defined('ABSPATH')) {
    define('ABSPATH', WEBROOT_DIR . '/wp/');
}

/* THEME */
define('WP_DEFAULT_THEME', 'wp-cubi-debug-theme');

/* UPDATES */
// NOTE: If you need to edit this section, you may need to edit htaccess-security > Block sensitive WordPress files
define('AUTOMATIC_UPDATER_DISABLED', true);
define('WP_AUTO_UPDATE_CORE', false);
define('WP_CUBI_DISABLE_ALL_VERSION_UPDATE_CHECKS', true);

/* SECURITY */
// NOTE: If you need to edit this section, you may need to edit htaccess-security > Block sensitive WordPress files
define('DISALLOW_FILE_MODS', true);
define('DISALLOW_FILE_EDIT', true);

/* PASSWORDS */
define('WP_CUBI_DISALLOW_WEAKS_PASSWORDS', true);

/* ADMIN AJAX */
define('WP_CUBI_HIDE_ADMIN_AJAX_URL', true);

/* REST API */
define('WP_CUBI_ENABLE_REST_API', false);

/* XML-RPC */
define('WP_CUBI_ENABLE_XMLRPC', false);

/* BLOCK-EDITOR (PREV. GUTENBERG) */
define('WP_CUBI_ENABLE_BLOCK_EDITOR', false);

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
define('WP_CUBI_PUBLIC_URLS', [
    'staging'    => 'https://staging.example.com/',
    'production' => 'https://example.com/',
]);
