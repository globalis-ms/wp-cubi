<?php

/* MAIL TRAPPING */
define('WP_CUBI_MAIL_TRAPPING', [
    WP_CUBI_CONFIG['DEV_MAIL'],
]);

/* Edit to locally disable wp-cubi-transient cache */
define('WP_CUBI_TRANSIENT_CACHE_BYPASS_ALL', false);
define('WP_CUBI_TRANSIENT_CACHE_BYPASS_TEMPLATES', false);
define('WP_CUBI_TRANSIENT_CACHE_DISABLE_AUTO_CACHE_NAV_MENUS', false);

/* SMTP SETTINGS (for real smtp server) */
// define('WP_MAIL_SMTP_ENABLED', true);
// define('WP_MAIL_SMTP_HOST', 'smtp.example.com');
// define('WP_MAIL_SMTP_PORT', 587);
// define('WP_MAIL_SMTP_ENCRYPTION', 'tls');
// define('WP_MAIL_SMTP_AUTO_TLS', true);
// define('WP_MAIL_SMTP_AUTH', true);
// define('WP_MAIL_SMTP_USER', 'username');
// define('WP_MAIL_SMTP_PASSWORD', 'password');

/* SMTP SETTINGS (for mailhog smtp server) */
// define('WP_MAIL_SMTP_ENABLED', true);
// define('WP_MAIL_SMTP_HOST', '127.0.0.1');
// define('WP_MAIL_SMTP_PORT', 1025);
// define('WP_MAIL_SMTP_AUTH', false);
