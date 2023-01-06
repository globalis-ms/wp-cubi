<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_menu', function () {
    remove_menu_page('plugins.php');
});
