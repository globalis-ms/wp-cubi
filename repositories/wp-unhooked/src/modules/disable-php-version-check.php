<?php

namespace Globalis\WP\WPUnhooked;

disable_php_version_check();

function disable_php_version_check()
{
    if (!is_admin()) {
        return;
    }
    $version = phpversion();
    $key = md5($version);
    add_filter('pre_site_transient_php_check_' . $key, function () {
        return ['is_acceptable' => true];
    });
}
