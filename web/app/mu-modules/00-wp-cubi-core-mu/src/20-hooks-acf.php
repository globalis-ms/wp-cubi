<?php

namespace Globalis\WP\Cubi;

/*
 * Remove smilies and useless capital_P_dangit filter in ACF fields
 */
add_filter('acf_the_content', function ($default) {
    remove_filter('acf_the_content', 'convert_smilies', 20);
    remove_filter('acf_the_content', 'capital_P_dangit', 11);
    return $default;
}, 0);

add_filter('pre_option_acf_pro_license', '__return_zero');
add_filter('acf/settings/show_updates', '__return_false');

add_filter('acf/settings/autoload', function () {
    return 'yes';
});

add_filter('acf/validate_field', __NAMESPACE__ . '\\acf_force_return_format_id');

function acf_force_return_format_id($field)
{
    if (acf_is_field_setting_return_format($field)) {
        if (isset($field['choices']['id'])) {
            if (isset($field['choices']['array'])) {
                unset($field['choices']['array']);
            }
            if (isset($field['choices']['object'])) {
                unset($field['choices']['object']);
            }
            if (isset($field['choices']['url'])) {
                unset($field['choices']['url']);
            }
        }
    } elseif (isset($field['return_format'])) {
        if (in_array($field['type'], ['post_object', 'relationship', 'taxonomy', 'user', 'image', 'file'])) {
            $field['return_format'] = 'id';
        }
    }

    return $field;
}

function acf_is_field_setting_return_format($field)
{
    if ($field['name'] !== 'return_format' || $field['_name'] !== 'return_format') {
        return false;
    }
    if (!isset($field['choices']) || ! is_array($field['choices'])) {
        return false;
    }
    return true;
}

add_filter('acf/fields/post_object/result', __NAMESPACE__ . '\\acf_post_object_title_show_ID', 10, 4);
add_filter('acf/fields/post_object/query', __NAMESPACE__ . '\\acf_post_object_search_by_ID', 10, 3);

function acf_post_object_title_show_ID($title, $post, $field, $post_id)
{
    return $post->ID . ' : ' . $title;
}

function acf_post_object_search_by_ID($args, $field, $post_id)
{
    if (!empty($args['s']) && is_numeric($args['s']) && strval(intval($args['s'])) == strval($args['s'])) {
        $search_id = $args['s'];
        $args['post__in'] = [$search_id];
        unset($args['s']);
    }

    $args['orderby'] = 'date';
    $args['order']   = 'DESC';

    return $args;
}

add_filter('acf/get_field_group', __NAMESPACE__ . '\\acf_field_group_default_menu_order');
add_filter('acf/input/meta_box_priority', __NAMESPACE__ . '\\acf_set_side_metabox_priority', 10, 2);

function acf_field_group_default_menu_order($field_group)
{
    if (empty($field_group['menu_order'])) {
        $field_group['menu_order'] = 50;
    }
    return $field_group;
}

function acf_set_side_metabox_priority($priority, $field_group)
{
    if (isset($field_group['position']) && 'side' === $field_group['position']) {
        return 'low';
    }

    return $priority;
}

add_filter('posts_pre_query', function ($posts, $wp_query) {
    if (WP_ENV === 'development') {
        return $posts;
    }
    if (!isset($wp_query->query) || !isset($wp_query->query["post_type"])) {
        return $posts;
    }
    if ("acf-field-group" !== $wp_query->query["post_type"]) {
        return $posts;
    }
    return [];
}, 10, 2);

add_action('acfe/init', function () {
    acfe_update_setting('dev', WP_ENV === "development");
    acfe_update_setting('modules/author', true);
    acfe_update_setting('modules/categories', false);
    acfe_update_setting('modules/block_types', false);
    acfe_update_setting('modules/forms', false);
    acfe_update_setting('modules/options_pages', false);
    acfe_update_setting('modules/post_types', false);
    acfe_update_setting('modules/taxonomies', false);
    acfe_update_setting('modules/multilang', false);
    acfe_update_setting('modules/options', false);
    acfe_update_setting('modules/performance', false);
    acfe_update_setting('modules/ui', false);
    acfe_update_setting('modules/rewrite_rules', false);
    acfe_update_setting('modules/templates', false);
    acfe_update_setting('modules/classic_editor', false);
    acfe_update_setting('modules/field_group_ui', true);
});

add_filter('acf/settings/enable_post_types', '__return_false');
add_filter('acf/settings/rest_api_enabled', '__return_false');
add_filter('acf/settings/preload_blocks', '__return_false');
