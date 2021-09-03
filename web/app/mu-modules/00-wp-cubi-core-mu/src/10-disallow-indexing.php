<?php

namespace Globalis\WP\Cubi;

if (WP_ENV === 'production') {
    define('WP_CUBI_SITE_PUBLIC', true);

    add_filter('pre_option_blog_public', function () {
        return '1';
    });
} else {
    define('WP_CUBI_SITE_PUBLIC', false);

    add_filter('pre_option_blog_public', function () {
        return '0';
    });

    add_filter('robots_txt', function ($output, $public) {
        $output = "User-agent: *\r\n";
        $output .= "Disallow: /\r\n";
        return $output;
    }, 99, 2);
}

/*
 * Prevent AMP pages being indexed when option blog_public != 1
 */
add_action('amp_post_template_head', 'wp_robots');
