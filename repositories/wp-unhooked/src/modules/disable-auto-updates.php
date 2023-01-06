<?php

namespace Globalis\WP\WPUnhooked;

if (!defined('AUTOMATIC_UPDATER_DISABLED')) {
    define('AUTOMATIC_UPDATER_DISABLED', true);
}

if (!defined('WP_AUTO_UPDATE_CORE')) {
    define('WP_AUTO_UPDATE_CORE', false);
}

add_filter('pre_option_auto_update_core_dev', '__return_zero');
add_filter('pre_option_auto_update_core_minor', '__return_zero');
add_filter('pre_option_auto_update_core_major', '__return_zero');
add_filter('pre_option_auto_core_update_failed', '__return_zero');
add_filter('pre_option_auto_update_themes', '__return_zero');
add_filter('pre_option_auto_update_plugins', '__return_zero');
