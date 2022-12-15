<?php

namespace Globalis\WP\Cubi;

if (true === WP_CUBI_ENABLE_BLOCK_EDITOR) {
    return;
}

add_action('admin_init', __NAMESPACE__ . '\\disable_block_editor_privacy_notice');

add_filter('pre_get_block_file_template', '__return_false');
add_filter('pre_get_block_template', '__return_false');
add_filter('pre_get_block_templates', '__return_empty_array');
add_filter('posts_pre_query', __NAMESPACE__ . '\\disable_block_template_queries', 10, 2);
add_filter('terms_pre_query', __NAMESPACE__ . '\\disable_taxonomy_template_queries', 10, 2);
add_filter('use_block_editor_for_post_type', '__return_false', 100, 2);
add_filter('wp_enqueue_scripts', __NAMESPACE__ . '\\disable_block_editor_wp_enqueue_scripts', 100);

remove_action('admin_enqueue_scripts', 'wp_common_block_scripts_and_styles');
remove_action('after_setup_theme', 'wp_setup_widgets_block_editor', 1);
remove_action('enqueue_block_assets', 'wp_enqueue_registered_block_scripts_and_styles');
remove_action('enqueue_block_editor_assets', 'wp_enqueue_registered_block_scripts_and_styles');

remove_action('plugins_loaded', '_wp_theme_json_webfonts_handler');
remove_action('switch_theme', ['WP_Theme_JSON_Resolver', 'clean_cached_data']);
remove_action('start_previewing_theme', ['WP_Theme_JSON_Resolver', 'clean_cached_data']);

remove_action('init', '_register_core_block_patterns_and_categories');
remove_action('init', 'register_block_core_archives');
remove_action('init', 'register_block_core_block');
remove_action('init', 'register_block_core_calendar');
remove_action('init', 'register_block_core_categories');
remove_action('init', 'register_block_core_file');
remove_action('init', 'register_block_core_gallery');
remove_action('init', 'register_block_core_image');
remove_action('init', 'register_block_core_latest_comments');
remove_action('init', 'register_block_core_latest_posts');
remove_action('init', 'register_block_core_legacy_widget');
remove_action('init', 'register_block_core_loginout');
remove_action('init', 'register_block_core_navigation');
remove_action('init', 'register_block_core_navigation_link');
remove_action('init', 'register_block_core_navigation_submenu');
remove_action('init', 'register_block_core_page_list');
remove_action('init', 'register_block_core_pattern');
remove_action('init', 'register_block_core_post_author');
remove_action('init', 'register_block_core_post_comments');
remove_action('init', 'register_block_core_post_content');
remove_action('init', 'register_block_core_post_date');
remove_action('init', 'register_block_core_post_excerpt');
remove_action('init', 'register_block_core_post_featured_image');
remove_action('init', 'register_block_core_post_navigation_link');
remove_action('init', 'register_block_core_post_template');
remove_action('init', 'register_block_core_post_terms');
remove_action('init', 'register_block_core_post_title');
remove_action('init', 'register_block_core_query');
remove_action('init', 'register_block_core_query_pagination');
remove_action('init', 'register_block_core_query_pagination_next');
remove_action('init', 'register_block_core_query_pagination_numbers');
remove_action('init', 'register_block_core_query_pagination_previous');
remove_action('init', 'register_block_core_query_title');
remove_action('init', 'register_block_core_rss');
remove_action('init', 'register_block_core_search');
remove_action('init', 'register_block_core_shortcode');
remove_action('init', 'register_block_core_site_logo');
remove_action('init', 'register_block_core_site_tagline');
remove_action('init', 'register_block_core_site_title');
remove_action('init', 'register_block_core_social_link');
remove_action('init', 'register_block_core_tag_cloud');
remove_action('init', 'register_block_core_template_part');
remove_action('init', 'register_block_core_term_description');
remove_action('init', 'register_block_core_widget_group');
remove_action('init', 'register_core_block_types_from_metadata');
remove_action('init', ['WP_Block_Supports', 'init'], 22);

remove_action('setup_theme', 'wp_enable_block_templates');
remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');
remove_action('wp_footer', 'the_block_template_skip_link');

remove_theme_support('core-block-patterns');

function disable_block_editor_wp_enqueue_scripts()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
}

function disable_block_editor_privacy_notice()
{
    remove_action('admin_notices', ['WP_Privacy_Policy_Content', 'notice']);
    add_action('edit_form_after_title', ['WP_Privacy_Policy_Content', 'notice']);
}

function disable_block_template_queries($posts, $wp_query)
{
    if (!isset($wp_query->query_vars['post_type']) || 'wp_template' !== $wp_query->query_vars['post_type']) {
        return $posts;
    }
    return [];
}

function disable_taxonomy_template_queries($terms, $wp_query)
{
    if (!isset($wp_query->query_vars['taxonomy']) || 1 != count($wp_query->query_vars['taxonomy']) || "wp_theme" !== current($wp_query->query_vars['taxonomy'])) {
        return $terms;
    }
    return [];
}
