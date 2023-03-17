<?php

namespace Globalis\WP\Cubi;

define('THE_SEO_FRAMEWORK_HEADLESS', [
    'user' => true,
    'meta' => false,
    'settings' => false,
]);

define('TSF_DISABLE_SUGGESTIONS', true);

add_filter('the_seo_framework_default_site_options', function ($config) {
    $config['alter_search_query'] = 0;
    $config['alter_archive_query'] = 0;
    $config['canonical_scheme'] = WP_SCHEME;
    //var_dump($config);
    //die;
    return $config;
}, 10, 1);

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

add_action('plugins_loaded', function () {
    remove_action('the_seo_framework_upgraded', 'The_SEO_Framework\\Bootstrap\\_prepare_upgrade_notice', 99, 2);
    remove_action('the_seo_framework_upgraded', 'The_SEO_Framework\\Bootstrap\\_prepare_upgrade_suggestion', 100, 2);
});


if (!defined('WP_INSTALLING') || !WP_INSTALLING) {
    add_filter('get_user_metadata', __NAMESPACE__ . '\\disable_autodescription_usermeta_queries', 10, 4);
}

function disable_autodescription_usermeta_queries($null, $object_id, $meta_key, $single)
{
    if (!defined('THE_SEO_FRAMEWORK_USER_OPTIONS')) {
        return;
    }

    if (!\Globalis\WP\Cubi\is_frontend()) {
        return $null;
    }

    if ($meta_key !== THE_SEO_FRAMEWORK_USER_OPTIONS) {
        return $null;
    }

    return '';
}

add_filter('robots_txt', __NAMESPACE__ . '\\hide_wp_admin_in_robots_txt', 99, 2);

function hide_wp_admin_in_robots_txt($output, $public)
{
    $site_path = \esc_attr(parse_url(\site_url(), PHP_URL_PATH)) ?: '';
    $search = "User-agent: *\n";
    $search .= "Disallow: $site_path/wp-admin/\n";
    $search .= "Allow: $site_path/wp-admin/admin-ajax.php\n";

    $output = str_replace($search, '', $output);

    return $output;
}
