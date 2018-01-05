<?php

/* DEBUG */
ini_set('display_errors', 1);
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
define('SCRIPT_DEBUG', true);
define('QUERY_MONITOR_HANDLE_PHP_ERRORS', false);
define('SAVEQUERIES', PHP_SAPI != 'cli');

/* MEMORY */
define('WP_MEMORY_LIMIT', '128M');

/* SECURITY */
define('FORCE_SSL_ADMIN', 'https' === wpg_local('WEB_SCHEME'));

/* AUTOSAVE, REVISIONS, TRASH */
define('AUTOSAVE_INTERVAL', '300');
define('WP_POST_REVISIONS', false);
define('MEDIA_TRASH', false);
define('EMPTY_TRASH_DAYS', '50');

/* HIDE ACF MENU */
define('ACF_LITE', false);

/* WP-CRON */
define('DISABLE_WP_CRON', false);
define('ALTERNATE_WP_CRON', PHP_SAPI != 'cli');
define('WP_CRON_LOCK_TIMEOUT', 60);
