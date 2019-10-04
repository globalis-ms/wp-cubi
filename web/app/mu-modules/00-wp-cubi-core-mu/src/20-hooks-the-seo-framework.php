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

add_filter('option_autodescription-site-settings', function ($settings) {
    $settings['canonical_scheme'] = WP_SCHEME;
    return $settings;
});

add_action('wp_head', __NAMESPACE__ . '\\seo_meta_robo_eucd', 1, 1);
add_action('amp_post_template_head', __NAMESPACE__ . '\\seo_meta_robo_eucd', 1, 1);

function seo_meta_robo_eucd()
{
    ?>
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <?php
}
