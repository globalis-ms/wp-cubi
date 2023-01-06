<?php

namespace Globalis\WP\WPUnhooked;

add_action('init', function () {
    unregister_builtin_taxonomy('category');
});
