<?php

namespace Globalis\WP\Cubi;

add_filter('register_post_type_args', function ($args, $name) {
    if ($name === 'attachment') {
        $args['public'] = false;
    }
    return $args;
}, 10, 2);

add_filter('media_row_actions', function ($actions, $post, $detached) {
    unset($actions['view']);
    return $actions;
}, 10, 3);

add_filter('attachment_link', function ($link, $post_ID) {
    return home_url('/');
}, 10, 2);

add_action('parse_query', function ($query) {
    if ($query->is_archive) {
        return;
    }
    if (!$query->is_singular) {
        return;
    }
    if ($query->is_attachment) {
        $is_attachment = true;
    } elseif (isset($query->queried_object) && isset($query->queried_object->post_type) && 'attachment' == $query->queried_object->post_type) {
        $is_attachment = true;
    } else {
        $is_attachment = false;
    }
    if (!$is_attachment) {
        return;
    }
    $query->is_attachment = false;
    unset($query->queried_object);
    unset($query->queried_object_id);
    $query->set_404();
});
