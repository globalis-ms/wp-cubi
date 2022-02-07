<?php

namespace Globalis\WP\Cubi;

define('TSF_DISABLE_SUGGESTIONS', true);

add_filter('the_seo_framework_indicator', '__return_false');

add_filter('the_seo_framework_indicator_sitemap', '__return_false');

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

define( 'THE_SEO_FRAMEWORK_HEADLESS', [
    'user' => true,
    'meta' => false,
    'settings' => true,
]);

add_filter('option_autodescription-site-settings', function ($settings) {
    $settings['canonical_scheme'] = WP_SCHEME;
    return $settings;
});

add_filter('the_seo_framework_default_site_options', function($config) {
    $config['display_seo_bar_tables'] = 0;
    return $config;
}, 10, 1);

add_filter('the_seo_framework_user_meta_defaults', function($config, $user_id) {
    // var_dump($config);
    // die;
    return $config;
}, 10, 2);

add_filter('the_seo_framework_post_meta_defaults', function($config, $post_id) {
    // var_dump($config);
    // die;
    return $config;
}, 10, 2);

add_filter('the_seo_framework_term_meta_defaults', function($config, $term_id) {
    // var_dump($config);
    // die;
    return $config;
}, 10, 2);
