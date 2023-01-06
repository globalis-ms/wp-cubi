<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_head', function () {
    add_filter('screen_options_show_screen', '__return_false');
});
