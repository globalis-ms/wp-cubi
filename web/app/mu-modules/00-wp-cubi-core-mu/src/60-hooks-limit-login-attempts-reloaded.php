<?php

namespace Globalis\WP\Cubi;

add_action('admin_head', __NAMESPACE__ . '\\hide_llar_upgrade_message_and_premium_features');

function hide_llar_upgrade_message_and_premium_features()
{
    ?>
    <style type="text/css">
        #llar-header-upgrade-message,
        #llar-dashboard-page .dashboard-section-4,
        #llar-dashboard-page .info-box-3 {
            display: none;
        }
    </style>
    <?php
}

// Clean WordPress admin

add_filter('pre_option_limit_login_onboarding_popup_shown', function () {
    return '1';
});

add_filter('pre_option_limit_login_hide_dashboard_widget', function () {
    return '1';
});

add_filter('pre_option_limit_login_show_warning_badge', function () {
    return '0';
});

add_filter('pre_option_limit_login_show_top_level_menu_item', function () {
    return '0';
});

add_filter('pre_option_limit_login_auto_update_choice', function () {
    return '0';
});

add_filter('pre_transient_llar_welcome_redirect', function () {
    return '0';
});

add_filter('pre_option_limit_login_review_notice_shown', function () {
    return true;
});

add_filter('pre_option_limit_login_notice_enable_notify_timestamp', function () {
    return 99999999999999;
});

add_filter('pre_option_limit_login_activation_timestamp', function () {
    return 99999999999999;
});

// Turn off mail notifications

add_filter('pre_option_limit_login_lockout_notify', function () {
    return '';
});

// Don't modify wp-login page

add_filter('pre_option_limit_login_gdpr', function () {
    return '0';
});

// Avoid useless SQL request

add_filter('pre_option_limit_login_active_app', function () {
    return 'local';
});

// Configure plugin with wp-cubi constants

if (defined("WP_CUBI_LIMIT_LOGIN_ALLOWED_RETRIES")) {
    add_filter('pre_option_limit_login_allowed_retries', function () {
        return WP_CUBI_LIMIT_LOGIN_ALLOWED_RETRIES;
    });
}

if (defined("WP_CUBI_LIMIT_LOGIN_LOCKOUT_DURATION_IN_MINUTES")) {
    add_filter('pre_option_limit_login_lockout_duration', function () {
        return WP_CUBI_LIMIT_LOGIN_LOCKOUT_DURATION_IN_MINUTES * MINUTE_IN_SECONDS;
    });
}

if (defined("WP_CUBI_LIMIT_LOGIN_LOCKOUT_MAX_LOCKOUTS")) {
    add_filter('pre_option_limit_login_allowed_lockouts', function () {
        return WP_CUBI_LIMIT_LOGIN_LOCKOUT_MAX_LOCKOUTS;
    });
}

if (defined("WP_CUBI_LIMIT_LOGIN_LOCKOUT_DURATION_IN_HOURS")) {
    add_filter('pre_option_limit_login_long_duration', function () {
        return WP_CUBI_LIMIT_LOGIN_LOCKOUT_DURATION_IN_HOURS * HOUR_IN_SECONDS;
    });
}

if (defined("WP_CUBI_LIMIT_LOGIN_RESET_RETRIES_AFTER_DURATION_IN_HOURS")) {
    add_filter('pre_option_limit_login_valid_duration', function () {
        return WP_CUBI_LIMIT_LOGIN_RESET_RETRIES_AFTER_DURATION_IN_HOURS * HOUR_IN_SECONDS;
    });
}
