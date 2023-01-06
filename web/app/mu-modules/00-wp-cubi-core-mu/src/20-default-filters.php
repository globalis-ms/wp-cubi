<?php

namespace Globalis\WP\Cubi;

/*
 * Prevent modification of .htaccess by WordPress, as it is handled by wp-cubi and Robo
 */
add_filter('flush_rewrite_rules_hard', '__return_false', 99, 1);

/*
 * Remove accents from media uploads
 */
add_filter('sanitize_file_name', 'remove_accents', 10, 1);

/*
 * Enable svg upload support
 */
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});
