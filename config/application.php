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
define('WP_SITEURL', WP_HOME . '/wpcb');
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
    define('ABSPATH', WEBROOT_DIR . '/wpcb/');
}

/* THEME */
define('WP_DEFAULT_THEME', 'wp-cubi-debug-theme');

/* UPDATES */
// NOTE: If you need to edit this section, you may need to edit htaccess-security > "Block sensitive WordPress files" and WP_UNHOOKED_CONFIG
define('AUTOMATIC_UPDATER_DISABLED', true);
define('WP_AUTO_UPDATE_CORE', false);

/* SECURITY */

// NOTE: If you need to edit this section, you may need to edit htaccess-security > "Block sensitive WordPress files" and WP_UNHOOKED_CONFIG
define('DISALLOW_FILE_MODS', true);
define('DISALLOW_FILE_EDIT', true);

/* LIMIT LOGIN ATTEMPTS */
define("WP_CUBI_LIMIT_LOGIN_ALLOWED_RETRIES", 5);
define("WP_CUBI_LIMIT_LOGIN_LOCKOUT_DURATION_IN_MINUTES", 20);
define("WP_CUBI_LIMIT_LOGIN_LOCKOUT_MAX_LOCKOUTS", 3);
define("WP_CUBI_LIMIT_LOGIN_LOCKOUT_DURATION_IN_HOURS", 72);
define("WP_CUBI_LIMIT_LOGIN_RESET_RETRIES_AFTER_DURATION_IN_HOURS", 24);

/* PASSWORDS */
define('WP_CUBI_DISALLOW_WEAK_PASSWORDS', true);

/* ADMIN AJAX */
define('WP_CUBI_HIDE_ADMIN_AJAX_URL', true);

/* PERFORMANCES */
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);
define('CONCATENATE_SCRIPTS', true);
define('ENFORCE_GZIP', true);

/* WONOLOG */
define('WP_DEBUG_LOG', false);
define('WP_CUBI_LOG_DIR', ROOT_DIR . '/log');
define('WP_CUBI_LOG_MAX_FILES', 30);
define('WP_CUBI_LOG_CUSTOM_CHANNELS', []);

/* PUBLIC URLS */
define('WP_CUBI_PUBLIC_URLS', [
    'staging'    => 'https://staging.example.com/',
    'production' => 'https://example.com/',
]);

/* EMAIL */
// define('WP_MAIL_FROM_EMAIL', 'no-reply@example.com');
// define('WP_MAIL_FROM_NAME', 'Example');

define('WP_UNHOOKED_CONFIG', [
    'disable-admin-bar' => false,
    'disable-admin-bar-comments-menu' => true,
    'disable-admin-bar-customize-menu' => true,
    'disable-admin-bar-edit-menu' => false,
    'disable-admin-bar-edit-site-menu' => false,
    'disable-admin-bar-howdy' => true,
    'disable-admin-bar-my-account-menu' => false,
    'disable-admin-bar-my-sites-menu' => false,
    'disable-admin-bar-new-content-menu' => true,
    'disable-admin-bar-recovery-mode-menu' => true,
    'disable-admin-bar-search-menu' => true,
    'disable-admin-bar-sidebar-toggle' => false,
    'disable-admin-bar-site-menu' => false,
    'disable-admin-bar-site-nav-menus' => true,
    'disable-admin-bar-site-themes' => true,
    'disable-admin-bar-site-widgets' => true,
    'disable-admin-bar-updates-menu' => true,
    'disable-admin-bar-wp-menu' => true,
    'disable-admin-dashboard-tab-help' => true,
    'disable-admin-dashboard-tab-screen-options' => true,
    'disable-admin-dashboard-welcome-panel' => true,
    'disable-admin-dashboard-widget-activity' => true,
    'disable-admin-dashboard-widget-browser-nag' => true,
    'disable-admin-dashboard-widget-incoming-links' => true,
    'disable-admin-dashboard-widget-plugins' => true,
    'disable-admin-dashboard-widget-primary' => true,
    'disable-admin-dashboard-widget-quick-press' => true,
    'disable-admin-dashboard-widget-recent-comments' => true,
    'disable-admin-dashboard-widget-recent-drafts' => true,
    'disable-admin-dashboard-widget-right-now' => false,
    'disable-admin-dashboard-widget-site-health' => true,
    'disable-admin-footer' => true,
    'disable-admin-plugins-menu' => false,
    'disable-admin-plugins-page' => false,
    'disable-admin-tab-help' => true,
    'disable-admin-tab-screen-options' => false,
    'disable-admin-themes-customize-menu' => false,
    'disable-admin-themes-customize-page' => false,
    'disable-admin-themes-menu' => false,
    'disable-admin-themes-page' => false,
    'disable-admin-tools-erase-personal-data' => true,
    'disable-admin-tools-export-personal-data' => true,
    'disable-admin-tools-export' => true,
    'disable-admin-tools-import' => true,
    'disable-admin-tools-menu' => false,
    'disable-admin-tools-page' => true,
    'disable-admin-tools-site-health' => true,
    'disable-asset-versioning' => true,
    'disable-auto-updates' => true,
    'disable-block-editor' => true,
    'disable-browser-check' => true,
    'disable-capital-p-dangit' => true,
    'disable-comments' => true,
    'disable-emojis' => true,
    'disable-frontend-wp-info' => true,
    'disable-gallery-css' => true,
    'disable-php-version-check' => true,
    'disable-rest-api' => true,
    'disable-sitemaps' => true,
    'disable-smilies' => true,
    'disable-taxonomy-category' => true,
    'disable-taxonomy-post-format' => true,
    'disable-taxonomy-post-tag' => true,
    'disable-trackbacks' => true,
    'disable-update-notices' => true,
    'disable-xmlrpc' => true,
]);
