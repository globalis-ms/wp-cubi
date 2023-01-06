<?php

namespace Globalis\WP\WPUnhooked;

// @todo move helpers into wp-cubi-helpers

function unregister_builtin_taxonomy($taxonomy)
{
    unregister_taxonomy_for_object_type($taxonomy, 'post');
    add_filter('acf/get_taxonomies', function ($taxonomies, $args) use ($taxonomy) {
        return array_diff($taxonomies, [$taxonomy]);
    }, 10, 2);
}
