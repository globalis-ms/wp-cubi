<?php
/**
 * Plugin Name:         wp-cubi default filters
 * Plugin URI:          https://github.com/globalis-ms/wp-cubi/blob/master/web/app/mu-modules/wp-cubi-default-filters.php
 * Description:         Collection of filters for wp-cubi
 * Author:              Pierre Dargham, Globalis Media Systems
 * Author URI:          https://www.globalis-ms.com/
 * License:             GPL2
 *
 * Version:             0.1.0
 * Requires at least:   4.0.0
 * Tested up to:        4.8.1
 */

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
 * Disable conversion of wysiwyg smilies codes to images
 */
add_filter('pre_option_use_smilies', '__return_zero', 10, 1);

/*
 * Disable dashboard browse-happy requests / widget
 */
add_filter('pre_site_transient_browser_' . md5($_SERVER['HTTP_USER_AGENT']), '__return_true');
