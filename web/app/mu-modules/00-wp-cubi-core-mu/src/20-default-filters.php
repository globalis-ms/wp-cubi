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
 * Disable conversion of wysiwyg smilies codes to images
 */
add_filter('pre_option_use_smilies', '__return_zero', 10, 1);

/*
 * Remove emojis and smilies hooks
 */
remove_action('init', 'smilies_init', 5);
remove_filter('the_content', 'convert_smilies', 20);
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

/*
 * Disable legacy filter causing useless SQL queries
 */
add_filter('pre_option__split_terms', '__return_empty_array');

/*
 * Disable dashboard browse-happy requests / widget
 */
if (!empty($_SERVER['HTTP_USER_AGENT'])) {
    add_filter('pre_site_transient_browser_' . md5($_SERVER['HTTP_USER_AGENT']), '__return_true');
}

/*
 * Remove useless capital_P_dangit filter (priority: 11)
 */
foreach (['wp_title', 'the_title', 'the_content', 'widget_text_content'] as $filter) {
    remove_filter($filter, 'capital_P_dangit', 11);
}

/*
 * Remove useless capital_P_dangit filter (priority: 31)
 */
foreach (['comment_text'] as $filter) {
    remove_filter($filter, 'capital_P_dangit', 31);
}

/*
 * Avoid useless SQL queries caused by script_concat_settings()
 */
if (!is_admin() && !wp_doing_ajax()) {
    global $concatenate_scripts, $compress_scripts, $compress_css;
    $concatenate_scripts = false;
    $compress_scripts    = false;
    $compress_css        = false;
}
