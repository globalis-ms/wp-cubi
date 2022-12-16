<?php

namespace Globalis\WP\Cubi;

if (!defined('WP_MAIL_SMTP_ENABLED') || !WP_MAIL_SMTP_ENABLED) {
    return;
}

// Configure SMTP settings in config/local.php OR in config/application.php OR in config/environments/

add_action('phpmailer_init', function ($phpmailer) {
    $phpmailer->isSMTP();

    if (defined('WP_MAIL_SMTP_HOST')) {
        $phpmailer->Host = WP_MAIL_SMTP_HOST;
    }

    if (defined('WP_MAIL_SMTP_PORT')) {
        $phpmailer->Port = WP_MAIL_SMTP_PORT;
    }

    if (defined('WP_MAIL_SMTP_ENCRYPTION')) {
        $phpmailer->SMTPSecure = WP_MAIL_SMTP_ENCRYPTION;
    }

    if (defined('WP_MAIL_SMTP_AUTO_TLS')) {
        $phpmailer->SMTPAutoTLS = WP_MAIL_SMTP_AUTO_TLS;
    }

    if (defined('WP_MAIL_SMTP_AUTH')) {
        $phpmailer->SMTPAuth = WP_MAIL_SMTP_AUTH;
    }

    if (defined('WP_MAIL_SMTP_USER')) {
        $phpmailer->Username = WP_MAIL_SMTP_USER;
    }

    if (defined('WP_MAIL_SMTP_PASSWORD')) {
        $phpmailer->Password = WP_MAIL_SMTP_PASSWORD;
    }

    if (defined('WP_MAIL_FROM_EMAIL') && defined('WP_MAIL_FROM_NAME')) {
        $phpmailer->setFrom(WP_MAIL_FROM_EMAIL, WP_MAIL_FROM_NAME);
    }
});
