<?php

/* DEBUG */
ini_set('display_errors', 1);
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
define('SCRIPT_DEBUG', false);
define('SAVEQUERIES', PHP_SAPI != 'cli');
define('SQL_CACHE_QUERIES', false);
define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
define('WP_ENVIRONMENT_TYPE', 'staging');

/* QUERY MONITOR */
define('QM_DISABLE_ERROR_HANDLER', false);
define('QM_ENABLE_CAPS_PANEL', true);

/* WONOLOG */
define('WP_CUBI_LOG_ENABLED', true);
define('WP_CUBI_LOG_LEVEL', 'DEBUG');
define('WP_CUBI_LOG_PHP_ERRORS', true);

/* MEMORY */
define('WP_MEMORY_LIMIT', '256M');

/* AUTOSAVE, REVISIONS, TRASH */
define('AUTOSAVE_INTERVAL', '300');
define('WP_POST_REVISIONS', 10);
define('MEDIA_TRASH', true);
define('EMPTY_TRASH_DAYS', '50');

/* HIDE ACF MENU */
define('ACF_LITE', true);

/* WP-CRON */
define('DISABLE_WP_CRON', false);
define('ALTERNATE_WP_CRON', false);
define('WP_CRON_LOCK_TIMEOUT', 60);
