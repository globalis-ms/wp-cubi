<?php

namespace Globalis\WP\Cubi;

add_filter('pre_transient_health-check-site-status-result', '__return_zero');

add_filter('site_status_tests', __NAMESPACE__ . '\\wp_site_health_options', 10, 1);

function wp_site_health_options($tests)
{
    if (isset($tests['direct']['debug_enabled']) && WP_ENV !== 'production') {
        unset($tests['direct']['debug_enabled']);
    }

    if (isset($tests['direct']['rest_availability']) && WP_CUBI_ENABLE_REST_API !== true) {
        unset($tests['direct']['rest_availability']);
    }

    if (isset($tests['direct']['wordpress_version'])) {
        unset($tests['direct']['wordpress_version']);
    }

    if (isset($tests['async']['background_updates'])) {
        unset($tests['async']['background_updates']);
    }

    return $tests;
}
