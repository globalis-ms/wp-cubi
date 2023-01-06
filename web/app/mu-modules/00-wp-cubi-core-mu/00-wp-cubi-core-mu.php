<?php

/**
 * Plugin Name:         wp-cubi-core-mu
 * Plugin URI:          https://github.com/globalis-ms/wp-cubi/blob/master/web/app/mu-modules/00-wp-cubi-core-mu
 * Description:         Collection of wp-cubi core must-use plugins
 * Author:              Pierre Dargham, Globalis Media Systems
 * Author URI:          https://www.globalis-ms.com/
 * License:             GPL2
 */

// Wonolog / Monolog bootstrap
require_once __DIR__ . '/src/00-wonolog.php';

// Automated `no-index` on development / staging environments
require_once __DIR__ . '/src/10-disallow-indexing.php';

// Mail configuration
require_once __DIR__ . '/src/10-mail.php';

// SMTP server configuration
require_once __DIR__ . '/src/10-mail-smtp.php';

// Mail-trapper on development / staging environments
require_once __DIR__ . '/src/10-mail-trapper.php';

// Force secure cookies if site has HTTPS scheme (better reverse proxies compatibility)
require_once __DIR__ . '/src/10-secure-cookies.php';

// Force uploads path to configuration constants values
require_once __DIR__ . '/src/10-uploads-path.php';

// Clean WordPress frontend (remove unwanted inline css and other things)
require_once __DIR__ . '/src/20-clean-frontend.php';

// Various default filters
require_once __DIR__ . '/src/20-default-filters.php';

// Hooks on autodescription / The SEO Framework plugin
if (!defined('WP_INSTALLING') || !WP_INSTALLING) {
    require_once __DIR__ . '/src/20-hooks-the-seo-framework.php';
}

// Activate roots/soil modules / theme-supports (provides cleaner front DOM)
if (!defined('WP_INSTALLING') || !WP_INSTALLING) {
    require_once __DIR__ . '/src/20-soil-modules.php';
}

// Hooks on acf & acf-pro
require_once __DIR__ . '/src/20-hooks-acf.php';

// Hide admin-ajax URL
require_once __DIR__ . '/src/20-hide-admin-ajax-url.php';

// Customize wp-login.php page with application logo and url
require_once __DIR__ . '/src/20-wp-login.php';

// Disable core useless SQL queries
require_once __DIR__ . '/src/30-disable-useless-sql-queries.php';

// jQuery (frontend): use cdnjs.cloudflare.com
require_once __DIR__ . '/src/40-jquery-cdn.php';

// Disallow weak passwords
require_once __DIR__ . '/src/40-disallow-weak-passwords.php';

// Remove attachments public templates and permalinks
require_once __DIR__ . '/src/50-disable-attachments-public.php';

// Limit login attempts
require_once __DIR__ . '/src/60-hooks-limit-login-attempts-reloaded.php';
