<?php

namespace Globalis\WP\WPUnhooked;

// Front-end
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Admin
add_action('admin_init', function () {
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
});

// Feeds
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');

// Embeds
remove_filter('embed_head', 'print_emoji_detection_script');

// Emails
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

// SVG
add_filter('emoji_svg_url', '__return_false');

// Tiny MCE
add_filter('tiny_mce_plugins', function ($plugins) {
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
});
