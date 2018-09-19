<?php

namespace Globalis\WP\Cubi;

define('TSF_DISABLE_SUGGESTIONS', true);

add_filter('the_seo_framework_indicator', '__return_false');

add_filter('the_seo_framework_metabox_priority', function ($priority) {
    return 'low';
});

add_filter('the_seo_framework_ld_json_search_url', function ($url) {
    $filter = 'Roots\\Soil\\NiceSearch\\rewrite';
    if (function_exists($filter) && true === get_theme_support('soil-nice-search')) {
        $url = call_user_func($filter, $url);
    }
    return $url;
});
