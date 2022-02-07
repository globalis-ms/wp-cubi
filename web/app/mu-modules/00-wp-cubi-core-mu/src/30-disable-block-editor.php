<?php

namespace Globalis\WP\Cubi;

if (true === WP_CUBI_ENABLE_BLOCK_EDITOR) {
    return;
}

add_filter('use_block_editor_for_post_type', '__return_false', 100, 2);
add_filter('wp_enqueue_scripts', __NAMESPACE__ . '\\disable_block_editor_wp_enqueue_scripts', 100);
add_action('admin_init', __NAMESPACE__ . '\\disable_block_editor_privacy_notice');

remove_action('init', 'register_block_core_archives');
remove_action('init', 'register_block_core_categories');
remove_action('init', 'register_block_core_latest_posts');
remove_action('init', 'register_block_core_shortcode');

remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');
remove_action('admin_enqueue_scripts', 'wp_common_block_scripts_and_styles');
remove_action('enqueue_block_assets', 'wp_enqueue_registered_block_scripts_and_styles');
remove_action('enqueue_block_editor_assets', 'wp_enqueue_registered_block_scripts_and_styles');

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

add_filter('posts_pre_query', __NAMESPACE__ . '\\disable_block_template_queries', 10, 2);
add_filter('terms_pre_query', __NAMESPACE__ . '\\disable_taxonomy_template_queries', 10, 2);

function disable_block_template_queries($posts, $wp_query)
{
    if(!isset($wp_query->query_vars['post_type']) || 'wp_template' !== $wp_query->query_vars['post_type']) {
        return $posts;
    }
    return [];
}

function disable_taxonomy_template_queries($terms, $wp_query)
{
    if(!isset($wp_query->query_vars['taxonomy']) || 1 != count($wp_query->query_vars['taxonomy']) || "wp_theme" !== current($wp_query->query_vars['taxonomy'])) {
        return $terms;
    }
    return [];
}
