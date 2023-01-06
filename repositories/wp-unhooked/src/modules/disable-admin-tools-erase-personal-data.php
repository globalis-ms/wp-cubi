<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_menu', function () {
    remove_submenu_page('tools.php', 'erase-personal-data.php');
});

add_action('admin_init', function () {
        global $pagenow;

    if ("erase-personal-data.php" === $pagenow) {
        wp_safe_redirect(admin_url());
        exit;
    }
});
