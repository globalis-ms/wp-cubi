<?php

namespace Globalis\WP\Cubi;

add_filter('sober/intervention/return', function($path)
{
    return __DIR__ . '/20-clean-wp-admin-intervention.php';
});
